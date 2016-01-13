	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Adding Account Receivable</h1>
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
						
						<form id="addacrec" name="addacrec" class="stdform" method="post" action="<?=base_url()?>index.php/acrec/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Add Account Receivable </h4>
								<div class="widgetcontent">
								<p>
									<label>AR Type<font style="color:red;">*</font></label>
									<span class="field">
										<select name="acrec_type_id" id="acrec_type_id" style="width:200px;" class="validate[required]">
											<option>- Choose Type -</option>
										<?php foreach($acrec_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="acrec_date" id ="acrec_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="acrec_desc" id="acrec_desc" /> 
									</span>
								</p>
								<p>
									<label>Nominal<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="acrec_nominal" id="acrec_nominal" /> 
										
										<select name="bank_account_id" id="bank_account_id" style="width:200px;" class="validate[required]">
											<option value=''>- Select Bank -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<!--p>
									<label>Nominal Credit<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="acrec_nominal_credit" id="acrec_nominal_credit" /> 
										
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
									<a href="<?=base_url()?>index.php/acrec/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addacrec").validationEngine();
    });
	
	$(function() {
		$( "#acrec_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>