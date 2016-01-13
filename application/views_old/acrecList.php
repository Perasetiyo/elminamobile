<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Account Receivable</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/acrec/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								
								
								<li>
									<label>Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="acrec_desc" id="acrec_desc" /> 
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
				<p class="stdformbutton">
						<a href="<?=base_url()?>index.php/acrec/add" title="Add Event" style = "color:#fff;" class="btn btn-success">Add Account Receivable</a>&nbsp;
						<a href="<?=base_url()?>index.php/acrec/detail" title="Detail" class="btn">Detail</a>&nbsp;
						<a href="<?=base_url()?>index.php/balance_sheet/main" title="Balance Sheet" class="btn">Balance Sheet</a>&nbsp;
				</p>
			
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
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_acrec == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_acrec as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->option_desc?></td>
                            <td><?=$item->acrec_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->acrec_date))?></td>
                            <td class="center"><?=$item->acrec_nominal?></td>
                            <td class="centeralign">
								<?php 
									if ($item->acrec_type_id == 23) {
										echo 'Disabled';
									} else {
								?>
								<a href="<?=base_url()?>index.php/acrec/update/<?=$item->acrec_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('acrec/delete/'.$item->acrec_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
								<?php }?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<hr/>
				<p><?php echo $links; ?></p>