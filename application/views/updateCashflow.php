<?php foreach ($cashflow as $item): ?>
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Ubah Arus Kas <?=$item->cashflow_desc?></h1>
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
						
						<form id="billingForm" name="billingForm" class="stdform" method="post" action="<?=base_url()?>index.php/cashflowAction/doUpdateCashflow/<?=$item->cashflow_id?>" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Detail Arus Kas </h4>
								<div class="widgetcontent">
								<p>
									<label>Tipe Arus <font style="color:red;">*</font></label>
									<span class="field">
										<select name="cashflow_type_id" id="cashflow_type_id" style="width:200px;" class="validate[required]">
											<option>Pilih Satu</option>
										<?php foreach($cashflow_type as $type): ?>
											<option value="<?php echo $type->id?>" <?php if ($item->cashflow_type_id == $type->id) echo 'selected'; ?>><?php echo $type->cashflow_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Tanggal</label>
									<span class="field">
										<input type="text" name="cashflow_date" id ="cashflow_date"  value="<?php if ($item->cashflow_date != '0000-00-00') echo date("d-M-Y", strtotime($item->cashflow_date)); else echo '';?>">
									</span>
								</p>
								<p>
									<label>Deskripsi <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="cashflow_desc" id="cashflow_desc" value="<?=$item->cashflow_desc?>"/> 
									</span>
								</p>
								<p>
									<label>Nominal <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="cashflow_nominal" id="cashflow_nominal" value="<?=$item->cashflow_nominal?>"/> 
									</span>
								</p>
								<p>
									<label>Bank Account <font style="color:red;">*</font></label>
									<span class="field">
										<select name="bank_account_id" id="bank_account_id" style="width:200px;" class="validate[required]">
											<option>Pilih Satu</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>" <?php if ($item->bank_account_id == $account->id) echo 'selected'; ?>><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Debet/Kredit <font style="color:red;">*</font></label>
									<span class="field">
										<select name="debet_credit" id="debet_credit" style="width:120px;" class="validate[required]">
											<option>Pilih Satu</option>
											<option value="DEBET" <?php if ($item->debet_credit == 'DEBET') echo 'selected'; ?>>Debet</option>
											<option value="KREDIT" <?php if ($item->debet_credit == 'KREDIT') echo 'selected'; ?>>Kredit</option>
										</select>
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn">RESET</button>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>				<?php endforeach;?>
				                        
<script>
    $(document).ready(function(){
		$("#billing_form_").validationEngine();
    });
	
	$(function() {
		$( "#cashflow_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>