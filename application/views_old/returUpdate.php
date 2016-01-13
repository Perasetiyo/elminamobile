	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Ubah Order </h1>
	</div>
</div><!--pageheader-->
    <?php foreach ($orders as $order) :?>
        <div class="maincontent">
            <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
					<div class="span8">
						<div class="widgetbox personal-information">
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
										<h4 class="widgettitle">&nbsp; Cart </h4>
										<div class="widgetcontent">
											<p>
												<label>Shopping Cart</label>
													<span class="field">
														<input type="text" class="input-xlarge validate[required]" name="inv_query" id="inv_query" 
																placeholder="Stock Name"/> 
														<input type="submit" class="btn btn-danger" name="action" value="CETAK NOTA" />
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
										<h4 class="widgettitle">&nbsp; Pengiriman </h4>
										<div class="widgetcontent">
											<p>
												<label>Ekspedisi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="expedition" id="expedition" 
														value="<?=$order->expedition?>"/> 
												</span>
											</p>
											<p>
												<label>Layanan Eksp <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="service" id="service" 
														value="<?=$order->service?>"/> 
												</span>
											</p>
											<p>
												<label>Ongkir <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="exp_cost" id="exp_cost" 
														value="<?=$order->exp_cost?>"/> 
												</span>
											</p>                              
											<p>
												<label>Diskon <font style="color:red;"></font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="discount_amount" id="discount_amount" 
														value="<?=$order->discount_amount?>"/> 
												</span>
											</p> 

											<p>
												<label>Barang Sudah Dikirim ?<font style="color:red;">*</font></label>
												<span class="field">
													<select name="package_status" id="package_status" style="width:200px;" class="validate[required]">
														<option value="0" <?php if ($order->package_status == 0) echo 'selected';?>>Belum Dikirim</option>
														<option value="1" <?php if ($order->package_status == 1) echo 'selected';?>>Sudah Dikirim</option>
													</select>
												</span>
											</p> 											
										</div>
										
										<h4 class="widgettitle">&nbsp; Penerima / Consignee </h4>
										<input type="hidden" value="<?=$order->billing_id?>" name="billing_id"/>
										<div class="widgetcontent">
											<p>
												<label>Tanggal Order</label>
												<span class="field">
													<input type="text" name="order_date" id ="order_date"  value="<?php echo date("d-M-Y", strtotime($order->order_date))?>">
												</span>
											</p>
											<p>
												<label>Nama Penerima <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_name" id="billing_name" 
														value="<?=$order->billing_name?>"/> 
												</span>
											</p>
											<p>
												<label>Alamat Jalan <font style="color:red;">*</font></label>
												<span class="field">
													<textarea class="input-xlarge validate[required]" name="billing_street" id="billing_street">
														<?=$order->billing_street?>
													</textarea>
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_kec" id="billing_kec" 
														value="<?=$order->billing_kec?>" /> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_city" id="billing_city" 
														value="<?=$order->billing_city?>"/> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="billing_prov" id="billing_prov" 
														value="<?=$order->billing_prov?>"/> 
												</span>
											</p>
											<p>
												<label>Negara <font style="color:red;">*</font></label>
												<span class="field">
													<select name="billing_country" id="billing_country" style="width:200px;" class="validate[required]">
														<option value="Indonesia" <?php if ($order->billing_country == 'Indonesia') echo 'selected';?>>Indonesia</option>
														<option value="Malaysia" <?php if ($order->billing_country == 'Malaysia') echo 'selected';?>>Malaysia</option>
														<option value="Hong Kong" <?php if ($order->billing_country == 'Hong Kong') echo 'selected';?>>Hong Kong</option>
														<option value="Singapore" <?php if ($order->billing_country == 'Singapore') echo 'selected';?>>Singapore</option>
														<option value="Taiwan" <?php if ($order->billing_country == 'Taiwan') echo 'selected';?>>Taiwan</option>
													</select>
												</span>
											</p>
											<p>
												<label>No Telp <font style="color:red;">*</font></label>
												<span class="field"><input type="text" name="billing_phone" id="billing_phone" 
										 			class="input-medium validate[required],custom[phone]" value="<?=$order->billing_phone?>"/></span>
											</p>
											<p>												
												<label>Level<font style="color:red;"></font></label>
												<span class="field">
													<select name="billing_level" id="billing_level" class="input-xlarge">
														<option value=''>- Choose One -</option>
													<?php foreach($billing_level as $level): ?>
														<option value="<?php echo $level->option_id?>" 
															<?php if ($order->billing_level == $level->option_id) echo 'selected'; ?>>
																<?php echo $level->option_desc?>
														</option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
										</div>
										<h4 class="widgettitle">&nbsp; Info Pembayaran </h4>
										<div class="widgetcontent">
											<p>
												<label>Status Pembayaran <font style="color:red;">*</font></label>
												<span class="field">
													<select name="order_status" id="order_status" style="width:200px;" class="validate[required]">
														<option value>- Pilih Satu -</option>
														<option value="0" <?php if ($order->order_status == '0') echo 'selected';?>>Belum Bayar</option>
														<option value="1" <?php if ($order->order_status == '1') echo 'selected';?>>DP</option>
														<option value="2" <?php if ($order->order_status == '2') echo 'selected';?>>Lunas</option>
													</select>
												</span>
											</p>
											<p>
												<label>Nominal Cash</label>
												<span class="field">
													<input type="text" class="input-medium" name="purchase_nominal_cash" id="purchase_nominal_cash" 
														value="<?php echo $order->purchase_nominal_cash; ?>"/> 
													
													<select name="bank_account_id" id="bank_account_id" style="width:200px;">
														<option value=''>- Select Bank -</option>
													<?php foreach($bank_account as $account): ?>
														<option value="<?php echo $account->id?>" <?php if ($order->bank_account_id == $account->id) echo 'selected'; ?>>
															<?php echo $account->bank_account_name?>
													</option>
													<?php endforeach; ?>
													</select>
												</span>
											</p>
											<p>
												<label>Nominal Credit</label>
												<span class="field">
													<input type="text" class="input-medium" name="purchase_nominal_credit" id="purchase_nominal_credit" 
														value="<?php echo trim($order->purchase_nominal_credit,"-")?>"/>
											</p>
											<p>
												<label>Tanggal Pembayaran</label>
												<span class="field">
													<input type="text" name="purchase_date" id ="purchase_date"  value="<?php if ($order->purchase_date != '0000-00-00') echo date("d-M-Y", strtotime($order->purchase_date)); else echo '';?>">
												</span>
											</p>
										</div>
										<h4 class="widgettitle">&nbsp; Pengirim / Shipper </h4>
										<input type="hidden" value="<?=$order->shipper_id?>" name="shipper_id"/>
										<div class="widgetcontent">
											<p>
												<label>Nama Pengirim <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_name" 
														id="shipper_name" value="<?=$order->shipper_name?>"/>
												</span>
											</p>
											<p>
												<label>Alamat Jalan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_street" 
														id="billing_street" value="<?=$order->shipper_street?>"/> 
												</span>
											</p>
											<p>
												<label>Kecamatan <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_kecamatan" 
														id="shipper_kec" value="<?=$order->shipper_kecamatan?>"/> 
												</span>
											</p>
											<p>
												<label>Kota / Kabupaten <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_city" 
														id="billing_city" value="<?=$order->shipper_city?>"/> 
												</span>
											</p>
											<p>
												<label>Provinsi <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" class="input-xlarge validate[required]" name="shipper_prov" 
														id="shipper_prov" value="<?=$order->shipper_prov?>"/>
												</span>
											</p>
											<p>
												<label>Telp. Pengirim <font style="color:red;">*</font></label>
												<span class="field">
													<input type="text" value="<?=$order->shipper_phone?>" class="input-medium validate[required]" name="shipper_phone" id="shipper_phone" />
												</span>
											</p>
											
											<p class="stdformbutton">
												<input type="submit" class="btn btn-primary" name="action" value="UPDATE" />
												<button type="reset" class="btn">RESET</button>
											</p>
										</div>
									</div><!--#wiz1step1-->
								</form>
						</div>
				<?php endforeach; ?>
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

