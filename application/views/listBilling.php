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
			<h4 class="widgettitle">List Building</h4><br/>
				<p class="stdformbutton">
					<a href="<?=base_url()?>index.php/billingAction/addBilling" title="Tambah" style = "color:#fff;" class="btn btn-success">Tambah List Building</a>&nbsp;
				</p>
				
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
                          	<th width="3%" class="head1 center">No</th>
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="20%" class="head1 center">Nama</th>
							<th width="13%" class="head0 center">Kota</th>
                            <th width="16%" class="head0 center">Telepon</th>
                            <th width="9%" class="head1 center">Reseller ?</th>
							<th width="15%" class="head1 center">Dropshipper</th>
                            <th width="9%" class="head0 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
							$i = 1;
							foreach($listBilling as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$i++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->billing_name?></td>
                            <td class="center"><?=$item->billing_city?></td>
                            <td class="center"><?=$item->billing_phone?></td>
							<td class="center">
								<?php 
									if ($item->is_reseller == 0) echo 'Bukan';
									else echo 'Ya';?>
							</td>
                            <td class="center"><?=$item->shipper_name?></td>
							<td class="centeralign">
                            	<a href="<?=base_url()?>index.php/billingAction/updateBilling/<?=$item->billing_id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('billingAction/deleteBilling/'.$item->billing_id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus member tersebut?')"));?>
							</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
