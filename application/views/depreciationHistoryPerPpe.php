<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Depreciation History of  <Month></h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
				
				<a href="<?=base_url()?>index.php/depreciation/lists" class="btn">BACK</a>&nbsp;
				
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
							<th width="10%" class="head0 center">PPE Stuff Name</th>
							<th width="8%" class="head1 center">Depreciation Date</th>
                            <th width="5%" class="head0 center">Period</th>
                            
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_history_per_ppe == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_history_per_ppe as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$i++;?></td>
							<td><?=$item->ppe_desc?></td>
                            <td class="center"><?php echo date("d-M-Y", strtotime($item->depreciation_date))?></td>
							<td class="center"><?=$item->depreciation_period?></td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
			
