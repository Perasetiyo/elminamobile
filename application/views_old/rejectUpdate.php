<?php foreach ($reject as $item): ?>
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Reject Stock</h1>
	</div>
</div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
					<div class="span11">
						
						<div class="error message">
							<?php echo $this->session->flashdata('validation_error_message'); ?>
							<?php echo $this->session->flashdata('error_message'); ?>
						</div>
						<div class="success message">
							<?php echo $this->session->flashdata('success_message'); ?>
						</div>
						<form id="orderForm" name="orderForm" class="stdform" method="post" action="<?=base_url()?>index.php/reject/doUpdate" />
							<div>
								<div class="span10 profile-left" id="cart">
									<h4 class="widgettitle">&nbsp; Cart </h4>
									<div class="widgetcontent">
										<input type="hidden" value="<?=$item->reject_id?>" name="reject_id"/>
										<p>
											<label>Tanggal</label>
											<span class="field">
												<input type="text" name="reject_date" id ="reject_date" class="validate[required]" value="<?=$item->reject_date?>">
											</span>
										</p>
										<p>
											<label>Stock List</label>
											<span class="field">
											<input type="text" class="input-xlarge" name="inv_query" id="inv_query" 
														placeholder="Stock Name"/> 
											</span>
										</p>
										<p>
											<table id="inv_table" class="table table-bordered" style="width: 60%; margin-left:120px;">
												<tr>
													<th>Name</th>
													<th>Qty</th>
												</tr>
												<tbody class="zebra bordered">
													<?php foreach($stock as $item) {
																$qty_keeped = 0;
																foreach ($cart as $cart2) {
																	if ($item->stock_id == $cart2->stock_id) {
																		$qty_keeped = $cart2->reject_cart_qty;
																	}
																}
														?>
														<tr>
															<td><?=$item->product_name;?> - <?=$item->stock_desc;?> [Sisa : <b><?=$item->stock_qty;?></b>]&nbsp;&nbsp;</td>
															<td><input type="text" name="qty<?=$item->stock_id?>" value="<?php echo $qty_keeped?>" id="qty<?=$item->stock_id?>" style="width:30px;"/></td> 
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</p>
										<p>
											<span class="field">
												<button class="btn btn-primary">SUBMIT</button>
												<button type="reset" class="btn">RESET</button>
											</span>
										</p>
									</div>
								</div>
							</div>
						</form>
						<?php endforeach; ?>
				
<script>
    $(document).ready(function(){
		$("#orderForm").validationEngine();
    });
	
	$(function() {
		$( "#reject_date" ).datepicker({dateFormat: "dd-M-yy"});
	});
</script>

<script type="text/javascript">
// When document is ready: this gets fired before body onload :)
$(document).ready(function(){
	// Write on keyup event of keyword input element
	$("#inv_query").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#inv_table tbody>tr").hide();
			$("#inv_table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#inv_table tbody>tr").show();
		}
	});
});
// jQuery expression for case-insensitive filter
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

</script>

</body>
</html>

