<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Registrasi Online - Kamilah Wisata</title>
		
		<!-- ICON -->
		<link rel="icon" type="image/x-icon" href="<?php echo base_url() ?>images/favicon.ico" />
		
		<!-- CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>css/front_ui.css" type="text/css" media="screen" title="default" /> <!-- main css -->
		<link rel="stylesheet" href="<?php echo base_url();?>css/form.css" type="text/css" media="screen" title="default" /> <!-- form css -->
		<link rel="stylesheet" href="<?php echo base_url();?>css/chosen.css" type="text/css" media="screen" title="default" /> <!-- searchable combobox css -->
		<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' /> <!-- import font -->
	
		<!-- JAVASCRIPT -->
		<!--<script src="<?php //echo base_url();?>js/jquery-1.6.2.min.js" type="text/javascript"></script>-->
        <script src="<?php echo base_url();?>js/jquery.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/jquery.pstrength-min.1.2.js" type="text/javascript"></script> <!-- password strenght js -->
        <script src="<?php echo base_url();?>js/jquery.validate.js" type="text/javascript"></script>
		<script>
			$().ready(function() {
				// validation confirmation password			   
				$("#myform").validate({
					rules: {
						password_verification: {
							equalTo: "#password",
						}
					},
					messages: {
						password_verification: {
							equalTo: "tidak sama dengan field Password",
						}
					}
				});
			});
		
		
			$(function() {
				// password strength
				$('.password').pstrength();
				
				// tooltip
				$("#myform :input[title]").tooltip({
					position: "center right",
					offset: [-2, 10],
					effect: "fade",
					opacity: 0.9
				});
				$("#myform :textarea[title]").tooltip({
					position: "center right",
					offset: [-2, 10],
					effect: "fade",
					opacity: 0.8
				
				});
				$("#myform :select").tooltip({
					position: "center right",
					offset: [-2, 10],
					effect: "fade",
					opacity: 0.8
				
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
			<div class="con_top_nav">
				<div class="left_shadow"></div>
				<div class="top_nav">
                <!-- edit menu atas disini -->
                <? if($this->session->userdata('email') == NULL){ ?>
					<span class="top_separator">|</span>
					<a href="<?php echo site_url();?>">Registrasi</a>
					<span class="top_separator">|</span>
					<a href="<?php echo base_url().'index.php/login/';?>">Login</a>
					<span class="top_separator">|</span>
					<a href="http://umrahkamilah.com">Informasi</a>
					<span class="top_separator">|</span>
                <? } else { ?>
					<span class="top_separator">|</span>
					<a href="<?php echo site_url()."/welcome";?>">Informasi</a>
					<span class="top_separator">|</span>
					<a href="<?php echo site_url()."/beranda";?>">Konfirmasi</a>
					<span class="top_separator">|</span>
                <?	 	if($this->session->userdata('order_packet') == 1){ ?>
					<a href="<?php echo site_url()."/biodata";?>">Data</a>
					<span class="top_separator">|</span>
                <? } } ?>
				</div>
				<div class="right_shadow"></div>
			</div>
			<!-- END NAVIGATION -->
			
			<!-- CONTENT -->
			<div class="dynamic_content">
				<?php echo $content;?>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END MAIN SECTION -->
	
		<!-- FOOTER SECTION -->
		<div class="footer">
			<div class="horizontal_line"></div>
			<div class="con_footer">
				<div class="left_shadow"></div>
				<div class="foot_content">
					<p>
						<img src="<?php echo base_url();?>images/front/logofooter.png" /> 
						<span>Membangun Karakter Jamaah Menuju Kehidupan yang Lebih Baik</span>
					<p>
				</div>
				<div class="right_shadow"></div>
			</div>
		</div>
		<!-- END FOOTER SECTION -->
		
		<!-- Script for searchable combobox -->
		<script src="<?php echo base_url();?>js/chosen.jquery.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(".chzn-select").chosen({no_results_text: "Data yang dicari tidak ditemukan"});
		</script>
	</body>
</html> 