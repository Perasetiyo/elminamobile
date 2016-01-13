<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Perbandingan Barang Terjual dan Restock per Bulan</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				
			
				<form method="post" action="<?=base_url()?>index.php/cart_stats/search_sold_restock">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<a href="<?=base_url()?>index.php/cart_stats/main" title="PRODUCTS TREND" class="btn">TREN PRODUK</a>&nbsp;
						<a href="<?=base_url()?>index.php/cart_stats/sold_restock" title="SOLD vs RESTOCK" class="btn">SOLD vs RESTOCK</a>&nbsp;
						
						<h4 class="widgettitle">Filter Bulan</h4>
						<div class="widgetcontent">
							<label>						
								<span class="field">
									<select name="option_id" id="option_id" style="width:200px;" class="validate[required]">
										<option> - Bulan - </option>
									<?php foreach($months as $month): ?>
										<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
									<?php endforeach; ?>
									</select>
								</span>
								<span class="field">
									<select name="product_id" id="product_id" style="width:200px;" class="validate[required]">
										<option> - Produk - </option>
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
							<th width="25%" class="center">Product Name</th>
							<th width="10%" class="center">Sold Qty</th>
							<th width="10%" class="center">Restock Qty</th>
							<th width="10%" class="center">Free Qty</th>
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
					        <td><?=$item->product_name?> - <?=$item->stock_desc?></td>
							<td class="center sold"><?=$item->sold_qty?></td>
							<td class="center sold"><?=$item->restock_qty?></td>
							<td class="center sold"><?=$item->stock_qty?></td>
                        </tr>
					   <?php }} ?>
					   
						<tr class="gradeX">
						    <td class="center" colspan="2">Total : </td>
							<td class="center"><?=$total_sold_qty?></td>
							<td class="center"><?=$total_restock_qty?></td>
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