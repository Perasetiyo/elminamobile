<?php $row = 1;
					foreach ($items as $item) {?>
<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Income Statement of <?=$item->month?></h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/income_statement/main">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">Periode</h4>
						<div class="widgetcontent">
							<label>						
								<span class="field">
									<select name="period" id="period" style="width:200px;" class="validate[required]">
										<option> - Month - </option>
									<?php foreach($months as $month): ?>
										<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
									<?php endforeach; ?>
									</select>
								</span>
								<span class="field">
									<select name="year" id="year" style="width:100px;" class="validate[required]">
										<option> - Year - </option>
										<option value="2015">2015</option>
										<option value="2014">2014</option>
									</select>
								</span>
								<button class="btn btn-primary">SUBMIT</button>
							</label>
						</div>
					</div>
				</form>
				
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
							<th width="5%" class="head1 center">%</th>
                        </tr>
                    </thead>
                    <tbody>
							
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Sales</td>
							<td class="right nominal"><?=$item->sales_nominal?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) echo $item->sales_nominal / $item->sales_nominal * 100; else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>COGS</td>
							<td class="right nominal"><?=$item->cogs_nominal?></td>
							
							<td class="center"><?php if ($item->cogs_nominal != 0) 
														echo round(trim($item->cogs_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='5' class="right"></td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Gross Profit</td>
							<td class="right nominal" style="font-weight:bold; font-size:12pt;color:blue;"><b><?=$item->gp_nominal?></b></td>
							<td class="center" style="font-weight:bold; font-size:12pt;color:blue;">
								<?php if ($item->sales_nominal != 0) 
														echo round(trim($item->gp_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='5' class="right"></td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Ongkir / Expedition Cost</td>
							<td class="right nominal"><?=$item->ongkir_nominal?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($item->ongkir_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='5' class="right"></td>
                        </tr>

						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Expenses - Variable Cost</td>
							<td class="center nominal"></td>
							<td class="center nominal"></td>
                        </tr>
						
					<?php
					$total_variable_cost = 0;
					if ($expenses == null) {
						echo '---';
					} 
					else {
						
						foreach ($expenses as $expense) { ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="<?=base_url()?>index.php/income_statement/expenses_detail_per_type_list/<?=$item->month_id?>/<?=$expense->expense_type_id?>" title="Detail">
									<?=$expense->option_desc?>
								</a>
							</td>
							<td class="right nominal">-<?=$expense->exp_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($expense->exp_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>

					<?php 
						$total_variable_cost = $total_variable_cost + $expense->exp_nominal;
					}} ?>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Expenses - Fixed Cost</td>
							<td class="center nominal"></td>
							<td class="center nominal"></td>
                        </tr>
						
					<?php 
					$total_fixed_cost = 0;
					if ($expenses == null) {
						echo '---';
					} else {
						foreach ($fixed_cost_history as $fixed_cost) { ?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="<?=base_url()?>index.php/fixed_cost_history/detail/<?=$item->month_id?>/<?=$fixed_cost->fixed_cost_type_id?>" title="Detail">
									<?=$fixed_cost->option_desc?>
								</a>
							</td>
							<td class="right nominal">-<?=$fixed_cost->fixed_cost_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($fixed_cost->fixed_cost_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
					<?php 
						$total_fixed_cost = $total_fixed_cost + $fixed_cost->fixed_cost_nominal;
						
					}} 
					$total_expenses = $total_variable_cost + $total_fixed_cost;
					?>
						

					
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Total Expenses</td>
							<td class="right nominal">-<?=$total_expenses?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($total_expenses / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					
					<?php 
						$np_nominal = $item->gp_nominal + $item->ongkir_nominal - $total_expenses;
					?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Nett Profit</td>
							<td class="right nominal" style="font-weight:bold;font-size:12pt;color:blue;"><b><?=$np_nominal?></b></td>
							<td class="center" style="font-weight:bold;font-size:12pt;color:blue;">
								<?php if ($item->sales_nominal != 0) 
											echo round(trim($np_nominal / $item->sales_nominal * 100,'-'),2);
										else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
							<td colspan='2'>Sedekah</td>
							<td class="right nominal"><?=$item->sedekah_nominal?></td>
							
							<td class="center">
								Persentase dari NP : <br/>
								<?php if ($item->sedekah_nominal != 0) 
														echo round(trim($item->sedekah_nominal / $np_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						
					
					<?php } ?>
                    </tbody>
                </table>
