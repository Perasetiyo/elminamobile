f<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Customer</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/customer/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Level</label>
									<span class="field">
										<select name="billing_level" id="billing_level" class="input-xlarge">
											<option value=''>- Choose One -</option>
										<?php foreach($billing_level as $level): ?>
											<option value="<?php echo $level->option_id?>"><?php echo $level->option_desc?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Customer Name</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="billing_name" id="billing_name" /> 
									</span>
								</li>
								<li>
									<label>Customer Phone</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="billing_phone" id="billing_phone" /> 
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
				
			<h4 class="widgettitle">List Building</h4><br/>
				<p class="stdformbutton">
					<a href="<?=base_url()?>index.php/customer/add" title="Tambah" style = "color:#fff;" class="btn btn-success">Add Customer</a>&nbsp;
				</p>
				
                <table width="100%" class="table table-bordered" id="dyntable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="20%" class="head1 center">Nama</th>
							<th width="13%" class="head0 center">Kota</th>
                            <th width="16%" class="head0 center">Telepon</th>
                            <th width="9%" class="head1 center">Level</th>
							<th width="15%" class="head1 center">Dropshipper</th>
                            <th width="9%" class="head0 center">Action</th>
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
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->billing_name?></td>
                            <td class="center"><?=$item->billing_city?></td>
                            <td class="center"><?=$item->billing_phone?></td>
							<td class="center"><?=$item->option_desc?></td>
                            <td class="center"><?=$item->ds_name?></td>
							<td class="centeralign">
                            	<a href="<?=base_url()?>index.php/customer/update/<?=$item->billing_id?>" title="Ubah"><span class="iconsweets-create"></span></a>&nbsp;
							</td>
                        </tr>
                        <?php endforeach; 
							}
							?>
                    </tbody>
                </table>
