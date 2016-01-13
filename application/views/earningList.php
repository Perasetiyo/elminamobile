<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Inventory Statistic</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<div class="span5" >
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
						
							<tr>
								<th class="center" colspan="3" style="background-color: #0866C5;">
									Income until <?=date("d M Y")?>
								</th>
							</tr>
							<tr>
								<th width="1%" class="center">No</th>
								<th width="5%" class="center">Account Name</th>
								<th width="20%" class="center">Nominal</th>
							</tr>
						</thead>
						<tbody>
						
							<?php
								$row = 1;
								$total_income = 0;
								if ($income == null) {
									
								} else {
											
									foreach ($income as $item) { 
										if ($item->income_nominal > 0) {
							?>
											<tr class="gradeX">
												<td class="center" width="2%"><?=$row++;?></td>
													<td>
														<a href="<?=base_url()?>index.php/income/search/<?=$item->income_type_id?>" title="Detail">
															<?=$item->option_desc?>
														</a>
													</td>
													<td class="right nominal"><?=$item->income_nominal?></td>
												</tr>
							<?php 
											$total_income = $total_income + $item->income_nominal;
								}}} ?>
												
								<tr class="gradeX">
									<td colspan="2">Total Income</td>
									<td class="right nominal"><b><?=$total_income?></b></td>
								</tr>
						</tbody>
					</table>
				</div>
				
				<div class="span5 profile-right" >
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
						
							<tr>
								<th class="center" colspan="3" style="background-color: #0866C5;">
									Earning until <?=date("d M Y")?>
								</th>
							</tr>
							<tr>
								<th width="1%" class="center">No</th>
								<th width="5%" class="center">Account Name</th>
								<th width="20%" class="center">Nominal</th>
							</tr>
						</thead>
						<tbody>
							<tr class="gradeX">
								<td class="center" width="2%">1</td>
								<td>Retain Earning</td>
								<td class="right nominal"><?=$retain_earning_nominal?></td>
							</tr>
							<tr class="gradeX">
								<td class="center" width="2%">1</td>
								<td>Earning Week to Date</td>
								<td class="right nominal"><?=$earning_week_to_date_nominal?></td>
							</tr>
												
							<tr class="gradeX">
								<td colspan="2">Total Earnings</td>
								<td class="right nominal"><b><?=$retain_earning_nominal + $earning_nominal?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="span5 profile-right" >
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
						
							<tr>
								<th class="center" colspan="3" style="background-color: #0866C5;">
									Sedekah until <?=date("d M Y")?>
								</th>
							</tr>
							<tr>
								<th width="1%" class="center">No</th>
								<th width="5%" class="center">Account Name</th>
								<th width="20%" class="center">Nominal</th>
							</tr>
						</thead>
						<tbody>
							<tr class="gradeX">
								<td class="center" width="2%">1</td>
								<td>Sedekah yang HARUS disalurkan</td>
								<td class="right nominal"><?=$supposedto_sedekah_nominal?></td>
							</tr>
							<tr class="gradeX">
								<td class="center" width="2%">1</td>
								<td>Telah di salurkan sebesar : </td>
								<td class="right nominal"><?=$current_sedekah_nominal?></td>
							</tr>
												
							<tr class="gradeX">
								<td colspan="2">Sedekah yang BELUM tersalurkan</td>
								<td class="right nominal"><b><?=$supposedto_sedekah_nominal + $current_sedekah_nominal?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
				
<script> 
	
	$(calculatesold);
	function calculatesold() {
		var sum = 0;
		
		$(".sold").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalsold').text('Total Sold : ' + sum);
	};
	
	$(calculaterestock);
	function calculaterestock() {
		var sum = 0;
		
		$(".restock").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalrestock').text('Total Restock : ' + sum);
	};
</script>

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
