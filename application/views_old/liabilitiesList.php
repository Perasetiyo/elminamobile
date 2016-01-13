<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Liabilities</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/liabilities/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Type</label>
									<span class="field">
										<select name="liabilities_type_id" id="liabilities_type_id" class="input-xlarge">
											<option value=''>Choose One</option>
										<?php foreach($liabilities_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								
								<li>
									<label>Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="liabilities_desc" id="liabilities_desc" /> 
									</span>
								</li>
								<li>
									<label>Nominal Type </label>
									<span class="field">
										<select name="nominal_type" id="nominal_type" style="width:50px;" class="input-xlarge">
											<option value="+">+</option>
											<option value="-">-</option>
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
					<div style="float:left">
						<a href="<?=base_url()?>index.php/liabilities/money_repaid" title="Money Repaid / Bayar Hutang" style = "color:#fff;" class="btn btn-primary">Money Repaid</a>
						<a href="<?=base_url()?>index.php/balance_sheet/main" title="Balance Sheet" class="btn ">Balance Sheet</a>&nbsp;
					</div>
					<?php echo $links; ?>
				
                <table width="100%" class="table table-bordered" id="dyntable">
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
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="10%" class="head1 center">Type</th>
							<th width="18%" class="head0 center">Desc</th>
							<th width="8%" class="head1 center">Date</th>
                            <th width="8%" class="head0 center">Nominal</th>
                            <th width="10%" class="head1 center">Cause</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_liabilities == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_liabilities as $item) {?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->type_option_desc?></td>
                            <td><?=$item->liabilities_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->liabilities_date))?></td>
                            <td class="center"><?=$item->liabilities_nominal?></td>
							<td class="center"><?=$item->cause_option_desc?></td>
                            <td class="centeralign">
								<?php 	if (!empty($item->liabilities_cause_id)) {
											echo 'Disabled';
										} else {
											if ($item->liabilities_type_id == 84) {?> 
												<a href="<?=base_url()?>index.php/liabilities/updateRepaid/<?=$item->liabilities_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
											<?php } else {?>
												<a href="<?=base_url()?>index.php/liabilities/update/<?=$item->liabilities_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
											<?php } ?>
											<?php echo anchor('liabilities/delete/'.$item->liabilities_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));
										}?>
							</td>
                        </tr>
                        <?php }; 
						};?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			
