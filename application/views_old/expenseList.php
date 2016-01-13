<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Expenses</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/expense/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Type</label>
									<span class="field">
										<select name="expense_type_id" id="expense_type_id" class="input-xlarge">
											<option value=''>Choose One</option>
										<?php foreach($expense_type as $type): ?>
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
											<input type="text" class="input-xlarge" name="expense_desc" id="expense_desc" /> 
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
						<a href="<?=base_url()?>index.php/expense/add" title="Add Event" style = "color:#fff;" class="btn btn-success">Add Expense</a>&nbsp;
						<a href="<?=base_url()?>index.php/expense/detail" title="Detail" class="btn">Detail</a>&nbsp;
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
                            <th width="10%" class="head1 center">Bank Account</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_expense == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_expense as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->option_desc?></td>
                            <td><?=$item->expense_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->expense_date))?></td>
                            <td class="center"><?=$item->expense_nominal?></td>
							<td class="center"><?=$item->bank_account_name?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/expense/update/<?=$item->expense_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('expense/delete/'.$item->expense_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
								
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			
