<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>History Barang <?=$material_code?></h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
					<div style="float:left">
						<a href="<?=base_url()?>index.php/material_inventory/lists" title="Add" class="btn btn_success">Back</a>&nbsp;
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
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="5%" class="head1 center">Tanggal</th>
							<th width="8%" class="head1 center">Desc</th>
							<th width="3%" class="head0 center">Terpakai</th>
							<th width="3%" class="head1 center">Sisa</th>
                            <th width="5%" class="head0 center">Terpakai</th>
							<th width="5%" class="head1 center">Sisa</th>
							
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_material_inventory_log == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 1;
								foreach($list_material_inventory_log as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->material_inventory_log_date))?></td>
                            <td><?=$item->material_inventory_log_desc?></td>
							<td class="center"><?=$item->material_inventory_log_used_qty?></td>
							<td class="center"><?=$item->material_inventory_log_last_qty?></td>
							<td class="center"><?=$item->material_inventory_log_used_nominal?></td>
							<td class="center"><?=$item->material_inventory_log_last_nominal?></td>
							
                        </tr>
								<?php endforeach; 
							}?>
                    </tbody>
                </table>
