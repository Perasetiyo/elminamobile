<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Existing Customer</h1>
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
					<div>
					<a href="<?=base_url()?>index.php/order" title="Back" style = "color:#fff;" class="btn btn-success">Back</a>&nbsp;
					</div>
				
				<form method="post" action="<?=base_url()?>index.php/order/choose_customer">
					
					<table width="100%" class="table table-bordered" id="dyntable">
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
								<th class="head1 center">Nama</th>
								<th class="head0 center">Daerah Pengiriman</th>
								<th class="head1 center">No Telp</th>
								<th class="head1 center">Level</th>
								<th class="head0 center">Action</th>
							</tr>
						</thead>
					<tbody>
						
                    	<?php 
							if ($list_customer == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
							} else {
						
							$row = 1;
							foreach($list_customer as $customer):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
					        <td><?=$customer->billing_name?></td>
							<td class="center"><?=$customer->billing_street?><br/>
												<?=$customer->billing_city?></td> 
							<td class="center"><?=$customer->billing_phone?></td>
							<td class="center"><?=$customer->option_desc?></td>
							<td class="centeralign">
                            	<a href="<?=base_url()?>index.php/order/choose_customer/<?=$customer->billing_id?>" title="Pilih"><span class="iconsweets-like"></span></a>&nbsp;
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
				</form>
				
					<p><?php echo $links; ?></p>
				
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
							
						</colgroup>
						<thead>
							<tr>
								<th  class="head1 center">No</th>
								<th  class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
								<th  class="head1 center">Penerima</th>
								<th class="head1 center">Tanggal Order</th>
								<th class="head0 center">Daerah Pengiriman</th>
								<th class="head1 center">Belanja</th>
								<th class="head1 center">Diskon</th>
								<th class="head0 center">Ongkir</th>
								<th class="head0 center">Total</th>
								<th class="head1 center">Status Bayar</th>
								<th class="head1 center">Status Kirim</th>
							</tr>
						</thead>
									
						<tbody>
							
							<?php 
								if ($list_history_order == null) {
								?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
								} else {
							
								$i = 1;
								foreach($list_history_order as $order):?>
							<tr class="gradeX">
							
								<td class="center"><?=$row++;?></td>
								<td class="center">
									<span class="center">
										<input type="checkbox" />
									</span>
								</td>
								<td><?=$order->billing_name?></td>
								<td class="center"><?php echo date("d-M-Y", strtotime($order->order_date))?></td>
								<td class="center"><?=$order->billing_kec?>,&nbsp; <?=$order->billing_city?></td>
								<td class="center">
									<?php if ($order->product_amount >= 1000000) { ?> 
										<span style="color:blue; font-weight:bold;"><?=$order->product_amount?></span>
									<?php } else if ($order->product_amount >= 500000) { ?>
										<span style="color:blue;"><?=$order->product_amount?></span>
									<?php } else { ?>
										<span style="color:red;"><?=$order->product_amount?></span>
									<?php } ?>
								</td> 
								<td class="center"><?=$order->discount_amount?></td>
								<td class="center"><?=$order->exp_cost?></td>
								<td class="center"><?=$order->total_amount?></td>
								<td class="center"><?php 
														if ($order->order_status == 2) {
															echo '<font color="lime">Lunas</font>';
														} else if ($order->order_status == 1){ 
															echo '<font color="blue">
																	DP : '.$order->purchase_nominal_cash.'<br/>
																	Sisa : '.$order->purchase_nominal_credit.'
																 </font>';
														} else {
															echo '<font color="red">Belum</font>';
														}
														?></td>
								<td class="center"><?php if ($order->package_status == 1) 
															echo '<font color="lime">Sudah</font>';else echo '<font color="red">Belum</font>';?></td>
							</tr>
							<?php endforeach; 
							}?>
						</tbody>
						
					</div>	
					</table>
					<p><?php echo $links; ?></p>
					
				</div>	
                </table>
				
<script>
	$("#togglefindcustomer").click(function(){
		$("#find_customer").slideToggle();
	});
</script>				