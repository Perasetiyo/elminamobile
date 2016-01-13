<?php foreach ($liabilities as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Liabilities <?=$item->liabilities_desc?></h1>
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
						
						<form id="addliabilities" name="addliabilities" class="stdform" method="post" action="<?=base_url()?>index.php/liabilities/doUpdate/" />
							<input type="hidden" value="<?=$item->liabilities_id?>" name="liabilities_id"/>
							
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Liabilities </h4>
								<div class="widgetcontent">
								<p>
									<label>Liabilities Type<font style="color:red;">*</font></label>
									<span class="field">
										<select name="liabilities_type_id" id="liabilities_type_id" style="width:200px;" class="validate[required]">
											<option>- Liabilities Type -</option>
										<?php foreach($liabilities_type as $type): ?>
											<option value="<?php echo $type->option_id?>" <?php if ($item->liabilities_type_id == $type->option_id) echo 'selected'; ?>><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="liabilities_date" id ="liabilities_date" class="validate[required]" value="<?php if ($item->liabilities_date != '0000-00-00') echo date("d-M-Y", strtotime($item->liabilities_date)); else echo '';?>"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="liabilities_desc" id="liabilities_desc" value="<?=$item->liabilities_desc?>"/> 
									</span>
								</p>
								<p>
									<label>Nominal Credit<font style="color:red;">*</font></label>
										<span class="field">
											<input type="text" class="input-medium" name="liabilities_nominal" id="liabilities_nominal" 
												value="<?php echo trim($item->liabilities_nominal,"-")?>"/>
										
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
									<a href="<?=base_url()?>index.php/liabilities/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
<?php endforeach;?>				                        
<script>
    $(document).ready(function(){
		$("#addliabilities").validationEngine();
    });
	
	$(function() {
		$( "#liabilities_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>