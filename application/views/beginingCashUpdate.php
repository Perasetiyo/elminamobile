<?php foreach ($begining_cash as $item): ?>
<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
	<h1>&nbsp;Pencatatan Begining Cash</h1>
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
						
						<form id="addBegining Cash" name="addBegining Cash" class="stdform" method="post" action="<?=base_url()?>index.php/begining_cash/doUpdate" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Begining Cash </h4>
								<div class="widgetcontent">
								<input type="hidden" value="<?=$item->begining_cash_id?>" name="begining_cash_id"/>
								
								<p>
									<label>Period<font style="color:red;">*</font></label>
									<input type="hidden" value="<?=$item->begining_cash_period?>" name="period"/>
									<select name="" disabled id="period" style="width:100px;" class="validate[required]">
										<option> - Month - </option>
									<?php foreach($months as $month): ?>
										<option value="<?php echo $month->option_code?>" 
											<?php if ($item->begining_cash_period == $month->option_code) echo 'selected'; ?>>
											<?php echo $month->option_desc;?>
										</option>
									<?php endforeach; ?>
									</select>
								</p>
								<p>
									<label>Year <font style="color:red;">*</font></label>
									<span class="field">
										<input type="hidden" value="<?=$item->begining_cash_year?>" name="year"/>
										<input type="text" disabled class="input-xlarge validate[required]" value="<?=$item->begining_cash_year?>" /> 
									</span>
								</p>
								
								<p>
									<?php 
										$searchterm['period'] = $item->begining_cash_period;
										$searchterm['year'] = $item->begining_cash_year;
										$list_nominal_per_bank = $this->begining_cash_model->fetch_nominal_per_bank($searchterm);
										foreach($list_nominal_per_bank as $bank) { ?>
										
									<label> <?=$bank->bank_account_name?> <font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="begining_cash_nominal<?=$bank->id?>" id="begining_cash_nominal"
										value="<?=$bank->begining_cash_nominal?>"/> 
									</span>
									<?php } ?>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/begining_cash/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
					<?php endforeach;?>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addBegining Cash").validationEngine();
    });
	
	$(function() {
		$( "#begining_cash_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>