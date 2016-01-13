<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Re-Stock History</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<?php $session_data = $this->session->userdata('logged_in'); ?>
				<div style="float:left">
					<a href="<?=base_url()?>index.php/stock/lists" title="Back" style = "color:#fff;" class="btn btn-success">Back</a>&nbsp;
				</div>
				
                <table width="100%" class="table table-bordered" id="dyntable" >
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="8%" class="head1 center">Restock Date</th>
							<th width="5%" class="head0 center">QTY</th>
							<th width="5%" class="head0 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_cart == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_cart as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$i++;?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->inventory_date))?></td>
							<td class="center"><?=$item->stock_qty?></td>
							<td class="centeralign">
								<a href="<?=base_url()?>index.php/stock/undo_restock/<?=$item->inventory_id?>" title="Undo Restock">Undo</a>&nbsp;
							</td>	
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
			

						<script>
							$("#togglefindproduct").click(function(){
								$("#find_product").slideToggle();
							});
						</script>	