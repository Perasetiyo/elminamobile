	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Add New Product</h1>
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
						
						<form id="addproduct" name="addproduct" class="stdform" method="post" action="<?=base_url()?>index.php/product/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Product </h4>
								<div class="widgetcontent">
								<p>
									<label>Product Name <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="product_name" id="product_name" /> 
									</span>
								</p>
								<!--p>
									<label>Product COGS <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="current_cogs" id="current_cogs" /> 
									</span>
								</p-->
								
								<p>
									<label>Product Code<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-xlarge" name="product_code" id="product_code" /> 
									</span>
								</p>
								
								<p>
									<label>Product Weight (gram)<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-small" style="width:50px;" name="product_weight" id="product_weight" /> 
									</span>
								</p>
								<p>
									<label>Price 1-9<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-small" name="product_price_1" id="product_price_1" /> 
									</span>
								</p>
								<p>
									<label>Price >=10<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-small" name="product_price_2" id="product_price_2" /> 
									</span>
								</p>
								<p>
									<label>Status <font style="color:red;">*</font></label>
									<span class="field">
										<select name="status" id="status" style="width:100px;" class="validate[required]">
											<option value="1">Active</option>
											<option value="0">Inactive</option>
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
		$("#addproduct").validationEngine();
    });
	
	$(function() {
		$( "#product_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>