<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Existing Customer</h1>
</div>          
				
				<div class="maincontentinner">
						
					<div class="success message">
						<?php 
							echo $this->session->flashdata('success_message');
						?>
					</div>
					<div class="error message">
						<?php 
							echo $this->session->flashdata('error_message');
						?>
					</div>
				
				<form method="post" action="<?=base_url()?>index.php/deposit/choose_customer">
					
					<table width="100%" class="table table-bordered" id="dyntable">
						<colgroup>
							<col class="con0" style="align: center; width: 4%" />
							<col class="con1" />
							<col class="con0" />
							<col class="con1" />
							<col class="con0" />
						</colgroup>
						<thead>
							<tr>
								<th class="head1 center">No</th>
								<th class="head1 center">Nama</th>
								<th class="head0 center">Daerah Pengiriman</th>
								<th class="head1 center">No Telp</th>
								<th class="head0 center">Action</th>
							</tr>
						</thead>
					<tbody>
						
                    	<?php 
							if ($list_customer == null) {
							?><tr class="gradeX"><td colspan="8"><font class="no-data-tabel">Data tidak ditemukan</font></td></tr><?php
							} else {
						
							$row = 1;
							foreach($list_customer as $customer):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$row++;?></td>
					        <td><?=$customer->billing_name?></td>
							<td class="center"><?=$customer->billing_street?><br/>
												<?=$customer->billing_city?></td> 
							<td class="center"><?=$customer->billing_phone?></td>
							<td class="centeralign">
                            	<a href="<?=base_url()?>index.php/deposit/add/<?=$customer->billing_id?>" title="Pilih"><span class="iconsweets-like"></span></a>&nbsp;
							</td>
                        </tr>
                        <?php endforeach; 
						}?>
                    </tbody>
					
				</div>	
                </table>
				
<script>
	$("#togglefindcustomer").click(function(){
		$("#find_customer").slideToggle();
	});
</script>				