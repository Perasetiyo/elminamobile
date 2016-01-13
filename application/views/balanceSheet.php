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
						
								<div class="span5" id="asset">
									
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
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															<a href="<?=base_url()?>index.php/orders/" title="Detail">
																Unfinished Order
															</a>
														</td>
														<td class="right nominal"><b><?=$unfinish_order_nominal?></b></td>
													</tr>
													
													<tr class="gradeX">
														<td colspan="3">Total Account Receivable</td>
														<td class="right nominal"><b><?=$total_account_receivable + $unfinish_order_nominal?></b></td>
													</tr>
												</table>
											</p>
											<p>												
												<table width="100%" class="table " id="income_table">
													<tr>
														<td colspan="4">Inventory  </td> 
													</tr>
													<?php
													$row = 1;
													$total_inventory = 0;?>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															Inventory Barang Mentah
														</td>
														<td class="right nominal"><?=$inventory_barang_mentah?></td>
													</tr>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															Inventory Barang 1/2 Jadi
														</td>
														<td class="right nominal"><?=$inventory_barang_setengah_jadi?></td>
													</tr>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															Inventory Barang Jadi
														</td>
														<td class="right nominal"><?=$inventory_barang_jadi?></td>
													</tr>
													<?php 
															$total_inventory = $inventory_barang_mentah + $inventory_barang_setengah_jadi + 
																						$inventory_barang_jadi;
														 ?>
													<tr class="gradeX">
														<td colspan="3">Total Inventory</td>
														<td class="right nominal"><b><?=$total_inventory?></b></td>
													</tr>
												</table>
											</p>
											<p>
												<table width="100%" class="table " id="income_table">
												<tr>
														<td colspan="4">Property, Plant, Equipment (PPE)</td> 
												</tr>
												<?php
												$row = 1;
												$total_ppe = 0;
												if ($list_ppe == null) {
													echo '---';
												} 
												else {
													foreach ($list_ppe as $ppe) { ?>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															<a href="<?=base_url()?>index.php/ppe/search/<?=$ppe->ppe_type_id?>" title="Detail">
																<?=$ppe->option_desc?>
															</a>
														</td>
														<td class="right nominal"><?=$ppe->ppe_nominal?></td>
													</tr>
												<?php 
														$total_ppe = $total_ppe + $ppe->ppe_nominal;
													}} ?>
												
													<tr class="gradeX">
														<td colspan="3">Total PPE</td>
														<td class="right nominal"><b><?=$total_ppe?></b></td>
													</tr>
												</table>
											</p>
										</div>
								</div>
								<div class="span5 profile-right" id="liabilities">
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
													
													foreach ($account_payable as $payable) { 
														if ($payable->liabilities_nominal > 0) {
												?>
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
													}}} ?>
												
													<tr class="gradeX">
														<td colspan="3">Total Account Payable</td>
														<td class="right nominal"><b><?=$total_account_payable?></b></td>
													</tr>
													
													<tr class="gradeX">
														<td colspan="3">
															<a href="<?=base_url()?>index.php/wallet/search_users" title="Detail">
																Customer Deposits (Wallet)
															</a>
														</td>
														<td class="right nominal"><b><?=$wallet_nominal?></b></td>
													</tr>
													
													<tr class="gradeX">
														<td colspan="3"><b>Total : </b></td>
														<td class="right nominal"><b><?=$total_account_payable + $wallet_nominal?></b></td>
													</tr>
												
												</table>
											</p>
											
									</div>
								</div>
								<div class="span5 profile-right" id="equity">
									<h4 class="widgettitle">&nbsp; Owner Equity / Kepemilikan </h4>
									<div class="widgetcontent">
										<p>
										<table width="100%" class="table " id="income_table">
											<?php
												$row = 1;
												$total_equity = 0;
												if ($equities == null) {
													echo '---';
												} 
												else {
													
													foreach ($equities as $equity) { ?>
													<tr class="gradeX">
														<td class="center" width="2%"><?=$row++;?></td>
														<td>
															<a href="<?=base_url()?>index.php/equity/search/<?=$equity->equity_type_id?>" title="Detail">
																<?=$equity->option_desc?>
															</a>
														</td>
														<td class="right nominal"><?=$equity->equity_nominal?></td>
													</tr>
												<?php 
														$total_equity = $total_equity + $equity->equity_nominal;
													}} ?>
													<tr class="gradeX">
														<td colspan="3">Total Equity </td>
														<td class="right nominal"><b><?=$total_equity?></b></td>
													</tr>
												
												<tr class="gradeX">
													<td colspan="3">
															Earnings
													</td>
													<td class="right nominal"><b><?=$earnings_nominal?></b></td>
												</tr>
											<tr class="gradeX">
												<td colspan="3"><b>Total : </b></td>
												<td class="right nominal"><b><?=$total_equity + $earnings_nominal?></b></td>
											</tr>
										</table>
										</p>
									</div>
								</div>
								
							</div>
							<div class="span11">
								<div class="span4">
									<?php $total_asset = $total_cash + $total_account_receivable + $unfinish_order_nominal + $total_inventory + $total_ppe?>
									<h3 align="right" class="nominal"><?=$total_asset?> </h3>
								</div>
								<div class="span4">
									<?php $total_owe_own = $total_account_payable + $total_equity + $earnings_nominal?>
									<h3 align="right" class="nominal"><?=$total_owe_own?> </h3>
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

