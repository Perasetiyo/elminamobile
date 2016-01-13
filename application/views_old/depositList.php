<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Deposit</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<form method="post" action="<?=base_url()?>index.php/deposit/search">
					<div id="wiz1step1" class="formwiz">
						<hr>
						<h4 class="widgettitle">&nbsp; Search</h4>
						<div class="widgetcontent">
							<ul class="search-field">
								
								<li>
									<label>Billing Name</label>
										<span class="field">
										<input type="text" class="input-xlarge" name="billing_name" id="billing_name" /> 
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
						<a href="<?=base_url()?>index.php/deposit/add" title="Tambah" style = "color:#fff;" class="btn btn-success">Add Deposit</a>&nbsp;
					</div>
					
					<form method="post" action="<?=base_url()?>index.php/deposit/find_customer" >
						<a href="#" id="togglefindcustomer" class="btn btn-inverse" style="float:left; margin-right:10px;">Find Customer</a>
						<div id="find_customer" style="display: none;">
						<font style="color:blue;">Nama Pelanggan</font>
						<input type="text" name="billing_name" id="billing_name" class="input-small" />
						<font style="color:blue;">Telp Pelanggan</font>
						<input type="text" name="billing_phone" id="billing_phone" class="input-small" />
						<input type="submit" name="action" class="btn btn-primary" value="Add Deposit" />
						</div>
					</form>
					
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
                          	<th width="3%" class="head1 center">No</th>
							<th width="4%" class="head0 center nosort"><input type="checkbox" class="checkall" /></th>
							<th width="18%" class="head0 center">Desc</th>
							<th width="8%" class="head1 center">Date</th>
                            <th width="8%" class="head0 center">Nominal</th>
                            <th width="10%" class="head1 center">Billing Name</th>
							<th width="7%" class="head1 center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						
                    	<?php 
							if ($list_deposit == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data not found</font></td></tr><?php
							} else {
								$i = 1;
								foreach($list_deposit as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
							<td class="center">
								<span class="center">
									<input type="checkbox" />
								</span>
							</td>
                            <td><?=$item->deposit_desc?></td>
							<td class="center"><?php echo date("d-M-Y", strtotime($item->deposit_date))?></td>
                            <td class="center"><?=$item->deposit_nominal?></td>
							<td class="center"><?=$item->billing_name?></td>
                            <td class="centeralign">
								<a href="<?=base_url()?>index.php/deposit/update/<?=$item->deposit_id?>" title="Edit / Update"><span class="iconsweets-create"></span></a>&nbsp;
								<?php echo anchor('deposit/delete/'.$item->deposit_id,'<span class="icon-trash"></span>', array('title' => 'Delete', 'onClick' => "return confirm('Are you sure about this?')"));?>
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
                </table>
				<p><?php echo $links; ?></p>
			

<script>
	$("#togglefindcustomer").click(function(){
		$("#find_customer").slideToggle();
	});
</script>				