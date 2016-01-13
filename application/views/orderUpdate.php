	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Ubah Order </h1>
	</div>
</div><!--pageheader-->
<?php $session_data = $this->session->userdata('logged_in'); ?>	
				
    <?php foreach ($orders as $order) :?>
        <div class="maincontent">
            <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
					<div class="span11">
						
							<div class="error message">
								<?php echo $this->session->flashdata('validation_error_message'); ?>
								<?php echo $this->session->flashdata('error_message'); ?>
							</div>
							<div class="success message">
								<?php echo $this->session->flashdata('success_message'); ?>
							</div>
				
							<form id="orderForm" name="orderForm" class="stdform" method="post" action="<?=base_url()?>index.php/order/push_button_order_form" />
								<div id="wiz1step1" class="formwiz">
										<input type="hidden" value="<?=$order->main_order_id?>" name="order_id"/>
									<div class="span5 profile-left" id="cart">	
										<h4 class="widgettitle">&nbsp; Cart </h4>
										<div class="widgetcontent">
											<p>
												<label>Shopping Cart</label>
													<span class="field">
														<input type="text" class="input-large" name="inv_query" id="inv_query" 
																placeholder="Stock Name"/> </br>
														
												<?php if ($session_data['user_role'] <> 'member') { ?>	
														<input id="special_price"  value="special_price" name="pricegroup" type="radio" onclick="priceGroup()" class="validate[required]" 
															<?php if ($order->price_level == 1) echo 'checked';?>> 1-9 
															&nbsp;&nbsp;
														<input id="wholesale_price" value="wholesale_price" name="pricegroup" type="radio" onclick="priceGroup()"
															<?php if ($order->price_level == 2) echo 'checked';?>>	>= 10 
															&nbsp;&nbsp;
														<input type="submit" class="btn btn-danger" name="action" value="CETAK NOTA" />
												<?php } ?>		
													</span>
											</p>
											<p>
											
												<table id="inv_table" class="table table-bordered" style="width: 60%; margin-left:120px;">
													<tr>
														<th>Name</th>
														<th>Qty</th>
													</tr>
													<tbody class="zebra bordered">
														<?php foreach($stock as $item) {
																$qty_keeped = 0;
																foreach ($cart as $cart2) {
																	if ($item->stock_id == $cart2->stock_id) {
																		$qty_keeped = $cart2->cart_qty;
																	}
																}
														?>
														<tr>
															<td><?=$item->product_name;?> - <?=$item->stock_desc;?> [Sisa : <b><?=$item->stock_qty;?></b>]&nbsp;&nbsp;</td>
															<td><input type="text" name="qty<?=$item->stock_id?>" value="<?php echo $qty_keeped?>" id="qty<?=$item->stock_id?>" style="width:30px;"/></td> 
														</tr>
														<?php } ?>
													
													</tbody>
												</table>
											</p>
										</div>
									</div>
									
									
									<div class="span5" id="penerima">
										<h4 class="widgettitle">&nbsp; Penerima / Consignee </h4>
										<input type="hidden" value="<?=$order->bill_id?>" name="billing_id"/>
										<div class="widgetcontent">
											
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Order VIA</label>
												<span class="field">
													<select name="order_channel" id="order_channel" class="input-xlarge">
														<option value=''>- Choose One -</option>
													<?php foreach($order_channel as $channel): ?>
														<option value="<?php echo $channel->option_id?>"
															<?php if ($order->order_channel == $channel->option_id) echo 'selected'; ?>>
																<?php echo $channel->option_desc?></option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<?php } ?>
											<p>
												<label>Tanggal Order</label>
												<span class="field">
													<input type="text" name="order_date" id ="order_date"  value="<?php echo date("d-M-Y", strtotime($order->order_date))?>">
												</span>
											</p>
											<p>
												<label>Nama Penerima <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="billing_name" id="billing_name" 
														value="<?=$order->bill_name?>"/> 
												</span>
											</p>
											<p>
												<label>Alamat Jalan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_street" id="billing_street" 
															value="<?=$order->bill_street?>"/> 
													
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="billing_kec" id="billing_kec" 
														value="<?=$order->bill_kec?>" /> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_city" id="billing_city" 
														value="<?=$order->bill_city?>"/> 
												</span>
											</p>
											<p>
												<label>Kode Pos <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="billing_postal_code" id="billing_postal_code" 
														value="<?=$order->bill_postal_code?>"/> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_prov" id="billing_prov" class="input-xlarge  validate[required]">
														<option value=''>- Choose One -</option>
													<?php foreach($provinces as $province): ?>
														<option value="<?php echo $province->option_id?>" 
															<?php if ($order->bill_prov == $province->option_id) echo 'selected';?>> 
																<?php echo $province->option_desc?>
														</option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<p>
												<label>Negara <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_country" id="billing_country" style="width:200px;">
														<option value="Indonesia" <?php if ($order->bill_country == 'Indonesia') echo 'selected';?>>Indonesia</option>
														<option value="Malaysia" <?php if ($order->bill_country == 'Malaysia') echo 'selected';?>>Malaysia</option>
														<option value="Hong Kong" <?php if ($order->bill_country == 'Hong Kong') echo 'selected';?>>Hong Kong</option>
														<option value="Singapore" <?php if ($order->bill_country == 'Singapore') echo 'selected';?>>Singapore</option>
														<option value="Taiwan" <?php if ($order->bill_country == 'Taiwan') echo 'selected';?>>Taiwan</option>
													</select>
												</span>
											</p>
											<p>
												<label>No Telp <font style="color:red;">*</font></label>
												<span class="field"><input type="text" name="billing_phone" id="billing_phone" 
										 			class="input-medium" value="<?=$order->bill_phone?>"/></span>
											</p>
											
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>												
												<label>Level<font style="color:red;"></font></label>
												<span class="field">
													<select name="billing_level" id="billing_level" class="input-xlarge validate[required]">
														<option value=''>- Choose One -</option>
													<?php foreach($billing_level as $level): ?>
														<option value="<?php echo $level->option_id?>" 
															<?php if ($order->bill_level == $level->option_id) echo 'selected'; ?>>
																<?php echo $level->option_desc?>
														</option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<?php } ?>
										</div>
									</div>
									<div class="span5" id="pengiriman">
										<h4 class="widgettitle">&nbsp; Pengiriman </h4>
										<div class="widgetcontent">
											<!--p>
												<label>Nama Pengirim (Old Table)<font style="color:red;"></font></label>
												<span class="field">
													<input type="text" disabled class="input-xlarge" value="<?=$old_shipper?>"/> 
												</span>
											</p-->
											
											
											<p>
												<label>Nama Pengirim<font style="color:red;">*</font></label>
												<select name="shipper_id" id="shipper_id" class="input-xlarge  validate[required]">
														<option value=''>- Choose One -</option>
												<?php foreach($shippers as $shipper): ?>
													<option value="<?php echo $shipper->billing_id?>"
														<?php if ($shipper->billing_id == $order->shipper_id) echo 'selected'; ?>>
															<?php echo $shipper->billing_name?>
													</option>
												<?php endforeach; ?>
												</select>
												
												<span class="field">
													<input type="text" class="input-xlarge" name="shipper_alias_name" id="shipper_alias_name" 
													<?php 	if (!empty($order->ship_alias_name)) {?>
																placeholder="<?=$order->ship_alias_name?>"
													<?php 	} else {?>
																placeholder="Masukkan nama alias"
													<?php 	}?>
														/> 
												</span>
											</p>
											
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Upline ?<font style="color:red;">*</font></label>
												<input id="yes"  value="yes" name="uplinestatus" type="radio" onclick="uplineStatus()" class="validate[required]" 
													<?php if ($order->shipper_id == $order->bill_upline) echo 'checked';?>> Ya  
															&nbsp;&nbsp;
												<input id="no" value="no" name="uplinestatus" type="radio" onclick="uplineStatus()"
													<?php if ($order->shipper_id <> $order->bill_upline) echo 'checked';?>>	Bukan
											</p>
											<p>
												<label>Ekspedisi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="expedition" id="expedition" 
														value="<?=$order->expedition?>"/> 
												</span>
											</p>
											<p>
												<label>Layanan Eksp <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="service" id="service" 
														value="<?=$order->service?>"/> 
												</span>
											</p>
											<p>
												<label>Ongkir <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="exp_cost" id="exp_cost" 
														value="<?=$order->exp_cost?>"/> <br/> 
													<small>Estimated Weight : <?=$total_weight?> gram(s)</small>
												</span>
											</p>                              
											<p>
												<label>Diskon <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge " disabled value="<?=$order->discount_amount?>"/> 
													<input type="hidden" value="<?=$order->discount_amount?>" name="discount_amount"/>
												</span>
											</p> 
											                             
											<p>
												<label>Penyesuaian Harga <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-large" name="adjustment_desc" value="<?=$order->adjustment_desc?>" placeholder="Deskripsi"/>
													<input type="text" class="input-medium" name="adjustment_nominal" value="<?=$order->adjustment_nominal?>" placeholder="Nominal"/><br/>
													<small><i>Contoh : Deskripsi : Registrasi [] Nominal : 50000 (Jika diskon masukkan simbol minus (-) terlebih dahulu)</i></small>
												</span>
											</p> 

											<p>
												<label>Barang Sudah Dikirim ?<font style="color:red;">*</font></label>
												<span class="field">
													<select name="package_status" id="package_status" style="width:200px;" >
														<option value="0" <?php if ($order->package_status == 0) echo 'selected';?>>Belum Dikirim</option>
														<option value="1" <?php if ($order->package_status == 1) echo 'selected';?>>Sudah Dikirim</option>
													</select>
												</span>
												
											</p>
											<?php } ?> 											
										</div>
									</div>
									
									<?php if ($session_data['user_role'] <> 'member') { ?>	
									<div class="span5" id="pembayaran">
										<h4 class="widgettitle">&nbsp; Info Pembayaran </h4>
										<div class="widgetcontent">											
											<label>Payment Via <font style="color:red;">*</font></label>
											<span class="field">
												<select name="payment_method" id="payment_method" style="width:200px;" onchange="choosePaymentMethod()" >
													<option>- Pilih Satu -</option>
													<option value="ATM_TRANSFER" <?php if ($wallet_id == null && $order->order_status > 0) echo 'selected'?>>														
														ATM Transfer													
													</option>
													<option value="WALLET" 	<?php if ($wallet_id != null) echo 'selected'?>>Wallet</option>
													
													
												</select>
																																					
												<div id="block_wallet" <?php if ($wallet_id != null) echo 'style="display:block;"'; else echo 'style="display:none"';?>>				
													<a href="<?=base_url()?>index.php/wallet/payment/<?=$wallet_id?>" class="btn btn-warning" style="color:#fff">PROSES PEMBAYARAN</a>	
												</div>											
											</span>									
													
											<div id="block_atm_transfer" <?php if ($wallet_id == null && $order->order_status > 0) echo 'style="display:block;"'; else echo 'style="display:none;"'?>>																										
												<label>Status <font style="color:red;">*</font></label>												
												<span class="field">													
													<select name="order_status" id="order_status" style="width:200px;" >														
														<option value>- Pilih Satu -</option>														
														 <option value="0" <?php if ($order->order_status == '0') echo 'selected';?>>Belum Bayar</option>	
														<option value="1" <?php if ($order->order_status == '1') echo 'selected';?>>DP</option>			
														<option value="2" <?php if ($order->order_status == '2') echo 'selected';?>>Lunas</option>		
													</select>												
												</span>																										
												<label>Nominal Cash</label>												
												<span class="field">													
													<input type="text" class="input-medium" name="purchase_nominal_cash" id="purchase_nominal_cash" 
															value="<?php echo $order->purchase_nominal_cash; ?>"/> 													
															<select name="bank_account_id" id="bank_account_id" style="width:200px;">						
																<option value=''>- Select Bank -</option>													
																<?php foreach($bank_account as $account): ?>														
																	<option value="<?php echo $account->id?>" <?php if ($order->bank_account_id == $account->id) echo'selected'; ?>>
																			<?php echo $account->bank_account_name?>			
																	</option>	
																<?php endforeach; ?>													
															</select>												
												</span>																																								
												<label>Nominal Credit</label>												
												<span class="field">													
													<input type="text" class="input-medium" name="purchase_nominal_credit" id="purchase_nominal_credit" 		
														value="<?php echo trim($order->purchase_nominal_credit,"-")?>"/>												
												</span>																							
												<label>Tanggal Pembayaran</label>												
												<span class="field">													
														<input type="text" name="purchase_date" id ="purchase_date"  
															value="<?php if ($order->purchase_date != '1970-01-01') echo date("d-M-Y", strtotime($order->purchase_date)); else echo '';?>">		
												</span>											
											</div><!--block_atm_transfer--> 
										</div><!--widgetcontent-->
									</div><!--pembayaran-->								
									<?php } ?>
											
									<div class="span5" >
										<p>
											<label></label>
											<span style="margin-left: 12px;">
												<input type="submit" class="btn btn-primary" name="action" value="SUBMIT" />&nbsp;
												<input type="submit" class="btn btn-warning" name="action" value="APPLY" />&nbsp;
												<button type="reset" class="btn">RESET</button>
											</span>
										</p>
									</div>
									
								</div><!--#wiz1step1-->
									
								</form>
						</div>
				<?php endforeach; ?>
<script>
    $(document).ready(function(){
		$("#orderForm").validationEngine();
    });
	
	$(function() {
		$( "#order_date" ).datepicker({dateFormat: "dd-M-yy"});
		$( "#purchase_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
	
</script>

<script type="text/javascript">
// When document is ready: this gets fired before body onload :)
$(document).ready(function(){
	// Write on keyup event of keyword input element
	$("#inv_query").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#inv_table tbody>tr").hide();
			$("#inv_table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#inv_table tbody>tr").show();
		}
	});
});
// jQuery expression for case-insensitive filter
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

	function choosePaymentMethod() {
        if (document.getElementById('payment_method').value == 'ATM_TRANSFER') {
            document.getElementById('block_atm_transfer').style.display="block";			document.getElementById('block_wallet').style.display="none";
			
        } else if (document.getElementById('payment_method').value == 'WALLET') {
            document.getElementById('block_atm_transfer').style.display="none";			document.getElementById('block_wallet').style.display="block";
        }
    }
</script>


</body>
</html>

