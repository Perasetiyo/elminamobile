<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Detail Pembayaran <?=$fixed_cost_name?> di bulan <?=$month?></h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/fixed_cost_history/search">
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
							<th width="12%" class="head1 center">Nama</th>
							<th width="12%" class="head1 center">Tanggal</th>
							<th width="12%" class="head0 center">Nominal</th>
							<th width="15%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_fixed_cost_history_detail == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								$row = 1;
								foreach($list_fixed_cost_history_detail as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td><?=$item->fixed_cost_name?></td>
                            <td class="center"><?=$item->fixed_cost_history_date?></td>
							<td class="right nominal"><?=$item->fixed_cost_history_nominal?></td>
							
							<td class="centeralign">
								<a href="<?=base_url()?>index.php/fixed_cost_history/update/<?=$item->fixed_cost_history_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('fixed_cost_history/delete/'.$item->fixed_cost_history_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
		
<script>		
$(function() {
		$( ".date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>		