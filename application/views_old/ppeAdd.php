	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Adding PPE</h1>
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
						
						<form id="addppe" name="addppe" class="stdform" method="post" action="<?=base_url()?>index.php/ppe/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; ppe </h4>
								<div class="widgetcontent">
								<p>
									<label>Ppe Type<font style="color:red;">*</font></label>
									<span class="field">
										<select name="ppe_type_id" id="ppe_type_id" style="width:200px;" class="validate[required]">
											<option>- PPE Type -</option>
										<?php foreach($ppe_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="ppe_date" id ="ppe_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="ppe_desc" id="ppe_desc" /> 
									</span>
								</p>
								<p>
									<label>Nominal Cash<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-medium " name="ppe_nominal_cash" id="ppe_nominal_cash" /> 
										
										<select name="bank_account_id" id="bank_account_id" style="width:200px;">
											<option value=''>Select Bank</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Nominal Credit<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-medium" name="ppe_nominal_credit" id="ppe_nominal_credit" /> 
										
										<select name="liabilities_type_id" id="liabilities_type_id" style="width:200px;">
											<option value=''>Liability Type</option>
										<?php foreach($liabilities_type as $type2): ?>
											<option value="<?php echo $type2->option_id?>"><?php echo $type2->option_desc?></option>
										<?php endforeach; ?>
										</select>
								</p>
								<p>
									<label>Depreciation Nominal <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="depreciation_nominal" id="depreciation_nominal" /> 
									</span>
								</p>
								<p>
									<label>Age of PPE <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="ppe_age" id="ppe_age" /> 
									</span>
								</p>
								<p>
									<label>Interval Depreciation (in month)<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="interval_in_month" id="interval_in_month" /> 
									</span>
								</p>

								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/ppe/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addppe").validationEngine();
    });
	
	$(function() {
		$( "#ppe_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>