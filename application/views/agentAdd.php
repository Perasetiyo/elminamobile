<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Input Agent</h1>
	</div>
</div><!--pageheader-->
        
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
				
						<form id="customerForm" class="stdform" method="post" action="<?=base_url()?>index.php/agent/doAdd" />
							<div id="wiz1step1" class="formwiz">
							<h4 class="widgettitle">&nbsp; Tambah Agen / Reseller</h4>
								<div class="widgetcontent">
									<p>
										<label>Level <font style="color:red;">*</font></label>
										<span class="field">
											<select name="billing_level" id="billing_level" class="input-medium">
												<option value=''>- Select -</option>
											<?php foreach($billing_levels as $level): ?>
												<option value="<?php echo $level->option_id?>"> 
													 <?php echo $level->option_desc?>
												</option>
											<?php endforeach; ?>
											</select>
										
											Upline <font style="color:red;">*</font>
										
											<select name="billing_upline" id="billing_upline" class="input-large">
												<option value=''>- Choose Upline -</option>
											<?php foreach($billing_upline as $upline): ?>
												<option value="<?php echo $upline->billing_id?>"> 
													 <?php echo $upline->billing_name?>
												</option>
											<?php endforeach; ?>
											</select>
										</span>
										
										<label>Nama <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_name" id="billing_name" /> 
										</span>
									</p>
									
									<p>
										<label>Alamat Jalan <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_street" id="billing_street" /> 
										</span>
									
										<label>Kecamatan <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-large validate[required]" name="billing_kec" id="billing_kec" /> 
										</span>
									
										<label>Kota / Kab <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-large validate[required]" name="billing_city" id="billing_city" /> 
										</span>
									
										<label>Kode Pos <font style="color:red;"></font></label>
										<span class="field">
											<input type="text" class="input-large validate[required]" name="billing_postal_code" id="billing_postal_code" /> 
										</span>
									
										<label>Provinsi <font style="color:red;">*</font></label>
										<span class="field">
											<select name="billing_prov" id="billing_prov" class="input-xlarge">
												<option value=''>- Choose One -</option>
											<?php foreach($province as $prov): ?>
												<option value="<?php echo $prov->option_id?>"> 
													 <?php echo $prov->option_desc?>
												</option>
											<?php endforeach; ?>
											</select>
										</span>
										
										
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
										
										<label>Email <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_email" id="billing_email" class="input-medium validate[required],custom[email]" /></span>
										
										<label>WhatsApp <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_whatsapp" id="billing_whatsapp" class="input-medium"/></span>
										
										<label>BBM <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_bbm" id="billing_phone" class="input-medium" /></span>
										
										<label>FB Profile<font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_facebook" id="billing_facebook" class="input-medium" /></span>
										
										<label>Fanspage<font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_fanpage" id="billing_fanpage" class="input-medium" /></span>
										
										<label>Twitter <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_twitter" id="billing_twitter" class="input-medium" /></span>
										
										<label>IG <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_instagram" id="billing_instagram" class="input-medium" /></span>
										
										<label>Web <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_web" id="billing_web" class="input-medium" /></span>
									</p>
									<p class="stdformbutton">
										<button class="btn btn-primary">SUBMIT</button>
										<button type="reset" class="btn">RESET</button>
									</p>
								</div>	
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
</script>
