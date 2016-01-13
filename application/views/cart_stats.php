<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Cart Statistics</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				
			
				<form method="post" action="<?=base_url()?>index.php/cart_stats/search_main">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<a href="<?=base_url()?>index.php/cart_stats/main" title="PRODUCTS TREND" class="btn">TREN PRODUK</a>&nbsp;
						<a href="<?=base_url()?>index.php/cart_stats/sold_restock" title="SOLD vs RESTOCK" class="btn">SOLD vs RESTOCK</a>&nbsp;
						
						<h4 class="widgettitle">Product Filter</h4>
						<div class="widgetcontent">
							<label>						
								<span class="field">
									<select name="product_id" id="product" style="width:200px;" class="validate[required]">
										<option> - Products - </option>
									<?php foreach($products as $product): ?>
										<option value="<?php echo $product->product_id?>"><?php echo $product->product_name?></option>
									<?php endforeach; ?>
									</select>
								</span>
								
								<button class="btn btn-primary">SUBMIT</button>
							</label>
						</div>
					</div>
				</form>
				
				
				<table width="100%" class="table table-bordered" id="rosetatable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="center">No</th>
							<th width="7%" class="center">Product Name</th>
							<th width="7%" class="center">Bulan</th>
							<th width="5%" class="center">Sold Qty</th>
							<th width="5%" class="center">Reject Qty</th>
							
                        </tr>
                    </thead>
                    <tbody>
					<?php 
						$row = 1;
						if ($list_monthly_sold_cart == null) {
						?>
						<tr class="gradeX">
							<td class="center"  colspan='5'><font class="no-data-tabel">No Sales!<font></td>
                        </tr>
					   <?php 
						} else {
							foreach ($list_monthly_sold_cart as $item) {?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
					        <td class="center"><?=$item->product_name?></td>
							<td class="center"><?=$item->month?></td>
							<td class="center sold"><?=$item->total_qty?></td>
							<td class="center reject"><?=$item->reject_qty?></td>
							
                        </tr>
					   <?php }} ?>
					   
						<tr class="gradeX">
						    <td class="center" colspan="3">Total Ready Stock : <?=$ready_qty?></td>
							
							<td class="center" id="totalsold">Total Sold : </td>
							<td class="center" id="totalreject">Total Reject : </td>
					    </tr>
                    </tbody>
                </table>
				
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
	
	$(calculatereject);
	function calculatereject() {
		var sum = 0;
		
		$(".reject").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalreject').text('Total Reject : ' + sum);
	};
</script>