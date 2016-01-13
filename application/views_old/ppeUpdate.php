<?php foreach ($ppe as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit PPE <?=$item->ppe_desc?></h1>
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
						
						<form id="addPPE" name="addPPE" class="stdform" method="post" action="<?=base_url()?>index.php/ppe/doUpdate/" />
							<input type="hidden" value="<?=$item->main_ppe_id?>" name="ppe_id"/>
							
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; PPE </h4>
								<div class="widgetcontent">
								<p>
									<label>PPE Type<font style="color:red;">*</font></label>
									<span class="field">
										<select name="ppe_type_id" id="ppe_type_id" style="width:200px;" class="validate[required]">
											<option>- PPE Type -</option>
										<?php foreach($ppe_type as $type): ?>
											<option value="<?php echo $type->option_id?>" <?php if ($item->ppe_type_id == $type->option_id) echo 'selected'; ?>><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="ppe_date" id ="ppe_date" class="validate[required]" value="<?php if ($item->ppe_date != '0000-00-00') echo date("d-M-Y", strtotime($item->ppe_date)); else echo '';?>"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="ppe_desc" id="ppe_desc" value="<?=$item->ppe_desc?>"/> 
									</span>
								</p>
								<p>
									<label>Nominal Cash<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-medium" name="ppe_nominal_cash" id="ppe_nominal_cash" 
											value="<?php echo trim($item->cash_nominal,"-")?>"/> 
										
										<select name="bank_account_id" id="bank_account_id" style="width:200px;">
											<option value=''>Select Bank</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>" <?php if ($item->bank_account_id == $account->id) echo 'selected'; ?>>
												<?php echo $account->bank_account_name?>
										</option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Nominal Credit<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-medium" name="ppe_nominal_credit" id="ppe_nominal_credit" 
											value="<?php echo trim($item->liabilities_nominal,"-")?>"/>
										
										<select name="liabilities_type_id" id="liabilities_type_id" style="width:200px;">
											<option value=''>Liability Type</option>
										<?php foreach($liabilities_type as $type2): ?>
											<option value="<?php echo $type2->option_id?>" <?php if ($item->liabilities_type_id == $type2->option_id) echo 'selected'; ?>>
												<?php echo $type2->option_desc?>
											</option>
										<?php endforeach; ?>
										</select>
								</p>
								<p>
									<label>Depreciation Nominal <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" value="<?=$item->depreciation_nominal?>" name="depreciation_nominal" id="depreciation_nominal" /> 
									</span>
								</p>
								<p>
									<label>Age of PPE <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" value="<?=$item->ppe_age?>" name="ppe_age" id="ppe_age" /> 
									</span>
								</p>
								<p>
									<label>Interval Depreciation (in month)<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" value="<?=$item->interval_in_month?>" name="interval_in_month" id="interval_in_month" /> 
									</span>
								</p>
								<p>
									<label>Current Period</label>
									<span class="field">
										<input type="text" class="input-small" disabled value="<?=$item->ppe_current_period?>"/>
									</span>
								</p>
								<p>
									<label>Net Price</label>
									<span class="field">
										<input type="text" class="input-small" disabled value="<?php echo $item->ppe_nominal - $item->ppe_has_been_paid?>"/>
									
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
<?php endforeach;?>				                        
<script>
    $(document).ready(function(){
		$("#addPPE").validationEngine();
    });
	
	$(function() {
		$( "#ppe_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>