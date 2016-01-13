<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Stock</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/stock/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								
								<li>
									<label>Product Name / Stock Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="stock_desc" id="stock_desc" /> 
									</span>
								</li>
								
								<li>
									<label>Qty</label>
									<span class="field">
										<select name="qty_minimum" id="qty_minimum" class="input-small">
											<option value>All</option>
											<option value='1'>> 0</option>
											<option value='3'>>= 3</option>
											<option value='5'>>= 5</option>
											
										</select>
									</span>
								</li>
								
								<li>
									<label>Status</label>
									<span class="field">
										<select name="stock_status" id="stock_status" class="input-medium">
											<option value>All</option>
											<option value=0>Prelaunch</option>
											<option value=1>Active</option>
											<option value=2>Trash</option>
											
										</select>
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
					<?php $session_data = $this->session->userdata('logged_in'); ?>
					<div>
						<?php if ($session_data['user_role'] == 'admin' || $session_data['user_role'] == 'manager') { ?>
						<a href="<?=base_url()?>index.php/product/add" title="Add Product" style = "color:#fff;" class="btn btn-success">Add Product</a>&nbsp;
						<a href="<?=base_url()?>index.php/stock/add" title="Add Stock" style = "color:#fff;" class="btn btn-success">Add Stock</a>&nbsp;
						<!-- edit product -->						
						<form method="post" action="<?=base_url()?>index.php/product/update" >
							<a href="#" id="togglefindproduct" class="btn btn-inverse" style="float:left; margin-right:10px;">Find Product</a>
							<div id="find_product" style="display: none;">
								<font style="color:blue;">Product Name</font>
								<select name="find_product_id" id="product_id" class="input-xlarge">
									<option value=''>Choose One</option>
									<?php foreach($products as $product): ?>
									<option value="<?php echo $product->product_id?>"><?php echo $product->product_name?></option>
									<?php endforeach; ?>
								</select>
								<input type="submit" name="action" class="btn" value="Edit Product" />
							</div>
						</form>
						<?php } ?>
						<!-- end edit product -->
						
						<!--a href="<?=base_url()?>index.php/stock/detail" title="Detail" class="btn">Detail</a>&nbsp;-->
						<a href="<?=base_url()?>index.php/stock/summary" style = "color:#fff;" title="Current Stock" class="btn btn-danger">Show Current Stock</a>&nbsp;
						
					</div>
					<?php echo $links; ?>
				
				<form method="post" action="<?=base_url()?>index.php/stock/push_button">
					<?php if ($session_data['user_role'] == 'admin' || $session_data['user_role'] == 'manager') { ?>
					<input type="submit" name="action" class="btn" value="Activate Stock" />
					<input type="submit" name="action" class="btn" value="Move Stock into Trash" />
					<?php } ?>
                <table width="100%" class="table table-bordered" id="dyntable" >
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
						<col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="2%" class="head1 center">No</th>
							<th width="2%" class="head0 center nosort"></th>
							<th width="15%" class="head0 center">Prod. Name - Stock Desc</th>
							<th width="2%" class="head0 center">Free</th>
							<th width="4%" class="head0 center">Keeped</th>
							<th width="4%" class="head0 center">Sold</th>
							<th width="2%" class="head0 center">Reject</th>
							<th width="2%" class="head0 center">Adjust.</th>
							<th width="6%" class="head1 center">Restock Date</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_stock == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								foreach($list_stock as $item):
									$row++;
							?>
                        <tr class="gradeX">
						
							<td class="center" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>><?=$row;?></td>
							<td class="center" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>>
								<span class="center">
									<input type="checkbox" name="ch<?=$row;?>" value="<?=$item->stock_id?>"/>
								</span>
							</td>
                            <td <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>>
								<a href="<?=base_url()?>index.php/stock/stock_detail/<?=$item->stock_id?>" title="Detail">
									<font <?php if ($item->stock_status == 0) echo 'style="color:black;"'; 
												else if ($item->stock_status == 2) echo 'style="color:grey;"';?> >
										<?=$item->product_name?> - <?=$item->stock_desc?>
									</font>
								</a>
									<?php if (!empty($item->store_id_product)) {?>
										<small title="asdsad"><?=$item->store_id_category_default?> - <?=$item->store_id_product?></small>
									<?php } ?>
							</td>
							<td class="center"  <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>>
								<?=$item->stock_qty?>
							</td>
							<td class="center" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>><?=$item->keeped_qty?> &nbsp; 
								<a href="<?=base_url()?>index.php/stock/who_keep/<?=$item->stock_id?>" title="Keeped">Who?</a>&nbsp;
							</td>
							<td class="center"  <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>><?=$item->sold_qty?>
								<a href="<?=base_url()?>index.php/stock/sold_history/<?=$item->stock_id?>" title="History">History</a>
							</td>
							<td class="center" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>><?=$item->reject_qty?> &nbsp;</td>
							<td class="center" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>><?=0?> &nbsp;</td>
							<td class="center" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>>
								<a href="<?=base_url()?>index.php/stock/restock_history/<?=$item->stock_id?>" title="History">
									<?php echo date("d-M-Y", strtotime($item->stock_date))?>
								</a>
							</td>
							<td class="centeralign" <?php if ($item->stock_qty <= 3) echo 'style="background-color:pink;"'; ?>>
								<?php if ($session_data['user_role'] == 'admin' || $session_data['user_role'] == 'manager') { ?>
								<a href="<?=base_url()?>index.php/stock/restock/<?=$item->stock_id?>" title="Add Stock">Restock</a>&nbsp;
								<?php } ?>
								<a href="<?=base_url()?>index.php/stock/update/<?=$item->stock_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<!--<?php echo anchor('stock/delete/'.$item->stock_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>-->
								
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				</form>
				<p><?php echo $links; ?></p>
			

						<script>
							$("#togglefindproduct").click(function(){
								$("#find_product").slideToggle();
							});
						</script>	
