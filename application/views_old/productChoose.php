	<div class="pageicon"><span class="iconfa-laptop"></span></div>
	<div class="pagetitle">
		<h1>&nbsp;Pilih Produk</h1>
		<small> Klik kotak yang ada di sebelah kiri nama yang diinginkan, lalu klik submit</small>
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
						
						<form id="addproduct" name="addproduct" class="stdform" method="post" action="<?=base_url()?>index.php/stock/view_stock" />
							<div id="wiz1step1" class="formwiz">
								<h4 class="widgettitle">&nbsp; Products </h4>
								<div class="widgetcontent">
								<p>
									<span class="field">
										<?php 
											if ($list_stock == null) {
												echo "Data not found";
											} else {
												$i = 1;
												$row = 1;
												foreach($list_stock as $item):?>
												<span class="center">
													<div style="width:200px;float:left;margin:5px;">
														<table>
														<tr>
															<td><input type="checkbox" name="ch<?=$row++;?>" value="<?=$item->product_id?>"/></td>
															<td><span >&nbsp;&nbsp;&nbsp;<?=$item->product_name?></span></td>
														</tr>
														</table>
													</div>
												</span>
										<?php 
												endforeach; 
											}
										?>
										<div class="clear"></div>
									</span>
								</p>
								<p class="stdformbutton">
									<button class="btn btn-primary">SUBMIT</button>
								<?php if (!empty($session_data['user_role'])) {?>
									<a href="<?=base_url()?>index.php/stock/lists" class="btn">BACK</a>
								<?php } ?>
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