<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Activity Log</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/activity/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								<li>
									<label>Username</label>
									<span class="field">
										<select name="user_id" id="user_id" class="input-xlarge">
											<option value=''>Choose One</option>
										<?php foreach($users as $user): ?>
											<option value="<?php echo $user->userid?>"><?php echo $user->user_name?></option>
										<?php endforeach; ?>
										</select>
									</span>
								</li>
								<li>
									<label>Description</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="activity_desc" id="activity_desc" /> 
									</span>
								</li>
							</ul>
							<div>
								<button class="btn btn-primary">Search</button>
							</div>							
						</div>
					</div>
				</form>
				<?php $session_data = $this->session->userdata('logged_in'); ?>
				<?php echo $links; ?>
				
                <table width="100%" class="table table-bordered" id="dyntable" >
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
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
							<th width="10%" class="head1 center">Username</th>
							<th width="14%" class="head0 center">Activity</th>
							<th width="4%" class="head0 center">Date</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_activity == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_activity as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->user_name?></td>
                            <td><?=$item->activity_desc?></td>
							<td class="center"><?=$item->activity_date?></td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>