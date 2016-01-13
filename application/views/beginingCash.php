<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Begining Cash Every Month</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/begining_cash/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">Periode</h4>
						<div class="widgetcontent">
							<label>						
								<span class="field">
									<select name="period" id="period" style="width:200px;" disabled class="validate[required]">
										<option> - Month - </option>
									<?php foreach($months as $month): ?>
										<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
									<?php endforeach; ?>
									</select>
								</span>
								<button class="btn btn-primary">SUBMIT</button>
							</label>
						</div>
					</div>
				</form>
				
				<div>
					<a href="<?=base_url()?>index.php/begining_cash/add" title="Begining Cash" class="btn btn-success" >Add Begining Cash</a>&nbsp;
					<a href="<?=base_url()?>index.php/cash_statement/main" title="Back to Cash Statement" class="btn" >Back</a>&nbsp;
				</div>
				
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
							<th width="10%" class="head1 center">Periode</th>
							<th width="18%" class="head0 center">List Account</th>
							<th width="8%" class="head1 center">Total Nominal</th>
                            <th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_begining_cash == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_begining_cash as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td><?=$item->begining_cash_period.'-'.$item->begining_cash_year?></td>
                            <td>
								<?php 
									$searchterm['period'] = $item->begining_cash_period;
									$searchterm['year'] = $item->begining_cash_year;
									$list_nominal_per_bank = $this->begining_cash_model->fetch_nominal_per_bank($searchterm);
									foreach($list_nominal_per_bank as $bank) {
										echo $bank->bank_account_name.' : '.$bank->begining_cash_nominal.'<br/>';
									} 
								?>
							</td>
							<td class="center"><?=$item->total_nominal?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/begining_cash/update/<?=$item->begining_cash_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('begining_cash/delete/'.$item->begining_cash_period.'/'.$item->begining_cash_year,
									'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Are you sure?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			
