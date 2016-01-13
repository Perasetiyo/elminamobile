<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Wallet History Log</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/wallet/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<span class="field" style="padding-right:5px;padding-bottom:5px;float:left;">
										<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
									</span>
								
									<span class="field">
										<input type="text" class="input-xlarge" name="billing_name" id="billing_name" placeholder="Agent Name"/> 
									</span>
									<br/>
									<span class="field">
										<input type="text" id="startdate" name="startdate" placeholder="Range Awal">
										<input type="text" id="enddate" name="enddate" placeholder="Range Akhir">
									</span>
									
									<span class="field">
									<button class="btn btn-primary">Search</button>
									</span>
								</li>
							</ul>
							<div>
								
							</div>							
						</div>
					</div>
				</form>
				<div>
					<a href="<?=base_url()?>index.php/wallet/users" title="Wallet Users"  class="btn "> << Back to Wallet Users</a>&nbsp;
					<a href="<?=base_url()?>index.php/wallet/add" title="Deposit Money" style = "color:#fff;" class="btn btn-success">Tambah Saldo</a>&nbsp;
					<a href="<?=base_url()?>index.php/wallet/reduce" title="Kurangin Saldo" style = "color:#fff;" class="btn btn-danger">Kurangin Saldo</a>&nbsp;
					
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
							<th width="9%" class="head1 center">Billing Name</th>
							<th width="20%" class="head1 center">Deskripsi</th>
							<th width="7%" class="head0 center">Mutasi</th>
							<th width="7%" class="head0 center">Saldo</th>
							<th width="8%" class="head1 center">Last Trx Date</th>
							<th width="5%" class="head1 center">Order Invoice</th>
							<th width="5%" class="head1 center">Status</th>
                            <th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<tr class="gradeX">
							
							<td colspan="3" class="center">Saldo Awal per tanggal <b><?=$startdate?></b></td>
							<td class="center" border="0px"></td>
							<td class="right nominal"><?=$first_balance?></td>
							<td class="center"></td>
							<td class="center"></td>
							<td class="center"></td>
							<td class="center"></td>
						</tr>
                    	
                    	<?php 
							if ($list_wallet == null) {
							?><tr class="gradeX"><td colspan="9"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								$balance = $first_balance;
								foreach($list_wallet as $item):
								$balance = $balance + $item->wallet_trx_nominal;
							?>
                        <tr class="gradeX">
							<td class="center"><?=$row++;?></td>
					        <td><?=$item->billing_name?></td>
							<td><?=$item->wallet_trx_desc?></td>
                            <td>
								<?php if ($item->wallet_trx_nominal >= 0) {?>
									<span style="color:green;">
								<?php } else {?>
								<span style="color:red;">
								<?php }?>
								<b><?=$item->wallet_trx_nominal?></b>
								</span>
							</td>
							<td class="right nominal"><?=$balance?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->wallet_trx_date))?></td>
							<td><a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat/<?=$item->order_id?>"><?=$item->order_id?></a></td>
							 
							<td><?php if ($item->wallet_status == 0) {
									echo '<span style="color:red;">Pending</span>';
								} else {
									echo '<span style="color:green;">Confirmed</span>';}?></span></td>
                            <td class="centeralign">
							<?php if ($item->order_id > 0 && $item->wallet_status == 0) {?>
								<a href="<?=base_url()?>index.php/wallet/payment/<?=$item->wallet_id?>" title="Pembayaran" style = "color:#fff;"><span class="iconsweets-pricetag"></span></a>&nbsp;
							<?php } else if ($item->order_id == 0 ){ ?>
								<a href="<?=base_url()?>index.php/wallet/update/<?=$item->wallet_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
					
								<?php } echo anchor('wallet/delete/'.$item->wallet_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
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
