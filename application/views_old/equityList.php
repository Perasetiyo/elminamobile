<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Equity</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/equity/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Type</label>
									<span class="field">
										<select name="equity_type_id" id="equity_type_id" class="input-xlarge">
											<option value=''>Choose One</option>
										<?php foreach($equity_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Bank Account</label>
									<span class="field">
										<select name="bank_account_id" id="bank_account_id" class="input-xlarge">
											<option value=''>Choose One</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="equity_desc" id="equity_desc" /> 
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
						<a href="<?=base_url()?>index.php/equity/add" title="Add Event" style = "color:#fff;" class="btn btn-success">Add Equity</a>&nbsp;
						<a href="<?=base_url()?>index.php/equity/detail" title="Detail" class="btn">Detail</a>&nbsp;
					</div>
					<?php echo $links; ?>
				
				
                <table width="100%" class="table table-bordered" id="dyntable" >
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
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_equity == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_equity as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->option_desc?></td>
                            <td><?=$item->equity_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->equity_date))?></td>
                            <td class="center"><?=$item->equity_nominal?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/equity/update/<?=$item->equity_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('equity/delete/'.$item->equity_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>