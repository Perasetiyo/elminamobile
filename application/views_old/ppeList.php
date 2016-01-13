<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List PPE</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/ppe/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Type</label>
									<span class="field">
										<select name="ppe_type_id" id="ppe_type_id" class="input-xlarge">
											<option value=''>Choose One</option>
										<?php foreach($ppe_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="ppe_desc" id="ppe_desc" /> 
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
						<a href="<?=base_url()?>index.php/ppe/add" title="Add Event" style = "color:#fff;" class="btn btn-success">Add ppe</a>&nbsp;
						<a href="<?=base_url()?>index.php/ppe/detail" title="Detail" class="btn">Detail</a>&nbsp;
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
							<th width="6%" class="head1 center">Type</th>
							<th width="10%" class="head0 center">Desc</th>
							<th width="7%" class="head1 center">Date</th>
                            <th width="7%" class="head0 center">Nominal</th>
							<th width="7%" class="head1 center">Net Price</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_ppe == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_ppe as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->option_desc?></td>
                            <td><?=$item->ppe_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->ppe_date))?></td>
                            <td class="center"><?=$item->ppe_nominal?></td>
							<td class="center"><?php echo $item->ppe_nominal - $item->ppe_has_been_paid?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/ppe/update/<?=$item->ppe_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('ppe/delete/'.$item->ppe_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
								
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			
