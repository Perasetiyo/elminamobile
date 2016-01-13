	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Money Repaid</h1>
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
						
						<form id="addliabilities" name="addliabilities" class="stdform" method="post" action="<?=base_url()?>index.php/liabilities/doRepaid" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Money Repaid </h4>
								<div class="widgetcontent">
								<p>
									<label>Liability Type<font style="color:red;">*</font></label>
									<span class="field">
										<select name="liabilities_type_id" id="liabilities_type_id" style="width:200px;" class="validate[required]">
											<option value>- Liability Type - </option>
										<?php foreach($liability_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Paid For<font style="color:red;">*</font></label>
									<span class="field">
										<select name="repaid_type_id" id="repaid_type_id" style="width:200px;" class="validate[required]">
											<option value>- Select Below - </option>
										<?php foreach($paid_for_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="repaid_date" id ="repaid_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="repaid_desc" id="repaid_desc" /> 
									</span>
								</p>
								
								<p>
									<label>Pay With <font style="color:red;">*</font></label>
									<span class="field">
										<select name="asset_code" id="asset_code" style="width:200px;" class="validate[required]" onchange="chooseAsset()">
											<option value>- Asset Type -</option>
										<?php foreach($asset_type as $asset): ?>
											<option value="<?php echo $asset->option_code?>"><?php echo $asset->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
										
								
								<p>
									<label>Nominal<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium" name="repaid_nominal" id="repaid_nominal" class="validate[required]"/>
										
										<select name="bank_account_id" id="bank_account_id" style="width:200px; display:none;" class="validate[required]">
											<option value>- Bank Account -</option>
										<?php foreach($bank_account as $bank): ?>
											<option value="<?php echo $bank->id?>"><?php echo $bank->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/liabilities/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addliabilities").validationEngine();
    });
	
	$(function() {
		$( "#repaid_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
	
	
	function chooseAsset() {
        if (document.getElementById('asset_code').value == 'CASH') {
            document.getElementById('bank_account_id').style.display="block";
        } else {
            document.getElementById('bank_account_id').style.display="none";
        }
    }
</script>
