	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Adding Equity</h1>
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
						
						<form id="addequity" name="addequity" class="stdform" method="post" action="<?=base_url()?>index.php/equity/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Add Equity </h4>
								<div class="widgetcontent">
								<p>
									<label>Equity Type<font style="color:red;">*</font></label>
									<span class="field">
										<select name="equity_type_id" id="equity_type_id" style="width:200px;" class="validate[required]">
											<option>Equity Type</option>
										<?php foreach($equity_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="equity_date" id ="equity_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="equity_desc" id="equity_desc" /> 
									</span>
								</p>
								<p>
									<label>Nominal Cash<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="equity_nominal_cash" id="equity_nominal_cash" /> 
										
										<select name="bank_account_id" id="bank_account_id" style="width:200px;" class="validate[required]">
											<option value=''>Select Bank</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<!--p>
									<label>Nominal Credit<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="equity_nominal_credit" id="equity_nominal_credit" /> 
										
										<select name="liabilities_type_id" id="liabilities_type_id" style="width:200px;" class="validate[required]">
											<option value=''>Liability Type</option>
										<?php foreach($liabilities_type as $type2): ?>
											<option value="<?php echo $type2->option_id?>"><?php echo $type2->option_desc?></option>
										<?php endforeach; ?>
										</select>
								</p-->
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/equity/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addequity").validationEngine();
    });
	
	$(function() {
		$( "#equity_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>