<div class="center">
	<table border="0" class="content_center">
		<tr>
			<td>
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Assalamu'alaikum Warohmatulloh Wabarokatuh</span>
					<div class="repeat_hline"></div>
				</div>
				
				<div align="center">
					<p>"Masukan <strong class="bold">Email</strong> & <strong class="bold">Password</strong> yang Valid <br/> Seperti yang anda masukkan pada saat proses registrasi."</p>
					<div>
						<img src="<?php echo base_url();?>images/front/userdir.png" alt="" />
					</div>
				</div>		

				<!-- FORM LOGIN -->
				<?php $attr = array('name' => 'cek_login', 'id' => 'myform'); echo form_open('login/cek_login', $attr); ?>
					<div class="title">
						<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
						<span class="text_title">Form Login Untuk Mengakses Dasboard Calon Jamaah</span>
					</div>
					<p>
						<div>
							<div class="label_login"><img src="<?php echo base_url();?>images/front/mail.png" width="16" height="16" alt="" />&nbsp;Email Anda</div>
							<? if(form_error('email') != '') {?>
								<div class="error_validation" style="text-align:center;margin:5px;"><?php echo form_error('email'); ?></div>
							<? }?>
							<div align="center"><input type="text" name="email" class="input_login" title="Isikan Alamat Email Anda" /></div>
						</div>
						
						<div>
							<div class="label_login"><img src="<?php echo base_url();?>images/front/key.png" width="16" height="16" alt="" />&nbsp;Password Akun</div>
							<? if(form_error('password') != '') {?>
								<div class="error_validation" style="text-align:center;margin:5px;"><?php echo form_error('password'); ?></div>
							<? }?>
							<div align="center"><input type="password" name="password" class="input_login" title="Isikan Password Anda" /></div>
						</div>

						<div align="center">
							<p><a href="<?=base_url() ?>lupapassword" title="Lupa Password Anda?">Lupa Password?</a></p>
						</div>
						
						<div style="margin:5px;text-align:center;">
							<input type="submit" value="Login" class="submit_button" />
							<input type="reset" value="Reset" class="reset_button" />
						</div>
					</p>
				<?php echo form_close();?>
				<!-- END FORM LOGIN -->
			</td>
		</tr>
	</table>
</div>