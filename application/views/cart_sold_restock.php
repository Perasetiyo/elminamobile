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
			
				<hr/>
				<a href="<?=base_url()?>index.php/cart_stats/sold_restock" title="INVENTORY STATS" class="btn">INVENTORY STATS</a>&nbsp;
				<a href="<?=base_url()?>index.php/cart_stats/trends" title="TRENDS" class="btn">TRENDS</a>&nbsp;
					<hr/>	
				<form method="post" action="<?=base_url()?>index.php/cart_stats/sold_restock">
					<div id="wiz1step1" class="formwiz span9">
						
						<h4 class="widgettitle">Filter Bulan</h4>
						<div class="widgetcontent">
							<label>						
								<span class="field" style="padding-right:5px;padding-bottom:5px;float:left;">
									<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
								</span>
								
								<span class="field">
									<input type="text" id="startdate" name="startdate" placeholder="Range Awal">
									<input type="text" id="enddate" name="enddate" placeholder="Range Akhir">
								</span>
								
								<span class="field">
									<select name="product_id" id="product_id" style="width:200px;" class="validate[required]">
										<option> - Produk - </option>
									<?php foreach($products as $product): ?>
										<option value="<?php echo $product->product_id?>"><?php echo $product->product_name?></option>
									<?php endforeach; ?>
									</select>
								</span>
								
								<p class="field">
									<button class="btn btn-primary">SUBMIT</button>
								</p>
							<div class="clear"></div>
							</label>
						</div>
					</div>
				</form>
				
				<div class="span3">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
						
							<tr>
								<th class="center" colspan="3" style="background-color: #0866C5;">
									<?=date("d M Y")?>
								</th>
							</tr>
							<tr>
								<th width="5%" class="center" rowspan="2">Product Name</th>
								<th width="20%" class="center" colspan="2">Free Qty</th>
							</tr>
							<tr>
								<th width="20%" class="center">Qty</th>
								<th width="20%" class="center">Nominal</th>
							</tr>
						</thead>
						<tbody>
						
							<tr class="gradeX">
								<td class="center" >All</td>
								<td class="center">
									<?=$total_free_qty?>
								</td>
								<td class="center nominal">
									<?=$total_free_nominal?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="span3">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
							<tr>
								<th class="center" colspan="3" style="background-color: #0866C5;">
									<?php if ($startdate != '1970-01-01') echo date("d M Y", strtotime($startdate)).' - '.date("d M Y", strtotime($enddate));
											else echo 'ALL TIME (valid on June)';
									?>
								</th>
							</tr>
							<tr>
								<th width="5%" class="center" rowspan="2">Product Name</th>
								<th width="20%" class="center" colspan="2">Sold Qty</th>
							</tr>
							<tr>
								<th width="20%" class="center">Qty</th>
								<th width="20%" class="center">Nominal</th>
							</tr>
						</thead>
						<tbody>
						
							<tr class="gradeX">
								<td class="center" >All</td>
								<td class="center">
									<?=$total_sold_qty?>
								</td>
								<td class="center nominal">
									<?=$total_sold_nominal?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="span3">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
							<tr>
								<th class="center" colspan="3" style="background-color: #0866C5;">
									
									<?php if ($startdate != '1970-01-01') echo date("d M Y", strtotime($startdate)).' - '.date("d M Y", strtotime($enddate));
											else echo 'ALL TIME (valid on June)';
									?>
								</th>
							</tr>
								<tr>
								<th width="5%" class="center" rowspan="2">Product Name</th>
								<th width="20%" class="center" colspan="2">Restock Qty</th>
							</tr>
							<tr>
								<th width="20%" class="center">Qty</th>
								<th width="20%" class="center">Nominal</th>
							</tr>
						</thead>
						<tbody>
						
							<tr class="gradeX">
								<td class="center" >All</td>
								<td class="center">
									<?=$total_restock_qty?>
								</td>
								<td class="center nominal">
									<?=$total_restock_nominal?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="span9">
					<hr/>
				</div>
				<div class="span3">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
							<tr>
								<th width="50%" class="center" >Product Name</th>
								<th width="30%" class="center" >Free Qty</th>
								<th width="30%" class="center" >Nominal</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if ($list_product_free == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								foreach($list_product_free as $item):
									$row++;
							?>
                        
							<tr class="gradeX">
								<td width="30%"><?=$item->product_name?></td>
								<td width="20%" class="center"><?=$item->total_qty?></td>
								<td width="50%" class="center nominal"><?=$item->total_nominal?></td>
								
							</tr>
							
							<?php endforeach; 
							}?>
						</tbody>
					</table>
				</div>
				<div class="span3">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						<thead>
							<tr>
								<th width="30%" >Product Name</th>
								<th width="20%" class="center" >Sold Qty</th>
								<th width="50%" class="center" >Sold COGS</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if ($list_product_sold == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								foreach($list_product_sold as $item):
									$row++;
							?>
                        
							<tr class="gradeX">
								<td>
									<a href="">
									<a href="<?=base_url()?>index.php/cart_stats/sold_per_stock/<?=$item->product_id?>" title="Detail">
										<?=$item->product_name?>
									</a>
								</td>
								<td class="center"><?=$item->total_qty?></td>
								<td class="center nominal"><?=$item->total_cogs?></td>
							</tr>
							
							<?php endforeach; 
							}?>
						</tbody>
					</table>
				</div>
				<div class="span3">
					<table width="100%" class="table table-bordered" id="rosetatable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con1" />
						</colgroup>
						
						<thead>
							<tr>
								<th width="30%" >Product Name</th>
								<th width="20%" class="center" >Restock Qty</th>
								<th width="50%" class="center" >Restock COGS</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if ($list_product_restock == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								foreach($list_product_restock as $item):
									$row++;
							?>
                        
							<tr class="gradeX">
								<td><?=$item->product_name?></td>
								<td class="center"><?=$item->total_qty?></td>
								<td class="center nominal"><?=$item->total_cogs?></td>
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
