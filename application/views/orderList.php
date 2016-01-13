
<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Order</h1>
</div>          
<?php $session_data = $this->session->userdata('logged_in'); ?>	
				
				
				<div class="maincontentinner">
						
					<div class="success message">
						<?php 
							echo $this->session->flashdata('success_message');
						?>
					</div>
					<div class="error message">
						<?php 
							echo $this->session->flashdata('error_message');
						?>
					</div>
					<hr>
				<?php if ($session_data['user_role'] <> 'member') { ?>
					<form method="post" action="<?=base_url()?>index.php/order/search">
						<div id="wiz1step1" class="formwiz">
							
							<h4 class="widgettitle">&nbsp; Pencarian</h4>
							<div class="widgetcontent">
								
								<ul class="search-field">
									<li>
										<label>Nama Penerima</label>
										<span class="field">
											<input type="text" class="input-medium" name="billing_name" id="billing_name" />
										</span>
									</li>
									<li>
										<label>Telp Penerima</label>
										<span class="field">
											<input type="text" class="input-medium" name="billing_phone" id="billing_phone" />
										</span>
									</li>
									<li>
										<label>Kecamatan Penerima</label>
										<span class="field">
											<input type="text" class="input-medium" name="billing_kec" id="billing_kec" />
										</span>
									</li>
									<li>
										<label>Kota Penerima</label>
										<span class="field">
											<input type="text" class="input-medium" name="billing_city" id="billing_city" />
										</span>
									</li>
								</ul>	
								
								<ul class="search-field">
									<li>
										<label>Nama Pengirim</label>
										<span class="field">
											<input type="text" class="input-medium" name="shipper_name" id="shipper_name" />
										</span>
									</li>
								</ul>	
								<div>
									<button class="btn btn-primary">Cari</button>
								</div>
							</div>
						</div>
					</form>
				
						<?php } ?>
					<p class="stdformbutton">
					<ul>
					<?php if ($session_data['user_role'] <> 'member') { ?>
								
						<li style="display:inline;">
							<a href="<?=base_url()?>index.php/order/add" title="Tambah" style = "color:#fff;" class="btn btn-success">Tambah Order - New Customer</a>&nbsp;
						</li>
					<?php } ?>
			
						
						<li style="display:inline;">
							<form method="post" action="<?=base_url()?>index.php/order/find_customer" >
								<a href="#" id="togglefindcustomer" style="color:#fff; margin-right:10px;" 
										<?php if ($session_data['user_role'] <> 'member') { ?>
								
								class="btn btn-inverse" >
											Tambah Order - Repeat Customer
										<?php } else { ?>
								class="btn btn-success" >
								
											Tambah Order
										<?php }?>		
								</a>
								<div id="find_customer" style="display: none;">
				<?php if ($session_data['user_role'] <> 'member') { ?>
								
								<font style="color:blue;">Nama Pelanggan</font>
								<input type="text" name="billing_name" id="billing_name" class="input-small" />
								<font style="color:blue;">Telp Pelanggan</font>
								<input type="text" name="billing_phone" id="billing_phone" class="input-small" />

				<?php } else if ($session_data['user_role'] == 'member') { ?>
								
								<select name="billing_id" id="billing_id" class="input-xlarge  validate[required]">
										<option value=''>- Choose One -</option>
									<?php foreach($shippers as $shipper): ?>
										<option value="<?php echo $shipper->billing_id?>">
											<?php echo $shipper->billing_name?>
										</option>
										
									<?php endforeach; ?>
										<option value='new'>- Lainnya -</option>
								</select>
				<?php } ?>
								
								<input type="submit" name="action" class="btn btn-primary" value="Submit" />
								</div>
							</form>
						</li>
					
				<?php if ($session_data['user_role'] <> 'member') { ?>
						
				<form method="post" action="<?=base_url()?>index.php/order/push_button">
				
					<li style="display:inline;">
						<input type="submit" name="action" class="btn" value="CETAK ALAMAT" />
					</li>
					<li style="display:inline;">
						<input type="submit" name="action" class="btn" value="SUDAH DIKIRIM" />
					</li>
				<?php } ?>
				</ul>
				
				</p>
				
				<h4 class="widgettitle">PENDING ORDERS</h4>
							
					<table width="100%" class="table table-bordered" id="dyntable">
						<colgroup>
							<col class="con0" style="align: center; width: 3%" />
							<col class="con1" style="width: 2%"/>
							<col class="con0" style="width: 15%"/>
							<col class="con1" style="width: 10%"/>
							<col class="con0" style="width: 10%"/>
							<col class="con1" style="width: 10%"/>
							<col class="con0" style="width: 15%"/>
							<col class="con1" style="width: 15%"/>
							<col class="con0" style="width: 5%"/>
							<col class="con1" style="width: 5%"/>
							<col class="con0" style="width: 10%"/>
							
						</colgroup>
						<thead>
							<tr>
								<th class="head1 center">No</th>
								
						<?php if ($session_data['user_role'] <> 'member') { ?>
								<th class="head0 center nosort"></th>
						<?php } ?>		
								<th class="head1 center">Penerima</th>
								<th class="head1 center">Pengirim</th>
								<th class="head1 center">Tanggal Order</th>
								
						<?php if ($session_data['user_role'] <> 'member') { ?>
								<th class="head1 center">Order Via</th>
								<th class="head1 center">Eksp & Daerah</th>
								<th class="head1 center" width="15%">Nilai Belanja</th>
								<th class="head1 center">Status Bayar</th>
								<th class="head1 center">Status Kirim</th>
								<th class="head0 center">Action</th>
						<?php }?>
							</tr>
						</thead>
						
						<tbody>
							<?php 
								if ($listOrderPending == null) {
								?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
								} else {
							
								foreach($listOrderPending as $order):?>
							<tr class="gradeX">
							
								<td class="center"><?=$row++;?></td>
						<?php if ($session_data['user_role'] <> 'member') { ?>
						
								<td class="center">
									<span class="center">
										<input type="checkbox" name="ch<?=$row;?>" value="<?=$order->id?>"/>
									</span>
						<?php } ?>
								</td>
								<td>
									<input type="hidden" value="<?=$order->id?>" id="order_id_detail">
								<?php if ($session_data['user_role'] <> 'member') { ?>
									<a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat_simple/<?=$order->id?>" title="Detail">
										<?=$order->bill_name?> 
									</a>
									
								<?php } else { ?>	
										<script>
										  $(function() {
											$( "#dialog<?=$order->id?>" ).dialog({
												
												dialogClass : 'fixed-dialog',
											  autoOpen: false,
											  show: {
												effect: "blind",
												duration: 1000
											  },
											  hide: {
												effect: "blind",
												duration: 1000
											  }
											});
										 
											$( "#opener<?=$order->id?>" ).click(function() {
											  $( "#dialog<?=$order->id?>" ).dialog( "open" );
											});
										  });
										  </script>
									  <div id="dialog<?=$order->id?>" title="Detail Pesanan">
										  <p>
										  <table width="100%" class="table table-bordered" id="dyntable">
											<colgroup>
												<col class="con0" style="align: center; width: 45%" />
												<col class="con1" style="width: 5%"/>
												<col class="con0" style="width: 25%"/>
												<col class="con1" style="width: 25%"/>
											</colgroup>
											<thead>
												<tr>
													<th class="head1 center">Nama Barang</th>
													<th class="head1 center">Qty</th>
													<th class="head1 center">Harga Satuan</th>
													<th class="head1 center">Subtotal</th>
												</tr>
											</thead>
											
											<tbody>
												<?=$order->cart_table?>
												<tr>
													<td colspan="4"></td></tr>
												<tr>
													<td><b> Total Belanja</b></td>
													<td><b> <?=$order->total_qty?></b></td>
													<td></td>
													<td><b><?=$order->subtotal?></b></td>
												</tr>
											</tbody>
										  </table>
										  </p>
										</div>
										 
										<button id="opener<?=$order->id?>" class="btn btn-primary"><?=$order->bill_name?> </button>
										 
								<?php } ?>		
										<br/>
										<?php if ($session_data['user_role'] <> 'member') { ?>
										<a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat/<?=$order->id?>" style="color:white;" class="btn btn-danger" title="Cetak Nota">Cetak Nota</a>&nbsp;
										<?php } ?>
										<br/>
										<a href="<?=base_url()?>index.php/order/update/<?=$order->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
										<?php echo anchor('order/delete/'.$order->id,'<span class="icon-trash"></span>', 
											array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus order tersebut?')"));?>								
									
									
								</td>
								
								<td><?=$order->shipper_name?></td>
								<td class="center"><?php echo date("d-M-Y", strtotime($order->order_date))?></td>
						<?php if ($session_data['user_role'] <> 'member') { ?>
						
								<td class="center"><?=$order->option_desc?></td>
								<td class="center">
									<?=$order->expedition?> <br/><b><small><?=$order->estimated_weight?> gr</small></b><br/>	
									<?=$order->bill_kec?>,&nbsp; <?=$order->bill_city?>
								</td>
								<td class="">
									Belanja : <b class="right nominal"><?=$order->product_amount - $order->discount_amount?></b><br/>
									Penyesuaian : <b class="right nominal"><?=$order->adjustment_nominal?></b><br/>
									Ongkir : <b class="right nominal"><?=$order->exp_cost?></b><br/>
									TOTAL : <b class="right nominal"><?=$order->total_amount?></b>
								</td>
								<td class="center"><?php 	if ($order->order_status == 2) {
																echo '<font color="lime">Lunas</font>';
															} else if ($order->order_status == 1){ 
																// get cash
																$this->db->where('order_id', $order->id);
																$this->db->limit(1);
																$cash = $this->db->get('tb_cash');
																if ($cash->num_rows > 0) {
																	$cash_nominal = $cash->row()->cash_nominal;
																} else {
																	$cash_nominal = 0;
																}
																// get acrec
																$this->db->where('order_id', $order->id);
																$this->db->limit(1);
																$acrec = $this->db->get('tb_acrec');
																if ($acrec->num_rows > 0) {
																	$acrec_nominal = $acrec->row()->acrec_nominal;
																} else {
																	$acrec_nominal = 0;
																}
																echo '<font color="blue">
																		DP : '.$cash_nominal.'<br/>
																		Sisa : '.$acrec_nominal.'
																	 </font>';
															} else {
																echo '<font color="red">Belum</font>';
															}
													?>
									<br/>
									<?php
																	
										// get wallet
										$this->db->where('order_id', $order->id);
										$wallet = $this->db->get('tb_wallet');
										if ($wallet->num_rows > 0) {
											echo '<small>Wallet</small>';
										} else {
											echo '<small>ATM Transfer</small>';
										}
									?>
								</td>
								<td class="center"><?php if ($order->package_status == 1) 
															echo '<font color="lime">Sudah</font>';else echo '<font color="red">Belum</font>';?></td>
						
								<td class="centeralign">
									<a href="<?=base_url()?>index.php/order/update/<?=$order->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
									<?php echo anchor('order/delete/'.$order->id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus order tersebut?')"));?>
								</td>
						<?php } ?>
							</tr>
							
							<?php endforeach; 
							}?>
						</tbody>
					</table>
				<?php if ($session_data['user_role'] <> 'member') { ?>
					</form>
				<?php } ?>
				
				<p><?php echo $links; ?></p>
				<br/><br/>
				<h4 class="widgettitle">COMPLETE ORDERS</h4>
				<table width="100%" class="table table-bordered" id="dyntable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						
                    </colgroup>
                    <thead>
                        <tr>
                          	<th  class="head1 center">No</th>
                        <?php if ($session_data['user_role'] <> 'member') { ?>
							<th  class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
						<?php } ?>
							<th  class="head1 center">Penerima</th>
							<th class="head1 center">Tanggal Order</th>
                        <?php if ($session_data['user_role'] <> 'member') { ?>
							<th class="head0 center">Daerah Pengiriman</th>
                            <th class="head0 center">Nilai Belanja</th>
							<th class="head1 center">Status Bayar</th>
							<th class="head1 center">Status Kirim</th>
                       <?php } ?>
                            <th class="head0 center">Action</th>
                        </tr>
                    </thead>
				                
					<tbody>
						
                    	<?php 
							if ($listOrderComplete == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
							} else {
						
							$row = 1;
							foreach($listOrderComplete as $order):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
						<?php if ($session_data['user_role'] <> 'member') { ?>
                       
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
						<?php } ?>
                            <td>
								<a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat/<?=$order->id?>" title="Detail">
									<?=$order->bill_name?>
								</a><br/>
							</td>
							
							<td class="center"><?php echo date("d-M-Y", strtotime($order->order_date))?></td>
						<?php if ($session_data['user_role'] <> 'member') { ?>
						
                            <td class="center"><?=$order->billing_kec?>,&nbsp; <?=$order->billing_city?></td>
                            <td class="">
									Belanja : <b class="right nominal"><?=$order->product_amount - $order->discount_amount?></b><br/>
									Penyesuaian : <b class="right nominal"><?=$order->adjustment_nominal?></b><br/>
									Ongkir : <b class="right nominal"><?=$order->exp_cost?></b><br/>
									TOTAL : <b class="right nominal"><?=$order->total_amount?></b>
							</td>
							<td class="center"><?php 
													if ($order->order_status == 2) {
														echo '<font color="lime">Lunas</font>';
													} else if ($order->order_status == 1){ 
														echo '<font color="blue">
																DP : '.$order->purchase_nominal_cash.'<br/>
																Sisa : '.$order->purchase_nominal_credit.'
															 </font>';
													} else {
														echo '<font color="red">Belum</font>';
													}
													?>
							
									<br/>
									<?php
																	
										// get wallet
										$this->db->where('order_id', $order->id);
										$wallet = $this->db->get('tb_wallet');
										if ($wallet->num_rows > 0) {
											echo '<small>Wallet</small>';
										} else {
											echo '<small>ATM Transfer</small>';
										}
									?>									
							</td>
							<td class="center"><?php if ($order->package_status == 1) 
														echo '<font color="lime">Sudah</font>';else echo '<font color="red">Belum</font>';?></td>
                            <?php } ?>
                            <td class="centeralign">
                            	<a href="<?=base_url()?>index.php/order/update/<?=$order->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('order/delete/'.$order->id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus order tersebut?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
					
				</div>	
                </table>
				<p><?php echo $links; ?></p>
		
				
<script>
	$("#togglefindcustomer").click(function(){
		$("#find_customer").slideToggle();
	});
	
	
</script>				
