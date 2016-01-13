	<?php foreach ($option as $item): ?>	
	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Edit Option <?=$item->option_desc?></h1>
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
						
						<form id="add" name="add" class="stdform" method="post" action="<?=base_url()?>index.php/options/doUpdate" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Options </h4>
								<input type="hidden" value="<?=$item->option_id?>" name="option_id">
								<div class="widgetcontent">
								<p>
									<label>Option Root<font style="color:red;">*</font></label>
									<span class="field">
										<select name="option_root_id" id="option_root_id" style="width:200px;" class="">
											<option value>- Root -</option>
										<?php foreach($root_type as $type): ?>
											<option value="<?php echo $type->option_id?>" <?php if ($item->option_root_id == $type->option_id) echo 'selected'; ?>><?php echo $type->option_desc?></option>
											
										<?php endforeach; ?>
										</select>
									</span>
								</p>
								<p>
									<label>Type <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="option_type" id="option_type" 
											value="<?=$item->option_type?>"/> 
									</span>
								</p>
								<p>
									<label>Code <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="option_code" id="option_code" 
											value="<?=$item->option_code?>"/> 
									</span>
								</p>
								<p>
									<label>Desc <font style="color:red;">*</font></label>
									<span class="field">
										<input type="text" class="input-xlarge validate[required]" name="option_desc" id="option_desc" 
											value="<?=$item->option_desc?>"/> 
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
									<button type="reset" class="btn btn-error">RESET</button>
									<a href="<?=base_url()?>index.php/expense/lists" class="btn">BACK</a>
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
	
</script>