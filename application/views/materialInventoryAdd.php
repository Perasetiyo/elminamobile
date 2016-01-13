	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Add Material Inventory</h1>
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
						
						<form id="add" name="add" class="stdform" method="post" action="<?=base_url()?>index.php/material_inventory/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Material Inventory</h4>
								<div class="widgetcontent">
								<p>
									<label>Tipe Bahan<font style="color:red;">*</font></label>
									<span class="field">
										<select name="material_bahan_id" id="material_bahan_id" style="width:200px;" class="validate[required]">
											<option value>- Tipe Bahan -</option>
										<?php foreach($material_bahan as $bahan): ?>
											<option value="<?php echo $bahan->option_id?>"><?php echo $bahan->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Warna Bahan<font style="color:red;">*</font></label>
									<span class="field">
										<select name="material_warna_id" id="material_warna_id" style="width:200px;" class="validate[required]">
											<option value>- Warna Bahan -</option>
										<?php foreach($material_warna as $warna): ?>
											<option value="<?php echo $warna->option_id?>"><?php echo $warna->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Tanggal Masuk<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="material_date_init" id ="material_date_init" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Qty <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-small validate[required]" name="material_qty_init" id="material_qty_init" /> 
									</span>
								</p>
								<p>
									<label>Harga / satuan<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-medium validate[required]" name="material_nominal_init" id="material_nominal_init" /> / satuan
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/material_inventory/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#add").validationEngine();
    });
	
	$(function() {
		$( "#material_date_init" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>