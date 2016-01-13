<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Fixed Cost</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/fixed_cost/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Nama</label>
										<span class="field">
											<input type="text" class="input-xlarge" name="fixed_cost_name" id="fixed_cost_name" /> 
										</span>
								</li>
							
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
				<?php echo $links; ?>
				
				<div style="float:left">
					<a href="<?=base_url()?>index.php/fixed_cost/add" title="Add Event" style = "color:#fff;" class="btn btn-success">Add Fixed Cost</a>&nbsp;
					
				</div>
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
							<th width="10%" class="head1 center">Nama</th>
							<th width="18%" class="head0 center">Nominal</th>
							<th width="18%" class="head0 center">Type</th>
							<th width="18%" class="head0 center">Change Date</th>
							<th width="18%" class="head0 center">Status</th>
							<th width="15%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_fixed_cost == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_fixed_cost as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td><?=$item->fixed_cost_name?></td>
							<td><?=$item->fixed_cost_nominal?></td>
							<td><?=$item->option_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->fixed_cost_per_date))?></td>
							<td><?=$item->fixed_cost_status?></td>
							<td class="centeralign">
								<a href="<?=base_url()?>index.php/fixed_cost/update/<?=$item->fixed_cost_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;

							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
		
<script>		
$(function() {
		$( ".date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>		