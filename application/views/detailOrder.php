	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Detail Order </h1>
	</div>
</div><!--pageheader-->
    <?php foreach ($orders as $order) :?>
        <div class="maincontent">
            <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
				<div class="span8">
						<a href="<?=base_url()?>index.php/orderAction/" title="Kembali" class="btn">KEMBALI</a>
						
					<div class="widgetbox personal-information">
						<div id="wiz1step1" class="formwiz">
							<input type="hidden" value="<?=$order->id?>" name="order_id"/>
							<div style = "float: left; margin-right: 20px;">
								<h4 class="widgettitle">Penerima / Consignee </h4>
								<div class="widgetcontent">
								<input type="hidden" value="<?=$order->billing_id?>" name="billing_id"/>
								<table>
									<tr>
										<td>Nama Penerima : </td>
										<td><?=$order->billing_name?></td>
									<tr>
									<tr>
										<td>D/a</td>
										<td>
											<?=$order->billing_street?><br/>
											<?php if ($order->billing_kec != '') {?>Kec. <?=$order->billing_kec?><br/><?php }?>
											<?=$order->billing_city?>, <?=$order->billing_prov?><br/>
											<?=$order->billing_country?>
										</td>
									</tr>		
									<tr>
										<td>Telp</td>
										<td><?=$order->billing_phone?></td>
									</tr>		
								</table>
								</div>
							</div>
							<div >
								<h4 class="widgettitle">Pengirim / Shipper </h4>
								<input type="hidden" value="<?=$order->shipper_id?>" name="shipper_id"/>
								<div class="widgetcontent">
								<table>
									<tr>
										<td>Nama Pengirim : </td>
										<td><?=$order->shipper_name?></td>
									<tr>
									<tr>
										<td></td>
										<td>
											<?=$order->shipper_prov?>
										</td>
									</tr>		
									<tr>
										<td>Telp</td>
										<td><?=$order->shipper_phone?></td>
									</tr>
									<tr>
										<td colspan = 2>_____________________________________<td>
									</tr>
									<tr>
										<td>Ekspedisi : </td>
										<td><?=$order->expedition?> / <?=$order->service?></td>
									<tr>
									<tr>
										<td>Ongkir : </td>
										<td>
											<?=$order->exp_cost?>
										</td>
									</tr>
								</table>
								</div>
							</div>		
						</div><!--#wiz1step1-->
						</div>
				<?php endforeach; ?>
</body>
</html>

