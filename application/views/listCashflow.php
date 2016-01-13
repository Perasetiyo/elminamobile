<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Arus Kas</h1>
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
			
			
				<form method="post" action="<?=base_url()?>index.php/cashflowAction/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Pencarian</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Tipe</label>
									<span class="field">
										<select name="cashflow_type_id" id="cashflow_type_id" class="input-xlarge">
											<option value=''>Pilih Satu</option>
										<?php foreach($cashflow_type as $type): ?>
											<option value="<?php echo $type->id?>"><?php echo $type->cashflow_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Bank Account</label>
									<span class="field">
										<select name="bank_account_id" id="bank_account_id" class="input-xlarge">
											<option value=''>Pilih Satu</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Keterangan</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="cashflow_desc" id="cashflow_desc" /> 
									</span>
								</li>
								<li>
									<label>Debet/Kredit </label>
									<span class="field">
										<select name="debet_credit" id="debet_credit" style="width:120px;" class="input-xlarge">
											<option value=''>Pilih Satu</option>
											<option value="DEBET">Debet</option>
											<option value="KREDIT">Kredit</option>
										</select>
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">CARI</button>
							</div>							
						</div>
					</div>
				</form>
				<p class="stdformbutton">
					<div style="float:left">
						<a href="<?=base_url()?>index.php/cashflowAction/addCashflow" title="Tambah" style = "color:#fff;" class="btn btn-success">Tambah Arus Kas</a>&nbsp;
						<a href="<?=base_url()?>index.php/cashflowAction/detailCashflow" title="Detail" class="btn">Detail Arus Kas</a>&nbsp;
					</div>
					<?php echo $links; ?>
					<div class="clear"></div>
					
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
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="10%" class="head1 center">Tipe</th>
							<th width="18%" class="head0 center">Keterangan</th>
							<th width="8%" class="head1 center">Tanggal</th>
                            <th width="8%" class="head0 center">Nominal</th>
                            <th width="10%" class="head1 center">Bank Account</th>
                            <th width="7%" class="head0 center">Debet/Kredit</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($listCashflow == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
							} else {
								$i = 1;
								foreach($listCashflow as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->cashflow_name?></td>
                            <td><?=$item->cashflow_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->cashflow_date))?></td>
                            <td class="center"><?=$item->cashflow_nominal?></td>
							<td class="center"><?=$item->bank_account_name?></td>
							<td class="center"><?=$item->debet_credit?></td>
                            <td class="centeralign">
								<?php if ($item->order_id == null) {?>
									<a href="<?=base_url()?>index.php/cashflowAction/updateCashflow/<?=$item->cashflow_id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
									<?php echo anchor('cashflowAction/deleteCashflow/'.$item->cashflow_id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus member tersebut?')"));?>
								<?php } else { echo 'Disabled'; }?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			
