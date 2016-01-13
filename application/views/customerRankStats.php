<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.tablesorter/jquery.tablesorter.js"></script>

<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Customer Rank</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
				<form method="post" action="<?=base_url()?>index.php/customer_stats/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">Search </h4>
						<div class="widgetcontent">
							
							<span class="field" style="padding-right:5px;padding-bottom:5px;float:left;">
								<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
							</span>
							
							<span class="field">
								<input type="text" id="startdate" name="startdate" placeholder="Range Awal">								
							</span>
							<span class="field">
								<input type="text" id="enddate" name="enddate" placeholder="Range Akhir">
							</span>
							<span class="field">
								<select name="billing_id" id="billing_id" class="input-xsmall">
									<option value>- Agent Name -</option>
									<?php foreach($billings as $billing) {?>
									<option value='<?=$billing->billing_id?>'><?=$billing->billing_name?></option>
									<?php } ?>
								</select>
							</span>
							<label>Order By Column :</label>
							<span class="field">
								<select name="order_column" id="order_column" class="input-xsmall">
									<option value='nominal'>Total Purchase</option>
									<option value='billing_name'>Billing Name</option>
									<option value='purchase_count'>Purchase Counts</option>
								</select>
								<select name="order_type" id="order_type" class="input-xsmall">
									<option value='desc'>Desc</option>
									<option value='asc'>Asc</option>
								</select>
							</span>
							<p>
								<button class="btn btn-primary">CARI</button>
							</p>
						</div>
						
					</div>
				</form>
				
				<h4 class="widgettitle">Range tanggal : <?=$startdate?> - <?=$enddate?></h4><br/>
				<table width="100%" class="table table-bordered tablesorter" id="customerRankTable">
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
                          	<th width="3%" class="center">No</th>
							<th width="7%">Billing Name</th>
							
							<th width="5%" class="center">Total Qty Sold</th>    
							
							<th width="5%" class="center">Billing Purchase</th>    
							<th width="5%" class="center">Billing Count</th>    
							<th width="5%" class="center">Dropship Purchase</th>  
							<th width="5%" class="center">Dropship Count</th>   
							<th width="5%" class="center">Reseller Purchase</th>    
							<th width="5%" class="center">Reseller Count</th>    
							<th width="5%" class="center">Total Purchase</th>    
							<th width="5%" class="center">Total Count</th>   
							
                        </tr>
                    </thead>
                    <tbody>
					<?php 
						if ($list_customer_rank == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
							$i = 1;
						foreach ($list_customer_rank as $item) {?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
					        <td><a href="<?=base_url()?>index.php/customer_stats/customer_rank_detail/<?=$item->billing_id?>"><?=$item->billing_name?></a></td>
							
							<td class="center">
								<?=$item->product_sold_qty?>
							</td>
							
							<td class="right nominal">
								<span class=""><?=$item->billing_nominal?></span><br/>
							</td>
							<td class="center">
								<?=$item->billing_count?>
							</td>
							<td class="right nominal">
								<span class=""><?=$item->dropship_nominal?></span><br/>
							</td>
							<td class="center">
								<?=$item->dropship_count?>
							</td>
							<td class="right nominal">
								<span class=""><?=$item->reseller_nominal?></span><br/>
							</td>
							<td class="center">
								<?=$item->reseller_count?>
							</td>
							<td class="right nominal">
								<span class=""><?=$item->billing_nominal + $item->dropship_nominal + $item->reseller_nominal?></span>
							</td>
							<td class="center">
								<?=$item->billing_count + $item->dropship_count + $item->reseller_count?>
							</td>
                        </tr>
					   <?php }} ?>
                    </tbody>
                </table>
				<div class="clear"></div>

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
		
	$(document).ready(function() { 
        $("#customerRankTable").tablesorter(); 
    }
); 
</script>
