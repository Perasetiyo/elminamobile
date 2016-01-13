<?php foreach ($stock as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;RE-Stock <?=$item->product_name?> - <?=$item->stock_desc?></h1>
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
						
						<form id="reStock" name="addStock" class="stdform" method="post" action="<?=base_url()?>index.php/stock/doRestock/" />
							<input type="hidden" value="<?=$item->product_id?>" name="product_id"/>
							<input type="hidden" value="<?=$item->stock_id?>" name="stock_id"/>
							
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; REStock </h4>
								<div class="widgetcontent">
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="stock_desc" id="stock_desc" value="<?=$item->stock_desc?>"/> 
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="stock_date" id ="stock_date" class="validate[required]"/>
									</span>
								</p>
								
								<p>
									<label>Qty <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-small validate[required]" style="width:30px;" name="stock_qty" id="stock_qty"/> 
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/stock/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
<?php endforeach;?>				                        
<script>
    $(document).ready(function(){
		$("#reStock").validationEngine();
    });
	
	$(function() {
		$( "#stock_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>