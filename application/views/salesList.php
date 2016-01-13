<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Sales</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/sales/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Description</label>
									<span class="field">
										<input type="text" class="input-xlarge" name="sales_desc" id="sales_desc" />
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
					<div style="float:left">
						<a href="<?=base_url()?>index.php/orderAction/addOrder" title="Add Order / Sales" style = "color:#fff;" class="btn btn-success">Add Sales</a>&nbsp;
					</div>
					<?php echo $links; ?>
					<div class="clear"></div>
				
				
                <table width="100%" class="table table-bordered" id="dyntable" style="margin-top:-185px;">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="10%" class="head1 center">Sales Date</th>
							<th width="18%" class="head0 center">Description</th>
							<th width="8%" class="head1 center">Nominal</th>
                            <th width="8%" class="head0 center">COGS</th>
                            <th width="10%" class="head1 center">Earning</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_sales == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_sales as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->sales_date))?></td>
                            <td><?=$item->sales_desc?></td>
							<td class="right nominal"><?=$item->sales_nominal?></td>
							<td class="right nominal"><?=$item->sales_cogs?></td>
							<td class="right nominal"><?php echo $item->sales_nominal - $item->sales_cogs?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/sales/update/<?=$item->sales_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('sales/delete/'.$item->sales_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			
