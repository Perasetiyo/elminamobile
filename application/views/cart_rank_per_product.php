<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Cart Rank Per Product</h1>
</div>
			<div class="maincontentinner">
				
				<hr/>
				<a href="<?=base_url()?>index.php/cart_stats/sold_restock" title="INVENTORY STATS" class="btn">INVENTORY STATS</a>&nbsp;
				<a href="<?=base_url()?>index.php/cart_stats/trends" title="TRENDS" class="btn">TRENDS</a>&nbsp;
				<hr/>	
				
				<div class="span5">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
							<tr>
								<th width="50%" class="center" >Product Name - Stock Desc</th>
								<th width="30%" class="center" >Sold Qty</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if ($list_stock_sold == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								foreach($list_stock_sold as $item):
									$row++;
							?>
                        
							<tr class="gradeX">
								<td class="left">
										<?=$item->product_name?> - <b><?=$item->stock_desc?></b>
								</td>
								<td class="center"><?=$item->total_qty?></td>
								
							</tr>
							
							<?php endforeach; 
							}?>
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
