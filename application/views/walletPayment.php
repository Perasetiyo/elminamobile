<?php foreach ($wallet as $item) {?>
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Wallet Payment</h1>
	</div>
</div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
			    <!-- START OF DEFAULT WIZARD -->
				<div class="span8">
					<div class="widgetbox personal-information">
						<div class="error message">
							<?php echo $this->session->flashdata('validation_error_message'); ?>
							<?php echo $this->session->flashdata('error_message'); ?>
						</div>
						<div class="success message">
							<?php echo $this->session->flashdata('success_message'); ?>
						</div>
						
						<form id="addForm" name="addForm" class="stdform" method="post" action="<?=base_url()?>index.php/wallet/doPayment" />
							<div id="wiz1step1" class="formwiz">
								<input type="hidden" name="billing_id" value="<?=$item->wallet_billing_id?>"/>;
								<input type="hidden" name="wallet_id" value="<?=$item->wallet_id?>"/>;
								<input type="hidden" name="order_id" value="<?=$item->order_id?>"/>;
								<input type="hidden" name="order_total_nominal" value="<?=$item->total_amount?>"/>;
								
								<h4 class="widgettitle">&nbsp; Wallet Payment</h4>
								<div class="widgetcontent">
								<p>
									<label>Billing Name</label>
									<span class="field">
										<input type="text" class="input-medium" value="<?=$item->billing_name?>" disabled/>
									</span>
								</p>
								
								<p>
									<label>Order No : <font style="color:red;"></font></label>
									<span class="field">
										<a href="<?=base_url()?>index.php/order/cetak_nota_dan_alamat/<?=$item->order_id?>"><?=$item->order_id?> </a>(Tanggal : <?=$item->order_date?>) 
									</span>
								</p>
								
								<p>
									<label>Nominal :<font style="color:red;"></font></label>
									<span class="field nominal">
										<a href=""><?=$item->total_amount?></a>
									</span>
								</p>
								
								
								<p class="stdformbutton">
									<button class="btn btn-primary">BAYAR</button>
									<a href="<?=base_url()?>index.php/wallet/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
<?php } ?>		                        
<script>
    $(document).ready(function(){
		$("#addForm").validationEngine();
    });
	
	$(function() {
		$( "#wallet_trx_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>

<script type="text/javascript">
// When document is ready: this gets fired before body onload :)
$(document).ready(function(){
	// Write on keyup event of keyword input element
	$("#billing_query").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#billing_table tbody>tr").hide();
			$("#billing_table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#billing_table tbody>tr").show();
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
