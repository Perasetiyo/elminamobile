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
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								
								<li>
									<label>Billing Name</label>
										<span class="field">
											<input type="text" class="input-xsmall" name="billing_name" id="billing_name" /> 
										</span>
								</li>
								<li>
									<label>Purchase Period :</label>
									<span class="field">
										<select name="purchase_period" id="purchase_period" style="width:100px;" class="validate[required]">
											<option value> - Month - </option>
										<?php foreach($months as $month): ?>
											<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Order By Column :</label>
									<span class="field">
										<select name="order_column" id="order_column" class="input-xsmall">
											<option value='nominal'>Total Purchase</option>
											<option value='billing_name'>Billing Name</option>
											<option value='purchase_count'>Purchase Counts</option>
										</select>
									</span>
								</li>
								<li>
									<label>Order Type :</label>
									<span class="field">
										<select name="order_type" id="order_type" class="input-xsmall">
											<option value='desc'>Desc</option>
											<option value='asc'>Asc</option>
										</select>
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Submit</button>
							</div>							
						</div>
					</div>
				</form>
				
				<?php echo $links; ?>
	
				<table width="100%" class="table table-bordered" id="rosetatable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
						<col class="con0" />
                        
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="center">No</th>
							<th width="7%" class="center">Billing Name</th>
							<th width="5%" class="center">Total Purchase</th>    
							<th width="5%" class="center">Purchase Count(s)</th>    							
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
					        <td><?=$item->billing_name?></td>
							<td class="center nominal"><?=$item->nominal?></td>
							<td class="center"><?=$item->purchase_count?></td>
                        </tr>
					   <?php }} ?>
                    </tbody>
                </table>
				<?php echo $links; ?>
				<div class="clear"></div>

