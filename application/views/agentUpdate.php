<?php foreach ($billing as $item): ?>
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Ubah Agen <?=$item->billing_name?></h1>
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
						
						<form id="customerForm" class="stdform" method="post" action="<?=base_url()?>index.php/agent/doUpdate/<?=$item->billing_id?>" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Detail List Agen</h4>
								<div class="widgetcontent">
									
									<p>
										
										<label>Level <font style="color:red;">*</font></label>
										<span class="field">
											<select name="billing_level" id="billing_level" class="input-xlarge">
												<option value=''>- Choose One -</option>
											<?php foreach($billing_levels as $level): ?>
												<option value="<?php echo $level->option_id?>"
													<?php if ($item->billing_level == $level->option_id) echo 'selected'; ?>> 
												
													 <?php echo $level->option_desc?>
												</option>
											<?php endforeach; ?>
											</select>
											
											Upline <font style="color:red;">*</font>
										
											<select name="billing_upline" id="billing_upline" class="input-large">
												<option value=''>- Choose Upline -</option>
											<?php foreach($billing_upline as $upline): ?>
												<option value="<?php echo $upline->billing_id?>"
													 <?php if ($item->billing_upline == $upline->billing_id) echo 'selected'; ?>> 
													 <?php echo $upline->billing_name?>
												</option>
											<?php endforeach; ?>
											</select>
										</span>
										
										<label>Nama <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_name" value="<?=$item->billing_name?>" id="billing_name" /> 
										</span>
									</p>
									
									<p>
										<label>Alamat Jalan <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_street" value="<?=$item->billing_street?>" id="billing_street" /> 
										</span>
									
										<label>Kecamatan <font style="color:red;"></font></label>
										<span class="field">
											<input type="text" class="input-large validate[required]" name="billing_kec" value="<?=$item->billing_kec?>" id="billing_kec" /> 
										</span>
									
										<label>Kota / Kab <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-large validate[required]" name="billing_city" value="<?=$item->billing_city?>" id="billing_city" /> 
										</span>
										
										<label>Kode Pos <font style="color:red;"></font></label>
										<span class="field">
											<input type="text" class="input-medium" name="billing_postal_code" value="<?=$item->billing_postal_code?>" 
												id="billing_postal_code" /> 
										</span>
										
										<label>Provinsi<font style="color:red;">*</font></label>
										<span class="field">
											<select name="billing_prov" id="billing_prov" class="input-xlarge">
												<option value=''>- Choose One -</option>
											<?php foreach($province as $prov): ?>
												<option value="<?php echo $prov->option_id?>" 
													<?php if ($item->billing_prov == $prov->option_id) echo 'selected'; ?>>
														<?php echo $prov->option_desc?>
												</option>
											<?php endforeach; ?>
											</select>
										</span>
										
										<label>Negara <font style="color:red;">*</font></label>
										<span class="field">
											<select name="billing_country" id="billing_country" style="width:200px;" class="validate[required]">
												<option value="Indonesia" <?php if ($item->billing_country == 'Indonesia') echo 'selected';?>>Indonesia</option>
												<option value="Malaysia" <?php if ($item->billing_country == 'Malaysia') echo 'selected';?>>Malaysia</option>
												<option value="Hong Kong" <?php if ($item->billing_country == 'Hong Kong') echo 'selected';?>>Hong Kong</option>
												<option value="Singapore" <?php if ($item->billing_country == 'Singapore') echo 'selected';?>>Singapore</option>
												<option value="Taiwan" <?php if ($item->billing_country == 'Taiwan') echo 'selected';?>>Taiwan</option>
											</select>
										</span>
									</p>
									<p>
										<label>No Telp <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_phone" value="<?=$item->billing_phone?>" id="billing_phone" 
											class="input-medium validate[required],custom[phone]" /></span>
										
										<label>Email <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_email" value="<?=$item->billing_email?>"
										id="billing_email" class="input-medium validate[required],custom[email]" /></span>
										
										<label>WhatsApp <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_whatsapp" value="<?=$item->billing_whatsapp?>" 
											id="billing_whatsapp" class="input-medium" /></span>
										
										<label>BBM <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_bbm" value="<?=$item->billing_bbm?>" id="billing_phone" class="input-medium" /></span>
										
										<label>FB Profile<font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_facebook" value="<?=$item->billing_facebook?>" id="billing_facebook" class="input-medium" /></span>
										
										<label>Fanspage<font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_fanpage" value="<?=$item->billing_fanpage?>" id="billing_fanpage" class="input-medium" /></span>
										
										<label>Twitter <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_twitter" value="<?=$item->billing_twitter?>" id="billing_twitter" class="input-medium" /></span>
										
										<label>IG <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_instagram" value="<?=$item->billing_instagram?>" id="billing_instagram" class="input-medium" /></span>
										
										<label>Web <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_web" value="<?=$item->billing_web?>" id="billing_web" class="input-medium" /></span>
									</p>
									
									<p>
										<label>Status<font style="color:red;">*</font></label>
										<span class="field">
											<select name="agen_status" id="agen_status" class="input-xlarge">
												<option value=''>- Choose One -</option>
											<?php foreach($agen_status as $status): ?>
												<option value="<?php echo $status->option_id?>" 
													<?php if ($item->billing_flag1 == $status->option_id) echo 'selected'; ?>>
														<?php echo $status->option_desc?>
												</option>
											<?php endforeach; ?>
											</select>
										</span>
										<label>Agent Code<font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" name="agent_code" id="agent_code" class="input-medium" />
										</span>
									</p>
									<p class="stdformbutton">
										<button class="btn btn-primary">SUBMIT</button>
										<button type="reset" class="btn">RESET</button>
									</p>
								</div>
							
								
						</div><!--#wiz1step1-->
					</form>
</div>				<?php endforeach;?>
				                        
<script>
</script>
