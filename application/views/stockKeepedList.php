<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Keeped Stock</h1>
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
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="18%" class="head0 center">Order by</th>
							<th width="8%" class="head1 center">Order Date</th>
							<th width="5%" class="head0 center">QTY</th>
							<th width="8%" class="head1 center">Sudah Bayar ?</th>
							<th width="8%" class="head1 center">Sudah Dikirim ?</th>
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
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->billing_name?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->order_date))?></td>
							<td class="center"><?=$item->cart_qty?></td>
							<td class="center"><?php 
								if ($item->order_status == 2) echo '<font color="lime">Sudah</font>';
								else if ($item->order_status == 1) echo '<font color="blue">Masih DP</font>';
								else echo '<font color="Red">Belum</font>';
								?>
							</td>
							<td class="center"><?php if ($item->package_status == 1) 
								echo '<font color="lime">Sudah</font>';else echo '<font color="red">Belum</font>';?>
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