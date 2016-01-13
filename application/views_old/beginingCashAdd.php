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
						
						<form id="addBegining Cash" name="addBegining Cash" class="stdform" method="post" action="<?=base_url()?>index.php/begining_cash/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Begining Cash </h4>
								<div class="widgetcontent">
								<p>
									<label>Period<font style="color:red;">*</font></label>
									<select name="period" id="period" style="width:100px;" class="validate[required]">
										<option> - Month - </option>
									<?php foreach($months as $month): ?>
										<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
									<?php endforeach; ?>
									</select>
								</p>
								<p>
									<label>Year <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="begining_cash_year" id="begining_cash_year" value="2014"/> 
									</span>
								</p>
								
								<p>
									<?php foreach($bank_account as $item) {?>
									<label> <?=$item->bank_account_name?> <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="begining_cash_nominal<?=$item->id?>" id="begining_cash_nominal"/> 
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
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addBegining Cash").validationEngine();
    });
	
	$(function() {
		$( "#begining_cash_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>