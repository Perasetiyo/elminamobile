<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Depreciation</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/depreciation/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>PPE</label>
									<span class="field">
										<select name="ppe_id" id="ppe_id" class="input-xlarge">
											<option value=''>- Choose One -</option>
										<?php foreach($ppe as $ppe): ?>
											<option value="<?php echo $ppe->ppe_id?>"><?php echo $ppe->ppe_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="depreciation_desc" id="depreciation_desc" /> 
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
					<div style="float:left">
						<a href="<?=base_url()?>index.php/depreciation/history" title="Depreciation History" style = "color:#fff;" class="btn btn-warning">
							Depreciation History</a>
					</div>
					<?php echo $links; ?>
				
				
                <table width="100%" class="table table-bordered" id="dyntable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
						<col class="con0" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="2%" class="head1 center">No</th>
							<th width="10%" class="head1 center">PPE</th>
							<th width="2%" class="head0 center">Ages</th>
							<th width="8%" class="head1 center">Purchase Date</th>
							<th width="5%" class="head1 center">Price</th>
                            <th width="5%" class="head0 center">Depreciation Nominal</th>
							<th width="8%" class="head0 center">Current Period</th>
							<th width="5%" class="head0 center">Paid</th>
							<th width="5%" class="head0 center">Depreciation Date</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_depreciation == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_depreciation as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
                            <td>
								<a href="<?=base_url()?>index.php/depreciation/history_per_ppe/<?=$item->ppe_id?>" title="Detail">
									<?=$item->ppe_desc?>
								</a>
							</td>
                            <td class="center"><?=$item->ppe_age?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->ppe_date))?></td>
                            <td class="right nominal"><?=$item->ppe_nominal?></td>
							<td class="right nominal"><?=$item->depreciation_nominal?></td>
							<td class="center"><?=$item->ppe_current_period?> <br/>
								<small>
									<?php echo 'Last : '.date("d-M-Y", strtotime($item->last_depreciation_date))?>
									
								</small>
							</td>
							<td class="center"><?=$item->ppe_has_been_paid?></td>
						<form id="depreciate<?=$row-1;?>" class="stdform" method="post" action="<?=base_url()?>index.php/depreciation/depreciate" />
							<td class="center">
								<input type="hidden" value="<?=$item->ppe_id?>" name="ppe_id"/>
								<input type="text" name="depreciation_date" id="depreciation_date<?=$row-1;?>" class="validate[required] datepicker input-small"/></td>
                            <td class="centeralign">
								<?php 
									if ($item->ppe_current_period < $item->ppe_age && $item->ppe_status > 0) {
										?><button class="btn btn-danger">Depreciate</button><?php
									} else { 
										echo 'Disabled';
									}
								?>
							</td>
						</form>
                        
						</tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
		
<script>	
	$(document).ready(function(){
		$(".stdform").validationEngine();
    });
	
	$(function() {
		$( ".datepicker" ).datepicker({dateFormat: "dd-M-yy"});
	}); 
</script>
