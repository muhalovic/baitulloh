<div class="center">
	<!-- CONTENT CENTER-->
	<div class="content_center">
		<div class="info_shape">
						
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Assalamu'alaikum Warohmatulloh Wabarokatuh</span>
			</div>
			
			<div align="center">
				<p>"Masukan <strong class="bold">Email</strong> & <strong class="bold">Password</strong> yang Valid <br/> Seperti yang anda masukkan pada saat proses registrasi."</p>
				<div>
					<img src="<?php echo base_url();?>images/front/userdir.png" alt="" />
				</div>
			</div>		

			<!-- FORM LOGIN -->
			<?php echo form_open('login/cek_login');?>
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Form Login Untuk Mengakses Dasboard Calon Jamaah</span>
				</div>
				<p>
					<div>
						<div class="label_login">Email Anda</div>
						<? if(form_error('email') != '') {?>
							<div class="error_validation" style="text-align:center;margin:5px;"><?php echo form_error('email'); ?></div>
						<? }?>
						<div align="center"><input type="text" name="email" class="input_login" /></div>
					</div>
					
					<div>
						<div class="label_login">Password Akun</div>
						<? if(form_error('password') != '') {?>
							<div class="error_validation" style="text-align:center;margin:5px;"><?php echo form_error('password'); ?></div>
						<? }?>
						<div align="center"><input type="password" name="password" class="input_login" /></div>
					</div>

					<div style="margin:5px;text-align:center;">
						<input type="submit" value="Login" class="submit_button" />
						<input type="reset" value="Reset" class="reset_button" />
					</div>
				</p>
			<?php echo form_close();?>
			<!-- END FORM LOGIN -->
			
		</div>
		<!-- END INFO SHAPE -->
		
	</div>
	<!-- END CONTENT CENTER-->
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