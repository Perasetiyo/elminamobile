<?php foreach ($cash as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Arus Kas <?=$item->cash_desc?></h1>
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
						
						<form id="addExpense" name="addExpense" class="stdform" method="post" action="<?=base_url()?>index.php/cash/doUpdate/" />
							
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Arus Kas </h4>
								<div class="widgetcontent">
								<input type="hidden" value="<?=$item->cash_id?>" name="cash_id"/>
								<p>
									<label>Tipe Arus Kas<font style="color:red;">*</font></label>
									<span class="field">
										<select name="cash_type_id" id="cash_type_id" style="width:200px;" class="validate[required]">
											<option>- Choose One -</option>
										<?php foreach($production_options as $options): ?>
											<option value="<?php echo $options->option_id?>" <?php if ($item->cash_type_id == $options->option_id) echo 'selected'; ?>><?php echo $options->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Deskripsi <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="cash_desc" id="cash_desc" value="<?=$item->cash_desc?>"/> 
									</span>
								</p>
								<p>
									<label>Tanggal  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="cash_date" id ="cash_date" class="validate[required]" value="<?php if ($item->cash_date != '0000-00-00') echo date("d-M-Y", strtotime($item->cash_date)); else echo '';?>"/>
									</span>
								</p>
								<p>
									<label>Nominal<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium" name="cash_nominal" id="cash_nominal" 
											value="<?php echo trim($item->cash_nominal,"-")?>"/>
										
										<select name="bank_account_id" id="bank_account_id" style="width:200px;">
											<option value=''>- Select Bank -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>" <?php if ($item->bank_account_id == $account->id) echo 'selected'; ?>>
												<?php echo $account->bank_account_name?>
										</option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								
								<p>
									<label>In / Out <font style="color:red;">*</font></label>
									<span class="field">
										<select name="in_out" id="in_out" style="width:120px;" >
											<option value="-">Out (-)</option>
											<option value="">In (+)</option>
										</select>
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/cash/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
<?php endforeach;?>				                        
<script>
    $(document).ready(function(){
		$("#addExpense").validationEngine();
    });
	
	$(function() {
		$( "#expense_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>