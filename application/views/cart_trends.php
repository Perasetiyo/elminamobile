<script type="text/javascript" src="<?=base_url()?>assets/js/highcharts.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.highchartTable.js"></script>

<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Cart Trends</h1>
		<h5><?=$page_title?></h5>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				
				<a href="<?=base_url()?>index.php/cart_stats/sold_restock" title="INVENTORY STATS" class="btn">INVENTORY STATS</a>&nbsp;
				<a href="<?=base_url()?>index.php/cart_stats/trends" title="TRENDS" class="btn">TRENDS</a>&nbsp;
						
				<form method="post" action="<?=base_url()?>index.php/cart_stats/trends">
					<div id="wiz1step1" class="formwiz span3">
					<hr>
										
						<h4 class="widgettitle">Filter</h4>
						<div class="widgetcontent">
							<label>		
								
								<span class="field">
									<input type="text" name="stockname" id="stockname" style="width:205px;" placeholder="Stock Description">
								</span>	
								
								<span class="field">
									<select name="interval" id="interval" style="width:219px;" class="validate[required]">
										<option value> Interval : </option>
										<option value="DAY">Daily</option>
										<option value="WEEK">Weekly</option>
										<option value="MONTH">Monthly</option>
									</select>
								</span>	
								
								<span class="field">
									<select name="group_by" id="group_by" style="width:219px;" class="validate[required]" onchange="chooseInventory()">
										<option value> Group By : </option>
										<option value="PRODUCT">Product</option>
										<option value="STOCK">Stock</option>
									</select>
								</span>
								
								<span class="field">
									<select name="product_id" id="product_id" style="width:219px; display:none;" class="validate[required]">
										<option value> - Produk - </option>
									<?php foreach($products as $product): ?>
										<option value="<?php echo $product->product_id?>"><?php echo $product->product_name?></option>
									<?php endforeach; ?>
									</select>
									
									<select name="stock_id" id="stock_id" style="width:219px; display:none;" class="validate[required]">
										<option value> - Stock - </option>
									<?php foreach($stocks as $stock): ?>
										<option value="<?php echo $stock->stock_id?>"><?php echo $stock->product_name?> - <?php echo $stock->stock_desc?></option>
									<?php endforeach; ?>
									</select>
								</span>
								
								<span class="field" style="padding-right:5px;padding-bottom:5px;float:left;">
									<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
								</span>
								
								<span class="field">
									<input type="text" id="startdate" name="startdate" placeholder="Range Awal">
									<input type="text" id="enddate" name="enddate" placeholder="Range Akhir">
								</span>
								
								<p class="field">
									<button class="btn btn-primary">SUBMIT</button>
								</p>
							<div class="clear"></div>
							</label>
						</div>
					</div>
				</form>
			<div id="wiz1step1" class="formwiz span7">	
					<hr>
				<?php 
					$all_qty = 0;
					$count = 0;
					
					if ($list_trends != null) {
						foreach ($list_trends as $kuantitas) {
							$all_qty += $kuantitas->total_qty;
							$count++;
						}
						$avg_sold_qty = round($all_qty/$count);
				?>		
				    <h4 class="widgettitle"><center>Grafik Penjualan<center></h4>
				    <h4>
						<?=$page_title?>
						Total sold : <b><?=$all_qty?> pcs in <?=$count?> day(s)</b> <br>
						Avg sold / day : <b><?=$avg_sold_qty?> pcs</b>
				    </h4>					
					<hr/>
				<?php } ?>
				
				<table width="100%" class="table table-bordered highchart" id="rosetatable" 
					data-graph-container-before="1" 
						<?php if ($interval == 'MONTH') {?>
							data-graph-type="column"
						<?php } else {?>
							data-graph-type="line"
						<?php } ?>	
				>
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead style="display:none;">
                        <tr>
                          	<th width="25%" class="center">Time</th>
							<th width="10%" class="center">Sold Qty</th>
							<th width="10%" class="center">Omset</th>
							<th width="10%" class="center">Avg Sold Qty</th>
							<?php if ($interval == 'MONTH') {?>
							<th width="10%" class="center">Target Omset</th>
							<?php } ?>
                        </tr>
                    </thead>
                    <tbody style="display:none;">
					
						
					<?php 
						$row = 1;?>
						
					<?php 
						if ($list_trends == null) {
						?>
						<tr class="gradeX">
							<td class="center"  colspan='3'><font class="no-data-tabel"><-- Belom difilter!<font></td>
                        </tr>
					   <?php 
						} else {
							foreach ($list_trends as $item) {?>
						<tr class="gradeX">
							<td><?=$item->dates?></td>
							<td class="center sold"><?=$item->total_qty?></td>
							<td class="center sold"><?=$item->total_omset/100000?></td>
							<td class="center sold"><?=$avg_sold_qty?></td>
							
							<?php if ($interval == 'MONTH') {?>
							<td class="center sold"><?=$item->target_monthly*10?></td>
							<?php } ?>
                        </tr>
                        <?php if ($interval == 'MONTH') {?>
                        <tr class="gradeX">
							<td></td><td></td><td></td><td></td>
						</tr>	
						<?php } ?>
					   <?php }} ?>
					   
                    </tbody>
                </table>
			</div>
			
			<div id="wiz1step1" class="formwiz span10">	
				<h4 class="widgettitle"><center>Trending Product<center></h4>
				
				<p style="text-align:center;">
					<label> . </label>
					<span class="field" >
						<input type="text" class="input-xlarge" name="inv_query" id="inv_query" 
									placeholder="Filter Product By Name"/> <br/>
					</span>
				</p>
				<p>
					<table id="inv_table" class="table table-bordered">
						<tr>
							<th width="25%" class="center">Product Name</th>
							
							<th width="10%" class="center">Last Restock</th>
							<th width="10%" class="center">Sold Qty</th>
							<th width="10%" class="center">Available Qty</th>
						</tr>
						<tbody class="zebra bordered">
							<?php if ($list_top_cart == null) {?>
								
								<tr class="gradeX"><td colspan="4"><center>Data Null</center></td></tr>
								<?php
								} else {
								foreach($list_top_cart as $top_cart) { ?>
							<tr class="gradeX">
								<td><?=$top_cart->product_name?> - <?=$top_cart->stock_desc?></td>
								<td class="center sold">
									<a href="<?=base_url()?>index.php/stock/restock_history/<?=$top_cart->stock_id?>" title="History">
										<?php echo date("d-M-Y", strtotime($top_cart->stock_date))?>
									</a>
								</td>
								
								<td class="center sold"><?=$top_cart->sold_qty?></td>
								<td class="center sold" <?php if ($top_cart->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>><?=$top_cart->stock_qty?></td>
							</tr>

							<?php }} ?>
										
						</tbody>
					</table>
			</div>

<script type="text/javascript">
// When document is ready: this gets fired before body onload :)
$(document).ready(function(){
	// Write on keyup event of keyword input element
	$("#inv_query").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#inv_table tbody>tr").hide();
			$("#inv_table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#inv_table tbody>tr").show();
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

function choosePaymentMethod() {
        if (document.getElementById('payment_method').value == 'ATM_TRANSFER') {
            document.getElementById('block_atm_transfer').style.display="block";
			
        } else if (document.getElementById('payment_method').value == 'WALLET') {
            document.getElementById('block_atm_transfer').style.display="none";
        }
    }

	
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

	function chooseInventory() {
        if (document.getElementById('group_by').value == 'PRODUCT') {
            document.getElementById('stock_id').style.display="none";
			document.getElementById('product_id').style.display="block";
			
        } else if (document.getElementById('group_by').value == 'STOCK') {
            document.getElementById('stock_id').style.display="block";
			document.getElementById('product_id').style.display="none";
        }
    }

	$(document).ready(function() {
	  $('table.highchart').highchartTable();
	});
</script>
