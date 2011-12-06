<link rel="stylesheet" type="text/css"  media="screen" title="default" href="<?php echo base_url(); ?>css/login_style.css" />
<!--  end step-holder -->
<div class="center">
	<!-- LEFT SIDE -->
	<div class="content_left">
		<div class="wrapper">
			<div class="content">
				<div id="form_wrapper" class="form_wrapper">
					<?php 
					if($this->uri->segment(1) === 'login'){
					$login = 'login active';
					$reset = 'forgot_password';
					}
					else{
					$login = 'login';
					$reset = 'forgot_password active';
					}
					?>
					<form class="<?php echo $login; ?>" method="post" action="<?php echo base_url().'index.php/login/cek_login';?>">
						<h3>Login</h3>
						<div>
							<label>Email:</label>
							<input type="text" name='email'/>
							<? 
							if($this->uri->segment(1) === 'login'){
							echo form_error('email'); 
							 if(isset($msg))
							{															
								echo '<span class="error">
										<strong>'.$msg.'
									  </span>';
							}
						
							}?>
						</div>
						<div>
							<label>Password: <a href="#" rel="forgot_password" class="forgot linkform">Lupa password?</a></label>
							<input type="password" name='password' />
								<? echo form_error('password'); ?>
						</div>
						<div class="bottom">
							<div class="remember"><input type="checkbox" /><span>Keep me logged in</span></div>
							<input type="submit" value="Login"></input>
							<div class="clear"></div>
						</div>
					</form>
					<form class="<?php echo $reset; ?>" method="post" action="<?php echo base_url().'index.php/forgot/send';?>">
						<h3>Lupa Password</h3>
						<div>
							<label>Email:</label>
							<input type="text" name='email'/>
							<?
							if($this->uri->segment(1) === 'forgot'){
							echo form_error('email'); 
						 if(isset($msg))
							{								
								echo '<span class="error">
										'.$msg.'
									  </span>';
							}
						 if(isset($success))
							{								
								echo '<span class="success">
										'.$success.'
									  </span>';
							}
							}?>
						</div>
						<div class="bottom">
							<input type="submit" value="Send reminder"></input>
							<a href="#" rel="login" class="linkform">Sudah ingat? Log in di sini</a>
							<div class="clear"></div>
						</div>
					</form>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<!-- END LEFT SIDE -->
	
	<!-- RIGHT SIDE -->
	<div class="content_right">
		<div class="info_shape">
						
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Informasi Login</span>
			</div>
			
			<div align="center">
				<p>Masukan <strong>Email</strong> yang valid sesusai saat proses registrasi User. dan <strong>Password</strong> yang dikirim melalui Email.</p>
			</div>			
			<div align="center">
				<p>Jika kurang yakin atau lupa dengan password Anda, silahkan klik Lupa Password untuk mendapatkan <strong>Password Baru</strong>. </p>
			</div>
			
			
		</div>
		
	</div>
	<!-- END RIGHT SIDE -->
</div>

<script type="text/javascript">
			$(function() {
					//the form wrapper (includes all forms)
				var $form_wrapper	= $('#form_wrapper'),
					//the current form is the one with class active
					$currentForm	= $form_wrapper.children('form.active'),
					//the change form links
					$linkform		= $form_wrapper.find('.linkform');
						
				//get width and height of each form and store them for later						
				$form_wrapper.children('form').each(function(i){
					var $theForm	= $(this);
					//solve the inline display none problem when using fadeIn fadeOut
					if(!$theForm.hasClass('active'))
						$theForm.hide();
					$theForm.data({
						width	: $theForm.width(),
						height	: $theForm.height()
					});
				});
				
				//set width and height of wrapper (same of current form)
				setWrapperWidth();
				
				/*
				clicking a link (change form event) in the form
				makes the current form hide.
				The wrapper animates its width and height to the 
				width and height of the new current form.
				After the animation, the new form is shown
				*/
				$linkform.bind('click',function(e){
					var $link	= $(this);
					var target	= $link.attr('rel');
					$currentForm.fadeOut(400,function(){
						//remove class active from current form
						$currentForm.removeClass('active');
						//new current form
						$currentForm= $form_wrapper.children('form.'+target);
						//animate the wrapper
						$form_wrapper.stop()
									 .animate({
										width	: $currentForm.data('width') + 'px',
										height	: $currentForm.data('height') + 'px'
									 },500,function(){
										//new form gets class active
										$currentForm.addClass('active');
										//show the new form
										$currentForm.fadeIn(400);
									 });
					});
					e.preventDefault();
				});
				
				function setWrapperWidth(){
					$form_wrapper.css({
						width	: $currentForm.data('width') + 'px',
						height	: $currentForm.data('height') + 'px'
					});
				}
				
				/*
				for the demo we disabled the submit buttons
				if you submit the form, you need to check the 
				which form was submited, and give the class active 
				to the form you want to show
				
				$form_wrapper.find('input[type="submit"]')
							 .click(function(e){
								e.preventDefault();
							 });	
							 
				*/			 
							 		 
			});
			
		
        </script>