<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>Customer Statistics</h1>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
			
				<table width="100%" class="table table-bordered" id="rosetatable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
						<col class="con0" />
                        
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="center">No</th>
							<th width="7%" class="center">Level</th>
							<th width="5%" class="center">Total Customer</th>                            
                        </tr>
                    </thead>
                    <tbody>
					<?php 
						$row = 1;
						foreach ($customer_stats as $item) {?>
						<tr class="gradeX">
							<td class="center"><?=$row++;?></td>
					        <td class="center"><?=$item->option_desc?></td>
							<td class="center"><?=$item->total_cust?></td>
                        </tr>
					   <?php } ?>
                    </tbody>
                </table>
				
				<div class="clear"></div>

