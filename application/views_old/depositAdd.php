	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Adding Deposit</h1>
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
						
						<form id="adddeposit" name="adddeposit" class="stdform" method="post" action="<?=base_url()?>index.php/deposit/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Deposit </h4>
								<div class="widgetcontent">
								<p>
									<label>Depositor / Customer  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" disabled name="billing_name" id ="billing_name" class="validate[required]"
											value="<?=$billing_name?>"/>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="deposit_date" id ="deposit_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="deposit_desc" id="deposit_desc" /> 
									</span>
								</p>
								<p>
									<label>Nominal<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium" name="deposit_nominal" id="deposit_nominal" class="validate[required]"/>
										<select name="bank_account_id" id="bank_account_id" style="width:200px;" class="validate[required]">
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
									<a href="<?=base_url()?>index.php/deposit/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#adddeposit").validationEngine();
    });
	
	$(function() {
		$( "#deposit_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>