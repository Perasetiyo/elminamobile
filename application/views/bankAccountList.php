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
			<h4 class="widgettitle">Data Bank Account</h4><br/>
				<p class="stdformbutton">
					<a href="<?=base_url()?>index.php/cash/lists" title="Back" class="btn">Back</a>&nbsp;
					
				</p>
				
                <table width="100%" class="table table-bordered" id="dyntable">
                    <colgroup>
                        <col class="con0" style="align: center; width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                          	<th width="3%" class="head1 center">No</th>
							<th width="29%" class="head1 center">Nama</th>
							<th width="10%" class="head0 center">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
							$i = 1;
							foreach($listBankAccount as $item):?>
                        <tr class="gradeX">
						
							<td class="center"><?=$i++;?></td>
                            <td><?=$item->bank_account_name?></td>
                            <td class="center nominal"><?=$item->nominal?></td>
                        </tr>
                        <?php endforeach; ?>
						<tr>
							<td class="center"></td>
							<td class="center"></td>
							<td class="center"></td>
						</tr>
                    </tbody>
                </table>
<script>
	$(document).ready(function() {
		$('.nominal').formatCurrency();
	});
</script>