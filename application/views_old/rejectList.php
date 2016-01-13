<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Reject Stock</h1>
</div>          
				
				<div class="maincontentinner">
						
					<div class="success message">
						<?php 
							echo $this->session->flashdata('success_message');
						?>
					</div>
					<div class="error message">
						<?php 
							echo $this->session->flashdata('error_message');
						?>
					</div>
					<p class="stdformbutton">
						<div style="float:left;">
							<a href="<?=base_url()?>index.php/reject/add" title="Tambah" style = "color:#fff;" class="btn btn-success">Add Reject Stock</a>&nbsp;
						</div>
					</p>
				
				<form method="post" action="<?=base_url()?>index.php/reject/push_button">
					
					<table width="100%" class="table table-brejected" id="dyntable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
						</colgroup>
						<thead>
							<tr>
								<th class="head1 center">No</th>
								<th class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
								<th class="head1 center">Tanggal</th>
								<th class="head1 center">QTY</th>
								<th class="head0 center">Action</th>
							</tr>
						</thead>
						
						<tbody>
							
							<?php if ($list_reject == null) {
									?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
									} else {
										$i = 1;
										foreach ($list_reject as $item) :?>
							<tr class="gradeX">
							
								<td class="center"><?=$row++;?></td>
								<td class="center">
									<span class="center">
										<input type="checkbox" name="ch<?=$row;?>" value="<?=$item->reject_id?>"/>
									</span>
								</td>
								<td class="center"><?php echo date("d-M-Y", strtotime($item->reject_date))?></td>
								<td class="center"><?=$item->reject_qty?></td>
								<td class="centeralign">
									<a href="<?=base_url()?>index.php/reject/update/<?=$item->reject_id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
									<!--<?php echo anchor('reject/delete/'.$item->reject_id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus reject tersebut?')"));?>-->
								</td>
							</tr>
							<?php endforeach;
								}?>
						</tbody>
					</table>
				</form>