<?php foreach ($deposit as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Deposit of <?=$item->billing_name?></h1>
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
						
						<form id="adddeposit" name="adddeposit" class="stdform" method="post" action="<?=base_url()?>index.php/deposit/doUpdate/" />
							<input type="hidden" value="<?=$item->deposit_id?>" name="deposit_id"/>
							
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Deposit </h4>
								<div class="widgetcontent">
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="deposit_date" id ="deposit_date" class="validate[required]" value="<?php if ($item->deposit_date != '0000-00-00') echo date("d-M-Y", strtotime($item->deposit_date)); else echo '';?>"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="deposit_desc" id="deposit_desc" value="<?=$item->deposit_desc?>"/> 
									</span>
								</p>
								<p>
									<label>Nominal<font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-medium" name="deposit_nominal" id="deposit_nominal" 
												value="<?php echo trim($item->deposit_nominal,"-")?>"/>
										
											<select name="bank_account_id" id="bank_account_id" style="width:200px;" class="validate[required]">
												<option value>- Bank Account -</option>
											<?php foreach($bank_account as $bank): ?>
												<option value="<?php echo $bank->id?>" <?php if ($item->bank_account_id == $bank->id) echo 'selected'; ?>><?php echo $bank->bank_account_name?></option>
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
<?php endforeach;?>				                        
<script>
    $(document).ready(function(){
		$("#adddeposit").validationEngine();
    });
	
	$(function() {
		$( "#deposit_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>