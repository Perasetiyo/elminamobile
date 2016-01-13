<div class="pageicon"><span class="iconfa-laptop"></span></div>
<div class="pagetitle">
		<h1>List Current Stock</h1>
		<small> Drag & Drop list stok dibawah menggunakan kursor, lalu copy</small>
</div>
			<div class="maincontentinner">
				<div class="success message">
					<?php echo $this->session->flashdata('success_message'); ?>
				</div>
				<div class="error message">
					<?php echo $this->session->flashdata('error_message'); ?>
				</div>
				<hr/>
				<p class="stdformbutton">
						<a href="<?=base_url()?>index.php/stock/summary" class="btn">BACK</a>&nbsp;
<?php if (!empty($session_data['user_role'])) {?>
						<a href="<?=base_url()?>index.php/stock/lists" class="btn">Stock List</a>&nbsp;
<?php } ?>
				</p>
				<hr/>
				<div>
					<form method="post" action="<?=base_url()?>index.php/stock/mail_current_stock">
						<p><?=$output?></p>
						
					</form>
				</div>
