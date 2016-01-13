	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Input Order / Sales</h1>
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
							
								<div class="span10 profile-left" id="cart">
									<h4 class="widgettitle">&nbsp; Cart </h4>
									<div class="widgetcontent">
										<p>
											<label>Shopping Cart</label>
											<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="inv_query" id="inv_query" 
														placeholder="Stock Name"/> 
											</span>
										</p>
										<p>
											<table id="inv_table" class="table table-bordered" style="width: 60%; margin-left:120px;">
												<tr>
													<th>Name</th>
													<th>Qty</th>
												</tr>
												<tbody class="zebra bordered">
													<?php foreach($stock as $item) { ?>
													<tr>
														<td><?=$item->product_name;?> - <?=$item->stock_desc;?> [Sisa : <b><?=$item->stock_qty;?></b>] &nbsp;&nbsp;</td>
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
											<p>
												<label>Tanggal Order</label>
												<span class="field">
													<input type="text" name="order_date" id ="order_date" class="validate[required]">
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
												
													<input type="text" class="input-xlarge validate[required]" name="billing_street" id="billing_street" /> 
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_kec" id="billing_kec" /> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_city" id="billing_city" /> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_prov" id="billing_prov" /> 
												</span>
											</p>
											<p>
												<label>Negara <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_country" id="billing_country" style="width:200px;" class="validate[required]">
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
												<span class="field"><input type="text" name="billing_phone" id="billing_phone" class="input-medium validate[required],custom[phone]" /></span>
											</p>
											<p>
												<label>Level<font style="color:red;"></font></label>
												<span class="field">
													<select name="billing_level" id="billing_level" class="input-xlarge">
														<option value=''>- Choose One -</option>
													<?php foreach($billing_level as $level): ?>
														<option value="<?php echo $level->option_id?>"><?php echo $level->option_desc?></option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<?php } else {
												foreach ($billing as $billing) :?>
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
													<input type="text" class="input-xlarge validate[required]" name="billing_street" id="billing_street" 
														value="<?=$billing->billing_street?>"/> 
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_kec" id="billing_kec" 
														value="<?=$billing->billing_kec?>"/> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_city" id="billing_city" 
														value="<?=$billing->billing_city?>"/> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_prov" id="billing_prov" 
														value="<?=$billing->billing_prov?>"/> 
												</span>
											</p>
											<p>
												<label>Negara <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_country" id="billing_country" style="width:200px;" class="validate[required]">
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
													<input type="text" name="billing_phone" id="billing_phone" class="input-medium validate[required],custom[phone]" 
														value="<?=$billing->billing_phone?>"/>
												</span>
											</p>
											<p>
												<label>Level<font style="color:red;"></font></label>
												<span class="field">
													<select name="billing_level" id="billing_level" class="input-xlarge">
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
								<div class="span5" id="pengirim">
										<h4 class="widgettitle">&nbsp; Pengirim / Shipper </h4>
										<div class="widgetcontent">
											<p>
												<label>Nama Pengirim <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" value = "ELMINA ONLINE STORE" class="input-xlarge validate[required]" name="shipper_name" id="shipper_name" />
												</span>
											</p>
											<p>
												<label>Alamat Jalan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_street" id="billing_street" 
														value="Jl. Saleh No. 18 Kelurahan. Sukabumi Utara Rawa Belong RT 05 / RW 11"/> 
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_kecamatan" id="billing_kec" 
														value="Kebon Jeruk"/> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_city" id="billing_city" 
														value="Jakarta Barat"/> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" value = "Jakarta" class="input-xlarge validate[required]" name="shipper_prov" id="shipper_prov" />
												</span>
											</p>
											<p>
												<label>Telp. Pengirim <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" value = "0838 9872 0334" class="input-medium validate[required]" name="shipper_phone" id="shipper_phone" />
												</span>
											</p>
									</div>
								</div>
								<div class="clear"></div>
								<div class="span5" id="pengiriman">
										<h4 class="widgettitle">&nbsp; Pengiriman </h4>
										<div class="widgetcontent">
										<?php if ($last_order == null) {?>
											<p>
												<label>Ekspedisi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="expedition" id="expedition" /> 
												</span>
											</p>
											<p>
												<label>Layanan Eksp <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="service" id="service" /> 
												</span>
											</p>
											<p>
												<label>Ongkir <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="exp_cost" id="exp_cost" /> 
												</span>
											</p>
										<?php } else {
										foreach ($last_order as $order) :?>
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
													<input type="text" class="input-xlarge validate[required]" name="service" id="service" 
														value="<?=$order->service?>"/>
												</span>
											</p>
											<p>
												<label>Ongkir <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="exp_cost" id="exp_cost" 
														value="<?=$order->exp_cost?>"/> 
												</span>
											</p>
										<?php endforeach; }?>
											<p>
												<label>Diskon <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="discount_amount" id="discount_amount" /> 
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
										</div>
								</div>		
								<div class="span5 profile-right" id="pembayaran">
									<h4 class="widgettitle">&nbsp; Info Pembayaran </h4>
									<div class="widgetcontent">
														
										<p>
											<label>Status Pembayaran <font style="color:red;">*</font></label>
											<span class="field">
												<select name="order_status" id="order_status" style="width:200px;" class="validate[required]">
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
										
										
										<p>
											<span class="field">
												<button class="btn btn-primary">SUBMIT</button>
												<button type="reset" class="btn">RESET</button>
											</span>
										</p>
									</div>
								</div>
								
								
							</div>
						</form>
						
				
<script>
    $(document).ready(function(){
		$("#orderForm_").validationEngine();
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

</script>

</body>
</html>

