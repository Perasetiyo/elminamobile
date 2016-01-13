<?php foreach ($material_inventory as $item): ?>		
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Use Material</h1>
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
						
						<form id="add" name="add" class="stdform" method="post" action="<?=base_url()?>index.php/material_inventory/doCutting" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Material Inventory</h4>
								<input type="hidden" value="<?=$item->material_id?>" name="material_id"/>
								<input type="hidden" value="<?=$item->material_code?>" name="material_code"/>
								<div class="widgetcontent">
								<p>
									<label>Code<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="validate[required]" disabled value="<?=$item->material_code?>"/>
									</span>
								</p>
								
								<p>
									<label>Notes<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="cutting_desc" id ="cutting_desc" class="validate[required]" value="Cutting bahan <?=$item->material_code?>"/>
									</span>
								</p>
								<p>
									<label>Tanggal Pemakaian<font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="cutting_date" id ="cutting_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Qty yang di pakai (yards) <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-small validate[required]" name="material_used" id="material_used" /> 
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
<?php endforeach;?>		                        
<script>
    $(document).ready(function(){
		$("#add").validationEngine();
    });
	
	$(function() {
		$( "#cutting_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>