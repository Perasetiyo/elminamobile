<?php foreach ($wallet as $item) {?>
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Wallet</h1>
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
						
						<form id="addForm" name="addForm" class="stdform" method="post" action="<?=base_url()?>index.php/wallet/doUpdate" />
							<div id="wiz1step1" class="formwiz">
								<input type="hidden" name="wallet_id" value="<?=$item->wallet_id?>"/>;
								<h4 class="widgettitle">&nbsp; Edit Wallet </h4>
								<div class="widgetcontent">
								<p>
									<label>Billing Name</label>
										
									<select name="billing_id" id="billing_id">
											<option value=''>- Select Agent -</option>
										<?php foreach($billing as $billing): ?>
											<option value="<?php echo $billing->billing_id?>"
												<?php if ($billing->billing_id == $item->billing_id) echo 'selected';?>
											><?php echo $billing->billing_name?></option>
										<?php endforeach; ?>
									</select>
								</p>
								
								<p>
									<label>Nominal<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="wallet_trx_nominal" id="wallet_trx_nominal" 
												value="<?=$item->wallet_trx_nominal?>"/> 
									
										<select name="bank_account_id" id="bank_account_id" style="width:200px;">
											<option value=''>- Select Bank -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"
												<?php if ($item->bank_account_id == $account->id) echo 'selected';?>
											><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								
								<p>
									<label>Status<font style="color:red;"></font></label>
									<span class="field">
										<select name="wallet_status" id="wallet_status" style="width:200px;">
											<option value='0' <?php if ($item->wallet_status == 0) echo 'selected';?>>Pending</option>
											<option value='1' <?php if ($item->wallet_status == 1) echo 'selected';?>>Confirmed</option>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="wallet_trx_date" id ="wallet_trx_date" class="validate[required]"
											value="<?=$item->wallet_trx_date?>"/>
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/wallet/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
<?php } ?>		                        
<script>
    $(document).ready(function(){
		$("#addForm").validationEngine();
    });
	
	$(function() {
		$( "#wallet_trx_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>

<script type="text/javascript">
// When document is ready: this gets fired before body onload :)
$(document).ready(function(){
	// Write on keyup event of keyword input element
	$("#billing_query").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#billing_table tbody>tr").hide();
			$("#billing_table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#billing_table tbody>tr").show();
		}
	});
});
// jQuery expression for case-insensitive filter
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

	
</script>
