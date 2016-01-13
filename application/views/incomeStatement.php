<?php $row = 1;
					foreach ($items as $item) {?>
<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Income Statement between <?=$item->startdate?> and <?=$item->enddate?></h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/income_statement/main">
					<div id="wiz1step1" class="formwiz span6">
						<hr>
						<h4 class="widgettitle">Filter Tanggal </h4>
						<div class="widgetcontent">
							
							<span class="field" style="padding-right:5px;padding-bottom:5px;float:left;">
								<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
							</span>
							
							<span class="field">
								<input type="text" id="startdate" name="startdate" placeholder="Range Awal">
								<input type="text" id="enddate" name="enddate" placeholder="Range Akhir">
							</span>
							<p>
									<button class="btn btn-primary">CARI</button>
							</p>
							<div class="clear"></div>
							
							
						</div>
					</div>
				</form>
				
                <table width="100%" class="table table-bordered" id="income_table">
                    <colgroup>
                        <col class="con0"/>
                        <col class="con1"/>
						<col class="con1"/>
                        <col class="con0"/>
                        
                    </colgroup>
                    <thead>
                        <tr>
							<th width="60%" class=" center" colspan='2'>Transaction / Event</th>
							<th width="30%" class="head1 center">Nominal</th>
							<th width="10%" class="head1 center">%</th>
                        </tr>
                    </thead>
                    <tbody>
							
						<tr class="gradeX">
							<td colspan='2'>Sales</td>
							<td class="right nominal"><?=$item->sales_nominal?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) echo $item->sales_nominal / $item->sales_nominal * 100; else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='2'>COGS</td>
							<td class="right nominal"><?=$item->cogs_nominal?></td>
							
							<td class="center"><?php if ($item->cogs_nominal != 0) 
														echo round(trim($item->cogs_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='4' class="right"></td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='2'>Gross Profit</td>
							<td class="right nominal" style="font-weight:bold; font-size:12pt;color:blue;"><b><?=$item->gp_nominal?></b></td>
							<td class="center" style="font-weight:bold; font-size:12pt;color:blue;">
								<?php if ($item->sales_nominal != 0) 
														echo round(trim($item->gp_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
						
						
						<tr class="gradeX">
							<td colspan='4' class="right"></td>
                        </tr>

						<tr class="gradeX">
							<td colspan='2'>Expenses</td>
							<td class="center nominal"></td>
							<td class="center nominal"></td>
                        </tr>
						
					<?php
					$total_expenses = 0;
					$i = 1;
					if ($expenses == null) {
						echo '---';
					} 
					else {
						
						foreach ($expenses as $expense) { ?>
						<tr class="gradeX">
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<a href="<?=base_url()?>index.php/income_statement/expenses_detail_per_type_list/<?=$item->startdate?>/<?=$item->enddate?>/<?=$expense->expense_type_id?>" title="Detail">
									<?=$i++.'. '.$expense->option_desc?>
								</a>
							</td>
							<td class="right nominal">-<?=$expense->exp_nominal?></td>
							<td class="center"></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($expense->exp_nominal / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					<?php 
							$total_expenses = $total_expenses + $expense->exp_nominal;
						}} ?>
					
						<tr class="gradeX">
							
							<td colspan='2'>Total Expenses</td>
							<td class="right nominal">-<?=$total_expenses?></td>
							<td class="center"><?php if ($item->sales_nominal != 0) 
														echo round(trim($total_expenses / $item->sales_nominal * 100,'-'),2);
													else echo 0?> %</td>
                        </tr>
					
					<?php 
						$np_nominal = $item->gp_nominal - $total_expenses;
					?>
						<tr class="gradeX">
							<td colspan='2'>Nett Profit</td>
							<td class="right nominal" style="font-weight:bold;font-size:12pt;color:blue;"> <b><?=$np_nominal?></b></td>
							<td class="center" style="font-weight:bold;font-size:12pt;color:blue;">
								<?php if ($item->sales_nominal != 0) 
											echo round(trim($np_nominal / $item->sales_nominal * 100,'-'),2);
										else echo 0?> %</td>
                        </tr>
						
						<tr class="gradeX">
							<td class="center" colspan="4"></td>
						</tr>
						
						<tr class="gradeX">
							<td colspan='2'>Sedekah</td>
							<td class="right nominal"><?=$item->sedekah_nominal?></td>
							<td class="center"></td>
                        </tr>
						
						<tr class="gradeX">
							<td colspan='2'><b>Ongkir</b></td>
							<td class="right nominal"></td>
							<td class="center"></td>
                        </tr>
						<tr class="gradeX">
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								Ongkir tercatat :
							</td>
							<td class="right nominal"><?=$item->ongkir_nominal?></td>
							<td class="center"></td>
							<td class="center"></td>
                        </tr>
						<tr class="gradeX">
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								Ongkir yang telah dibayar :
							</td>
							<td class="right nominal"><?=$item->ongkir_paid_nominal?></td>
							<td class="center"></td>
							<td class="center"></td>
                        </tr>
						<tr class="gradeX">
							<td colspan='2'>Selisih Ongkir</td>
							<td class="right nominal"><?=$item->ongkir_nominal - $item->ongkir_paid_nominal?></td>
							<td class="center"></td>
                        </tr>
					
					<?php } ?>
                    </tbody>
                </table>

<style type="text/css">
		.dp-highlight .ui-state-default {
			background: #478DD5;
			color: #FFF;
		}
</style>
<script type="text/javascript">
		/*
		 * jQuery UI Datepicker: Using Datepicker to Select Date Range
		 * http://salman-w.blogspot.com/2013/01/jquery-ui-datepicker-examples.html
		 */
		 
		$(function() {
			$("#datepicker").datepicker({
				beforeShowDay: function(date) {
					var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#startdate").val());
					var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#enddate").val());
					return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
				},
				onSelect: function(dateText, inst) {
					var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#startdate").val());
					var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#enddate").val());
					if (!date1 || date2) {
						$("#startdate").val(dateText);
						$("#enddate").val("");
						$(this).datepicker("option", "minDate", dateText);
					} else {
						$("#enddate").val(dateText);
						$(this).datepicker("option", "minDate", null);
					}
				}
			});
		});
</script>
