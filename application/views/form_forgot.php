<div class="center">
	<table border="0" class="content_center">
		<tr>
			<td>
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Lupa Password Dashboard Anda ?</span>
					<div class="repeat_hline"></div>
				</div>
				
				<div align="center">
					<p>"Masukan alamat email yang valid sesuai saat proses registrasi User."</p>
					<p>"Sistem otomatis akan <strong class="bold">mereset password</strong> anda dan mengirimkan password baru melalui email yg telah dimasukan."</p>
					<div>
						<img src="<?php echo base_url();?>images/front/book.png" width="138" height="138" alt="" />
					</div>
				</div>		

				<!-- FORM FORGOT -->
				<?php $attr = array('name' => 'cek_forgot', 'id' => 'myform'); echo form_open('/forgot/send', $attr); ?>
					<div>
						<div class="label_login">Isikan Email Anda</div>
						<? if(form_error('email') != '') {?>
							<div class="error_validation" style="text-align:center;margin:5px;"><?php echo form_error('email'); ?></div>
						<? }?>
						<div align="center"><input type="text" name="email" class="input_login" title="Masukkan Alamat Email Anda" /></div>
					</div>
					
					<div style="margin:5px;text-align:center;">
						<input type="submit" value="Kirim" class="submit_button" />
						<input type="reset" value="Reset" class="reset_button" />
					</div>
				<?php echo form_close();?>
				<!-- END FORM FORGOT -->
			</td>
		</tr>
	</table>
</div>