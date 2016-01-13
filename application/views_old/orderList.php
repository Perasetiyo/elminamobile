<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Order</h1>
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
					<form method="post" action="<?=base_url()?>index.php/order/search">
						<div id="wiz1step1" class="formwiz">
							<hr>
							<h4 class="widgettitle">&nbsp; Pencarian</h4>
							<div class="widgetcontent">
								<ul class="search-field">
									<li>
										<label>Nama Penerima</label>
										<span class="field">
											<input type="text" class="input-xlarge" name="billing_name" id="billing_name" />
										</span>
									</li>
									<li>
										<label>Telp Penerima</label>
										<span class="field">
											<input type="text" class="input-xlarge" name="billing_phone" id="billing_phone" />
										</span>
									</li>
									<li>
										<label>Kecamatan</label>
										<span class="field">
											<input type="text" class="input-xlarge" name="billing_kec" id="billing_kec" />
										</span>
									</li>
								</ul>	
								<div>
									<button class="btn btn-primary">Cari</button>
								</div>
							</div>
						</div>
					</form>
				
					<p class="stdformbutton">
						<div style="float:left;">
							<a href="<?=base_url()?>index.php/order/add" title="Tambah" style = "color:#fff;" class="btn btn-success">Add Order</a>&nbsp;
						</div>
						
						<form method="post" action="<?=base_url()?>index.php/order/find_customer" >
							<a href="#" id="togglefindcustomer" class="btn btn-inverse" style="float:left; margin-right:10px;">Find Customer</a>
							<div id="find_customer" style="display: none;">
							<font style="color:blue;">Nama Pelanggan</font>
							<input type="text" name="billing_name" id="billing_name" class="input-small" />
							<font style="color:blue;">Telp Pelanggan</font>
							<input type="text" name="billing_phone" id="billing_phone" class="input-small" />
							<input type="submit" name="action" class="btn btn-primary" value="Add Order" />
							</div>
						</form>
					</p>
				
				<form method="post" action="<?=base_url()?>index.php/order/push_button">
					<input type="submit" name="action" class="btn" value="CETAK ALAMAT" />
					<input type="submit" name="action" class="btn" value="SUDAH DIKIRIM" />
					
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
								<th class="head1 center">No</th>
								<th class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
								<th class="head1 center">Penerima</th>
								<th class="head1 center">Tanggal Order</th>
								<th class="head1 center">Order Via</th>
								<th class="head1 center">Eksp</th>
								<th class="head0 center">Daerah</th>
								<th class="head1 center">Belanja</th>
								<th class="head1 center">Diskon</th>
								<th class="head0 center">Ongkir</th>
								<th class="head0 center">Total</th>
								<th class="head1 center">Status Bayar</th>
								<th class="head1 center">Status Kirim</th>
								<th class="head0 center">Action</th>
							</tr>
						</thead>
						
						<tbody>
							<h4>Pending Orders</h4>
							<?php 
								if ($listOrderPending == null) {
								?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
								} else {
							
								foreach($listOrderPending as $order):?>
							<tr class="gradeX">
							
								<td class="center"><?=$row++;?></td>
								<td class="center">
									<span class="center">
										<input type="checkbox" name="ch<?=$row;?>" value="<?=$order->id?>"/>
									</span>
								</td>
								<td>
									<a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat/<?=$order->id?>" title="Detail">
										<?=$order->billing_name?> <br/>
										<a href="<?=base_url()?>index.php/order/update/<?=$order->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('order/delete/'.$order->id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus order tersebut?')"));?>
									</a>
								</td>
								<td class="center"><?php echo date("d-M-Y", strtotime($order->order_date))?></td>
								<td class="center"><?=$order->option_desc?></td>
								<td class="center"><?=$order->expedition?> <br/><b><small><?=$order->estimated_weight?> gr</small></b></td>
								<td class="center"><?=$order->billing_kec?>,&nbsp; <?=$order->billing_city?></td>
								<td class="center"><?=$order->product_amount?></td> 
								<td class="center"><?=$order->discount_amount?></td>
								<td class="center"><?=$order->exp_cost?></td>
								<td class="center"><?=$order->total_amount?></td>
								<td class="center"><?php 	if ($order->order_status == 2) {
																echo '<font color="lime">Lunas</font>';
															} else if ($order->order_status == 1){ 
																echo '<font color="blue">
																		DP : '.$order->purchase_nominal_cash.'<br/>
																		Sisa : '.$order->purchase_nominal_credit.'
																	 </font>';
															} else {
																echo '<font color="red">Belum</font>';
															}
													?>
								</td>
								<td class="center"><?php if ($order->package_status == 1) 
															echo '<font color="lime">Sudah</font>';else echo '<font color="red">Belum</font>';?></td>
								<td class="centeralign">
									<a href="<?=base_url()?>index.php/order/update/<?=$order->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
									<?php echo anchor('order/delete/'.$order->id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus order tersebut?')"));?>
								</td>
							</tr>
							<?php endforeach; 
							}?>
						</tbody>
					</table>
				</form>
				<p><?php echo $links; ?></p>
				<h4>Complete Orders</h4>
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
                            <th class="head0 center">Action</th>
                        </tr>
                    </thead>
				                
					<tbody>
						
                    	<?php 
							if ($listOrderComplete == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
							} else {
						
							$i = 1;
							foreach($listOrderComplete as $order):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td>
								<a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat/<?=$order->id?>" title="Detail">
									<?=$order->billing_name?>
								</a>
							</td>
							<td class="center"><?php echo date("d-M-Y", strtotime($order->order_date))?></td>
                            <td class="center"><?=$order->billing_kec?>,&nbsp; <?=$order->billing_city?></td>
                            <td class="center"><?=$order->product_amount?></td> 
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
                            <td class="centeralign">
                            	<a href="<?=base_url()?>index.php/order/update/<?=$order->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('order/delete/'.$order->id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus order tersebut?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
					
				</div>	
                </table>
				<p><?php echo $links; ?></p>
				
<script>
	$("#togglefindcustomer").click(function(){
		$("#find_customer").slideToggle();
	});
</script>				