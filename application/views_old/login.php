<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>LOGIN MINA BACKOFFICE</title>
<link rel="stylesheet" href="<?=base_url()?>assets/css/style.default.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body class="loginpage">

<div class="loginpanel">
    <div class="loginpanelinner">
        <div class="logo animate0 bounceIn"><img src="<?=base_url()?>assets/images/logo.png" alt="" /></div>
        <form id="login" action="<?=base_url()?>index.php/login/validate" method="post" enctype="multipart/form-data" />
           <div class="success message" >	
				<?php 
					echo $this->session->flashdata('success_message');
				?>
			</div>
			<div class="error message">
				<?php 
					echo $this->session->flashdata('error_message');
				?>
			</div>
            
            <div class="inputwrapper animate1 bounceIn">
                <input type="text" name="username" id="username" placeholder="Username" />
			</div>
            <div class="inputwrapper animate2 bounceIn">
                <input type="password" name="password" id="password" placeholder="Password" />
            </div>
            <div class="inputwrapper animate3 bounceIn">
                <button name="submit">Login</button>
            </div>
            
        </form>
    </div><!--loginpanelinner-->
</div><!--loginpanel-->

<div class="loginfooter">
    <p>&copy; 2014. MINA MUSLIM - <a href="http://www.ELMINASTORE.COM">ELMINASTORE.COM</a>. All Rights Reserved.</p>
</div>

</body>
</html>
