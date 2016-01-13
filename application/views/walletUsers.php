<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Wallet Users</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/wallet/search_users">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<span class="field">
										<input type="text" class="input-xlarge" name="billing_name" id="billing_name" placeholder="Agent Name"/> 
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
							<th width="20%" class="head1 center">Billing Name</th>
							<th width="5%" class="head1 center">Balance</th>
							<th width="5%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
							if ($list_wallet_users == null) {
							?><tr class="gradeX"><td colspan="9"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								
								foreach($list_wallet_users as $item):
								
							?>
                        <tr class="gradeX">
							<td class="center"><?=$row++;?></td>
					        <td>
								<a href="<?=base_url()?>index.php/wallet/search/<?=$item->billing_id?>" title="Detail">
										<?=$item->billing_name?>
								</a>
							</td>
							<td class="nominal"><?=$item->wallet_nominal?></td>
                             
							<td class="centeralign">
							-
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
