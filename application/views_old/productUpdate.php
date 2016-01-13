<?php foreach ($product as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Product <?=$item->product_name?></h1>
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
						
						<form id="addStock" name="addStock" class="stdform" method="post" action="<?=base_url()?>index.php/product/doUpdate/" />
							
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Update Product </h4>
								<div class="widgetcontent">
								<input type="hidden" value="<?=$item->product_id?>" name="product_id"/>
								<p>
									<label>Product Name<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="product_name" id="product_name" value="<?=$item->product_name?>"/> 
									</span>
								</p>
								
								<p>
									<label>COGS<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="current_cogs" id="current_cogs" value="<?=$item->current_cogs?>"/> 
									</span>
								</p>
								
								<p>
									<label>Product Code<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-xlarge" name="product_code" id="product_code" value="<?=$item->product_code?>"/> 
									</span>
								</p>
								
								<p>
									<label>Product Weight (gram)<font style="color:red;"></font></label>
									<span class="field">
										<input type="text" class="input-small" style="width:50px;" name="product_weight" id="product_weight" value="<?=$item->product_weight?>"/> 
									</span>
								</p>
								
								<p>
									<label>Status <font style="color:red;">*</font></label>
									<span class="field">
										<select name="status" id="status" style="width:100px;" class="validate[required]">											
											<option value="1" <?php if ($item->status == 1) echo 'selected'; ?>>Active</option>
											<option value="0" <?php if ($item->status == 0) echo 'selected'; ?>>Inactive</option>
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
<?php endforeach;?>        
<script>
    $(document).ready(function(){
		$("#addStock").validationEngine();
    });
	
</script>