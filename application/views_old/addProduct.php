	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Tambah Produk </h1>
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
						
						<form id="billingForm" name="billingForm" class="stdform" method="post" action="<?=base_url()?>index.php/productAction/doAddProduct" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Detail Produk </h4>
								<div class="widgetcontent">
								<p>
									<label>Nama Produk <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="product_name" id="product_name" /> 
									</span>
								</p>
								<p>
									<label>Kode Produk <font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="product_code" id="product_code" /> 
									</span>
								</p>
								<p>
									<label>Harga <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="product_price" id="product_price" /> 
									</span>
								</p>
								<p>
									<label>Stok <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="product_stock" id="product_stock" /> 
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn">RESET</button>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#billing_form_").validationEngine();
    });
</script>