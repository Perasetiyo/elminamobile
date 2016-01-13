	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Balance Sheet</h1>
	</div>
</div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
					<div class="span11">
						
						<div class="error message">
							<?php echo $this->session->flashdata('validation_error_message'); ?>
							<?php echo $this->session->flashdata('error_message'); ?>
						</div>
						<div class="success message">
							<?php echo $this->session->flashdata('success_message'); ?>
						</div>
						
								<div class="span4" id="asset">
									<h4 class="widgettitle">&nbsp; Assets </h4>
										<div class="widgetcontent">
											<p>
												<table width="100%" class="table " id="income_table">
												<tr>
														<td colspan="4">Cash  </td> 
												</tr>
												<?php
												$row = 1;
												$total_cash = 0;
												if ($cash == null) {
													echo '---';
												} 
												else {
													foreach ($cash as $cash) { ?>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															<?=$cash->bank_account_name?>
															
														</td>
														<td class="right nominal"><?=$cash->nominal?></td>
													</tr>
												<?php 
														$total_cash = $total_cash + $cash->nominal;
													}} ?>
												
													<tr class="gradeX">
														<td colspan="3">Total Cash</td>
														<td class="right nominal"><b><?=$total_cash?></b></td>
													</tr>
												</table>
											</p>
											<p>
												
											<table width="100%" class="table " id="income_table">
												<tr>
														<td colspan="4">Account Receivable / Piutang  </td> 
												</tr>
												<?php
												$row = 1;
												$total_account_receivable = 0;
												if ($account_receivable == null) {
													echo '---';
												} 
												else {
													foreach ($account_receivable as $acrec) { ?>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															<a href="<?=base_url()?>index.php/acrec/search/<?=$acrec->acrec_type_id?>" title="Detail">
																<?=$acrec->option_desc?>
															</a>
														</td>
														<td class="right nominal"><?=$acrec->acrec_nominal?></td>
													</tr>
												<?php 
														$total_account_receivable = $total_account_receivable + $acrec->acrec_nominal;
													}} ?>
												
													<tr class="gradeX">
														<td colspan="3">Total Account Payable</td>
														<td class="right nominal"><b><?=$total_account_receivable?></b></td>
													</tr>
												</table>
											</p>
											<p>
												<label>Inventory </label>
												<span class="field">
													100.000
												</span>
											</p>
											<p>
												<label>Equipment </label>
												<span class="field">
													100.000
												</span>
											</p>
											<p>
												<label>Property </label>
												<span class="field">
													100.000
												</span>
											</p>
											<p>
												<label>Net PPE </label>
												<span class="field">
													100.000
												</span>
											</p>
										</div>
								</div>
								<div class="span4 profile-right" id="liabilities">
										<h4 class="widgettitle">&nbsp; Liabilities / Pinjaman</h4>
										<div class="widgetcontent">
											<p>
											<table width="100%" class="table " id="income_table">
												<tr>
														<td colspan="4">Account Payable / Hutang Operasional  </td> 
												</tr>
												<?php
												$row = 1;
												$total_account_payable = 0;
												if ($account_payable == null) {
													echo '---';
												} 
												else {
													
													foreach ($account_payable as $payable) { ?>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															<a href="<?=base_url()?>index.php/liabilities/search/<?=$payable->liabilities_type_id?>/<?=$payable->liabilities_cause_id?>" title="Detail">
																<?=$payable->option_desc?>
															</a>
														</td>
														<td class="right nominal"><?=$payable->liabilities_nominal?></td>
													</tr>
												<?php 
														$total_account_payable = $total_account_payable + $payable->liabilities_nominal;
													}} ?>
												
													<tr class="gradeX">
														<td colspan="3">Total Account Payable</td>
														<td class="right nominal"><b><?=$total_account_payable?></b></td>
													</tr>
												
													<tr class="gradeX"><td colspan=4></td></tr>
													
													<tr class="gradeX">
														<td colspan="3">
															<a href="<?=base_url()?>index.php/liabilities/search/84" title="Detail">
																Money Repaid
															</a>
														</td>
														<td class="right nominal"><b><?=$money_repaid_nominal?></b></td>
													</tr>
													
													<tr class="gradeX"><td colspan=4></td></tr>
													
													<tr class="gradeX">
														<td colspan="3"><b>Total : </td>
														<td class="right nominal"><b><?=$total_account_payable + $money_repaid_nominal?></b></td>
													</tr>
													
												</table>
											</p>
											
									</div>
								</div>
								<div class="span4 profile-right" id="equity">
									<h4 class="widgettitle">&nbsp; Owner Equity / Kepemilikan </h4>
									<div class="widgetcontent">
										<p>
										<table width="100%" class="table " id="income_table">
											<tr class="gradeX">
												<td colspan="3">
														Earnings
												</td>
												<td class="right nominal"><b><?=$earnings_nominal?></b></td>
											</tr>
											<tr class="gradeX">
												<td colspan="3"><b>Total : </td>
												<td class="right nominal"><b>---</b></td>
											</tr>
										</table>
										</p>
									</div>
								</div>
								
							</div>
						
				
<script>
    $(document).ready(function(){
		$("#orderForm_").validationEngine();
    });
	
	$(function() {
		$( "#order_date" ).datepicker({dateFormat: "dd-M-yy"});
		$( "#purchase_date" ).datepicker({dateFormat: "dd-M-yy"});
	});
</script>


</body>
</html>

