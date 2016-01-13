<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Fixed Cost History</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/fixed_cost_history/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Nama</label>
										<span class="field">
											<input type="text" class="input-xlarge" name="fixed_cost_name" id="fixed_cost_name" /> 
										</span>
								</li>
								<li>
									<label>Type</label>
										<span class="field">
											<select name="fixed_cost_type_id" id="fixed_cost_type_id" style="width:150px;" class="validate[required]">
												<option value> - Type - </option>
											<?php foreach($fixed_cost_type as $type): ?>
												<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
											<?php endforeach; ?>
											</select>
										</span>
								</li>
								<li>
									<label>Month</label>
										<span class="field">
											<select name="period" id="period" style="width:100px;" class="validate[required]">
												<option value> - Month - </option>
											<?php foreach($months as $month): ?>
												<option value="<?php echo $month->option_code?>"><?php echo $month->option_desc?></option>
											<?php endforeach; ?>
											</select>
										</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
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
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="5%" class="head0 center">Bulan</th>
							<th width="12%" class="head1 center">Nama</th>
							<th width="12%" class="head0 center">Nominal</th>
							<th width="12%" class="head0 center">Paid</th>
							<th width="15%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_fixed_cost_history == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_fixed_cost_history as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center"><?=$item->fixed_cost_history_month?></td>
							<td>
								<?php if (!empty($item->fixed_cost_history_month)) { ?>
								<a href="<?=base_url()?>index.php/fixed_cost_history/detail/<?=$item->fixed_cost_history_month?>/<?=$item->fixed_cost_id?>" title="Detail"><?=$item->fixed_cost_name?></a>
								<?php } else { ?>
								<?=$item->fixed_cost_name?>
								<?php } ?>
							</td>
                            <td class="right nominal"><?=$item->fixed_cost_nominal?></td>
							<td class="right nominal"><?=$item->total_fixed_cost_history_nominal?></td>
							
							<td class="centeralign">
								<form method="post" action="<?=base_url()?>index.php/fixed_cost_history/doAdd" >
									<a id="togglepay<?=$row?>">Pay</a>
									<div id="pay<?=$row?>" style="display: none;">
									<p>
									<font style="color:blue;">Tanggal</font>
									<input type="text" name="fixed_cost_history_date" id="fixed_cost_history_date" class="input-small date" />
									</p>
									<p>
									<font style="color:blue;">Nominal</font>
									<input type="text" name="pay_nominal" id="pay_nominal" class="input-small" />
									</p>
									<p>
									<font style="color:blue;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank</font>
									<select name="bank_account_id" id="bank_account_id" style="width:104px;">
										<option value=''>- Bank -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
									</select>
									</p>
									
									<input type="hidden" value="<?=$item->fixed_cost_id?>" name="fixed_cost_id"/>
									<input type="submit" name="action" class="btn btn-primary" value="Pay" />
									</div>
									<script>
									$("#togglepay<?=$row?>").click(function(){
										$("#pay<?=$row?>").slideToggle();
									});
									</script>
								</form>
								
								
								</a>&nbsp;
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
		
<script>		
$(function() {
		$( ".date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>		