	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Tambah Bank Account </h1>
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
						
						<form id="billingForm" name="billingForm" class="stdform" method="post" action="<?=base_url()?>index.php/bankAccountAction/doAddBankAccount" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Detail Info Bank Account </h4>
								<div class="widgetcontent">
								<p>
									<label>Nama Account <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="bank_account_name" id="bank_account_name" /> 
									</span>
								</p>
								<p>
									<label>Saldo <font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="ba_saldo" id="ba_saldo" /> 
									</span>
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
    $(document).ready(function(){
		$("#billing_form_").validationEngine();
    });
</script>