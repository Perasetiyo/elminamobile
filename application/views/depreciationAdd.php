	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Adding Depreciation</h1>
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
						
						<form id="adddepreciation" name="adddepreciation" class="stdform" method="post" action="<?=base_url()?>index.php/depreciation/doAdd" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Depreciation </h4>
								<div class="widgetcontent">
								<p>
									<label>PPE<font style="color:red;">*</font></label>
									<span class="field">
										<select name="ppe_id" id="ppe_id" style="width:200px;" class="validate[required]">
											<option>- PPE Name -</option>
										<?php foreach($ppe as $ppe): ?>
											<option value="<?php echo $ppe->ppe_id?>"><?php echo $ppe->ppe_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Date  <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" name="depreciation_date" id ="depreciation_date" class="validate[required]"/>
									</span>
								</p>
								<p>
									<label>Description <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="depreciation_desc" id="depreciation_desc" /> 
									</span>
								</p>
								<p>
									<label>Depreciation Nominal <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="depreciation_nominal" id="depreciation_nominal" /> 
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/depreciation/lists" class="btn">BACK</a>
								</p>
							</div>
						</div><!--#wiz1step1-->
					</form>
</div>
				                        
<script>
    $(document).ready(function(){
		$("#adddepreciation").validationEngine();
    });
	
	$(function() {
		$( "#depreciation_date" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>