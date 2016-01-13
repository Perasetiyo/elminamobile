
<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Customer Rank Detail</h1>
</div>
			<div class="maincontentinner">
				
				<hr/>
				<a href="<?=base_url()?>index.php/customer_stats/customer_rank" title="Customer Rank" class="btn">Back to Rank</a>&nbsp;
				<hr/>	
				<h4>Date Range : <?php if ($startdate <> '01 Jan 1970') echo $startdate.' - '.$enddate; else echo 'N/A';?> <br/> 
					Total Sold : <?=$all_total_qty?> pcs</h4>
				<hr/>	
				
				<div class="span8">
					<h4 class="widgettitle">BILLING ORDERS</h4>
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
						</colgroup>
						<thead>
							<tr>
								<th width="1%" class="center" >No</th>
								<th width="20%" class="center" >Customer Name</th>
								<th width="10%" class="center" >Order Date</th>
								<th width="5%" class="center" >Qty</th>
								<th width="15%" class="center" >Sales</th>
								<th width="15%" class="center" >COGS</th>
								<th width="5%" class="center" >Margin</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if ($list_customer_detail == null) {
								$row = 0;
								$margintotal = 0;
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								$margintotal = 0;
								foreach($list_customer_detail as $item):
									$row++;
							?>
                        
							<tr class="gradeX">
								<td class="center"><?=$row?></td>
								<td class="left"><?=$item->billing_name?></td>
								<td class="center"><?=date('d-M-Y', strtotime($item->order_date))?></td>
								<td class="center qty all_qty"><?=$item->total_qty?></td>
								<td class="right nominal sales"><?=$item->total_amount?></td>
								<td class="right nominal cogs"><?=$item->inventory_nominal?></td>
								<td class="right"><?php echo round($item->margin_percentage, 2); 
														$margintotal = $margintotal + $item->margin_percentage;
												  ?> %</td>
							</tr>
							
							<?php endforeach; 
							}?>
							
							<tr>
								<td class="right" colspan="3">Total</td>
								<td class="center" id="totalqty" style="font-weight:bold;"> </td>
								<td class="right nominal" style="font-weight:bold;" id="totalsales"></td>
								<td class="right nominal" style="font-weight:bold;" id="totalcogs"></td>
								<td class="right" style="font-weight:bold;"><?php if ($row > 0) echo round($margintotal/$row,2); else echo 0;?> %</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				
				<div class="span8">
					<h4 class="widgettitle">DROPSHIP ORDERS</h4>
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
						</colgroup>
						<thead>
							<tr>
								<th width="1%" class="center" >No</th>
								<th width="20%" class="center" >Customer Name</th>
								<th width="10%" class="center" >Order Date</th>
								<th width="5%" class="center" >Qty</th>
								<th width="15%" class="center" >Sales</th>
								<th width="15%" class="center" >COGS</th>
								<th width="5%" class="center" >Margin</th>
							</tr>
						</thead>
						<tbody>
							<?php
							
							if ($list_customer_detail2 == null) {
								$row = 0;
								$margintotal = 0;
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								$margintotal = 0;
								foreach($list_customer_detail2 as $item):
									$row++;
							?>
                        
							<tr class="gradeX">
								<td class="center"><?=$row?></td>
								<td class="left"><?=$item->billing_name?></td>
								<td class="center"><?=date('d-M-Y', strtotime($item->order_date))?></td>
								<td class="center qty_ds all_qty"><?=$item->total_qty?></td>
								<td class="right nominal sales_ds"><?=$item->total_amount?></td>
								<td class="right nominal cogs_ds"><?=$item->inventory_nominal?></td>
								<td class="right"><?php echo round($item->margin_percentage, 2); 
														$margintotal = $margintotal + $item->margin_percentage;
												  ?> %</td>
							</tr>
							
							<?php endforeach; 
							}?>
							
							<tr>
								<td class="right" colspan="3">Total</td>
								<td class="center" id="totalqty_ds" style="font-weight:bold;"> </td>
								<td class="right nominal" style="font-weight:bold;" id="totalsales_ds"></td>
								<td class="right nominal" style="font-weight:bold;" id="totalcogs_ds"></td>
								<td class="right" style="font-weight:bold;"><?php if ($row > 0) echo round($margintotal/$row,2); else echo 0;?> %</td>
							</tr>
						</tbody>
					</table>
				</div>
				
<script> 
	
	$(calculateallqty);
	function calculateallqty() {
		var sum = 0;
		
		$(".allqty").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#all_total_qty').text(sum);
	};
	
	$(calculateqty);
	function calculateqty() {
		var sum = 0;
		
		$(".qty").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalqty').text(sum);
	};
	
	$(calculatesales);
	function calculatesales() {
		var sum = 0;
		
		$(".sales").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalsales').text(sum);
	};
	
	$(calculatecogs);
	function calculatecogs() {
		var sum = 0;
		
		$(".cogs").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalcogs').text(sum);
	};
	
	$(calculateqty_ds);
	function calculateqty_ds() {
		var sum = 0;
		
		$(".qty_ds").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalqty_ds').text(sum);
	};
	
	$(calculatesales_ds);
	function calculatesales_ds() {
		var sum = 0;
		
		$(".sales_ds").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalsales_ds').text(sum);
	};
	
	$(calculatecogs_ds);
	function calculatecogs_ds() {
		var sum = 0;
		
		$(".cogs_ds").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalcogs_ds').text(sum);
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
