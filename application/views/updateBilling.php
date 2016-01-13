<?php foreach ($billing as $item): ?>
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Ubah List Building <?=$item->billing_name?></h1>
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
						
						<form id="billingForm" name="billingForm" class="stdform" method="post" action="<?=base_url()?>index.php/billingAction/doUpdateBilling/<?=$item->billing_id?>" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Detail List Building</h4>
								<div class="widgetcontent">
									<p>
										<label>Nama Penerima <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_name" value="<?=$item->billing_name?>" id="billing_name" /> 
										</span>
									</p>
									<p>
										<label>Alamat Jalan <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_street" value="<?=$item->billing_street?>" id="billing_street" /> 
										</span>
									</p>
									<p>
										<label>Kecamatan <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_kec" value="<?=$item->billing_kec?>" id="billing_kec" /> 
										</span>
									</p>
									<p>
										<label>Kota / Kabupaten <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_city" value="<?=$item->billing_city?>" id="billing_city" /> 
										</span>
									</p>
									<p>
										<label>Provinsi <font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-xlarge validate[required]" name="billing_prov" value="<?=$item->billing_prov?>" id="billing_prov" /> 
										</span>
										</p>
									<p>
										<label>Negara <font style="color:red;">*</font></label>
										<span class="field">
											<select name="billing_country" id="billing_country" style="width:200px;" class="validate[required]">
												<option value="Indonesia">Indonesia</option>
											</select>
										</span>
									</p>
									<p>
										<label>No Telp <font style="color:red;">*</font></label>
										<span class="field"><input type="text" name="billing_phone" value="<?=$item->billing_phone?>" id="billing_phone" class="input-medium validate[required],custom[phone]" /></span>
									</p>
									<p>
										<label>Reseller ?<font style="color:red;">*</font></label>
										<span class="field">
											<select name="is_reseller" id="is_reseller" style="width:200px;" class="validate[required]">
												<option>Pilih Satu</option>
												<option value="0" <?php if ($item->is_reseller == '0') echo 'selected'; ?> >Bukan</option>
												<option value="1" <?php if ($item->is_reseller == '1') echo 'selected'; ?>>Ya</option>
											</select>
										</span>
									</p>
									<p>
										<label>Pelanggan Dropshipper ?<font style="color:red;">*</font></label>
										<span class="field">
											<input id="is_ds" name="is_ds" type="checkbox" onclick="validateDS()" <?php if ($item->shipper_name != "") echo 'checked' ?>/>
										</span>
									</p>
									<div id="dropshipForm" style="display:none;">
									<p>
										<label><font style="color:blue;">Nama Dropshipper</font></label>
										<span class="field"><input type="text" name="shipper_name" value="<?=$item->shipper_name?>" id="shipper_name" class="input-medium validate[required],custom[phone]" /></span>
									</p>
									<p>
										<label><font style="color:blue;">Telp Dropshipper</font></label>
										<span class="field"><input type="text" name="shipper_phone" value="<?=$item->shipper_phone?>" id="shipper_phone" class="input-medium validate[required],custom[phone]" /></span>
									</p>
									</div>
									<p class="stdformbutton">
										<button class="btn btn-primary">SUBMIT</button>
										<button type="reset" class="btn">RESET</button>
									</p>
								</div>
							
								
						</div><!--#wiz1step1-->
					</form>
</div>				<?php endforeach;?>
				                        
<script>
    $(document).ready(function(){
		$("#billing_form_").validationEngine();
		if (document.getElementById('is_ds').checked) {
            document.getElementById('dropshipForm').style.display="block";
        }
    });
	
	
	function validateDS() {
        if (document.getElementById('is_ds').checked) {
            document.getElementById('dropshipForm').style.display="block";
        } else {
            document.getElementById('dropshipForm').style.display="none";
        }
    }
</script>