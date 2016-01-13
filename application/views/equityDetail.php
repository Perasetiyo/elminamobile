<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Detail Cashflow </h1>
	</div>
</div><!--pageheader-->
    <div class="maincontent">
        <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
			<div class="span8">
				<a href="<?=base_url()?>index.php/cashflowAction/" title="Kembali" class="btn">KEMBALI</a>
				
				<div class="widgetbox personal-information">
					<div id="wiz1step1" class="formwiz">
						<div>
							<h4 class="widgettitle">Filter Tanggal </h4>
							<form class="stdform" method="post" action="<?=base_url()?>index.php/cashflowAction/reporting" />
							<div class="widgetcontent">
								<label>
									<input type="text" id="startdate" name="startdate" size="10" placeholder="Range Awal">
									<input type="text" id="enddate" name="enddate" size="10" placeholder="Range Akhir">
									<button class="btn btn-primary">CARI</button>
									
								</label>
								<span class="field" style="padding:5px;">
									<div id="datepicker" style="border: 1px solid #0c57a3;"></div>
								</span>
								
							</div>
							</form>
							<h4 class="widgettitle">Range Tanggal dari <strong><?php echo $startdate;?></strong> hingga <strong><?php echo $enddate;?></strong></h4>
							<div class="widgetcontent">
								<table>
									<tr>
										<td>Sedekah : </td>
										<td><?php if ($nominal_sedekah == null)  echo '-'; else echo $nominal_sedekah[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Belanja : </td>
										<td><?php if ($nominal_belanja == null)  echo '-'; else echo $nominal_belanja[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Pengeluaran Rutin : </td>
										<td><?php if ($nominal_expense == null)  echo '-'; else echo $nominal_expense[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Gaji </td>
										<td><?php if ($nominal_gaji == null)  echo '-'; else echo $nominal_gaji[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Hutang : </td>
										<td><?php if ($nominal_hutang == null)  echo '-'; else echo $nominal_hutang[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Piutang : </td>
										<td><?php if ($nominal_piutang == null)  echo '-'; else echo $nominal_piutang[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Kas : </td>
										<td><?php if ($nominal_kas == null)  echo '-'; else echo $nominal_kas[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Modal : </td>
										<td><?php if ($nominal_modal == null)  echo '-'; else echo $nominal_modal[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Investasi : </td>
										<td><?php if ($nominal_investasi == null)  echo '-'; else echo $nominal_investasi[0]->cashflow_nominal;?></td>
									<tr>
									<tr>
										<td>Omset : </td>
										<td><?php if ($nominal_omset == null)  echo '-'; else echo $nominal_omset[0]->cashflow_nominal;?></td>
									<tr>
								</table>
							</div>
						</div>		
					</div><!--#wiz1step1-->
				</div>
</body>

</html>
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