<?php $row = 1;
					foreach ($items as $item) {
											
						$begining_cash = $item->begining_cash;
						$sales_nominal = $item->sales_nominal;
						$money_borrowed = $item->money_borrowed;
						$money_repaid = $item->money_repaid;
						
						?>
<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Cash Statement of <?=$item->month?></h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/cash_statement/main">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">Periode</h4>
						<div class="widgetcontent">
							<label>						
								<span class="field">
									<select name="period" id="period" style="width:200px;" class="validate[required]">
										<option value> - Month - </option>
									<?php foreach($months as $month): ?>
										<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
									<?php endforeach; ?>
									</select>
								</span>
								<span class="field">
									<select name="year" id="year" style="width:200px;" class="validate[required]">
										<option value="2014">2015</option>
										<option value="2014">2014</option>
										<option value="2013">2013</option>
										
									
									</select>
								</span>
								<button class="btn btn-primary">SUBMIT</button>
							</label>
						</div>
					</div>
				</form>
				<div style="float:left">
					<a href="<?=base_url()?>index.php/begining_cash/lists" title="Begining Cash" class="btn" >Begining Cash</a>&nbsp;
				</div>
					
				
                <table width="100%" class="table table-bordered" id="income_table">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="1%" class=" center">No</th>
							<th width="7%" class=" center" colspan='2'>Transaction / Event</th>
							<th width="5%" class="head1 center">Nominal</th>
							<th width="3%" class="head1 center">%</th>
                        </tr>
                    </thead>
                    <tbody>
							
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Penjualan</td>
							<td class="right nominal"><?=$item->sales_nominal?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) echo $item->sales_nominal / $item->sales_nominal * 100; else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Cost Produksi</td>
							<td class="right">	</td>
							<td class="right">	</td>
                        </tr>
						
						<?php
					$total_prod_costs = 0;
					if ($prod_costs == null) {
						echo '';
					} 
					else {
						
						foreach ($prod_costs as $prod) { ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="<?=base_url()?>index.php/cash/search/<?=$item->month_id?>/<?=$prod->cash_type_id?>" title="Detail">
									<?=$prod->option_desc?>
								</a>
							</td>
							<td class="right nominal"><?=$prod->cash_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($prod->cash_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					<?php 
							$total_prod_costs = $total_prod_costs + $prod->cash_nominal;
						}} ?>
					
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Total Cost Produksi</td>
							<td class="right nominal"><?=$total_prod_costs?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($total_prod_costs / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Account Receivable / Piutang</td>
							<td class="right nominal"><?=$item->money_borrowed?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($item->money_borrowed / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Bayar Hutang</td>
							<td class="right nominal"><?=$item->money_repaid?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($item->money_repaid / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Investment (PPE)</td>
							<td class="center nominal"></td>
							<td class="center"></td>
                        </tr>
						
						<?php
					$total_ppe = 0;
					if ($ppes == null) {
						echo '';
					} 
					else {
						
						foreach ($ppes as $ppe) { ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="<?=base_url()?>index.php/cash/search/<?=$item->month_id?>/<?=$ppe->cash_type_id?>" title="Detail">	
									<?=$ppe->cash_desc?>
								</a>
							</td>
							<td class="right nominal"><?=$ppe->cash_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($ppe->cash_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					<?php 
							$total_ppe = $total_ppe + $ppe->cash_nominal;
						}} ?>
					
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Total Investment (PPE)</td>
							<td class="right nominal" style="font-weight:bold;font-size:11pt;"><?=$total_ppe?></td>
							<td class="center" style="font-weight:bold;font-size:11pt;"><?php if ($item->sales_nominal != 0) 
														echo round(trim($total_ppe / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Penambahan Modal</td>
							<td class="right nominal"></td>
							<td class="center">0 %</td>
                        </tr>
						
						<?php
					$total_equities = 0;
					if ($equities == null) {
						echo '';
					} 
					else {
						
						foreach ($equities as $item) { ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$item->cash_desc?></td>
							<td class="right nominal"><?=$item->cash_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($item->cash_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					<?php 
							$total_equities = $total_equities + $item->cash_nominal;
						}} ?>
					
					
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Total Penambahan Modal</td>
							<td class="right nominal" style="font-weight:bold;font-size:11pt;"><?=$total_equities?></td>
							<td class="center" style="font-weight:bold;font-size:11pt;"><?php if ($item->sales_nominal != 0) 
														echo round(trim($total_equities / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Expenses</td>
							<td class="center "></td>
							<td class="center "></td>
                        </tr>
						
					<?php
					$total_expenses = 0;
					if ($expenses == null) {
						echo '';
					} else {
						
						foreach ($expenses as $exp) { ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="<?=base_url()?>index.php/cash/search/<?=$item->month_id?>/<?=$exp->cash_type_id?>" title="Detail">
									<?=$exp->option_desc?>
								</a>
							</td>
							<td class="right nominal"><?=$exp->cash_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($exp->cash_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					<?php 
							$total_expenses = $total_expenses + $exp->cash_nominal;
						}
					} ?>
					
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Total Expenses</td>
							<td class="right nominal" style="font-weight:bold;font-size:11pt;"><?=$total_expenses?></td>
							<td class="center" style="font-weight:bold;font-size:11pt;"><?php if ($item->sales_nominal != 0) 
														echo round(trim($total_expenses / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					
					<?php 
						
						$change_in_cash =  $sales_nominal + $total_prod_costs +
										   $money_borrowed + $money_repaid +
										   $total_equities + $total_ppe +
										   $total_expenses;
						
						
						
					?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Perubahan Cash</td>
							<td class="right nominal" style="font-weight:bold;font-size:12pt;color:blue;"><b><?=$change_in_cash?></b></td>
							<td class="center" style="font-weight:bold;font-size:12pt;color:blue;"><?php if ($item->sales_nominal != 0) 
														echo round(trim($change_in_cash / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Cash Diawal</td>
							<td class="right nominal" style="font-weight:bold;font-size:12pt;color:blue;"><b><?=$begining_cash?></b></td>
							<td class="center" style="font-weight:bold;font-size:12pt;color:blue;"><?php if ($item->sales_nominal != 0) 
														echo round(trim($begining_cash / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<?php $ending_cash = $begining_cash + $change_in_cash; ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Ending Cash</td>
							<td class="right nominal" style="font-weight:bold;font-size:12pt;color:blue;"><b><?=$ending_cash?></b></td>
							<td class="center" style="font-weight:bold;font-size:12pt;color:blue;"><?php if ($item->sales_nominal != 0) 
														echo round(trim($ending_cash / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
					<?php } ?>
                    </tbody>
                </table>

<script>
	$(document).ready(function() {
		$('.nominal').formatCurrency();
	});
</script>