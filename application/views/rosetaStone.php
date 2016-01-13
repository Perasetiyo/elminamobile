<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Roseta Stone</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/roseta_stone/main">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">Filter Tanggal </h4>
						<div class="widgetcontent">
							<span class="field" style="padding:5px;">
								<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
							</span>
							<label>									
								<input type="text" id="startdate" name="startdate" size="10" placeholder="Range Awal">
								<input type="text" id="enddate" name="enddate" size="10" placeholder="Range Akhir">
								<button class="btn btn-primary">CARI</button>
							</label>
						</div>
					</div>
				</form>
					<div style="float:left">
						<a href="<?=base_url()?>index.php/expense/add" title="Add Event" style = "color:#fff;" class="btn btn-success">Add Expense</a>&nbsp;
						<a href="<?=base_url()?>index.php/expense/detail" title="Detail" class="btn">Detail</a>&nbsp;
					</div>
				
				
                <table width="100%" class="table table-bordered" id="rosetatable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class=" center">No</th>
							<th width="7%" class=" center">Transaction/Event</th>
							<th width="5%" class=" center">BCA</th>
							<th width="5%" class=" center">MANDIRI</th>
							<th width="5%" class=" center">BRI</th>
							<th width="5%" class=" center">BNI</th>
							<th width="5%" class=" center">RUSYDI</th>
							<th width="5%" class=" center">DIAZ</th>
							<th width="5%" class=" center">Account Receivable</th>
							<th width="5%" class=" center">Inventory</th>
                            <th width="5%" class=" center">PPE</th>
							<th width="2%" class=" center"></th>
                            <th width="5%" class="center">Account Payable</th>
							<th width="5%" class="head1 center">Rusydi Payable</th>
							<th width="5%" class="head0 center">Diaz Payable</th>
                            <th width="2%" class="head0 center"></th>
							<th width="5%" class="head0 center">Common Stock</th>
							<th width="5%" class="head1 center">Retain Earning</th>
                            
                        </tr>
                    </thead>
                    <tbody>
					<?php 
						$row = 1;
						foreach ($transactions as $item) {?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
					        <td><?=$item->option_desc?></td>
							<!--td class="center cash"><?=$item->cash?></td><!-- CASH -->
							<td class="center bca"><?=$item->bca?></td><!-- CASH -->
							<td class="center mandiri"><?=$item->mandiri?></td><!-- CASH -->
							<td class="center bri"><?=$item->bri?></td><!-- CASH -->
							<td class="center bni"><?=$item->bni?></td><!-- CASH -->
							<td class="center rusydi"><?=$item->rusydi?></td><!-- CASH -->
							<td class="center diaz"><?=$item->diaz?></td><!-- CASH -->
							
                    		<td class="center acc_rec"><?=$item->acc_rec?></td>
							<td class="center inventory"><?=$item->inventory?></td>
							<td class="center netppe"><?=$item->ppe?></td>
							<td class="center" style="background-color:#eee;"></td>
							<td class="center ap"><?=$item->account_pay?></td>
							<td class="center rp"><?=$item->rusydi_pay?></td>
							<td class="center dp"><?=$item->diaz_pay?></td>
							<td class="center" style="background-color:#eee;"></td>
							<td class="center equity"><?=$item->equity?></td>
							<td class="center income"><?=$item->income?></td>
                        </tr>
					   <?php } ?>
                        <tr class="gradeX" style="background-color:#eee;">
							<td class="center"></td>
					        <td>BALANCE</td>
                    		<!--td class="center" id="totalcash"></td-->
							
							<td class="center" id="totalbca"></td>
							<td class="center" id="totalmandiri"></td>
							<td class="center" id="totalbri"></td>
							<td class="center" id="totalbni"></td>
							<td class="center" id="totalrusydi"></td>
							<td class="center" id="totaldiaz"></td>
							
							<td class="center" id="totalar"></td>
							<td class="center" id="totalinventory"></td>
							<td class="center" id="totalnetppe"></td>
							<td class="center"></td>
							<td class="center" id="totalap"></td>
							<td class="center" id="totalrp"></td>
							<td class="center" id="totaldp"></td>
							<td class="center"></td>
							<td class="center" id="totalequity"></td>
							<td class="center" id="totalincome"></td>
                        </tr>
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
<script> 
	
	$(calculatecash);
	function calculatecash() {
		var sum = 0;
		
		$(".cash").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalcash').text(sum);
	};
	
	$(calculatebca);
	function calculatebca() {
		var sum = 0;
		
		$(".bca").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalbca').text(sum);
	};
	
	$(calculatebri);
	function calculatebri() {
		var sum = 0;
		
		$(".bri").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalbri').text(sum);
	};
	
	$(calculatemandiri);
	function calculatemandiri() {
		var sum = 0;
		
		$(".mandiri").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalmandiri').text(sum);
	};
	
	$(calculatebni);
	function calculatebni() {
		var sum = 0;
		
		$(".bni").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalbni').text(sum);
	};
	
	$(calculaterusydi);
	function calculaterusydi() {
		var sum = 0;
		
		$(".rusydi").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalrusydi').text(sum);
	};
	
	$(calculatediaz);
	function calculatediaz() {
		var sum = 0;
		
		$(".diaz").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totaldiaz').text(sum);
	};

	$(calculatear);
	
	function calculatear() {
		var sum = 0;
		
		$(".acc_rec").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalar').text(sum);
	};
	
	$(calculateinventory);

	function calculateinventory() {
		var sum = 0;
		
		$(".inventory").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalinventory').text(sum);
	};
	
	$(calculatenetppe);
	
	function calculatenetppe() {
		var sum = 0;
		
		$(".netppe").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalnetppe').text(sum);
	};
	
	$(calculateap);
	
	function calculateap() {
		var sum = 0;
		
		$(".ap").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalap').text(sum);
	};
	
	$(calculatenp);
	function calculatenp() {
		var sum = 0;
		
		$(".np").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalnp').text(sum);
	};
	
	$(calculatetp);
	function calculatetp() {
		var sum = 0;
		
		$(".tp").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totaltp').text(sum);
	};
	
	$(calculaterp);
	function calculaterp() {
		var sum = 0;
		
		$(".rp").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalrp').text(sum);
	};
	
	$(calculatedp);
	function calculatedp() {
		var sum = 0;
		
		$(".dp").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totaldp').text(sum);
	};
	
	$(calculateequity);
	
	function calculateequity() {
		var sum = 0;
		
		$(".equity").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalequity').text(sum);
	};

	$(calculateincome);
	
	function calculateincome() {
		var sum = 0;
		
		$(".income").each(function() {
				var value = $(this).text();
				if (!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			
		$('#totalincome').text(sum);
	};
</script>
</script>
