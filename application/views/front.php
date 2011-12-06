<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Registrasi Online - Kamilah Wisata</title>
		
		<!-- ICON -->
		<link rel="icon" type="image/x-icon" href="<?php echo base_url() ?>images/favicon.ico" />
		
		<!-- CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>css/newkamilah.css" type="text/css" media="screen" title="default" /> 
		<link rel="stylesheet" href="<?php echo base_url();?>css/form.css" type="text/css" media="screen" title="default" /> 
		<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
	
		<!-- JAVASCRIPT -->
		<script src="<?php echo base_url();?>js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/jquery.js" type="text/javascript"></script>
		<script>
        // tooltip
        $(function() {
			$("#myform :input").tooltip({
				position: "center right",
				offset: [-2, 10],
				effect: "fade",
				opacity: 0.7
			
			});
        });
        </script>
	</head>
	
	<body> 
		<!-- HEADER SECTION -->
		<div class="header">
			<!-- LOGO -->
			<div class="center_header">
				<a href="<?php echo base_url();?>">
					<img src="<?php echo base_url();?>images/front/logo.png" border="0" width="252" height="56" />
				</a>
			</div>
			<!-- END LOGO -->
		</div>
		<!-- END HEADER SECTION -->
		
		<!-- MAIN SECTION -->
		<div class="main">
			<!-- NAVIGATION -->
			<div class="line"></div>
			<div class="nav">
				<a href="<?php echo site_url();?>"><div class="label">Registrasi</div></a>
				<a href="<?php echo base_url().'index.php/login/';?>"><div class="label">Login</div></a>
				<a href="http://umrahkamilah.com"><div class="label">Website</div></a>
			</div>
			<div class="line"></div>
			<!-- END NAVIGATION -->
			
			<!-- CONTENT -->
			<div class="content">
				<?php echo $content;?>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END MAIN SECTION -->
		
		<!-- FOOTER SECTION -->
		<div class="footer">
			<div class="line"></div>Sistem Registrasi Online - Research & Development Team<div class="line"></div>
		</div>
		<!-- END FOOTER SECTION -->
	</body>
</html> 