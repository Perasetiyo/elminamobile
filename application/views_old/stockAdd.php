	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Stock</h1>
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
						
						<form id="addStock" name="addStock" class="stdform" method="post" action="<?=base_url()?>index.php/stock/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Stock </h4>
								<div class="widgetcontent">
								<p>
									<label>Product Name<font style="color:red;">*</font></label>
									<span class="field">
										<select name="product_id" id="product_id" style="width:200px;" class="validate[required]">
											<option> - Product Name - </option>
										<?php foreach($product_name as $product): ?>
											<option value="<?php echo $product->product_id?>"><?php echo $product->product_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="stock_date" id ="stock_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Stock Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="stock_desc" id="stock_desc" /> 
									</span>
								</p>
								<p>
									<label>Qty <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="stock_qty" id="stock_desc" /> 
									</span>
								</p>
								<p>
								`	<label>Retail Price<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="stock_price" id="stock_cogs" /> 
									</span>
								</p>
								
								<p>
								`	<label>Store ID Product<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="store_id_product" id="store_id_product" /> 
									</span>
								</p>
								<p>
									<label>Store Category<font style="color:red;">*</font></label>
									<span class="field">
										<select name="store_category" id="store_category" style="width:200px;" class="validate[required]">
											<option> - Store Category - </option>
										<?php foreach($store_category as $category): ?>
											<option value="<?php echo $category->option_code?>"><?php echo $category->option_desc?></option>
										<?php endforeach; ?>
										</select>
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
				                        
<script>
    $(document).ready(function(){
		$("#addStock").validationEngine();
    });
	
	$(function() {
		$( "#stock_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>