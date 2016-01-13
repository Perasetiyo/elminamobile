<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Barang / Bahan Mentah</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/material_inventory/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<a href="<?=base_url()?>index.php/material_inventory/lists" title="Inventory Barang Mentah" class="btn">Inventory Barang Mentah</a>&nbsp;
						<a href="<?=base_url()?>index.php/on_process_inventory/lists" title="Inventory Barang Jadi" class="btn">Inventory Barang 1/2 Jadi</a>&nbsp;
						<a href="<?=base_url()?>index.php/inventory/lists" title="Inventory Barang Jadi" class="btn">Inventory Barang Jadi</a>&nbsp;
						
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Material Code</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="material_code" id="material_code" /> 
									</span>
								</li>
								
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
				<form method="post" action="<?=base_url()?>index.php/material_inventory/push_button">
					<div style="float:left">
						<a href="<?=base_url()?>index.php/material_inventory/add" title="Add" class="btn btn-success">Add</a>&nbsp;
						<input type="submit" name="action" class="btn" value="Cetak Kode" />
						<input type="submit" name="action" class="btn" value="Bulk Emptying" />
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
                        <col class="con1" />
						<col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="2%" class="head1 center">No</th>
							<th width="1%" class="head0 center nosort"></th>
							<th width="8%" class="head1 center">Kode</th>
							<th width="10%" class="head1 center">Description</th>
							<th width="8%" class="head1 center">Tanggal Masuk</th>
							<th width="5%" class="head0 center">Ukuran saat masuk</th>
                            <th width="5%" class="head0 center">Sisa saat ini</th>
                            
							<th width="3%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_material_inventory == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$row = 0;
								foreach($list_material_inventory as $item):
									$row++;
								?>
									
                        <tr class="gradeX">
						
							<td class="center" <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>><?=$row;?></td>
							<td class="center" <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>>
								<span class="center">
									<input type="checkbox" name="ch<?=$row;?>" value="<?=$item->material_id?>"/>
								</span>
							</td>
							<td <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>><?=$item->material_code?></td>
							<td <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>>
								<?=$item->material_bahan?> - <?=$item->material_warna?> 
							</td>
							<td class="center" <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>><?php echo date("d-M-Y", strtotime($item->material_date_init))?></td>
                            <td class="center" <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>><?=$item->material_qty_init?></td>
							<td class="center" <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?>>
								<?=$item->material_qty?><br/>
								<a href="<?=base_url()?>index.php/material_inventory/log_history/<?=$item->material_id?>" title="Log History">History
								</td>
							
							<td class="center" <?php if ($item->material_qty <= 13) echo 'style="background-color:pink;"'; ?> >
								<a href="<?=base_url()?>index.php/material_inventory/cutting/<?=$item->material_id?>" title="Cutting">
									<span class="iconsweets-eyedrop">
								</a>&nbsp;
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				</form>
				<p><?php echo $links; ?></p>
			
