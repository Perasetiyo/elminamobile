	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Add Fixed Cost</h1>
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
						
						<form id="addfixed_cost" name="addfixed_cost" class="stdform" method="post" action="<?=base_url()?>index.php/fixed_cost/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Fixed Cost </h4>
								<div class="widgetcontent">
								<p>
									<label>Nama <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="fixed_cost_name" id="fixed_cost_name" /> 
									</span>
								</p>
								<p>
									<label>Nominal <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="fixed_cost_nominal" id="fixed_cost_nominal" /> 
									</span>
								</p>
								<p>
									<label>Change Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="fixed_cost_per_date" id ="fixed_cost_per_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Type <font style="color:red;">*</font></label>
									<span class="field">
										<select name="fixed_cost_type_id" id="fixed_cost_type_id" style="width:100px;" class="validate[required]">
											<option value>- Type -</option>
										<?php foreach($fixed_cost_type as $type): ?>
											<option value="<?php echo $type->option_id?>"><?php echo $type->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								
								<p>
									<label>Status <font style="color:red;">*</font></label>
									<span class="field">
										<select name="fixed_cost_status" id="fixed_cost_status" style="width:100px;" class="validate[required]">
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/fixed_cost/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#addfixed_cost").validationEngine();
    });
	
	$(function() {
		$( "#fixed_cost_per_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>