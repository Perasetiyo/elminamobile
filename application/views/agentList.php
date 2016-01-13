<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Partners</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/agent/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								
								<li>
									<label>Name</label>
									<span class="field">
										<input type="text" class="input-medium" name="billing_name" id="billing_name" /> 
									</span>
								</li>
								<li>
									<label>City</label>
										<span class="field">
										<input type="text" class="input-medium" name="billing_city" id="billing_city" /> 
									</span>
								</li>
								<li>
									<label>Level <font style="color:red;">*</font></label>
									<span class="field">
										<select name="billing_level" id="billing_levels" class="input-medium">
											<option value=''>- Choose One -</option>
										<?php foreach($billing_levels as $level): ?>
											<option value="<?php echo $level->option_id?>"> 
												 <?php echo $level->option_desc?>
											</option>
										<?php endforeach; ?>
										</select>
									</span>
										
								</li>
								<li>
									<label>Status</label>
									<span class="field">
										<select name="agen_status" id="agen_status" class="input-medium">
											<option value=''>- Choose One -</option>
										<?php foreach($agen_status as $status): ?>
											<option value="<?php echo $status->option_id?>"><?php echo $status->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
				
			<h4 class="widgettitle"><?=$total_list_billing?> Partners Found</h4><br/>
				<p class="stdformbutton">
					<a href="<?=base_url()?>index.php/agent/add" title="Tambah" style = "color:#fff;" class="btn btn-success">Add Partners</a>&nbsp;
				</p>
				<?php echo $links; ?>
                <table width="100%" class="table table-bordered" id="dyntable">
                    <colgroup>
                        <col class="con1" style="align: center; width: 4%" />
                        <!--col class="con1" style="align: center; width: 10%" /-->
                        <col class="con0" />
                        <col class="con1" style="align: center; width: 10%" />
                        <col class="con0" style="align: center; width: 30%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" style="align: center; width: 10%" />
						<col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th class="head1 center">No</th>
							<!--th class="head1 center">Kode</th-->
							<th class="head1 center">Nama</th>
							<th class="head1 center">Level</th>
							<th class="head0 center">Alamat</th>
                            <th class="head0 center">Kontak</th>
							<th class="head1 center">Status</th>
							<th class="head1 center">Upline</th>
                            <th class="head0 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
							if ($list_billing == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_billing as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$i++;?></td>
							<!--td class="center"><?=$item->billing_code?></td-->
                            <td><?=$item->billing_name?></td>
                            <td><?=$item->level_desc?></td>
                            <td class="center">
								<?=$item->billing_street?><br/>
								Kec. <?=$item->billing_kec?><br/>
								<b><?=$item->billing_city?> &nbsp; <?=$item->billing_postal_code?><br/>
								<?=$item->prov_desc?><br/></b>
								<?=$item->billing_country?>
							</td>
                            <td class="center">
								PH : <?=$item->billing_phone?><br/>
								Email : <?=$item->billing_email?><br/>
								WA : <?=$item->billing_whatsapp?><br/>
								BBM : <?=$item->billing_bbm?><br/>
								FB Profile : <?=$item->billing_facebook?><br/>
								FB Fanspage : <?=$item->billing_fanpage?><br/>
								TW : <?=$item->billing_twitter?><br/>
								IG : <?=$item->billing_instagram?><br/>
								WEB : <?=$item->billing_web?><br/>
							</td>
							<td class="center"><?=$item->flag1_desc?></td>
							<td class="center"><?=$item->upline_name?></td>
							<td class="centeralign">
                            	<a href="<?=base_url()?>index.php/agent/update/<?=$item->billing_id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
								
							</td>
                        </tr>
                        <?php endforeach; 
							}
							?>
                    </tbody>
                </table>
				<?php echo $links; ?>
