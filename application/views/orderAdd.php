
<?php $session_data = $this->session->userdata('logged_in'); ?>	
				
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Masukkan Pesanan /  Order</h1>
	</div>
</div><!--pageheader-->
        
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
						<form id="orderForm" name="orderForm" class="stdform" method="post" action="<?=base_url()?>index.php/order/doAdd" />
							<div>
							
								<div class="span5 profile-left" id="cart">
									<h4 class="widgettitle">&nbsp; Daftar Produk : </h4>
									<div class="widgetcontent">
										<p>
											<label>Filter : </label>
											<span class="field">
												<input type="text" class="input-xlarge" name="inv_query" id="inv_query" 
														placeholder="Stock Name"/> <br/>
												
											<?php if ($session_data['user_role'] <> 'member') { ?>	
												<input id="special_price"  value="special_price" name="pricegroup" type="radio" onclick="priceGroup()" class="validate[required]" > 1-9 
															&nbsp;&nbsp;
												<input id="wholesale_price" value="wholesale_price" name="pricegroup" type="radio" onclick="priceGroup()">	>= 10 
											<?php } ?>
											</span>
										</p>
										<p>
											<table id="inv_table" class="table table-bordered" style="width: 100%;">
												<tr>
													<th class="center">Nama</th>
													<th class="center">Qty</th>
												</tr>
												<tbody class="zebra bordered">
													<?php foreach($stock as $item) { ?>
													<tr>
														<td><?=$item->product_name;?> - <?=$item->stock_desc;?> [Sisa : <b><?=$item->stock_qty;?></b>] &nbsp;&nbsp;</td>
														<!--td class="retail_price_column">90000</td>
														<td class="special_price_column">80000</td>
														<td class="wholesale_price_column">70000</td-->
														<td><input type="text" value="0" name="qty<?=$item->stock_id?>" id="qty<?=$item->stock_id?>" style="width:30px;"/></td>
														
													</tr>
													<?php } ?>
												
												</tbody>
											</table>
										</p>
									</div>
								</div>
								
								<div class="span5" id="penerima">
									<h4 class="widgettitle">&nbsp; Penerima / Consignee </h4>
										<div class="widgetcontent">
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Order VIA</label>
												<span class="field">
													<select name="order_channel" id="order_channel" class="input-medium">
														<option value=''>- Choose One -</option>
													<?php foreach($order_channel as $channel): ?>
														<option value="<?php echo $channel->option_id?>"><?php echo $channel->option_desc?></option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<?php } ?>
											<p>
												<label>Tanggal Order</label>
												<span class="field">
													<input type="text" name="order_date" id ="order_date" class="validate[required]" value="<?php echo date('d-M-Y')?>">
												</span>
											</p>
										<?php if ($billing == null) {?>
											<!-- ADD NEW CUSTOMER -->
											<p>
												<label>Nama Penerima <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_name" id="billing_name" /> 
												</span>
											</p>
											<p>
												<label>Alamat Jalan <font style="color:red;">*</font></label>
												<span class="field">
												
													<input type="text" class="input-xlarge" name="billing_street" id="billing_street" /> 
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_kec" id="billing_kec" /> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_city" id="billing_city" /> 
												</span>
											</p>
											
											<p>
												<label>Kode Pos <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_postal_code" id="billing_postal_code" /> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_prov" id="billing_prov" class="input-xlarge  validate[required]">
														<option value=''>- Choose One -</option>
													<?php foreach($provinces as $province): ?>
														<option value="<?php echo $province->option_id?>"> 
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
														<option value="Indonesia">Indonesia</option>
														<option value="Malaysia">Malaysia</option>
														<option value="Hong Kong">Hong Kong</option>
														<option value="Singapore">Singapore</option>
														<option value="Taiwan">Taiwan</option>
													</select>
												</span>
											</p>
											<p>
												<label>No Telp <font style="color:red;">*</font></label>
												<span class="field"><input type="text" name="billing_phone" id="billing_phone" class="input-medium" /></span>
											</p>
											
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Level<font style="color:red;"></font></label>
												<span class="field">
													<select name="billing_level" id="billing_level" class="input-xlarge validate[required]">
														<option value=''>- Choose One -</option>
													<?php foreach($billing_level as $level): ?>
														<option value="<?php echo $level->option_id?>"><?php echo $level->option_desc?></option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											
											<?php } ?>	
											<?php } else {
												foreach ($billing as $billing) :?>
											<!-- REPEAT CUSTOMER -->
												
											<input type="hidden" value="<?=$billing->billing_id?>" name="billing_id"/>	
											<p>
												<label>Nama Penerima <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_name" id="billing_name" 
														value="<?=$billing->billing_name?>"/> 
												</span>
											</p>
											<p>
												<label>Alamat Jalan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_street" id="billing_street" 
														value="<?=$billing->billing_street?>"/> 
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="billing_kec" id="billing_kec" 
														value="<?=$billing->billing_kec?>"/> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="billing_city" id="billing_city" 
														value="<?=$billing->billing_city?>"/> 
												</span>
											</p>
											<p>
												<label>Kode Pos <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="billing_postal_code" id="billing_postal_code" 
														value="<?=$billing->billing_postal_code?>"/> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_prov" id="billing_prov" class="input-xlarge  validate[required]">
														<option value=''>- Choose One -</option>
													<?php foreach($provinces as $province): ?>
														<option value="<?php echo $province->option_id?>" 
															<?php if ($billing->billing_prov == $province->option_id) echo 'selected';?>> 
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
														<option value="Indonesia" <?php if ($billing->billing_country == 'Indonesia') echo 'selected';?>>Indonesia</option>
														<option value="Malaysia" <?php if ($billing->billing_country == 'Malaysia') echo 'selected';?>>Malaysia</option>
														<option value="Hong Kong" <?php if ($billing->billing_country == 'Hong Kong') echo 'selected';?>>Hong Kong</option>
														<option value="Singapore" <?php if ($billing->billing_country == 'Singapore') echo 'selected';?>>Singapore</option>
														<option value="Taiwan" <?php if ($billing->billing_country == 'Taiwan') echo 'selected';?>>Taiwan</option>
													</select>
												</span>
											</p>
											<p>
												<label>No Telp <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" name="billing_phone" id="billing_phone" class="input-medium" 
														value="<?=$billing->billing_phone?>"/>
												</span>
											</p>
											<p>
												<label>Level<font style="color:red;"></font></label>
												<span class="field">
													<select name="billing_level" id="billing_level" class="input-xlarge  validate[required]">
														<option value=''>- Choose One -</option>
													<?php foreach($billing_level as $level): ?>
														<option value="<?php echo $level->option_id?>" 
															<?php if ($billing->billing_level == $level->option_id) echo 'selected'; ?>>
																<?php echo $level->option_desc?>
														</option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
										
											<?php endforeach;
											}?>
										</div>
								</div>
								
								<div class="span5" id="pengiriman">
										<h4 class="widgettitle">&nbsp; Pengiriman </h4>

										<div class="widgetcontent">
											
										<?php if ($last_order == null) {?>
											<p>
												<label>Nama Pengirim<font style="color:red;">*</font></label>
												<select name="shipper_id" id="shipper_id" class="input-xlarge  validate[required]">
														<option value=''>- Choose One -</option>
												<?php foreach($shippers as $shipper): ?>
													<option value="<?php echo $shipper->billing_id?>">
															<?php echo $shipper->billing_name?>
													</option>
												<?php endforeach; ?>
												</select>
											</p>
											
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Upline ?<font style="color:red;">*</font></label>
												<input id="yes"  value="yes" name="uplinestatus" type="radio" onclick="uplineStatus()" class="validate[required]" > Ya 
															&nbsp;&nbsp;
												<input id="no" value="no" checked name="uplinestatus" type="radio" onclick="uplineStatus()">	Bukan
											</p>
											<p>
												<label>Ekspedisi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="expedition" id="expedition" /> 
												</span>
											</p>
											<p>
												<label>Layanan Eksp <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="service" id="service" /> 
												</span>
											</p>
											<p>
												<label>Ongkir <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge" name="exp_cost" id="exp_cost" /> 
												</span>
											</p>
											<?php } ?>
										<?php } else {
										foreach ($last_order as $order) :?>
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
											</p>
											
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Ekspedisi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="expedition" id="expedition" 
														value="<?=$order->expedition?>"/> 
												</span>
											</p>
											<p>
												<label>Layanan Eksp <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="service" id="service" 
														value="<?=$order->service?>"/>
												</span>
											</p>
											<p>
												<label>Ongkir <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge " name="exp_cost" id="exp_cost" 
														value="<?=$order->exp_cost?>"/> 
												</span>
											</p>
											<?php } ?>
										<?php endforeach; }?>
										
											<?php if ($session_data['user_role'] <> 'member') { ?>	
											<p>
												<label>Penyesuaian Harga <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-large" name="adjustment_desc" placeholder="Deskripsi"/>
													<input type="text" class="input-medium" name="adjustment_nominal" placeholder="Nominal"/><br/>
													<small><i>Contoh : Deskripsi : Registrasi , Nominal : 50000 <br/>(Jika diskon masukkan simbol minus (-) terlebih dahulu)</i></small>
												</span>
											</p> 
											
											<p>
												<label>Barang Sudah Dikirim ?<font style="color:red;">*</font></label>
												<span class="field">
													<select name="package_status" id="package_status" style="width:200px;" class="validate[required]">
														<option value="0">Belum Dikirim</option>
														<option value="1">Sudah Dikirim</option>
													</select>
												</span>
											</p>                              
											<?php } ?>
										</div>
								</div>		
								
								
								<?php if ($session_data['user_role'] <> 'member') { ?>	
								<div class="span5 profile-right" id="pembayaran">
									<h4 class="widgettitle">&nbsp; Info Pembayaran </h4>
									<div class="widgetcontent">

										<p>
											<label>Metode Pembayaran <font style="color:red;">*</font></label>
											<span class="field">
												<select name="payment_method" id="payment_method" style="width:200px;" onchange="choosePaymentMethod()" >
													<option>- Pilih Satu -</option>
													<option value="ATM_TRANSFER">ATM Transfer</option>
													<option value="WALLET">Wallet</option>
												</select>
											</span>
										</p>
										
										<div id="block_atm_transfer" style="display:none;">
											<p>
												<label>Status Pembayaran <font style="color:red;">*</font></label>
												<span class="field">
													<select name="order_status" id="order_status" style="width:200px;" >
														<option value>- Pilih Satu -</option>
														<option value="0">Belum Bayar</option>
														<option value="1">DP</option>
														<option value="2">Lunas</option>
													</select>
												</span>
											</p>
											<p>
												<label>Nominal Cash</label>
												<span class="field">
													<input type="text" class="input-medium " name="purchase_nominal_cash" id="purchase_nominal_cash" /> 
													
													<select name="bank_account_id" id="bank_account_id" style="width:200px;">
														<option value=''>- Select Bank -</option>
													<?php foreach($bank_account as $account): ?>
														<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<p>
												<label>Nominal Credit</label>
												<span class="field">
													<input type="text" class="input-medium" name="purchase_nominal_credit" id="purchase_nominal_credit" /> 
											</p>
											<p>
												<label>Tanggal Pembayaran</label>
												<span class="field">
													<input type="text" name="purchase_date" id ="purchase_date">
												</span>
											</p>
										</div>
										<hr/>
										
								<?php } ?>
										
									</div>
									<div class="span5">
										<p>
											<label></label>
											<span style="margin-left: 12px;">
												<input type="submit" class="btn btn-primary" name="action" value="SUBMIT" />&nbsp;
												<input type="submit" class="btn btn-warning" name="action" value="APPLY" />&nbsp;
												<button type="reset" class="btn">RESET</button>&nbsp;
											</span>
										</p>
									</div>
								</div>	
							</div>
						</form>
						
				
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
            document.getElementById('block_atm_transfer').style.display="block";
			
        } else if (document.getElementById('payment_method').value == 'WALLET') {
            document.getElementById('block_atm_transfer').style.display="none";
        }
    }

	
</script>

</body>
</html>

