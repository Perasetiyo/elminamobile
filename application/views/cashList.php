<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Cashflow</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/cash/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									
									<label>Date Range</label>
									<span class="field" style="padding-right:5px;padding-bottom:5px;float:left;">
										<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
									</span>
								
									<span class="field">
										<input type="text" id="startdate" name="startdate" placeholder="Range Awal">
										<input type="text" id="enddate" name="enddate" placeholder="Range Akhir">
									</span>
									<br/>
									<span class="field">
										<select name="cash_type_id" id="cash_type_id" class="input-xlarge">
											<option value=''>- Choose Type -</option>
										<?php foreach($cash_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
									<br/>
									<span class="field">
										<select name="bank_account_id" id="bank_account_id" class="input-xlarge">
											<option value=''>- Bank Account -</option>
										<?php foreach($bank_account as $account): ?>
											<option value="<?php echo $account->id?>"><?php echo $account->bank_account_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
									
									<span class="field">
										<input type="text" class="input-xlarge" name="cash_desc" id="cash_desc" placeholder="Description"/> 
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
					<div style="float:left">
						<a href="<?=base_url()?>index.php/cash/pindahan" title="Detail" class="btn" >Pemindahan Kas</a>&nbsp;
						<a href="<?=base_url()?>index.php/cash/add" title="Add Cashflow" class="btn btn-success" style="color:#fff;">Add Cashflow</a>&nbsp;
						<a href="<?=base_url()?>index.php/bank_account/lists" title="Bank Account" class="btn" >Bank Account</a>&nbsp;
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
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="10%" class="head1 center">Type</th>
							<th width="18%" class="head0 center">Desc</th>
							<th width="8%" class="head1 center">Date</th>
                            <th width="8%" class="head0 center">Nominal</th>
							<th width="8%" class="head0 center">Saldo</th>
                            <th width="10%" class="head1 center">Bank Account</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
						<tr class="gradeX">
							<td class="center"></td>
							<td class="center"></td>
							<td class="center"></td>
							<td colspan="2" class="center">Saldo Awal per tanggal <?=$startdate?></td>
							<td class="center" border="0px"></td>
							<td class="right nominal"><?=$first_balance?></td>
						</tr>
                    	<?php 
							if ($list_cash == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								$balance = $first_balance;
								foreach($list_cash as $item):
								$balance = $balance + $item->cash_nominal;
							?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->option_desc?></td>
                            <td><?=$item->cash_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->cash_date))?></td>
                            <td class="right nominal cash_nominal"><?=$item->cash_nominal?></td>
							<td class="right nominal"><?=$balance?></td>
							<td class="center"><?=$item->bank_account_name?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/cash/update/<?=$item->cash_id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('cash/delete/'.$item->cash_id,'<span class="icon-trash"></span>', array('title' => 'Hapus', 'onClick' => "return confirm('Anda yakin ingin menghapus cashflow tersebut?')"));?>
							</td>
                        </tr>
						
                        <?php 
							
						endforeach; 
						}?>
                    </tbody>
                </table>
				
			
<style type="text/css">
		.dp-highlight .ui-state-default {
			background: #478DD5;
			color: #FFF;
		}
</style>
<script type="text/javascript">
		/*
		 * jQuery UI Datepicker: Using Datepicker to Select Date Range
		 * http://salman-w.blogspot.com/2013/01/jquery-ui-datepicker-examples.html
		 */
		 
		$(function() {
			$("#datepicker").datepicker({
				beforeShowDay: function(date) {
					var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#startdate").val());
					var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#enddate").val());
					return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
				},
				onSelect: function(dateText, inst) {
					var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#startdate").val());
					var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#enddate").val());
					if (!date1 || date2) {
						$("#startdate").val(dateText);
						$("#enddate").val("");
						$(this).datepicker("option", "minDate", dateText);
					} else {
						$("#enddate").val(dateText);
						$(this).datepicker("option", "minDate", null);
					}
				}
			});
		});
</script>