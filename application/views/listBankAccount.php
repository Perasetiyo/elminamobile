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
			<h4 class="widgettitle">Data Bank Account</h4><br/>
				<p class="stdformbutton">
					<a href="<?=base_url()?>index.php/bankAccountAction/addBankAccount" title="Tambah" style = "color:#fff;" class="btn btn-success">Tambah Bank Account</a>&nbsp;
				</p>
				
                <table width="100%" class="table table-bordered" id="dyntable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
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
							<th width="29%" class="head1 center">Nama</th>
							<th width="10%" class="head0 center">Saldo</th>
                            <th width="9%" class="head0 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
							$i = 1;
							foreach($listBankAccount as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$i++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->bank_account_name?></td>
                            <td class="center"><?=$item->ba_saldo?></td>
                            <td class="centeralign">
                            	<a href="<?=base_url()?>index.php/bankAccountAction/updateBankAccount/<?=$item->id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('bankAccountAction/deleteBankAccount/'.$item->id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus member tersebut?')"));?>
							</td>
                        </tr>
                        <?php endforeach; ?>
						<tr>
							<td class="center"></td>
							<td class="center"></td>
							<td class="center"></td>
							<td class="center"><a href="<?=base_url()?>index.php/bankAccountAction" class="btn btn-danger">REFRESH DULU GAN!</a></td>
						</tr>
                    </tbody>
                </table>
