<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Cart logic adalah metode untuk mengecek tiap produk yang ada di form order apakah sudah ada di cart atau belum.
 * FLOW
 * 1.  QTY Cart diinput di order Form
 * 2.  Tiap stock di cek keberadaannya di tb_cart (looping stock)
 * 3.  Query seluruh stok, lalu disamakan dengan stock yang mau dicek (looping stock)
 * 4a. Kalo ketemu, dicek QTY sebelumnya berapa
 * - Kalo QTYnya sama, skip
 * - Kalo QTYnya beda, STOK & INVENTORY dibalikin dulu ke semula, lalu STOK & INVENTORY dikurangin sesuai QTY terbaru. 
 * - Cart Insertion
 * 4b. Kalo ga ketemu,
 * - Langsung dikurangin STOK & INVENTORY
 * - Cart Insertion
 * 
 * **/
class Cart_logic extends CI_Model {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		$this->load->model('activity_model');
		
		$this->load->model('GenericModel');
		
		
    }
	
	public function index() {
	}
	
	private function update_inventory_qty($qty, $id){
		$inventory_update = array(
			'inventory_qty' => $qty,
		);
												   
		$this->db->where('inventory_id', $id);
		$this->db->update('tb_inventory',$inventory_update);
	}
	
	public function add_to_cart(
		$stock_id, 
		$id, 
		$qty, 
		$amount, 
		$purchase_date, 
		$inventory_desc, 
		$total_qty, 
		$total_cogs_amount,
		$source) {
		
		/**	
		echo '<br/>---------------------------';
		echo '<br/>
			stock_id['.$stock_id.'],
			id['.$id.'],
			qty['.$qty.'],
			amount['.$amount.'],
			purchase_date['.$purchase_date.'],
			inv_desc['.$inventory_desc.'],
			total_qty['.$total_qty.'],
			total_cogs_amount['.$total_cogs_amount.'],
			source['.$source.']';
		*/
			
		$inventory_cogs_reduce = 0;
		
		// DEFINE SOURCE 
		if ($source == 'order') {
			$cart_table = 'tb_cart';
			$foreign_id = 'order_id';
			$inv_type_id = 24;
			
		} else if ($source == 'reject'){
			$cart_table = 'tb_reject_cart';
			$foreign_id = 'reject_id';
			$inv_type_id = 54;
			
		}
		
		// GET DATA STOCK
		$this->db->where('stock_id', $stock_id);
		$this->db->limit(1);
		$query_stock = $this->db->get('tb_stock');
							
		foreach ($query_stock->result() as $row_stock) {
			if ($row_stock->stock_id == $stock_id) {
				//echo '<br/>get in 1';
				// LOOKING FOR STOCK IN CART OF THIS ORDER
				$this->db->where('stock_id', $stock_id);
				$this->db->where($foreign_id, $id);
				$query_cart = $this->db->get($cart_table);
				if ($query_cart->num_rows > 0) { // IF STOCK EXIST
					foreach ($query_cart->result() as $row_cart) {
						// get previous qty
						$previous_qty = $row_cart->cart_qty;
						//echo 'QTY BEFORE = '.$previousPurchasedStockQty.' <br/>';
						//echo 'QTY NOW    = '.$qty.' <br/>';
						if ($previous_qty == $qty) { // QTY EQUAL
							/**
							echo '------------------------------------------<br/>';
							echo 'EQUAL!! Lanjut gan.. <br/>';
							echo 'source : '.$source.'<br/>';
							echo 'foreign_id : '.$foreign_id.'<br/>';
							*/
							
							//echo anchor('order/','KLIK -->> Kembali ke Order List');
							// UPDATE TANGGAL PEMBAYARAN
							$this->db->where($foreign_id, $id);
							$query = $this->db->get('tb_inventory');
							$inv = array (
								'inventory_date' => date('Y-m-d', strtotime($purchase_date)),	
							);
								
							if ($query->num_rows == 0) {
								$this->db->insert('tb_inventory', $inv);
							} else {
								$this->db->where($foreign_id, $id);
								$this->db->update('tb_inventory', $inv);
							}
							continue;
						} else { // QTY DIFFERENT
							/**
							echo '------------------------------------------<br/>';
							echo 'DIFFERENT!! Wah harus diupdate nih.. <br/>';
							echo 'source : '.$source.'<br/>';
							echo 'foreign_id : '.$foreign_id.'<br/>';
							*/
							
							// KEMBALIKAN STOCK SEMULA TERLEBIH DAHULU
							$undo_qty = $previous_qty + $row_stock->stock_qty;
						
							// KURANGI STOCK SEMULA DENGAN QTY SAAT INI
							$new_stock_qty = $undo_qty - $qty;
								
							// === UPDATE STOCK QTY ========================
							$new_stock = array (
								'stock_qty' => $new_stock_qty,
							);
															
							$this->db->where('stock_id', $stock_id);
							$this->db->update('tb_stock', $new_stock);
							// =============================================
							
							// === UNDO INVENTORY ========================== //echo '#1# UNDO INVENTORY <br/>';
						
							$this->db->where('stock_id', $row_cart->stock_id);
							$this->db->order_by('inventory_id', 'desc');
							$query_inventory = $this->db->get('tb_inventory');
							
							if ($query_inventory->num_rows > 0) {
								$undo_inventory_qty = $previous_qty; // ubah variable untuk dimanipulasi secara local
								
								foreach ($query_inventory->result() as $row_inventory) {
									//echo ': : id '.$rowInventory->inventory_id.' qty '.$undo_inventory_qty.' | inv_qty '.$rowInventory->inventory_qty.' | inv_qty_init '.$rowInventory->inventory_qty_init.'<br/>';
									
									$empty_slot_qty = $row_inventory->inventory_qty_init - $row_inventory->inventory_qty; // empty slot inv
									//echo ': : empty slot : '.$empty_slot_qty.'<br>';
									
									// KEMBALIKAN QTY INVENTORY SEPERTI SEMULA DULU
									if ($undo_inventory_qty < $empty_slot_qty) { // jika empty slot masih mencukupi 
										$new_undo_inv = $undo_inventory_qty + $row_inventory->inventory_qty;
										//echo ': : UPDATE QTY 1, new inv : '.$new_undo_inv.'<br>';
										$this->update_inventory_qty($new_undo_inv, $row_inventory->inventory_id);
										break;
									} else { 
										$new_undo_inv = $row_inventory->inventory_qty_init;
										//echo ': : UPDATE QTY 2, new inv : '.$new_undo_inv.'<br>';
										
										$this->update_inventory_qty($new_undo_inv, $row_inventory->inventory_id);
										$undo_inventory_qty = $undo_inventory_qty - $empty_slot_qty;
									}
									//echo ': : qty left : '.$undo_inventory_qty.' remains <br>';
								}
							}
							// ============================================= // Undo Inventory selesai!
							
							// === REDUCE INVENTORY ========================
							
							// GET DATA INVENTORY
							$msg = '';
							$this->db->where('stock_id', $row_cart->stock_id);
							$this->db->where('inventory_qty >', 0);
							$this->db->order_by('inventory_id', 'asc');
							$query_inventory = $this->db->get('tb_inventory');
							
							if ($query_inventory->num_rows > 0) {
								$temp_qty_for_inventory = $qty;
								$inventory_cogs = 0;					
									
								foreach ($query_inventory->result() as $row_inventory) {
									//echo ': : id '.$rowInventory->inventory_id.' | qty '.$cogs_cart_qty.' | inv_qty '.$rowInventory->inventory_qty.' | inv_qty_init '.$rowInventory->inventory_qty_init.'<br/>';
									// CALCULATING NEW INVENTORY COGS & QTY
									if ($temp_qty_for_inventory > 0) {
										if ($temp_qty_for_inventory <= $row_inventory->inventory_qty) { // jika stok di inventory mencukupi
											//echo ': : Calculate Magic 1 <br/>';
											$inventory_cogs = $inventory_cogs + ($temp_qty_for_inventory * $row_inventory->inventory_cogs);
											$inventory_qty = $row_inventory->inventory_qty - $temp_qty_for_inventory;  
											
											// UPDATE INV QTY 
											$this->update_inventory_qty($inventory_qty, $row_inventory->inventory_id);
											// update temp qty
											$temp_qty_for_inventory = 0;
										} else { // jika stok di inventory ga mencukupi 
											//echo ': : Calculate Magic 2 <br/>';
											$inventory_cogs = $inventory_cogs + ($row_inventory->inventory_qty * $row_inventory->inventory_cogs);
											$inventory_qty = 0;
											
											// UPDATE INV QTY
											$this->update_inventory_qty($inventory_update_qty, $row_inventory->inventory_id);
											// update temp qty
											$temp_qty_for_inventory = $temp_qty_for_inventory - $row_inventory->inventory_qty;
										}
									}
									//echo ': : Magic Number '.$inventory_cogs.'<br/>';
								}
								
								// ==== INSERT /UPDATE INVENTORY ===============
								//echo ' total_cogs_amount 2: '.$total_cogs_amount;
								$total_qty = $total_qty + $qty;
								$total_cogs_amount = $total_cogs_amount + $inventory_cogs;
								
								//echo '<br/>total_cogs_amount 2 : '.$total_cogs_amount;
								
								$this->db->where($foreign_id, $id);
								$query = $this->db->get('tb_inventory');
								$inv = array (
									'inventory_date' => date('Y-m-d', strtotime($purchase_date)),	
									'inventory_desc' => $inventory_desc,
									'inventory_qty' =>  $total_qty,
									'inventory_nominal' => '-'.$total_cogs_amount,
									'inventory_type_id' => $inv_type_id,
									$foreign_id => $id
								);
									
								if ($query->num_rows == 0) {
									$this->db->insert('tb_inventory', $inv);
								} else {
									$this->db->where($foreign_id, $id);
									$this->db->update('tb_inventory', $inv);
								}
							}
							//echo 'logic loop end<br/>';
							//echo '------------------------------------------<br/>';
							//echo anchor('order/','KLIK -->> Kembali ke Order List');
							
							$this->cart_insertion(
								$stock_id, 
								$id, 
								$qty, 
								$amount, 
								$total_cogs_amount,
								$foreign_id, 
								$cart_table,
								$source
								);
						}
					}
					
				} else { // IF STOCK NOT EXIST / STOCK IS NEW PURCHASE
					if ($qty > 0) { // STOCK PURCHASED
						
						//IF THIS STOCK IS A NEW PURCHASE
						$new_stock_qty = $row_stock->stock_qty - $qty;
												
						// === UPDATE STOCK ============================
						$new_stock = array (
							'stock_qty' => $new_stock_qty,
						);
														
						$this->db->where('stock_id', $stock_id);
						$this->db->update('tb_stock', $new_stock);
						// =============================================
						
						// INVENTORY QTY
						$this->db->where('stock_id', $stock_id);
						$this->db->where('inventory_qty >', 0);
						$this->db->order_by('inventory_date');
						$query_inventory = $this->db->get('tb_inventory');
						if ($query_inventory->num_rows > 0) {
							
							$temp_qty_for_inventory = $qty;
							$inventory_cogs = 0;
							//echo '<br/>inventory_cogs of '.$stock_id.' temp qty : '.$temp_qty_for_inventory;
							
							foreach ($query_inventory->result() as $row_inventory) {
								
								if ($temp_qty_for_inventory > 0) {
									if ($temp_qty_for_inventory <= $row_inventory->inventory_qty) { // jika stok di inventory mencukupi
										//echo ': : Calculate Magic 1 <br/>';
										$inventory_cogs = $inventory_cogs + ($temp_qty_for_inventory * $row_inventory->inventory_cogs);
										
										//echo '<br/>inventory_cogs 1 : '.$inventory_cogs;
										$inventory_qty = $row_inventory->inventory_qty - $temp_qty_for_inventory;  
										
										//echo '<br/>inventory_qty 1 : '.$inventory_qty;
										
										// UPDATE INV QTY 
										$this->update_inventory_qty($inventory_qty, $row_inventory->inventory_id);
										// update temp qty
										$temp_qty_for_inventory = 0;
									} else { // jika stok di inventory ga mencukupi 
										//echo ': : Calculate Magic 2 <br/>';
										$inventory_cogs = $inventory_cogs + ($row_inventory->inventory_qty * $row_inventory->inventory_cogs);
										$inventory_qty = 0;
										
										//echo '<br/>inventory_cogs 2 : '.$inventory_cogs;
										// UPDATE INV QTY
										$this->update_inventory_qty($inventory_qty, $row_inventory->inventory_id);
										//echo '<br/>inventory_qty 2 : '.$inventory_qty;
										
										// update temp qty
										$temp_qty_for_inventory = $temp_qty_for_inventory - $row_inventory->inventory_qty;
										//echo '<br/>temp_qty 1 : '.$temp_qty_for_inventory;
									}
								}
							}
							
							// ==== INSERT INVENTORY ===============
							//echo ' total_cogs_amount 2: '.$total_cogs_amount;
							$total_qty = $total_qty + $qty;
							$total_cogs_amount = $total_cogs_amount + $inventory_cogs;
							
							//echo '<br/>total_cogs_amount 3 : '.$total_cogs_amount;
							
							$this->db->where($foreign_id, $id);
							$query = $this->db->get('tb_inventory');
							$inv = array (
								'inventory_date' => date('Y-m-d', strtotime($purchase_date)),	
								'inventory_desc' => $inventory_desc,
								'inventory_qty' =>  $total_qty,
								'inventory_nominal' => '-'.$total_cogs_amount,
								'inventory_type_id' => $inv_type_id,
								$foreign_id => $id
							);
								
							if ($query->num_rows == 0) {
								$this->db->insert('tb_inventory', $inv);
							} else {
								$this->db->where($foreign_id, $id);
								$this->db->update('tb_inventory', $inv);
							}
						}					
						$this->cart_insertion(
									$stock_id, 
									$id, 
									$qty, 
									$amount, 
									$total_cogs_amount,
									$foreign_id, 
									$cart_table,
									$source
									);
					}
				}
				
			}
		}
	}

	private function cart_insertion(
		$stock_id, 
		$id, 
		$qty, 
		$amount, 
		$total_cogs_amount,
		$foreign_id, 
		$cart_table,
		$source) {
	
				// TB_CART
				if ($source == 'order') {
					$new_cart = array(
						'stock_id' => $stock_id,
						'order_id' => $id,
						'cart_qty' => $qty,
						'cart_amount' => $amount,
						'cart_cogs' => $total_cogs_amount,
					);
				} else {
					$new_cart = array(
						'stock_id' => $stock_id,
						'reject_id' => $id,
						'cart_qty' => $qty,
						'cart_amount' => $amount,
						'cart_cogs' => $total_cogs_amount,
					);
				}
						
				$this->db->where('stock_id', $stock_id);
				$this->db->where($foreign_id, $id);
				$query = $this->db->get($cart_table);
										
				if ($query->num_rows > 0 && $qty <= 0) {
					$this->db->delete($cart_table, array('stock_id' => $stock_id, $foreign_id => $id));
				} else if ($query->num_rows > 0 && $qty > 0) {
					//echo '<br/>qty 4 : '.$qty;
					//echo '<br/>total_cogs_amount 4 : '.$total_cogs_amount;
					$this->db->where('stock_id', $stock_id);
					$this->db->where($foreign_id, $id);
					$this->db->update($cart_table, $new_cart);
				} else if ($query->num_rows == 0 && $qty > 0) {
					$this->db->insert($cart_table, $new_cart);
				}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
