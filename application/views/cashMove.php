	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Moving Cash</h1>
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
						
						<form id="addacrec" name="addacrec" class="stdform" method="post" action="<?=base_url()?>index.php/cash/pindahin" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Pemindahan Cash </h4>
								<div class="widgetcontent">
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="cash_date" id ="cash_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="cash_desc" id="cash_desc" 
											value="Pemindahan Kas"/> 
									</span>
								</p>
								<p>
									<label>Nominal<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="cash_nominal" id="cash_nominal" /> 
									</span>
								</p>
								<p>
									<label>Dari<font style="color:red;"></font></label>
									<span class="field">
										<select name="bank_account_id_out" id="bank_account_id" style="width:200px;" class="validate[required]">
											<option value=''>- Select Bank -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
										Ke
										<select name="bank_account_id_in" id="bank_account_id" style="width:200px;" class="validate[required]">
											<option value=''>- Select Bank -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
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
		$( "#cash_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>