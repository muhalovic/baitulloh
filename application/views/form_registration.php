<!--  start step-holder -->
<div class="step-holder">
	<div class="step-no">1</div>
	<div class="step-light-left">Periksa Ketersediaan</a></div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">2</div>
	<div class="step-light-left">Hasil Ketersediaan</div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">3</div>
	<div class="step-dark-left">Pendaftaran</div>
	<div class="step-dark-right">&nbsp;</div>
	<div class="step-no-off">4</div>
	<div class="step-light-left">Selesai</div>
	<div class="step-light-round">&nbsp;</div>
	<div class="clear"></div>
</div>
<!--  end step-holder -->

<div class="center">
	<table border="0" width="100%" class="info_shape" cellpadding="10">
		<tr>
			<td colspan="2">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Form Pendaftaran / Pembuatan Akun Kamilah Wisata</span>
				</div>
				<div class="repeat_hline"></div>
			<td>
		</tr>
		<tr>
			<!-- LEFT SIDE -->
			<td valign="top">
				<div class="row">
					<? if(form_error('nama') != '') {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('nama'); ?></div></span>
					<? }?>
					<label class="col1">Nama (*)</label>
					<span class="col2">
						<input type="text" class="input_medium" title="Isikan nama anda" name="nama" value="<?php echo set_value('nama');?>"  />
					</span>
				</div>
				
				<div class="row">
					<? if(form_error('email') != '') {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('email'); ?></div></span>
					<? }?>
					<label class="col1">Email (*)</label>
					<span class="col2">
						<input type="text" class="input_medium" name="email" title="Isikan email anda" value="<?php echo set_value('email');?>"  />
					</span>
				</div>
				
				<div class="row">
					<? if(form_error('password') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('password'); ?></div></span>
					<? }?>
					<label class="col1">Password</label>
					<span class="col2">
						<input type="password" class="input_medium password" id="password" name="password" title="Isikan password yang akan anda gunakan. minimal 6 karakter"   />
					</span>
				</div>
				
				<div class="row">
					<? if(form_error('password_verification') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('password_verification'); ?></div></span>
					<? }?>
					<label class="col1" >Verifikasi Password</label>
					<span class="col2">
						<input type="password" class="input_medium" id="password_verification" name="password_verification" title="Isikan kembali password yang telah anda isikan pada field password"   /><label id="password_verification"></label>
					</span>
				</div>
				
				<div class="row">
					<? if(form_error('telp') != '') {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('telp'); ?></div></span>
					<? }?>
					<label class="col1">Telepon</label>
					<span class="col2">
						<input type="text" class="input_medium" name="telp" title="Isikan nomor telepon anda" value="<?php echo set_value('telp');?>"  />
					</span>
				</div>

				<div class="row">
					<? if(form_error('mobile') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('mobile'); ?></div></span>
					<? }?>
					<label class="col1">Mobile</label>
					<span class="col2">
						<input type="text" class="input_medium" name="mobile" title="Isikan nomor handphone anda" value="<?php echo set_value('mobile');?>"  />
					</span>
				</div>
			</td>
			
			<!-- RIGHT SIDE -->
			<td valign="top">
				<div class="row">
					<? if(form_error('province') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('province'); ?></div></span>
					<? }?>
					<label class="col1">Propinsi (*)</label>
					<span class="col2">
						<? $province = 0; if(set_value('province')!='') $province = set_value('province');
								echo form_dropdown('province', $province_options, $province,'id="province" class="chzn-select" title="Pilih provinsi tempat tinggal anda"'); ?>
								
					</span>
				</div>

				<div class="row">
					<? if(form_error('kota') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('kota'); ?></div></span>
					<? }?>
					<label class="col1">Kota (*)</label>
					<span class="col2">
						<? $kota = 0; if(set_value('kota')!='') $kota = set_value('kota');
								echo form_dropdown('kota', $kota_options, $kota,'id="kota" class="chzn-select" data-allows-new-values="true" title="Pilih kota tempat tinggal anda"'); ?>
								
					</span>
				</div>
				<div class="row">
					<? if(form_error('alamat') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('alamat'); ?></div></span>
					<? }?>
					<label class="col1">Alamat (*)</label>
					<span class="col2">
						<textarea title="Iskan alamat tempat tinggal anda" name="alamat" ><?php echo set_value('alamat');?></textarea>
					</span>
				</div>			
				
				<div class="row">
					<? if(form_error('id_card') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('id_card'); ?></div></span>
					<? }?>
					<label class="col1">No Identitas (*)</label>
					<span class="col2">
						<input type="text" class="input_medium" name="id_card" title="Isikan 10 digit nomor identitas anda (contoh: KTP)" value="<?php echo set_value('id_card');?>"  />
					</span>
				</div>
		
				<div class="row">
					<? if(form_error('captcha') != '' ) {?>
						<label class="col1"> &nbsp; </label>
						<span class="col2"><div class="error_validation"><?php echo form_error('captcha'); ?></div></span>
					<? }?>
					<label class="col1">Verifikasi</label>
				
					<span class="col2">
						<p><?php echo $captcha; ?></p>
					</span>
				</div>
				<div class="row">
				
					<label class="col1"></label>
					<span class="col2">
						<input type="text" class="input_medium" name="captcha"  title="Isikan angka yang tertera pada gambar"  />
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="row">
					<label class="col1">&nbsp;</label>
					<span class="col2">
						<input type="submit" value="Daftar" class="submit_button" />
						<input type="reset" value="Reset" class="reset_button" onclick="reload()"/>
					</span>
				</div>
			</td>
		</tr>
	</table>











	<!-- LEFT SIDE -->
	<div class="content_left">
		<?php echo form_open('registration/do_register',array('id' => 'myform', 'class' => 'myform')); ?>
			<div class="row">
				<? if(form_error('nama') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('nama'); ?></div></span>
				<? }?>
				<label class="col1">Nama (*)</label>
				<span class="col2">
					<input type="text" class="input_medium" title="Isikan nama anda" name="nama" value="<?php echo set_value('nama');?>"  />
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('email') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('email'); ?></div></span>
				<? }?>
				<label class="col1">Email (*)</label>
				<span class="col2">
					<input type="text" class="input_medium" name="email" title="Isikan email anda" value="<?php echo set_value('email');?>"  />
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('password') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('password'); ?></div></span>
				<? }?>
				<label class="col1">Password</label>
				<span class="col2">
					<input type="password" class="input_medium password" id="password" name="password" title="Isikan password yang akan anda gunakan. minimal 6 karakter"   />
				</span>
			</div>			
			<br />
			<div class="row">
				<? if(form_error('password_verification') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('password_verification'); ?></div></span>
				<? }?>
				<label class="col1" >Verifikasi Password</label>
				<span class="col2">
					<input type="password" class="input_medium" id="password_verification" name="password_verification" title="Isikan kembali password yang telah anda isikan pada field password"   /><label id="password_verification"></label>
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('telp') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('telp'); ?></div></span>
				<? }?>
				<label class="col1">Telepon</label>
				<span class="col2">
					<input type="text" class="input_medium" name="telp" title="Isikan nomor telepon anda" value="<?php echo set_value('telp');?>"  />
				</span>
			</div>

			<div class="row">
				<? if(form_error('mobile') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('mobile'); ?></div></span>
				<? }?>
				<label class="col1">Mobile</label>
				<span class="col2">
					<input type="text" class="input_medium" name="mobile" title="Isikan nomor handphone anda" value="<?php echo set_value('mobile');?>"  />
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('province') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('province'); ?></div></span>
				<? }?>
				<label class="col1">Propinsi (*)</label>
				<span class="col2">
					<? $province = 0; if(set_value('province')!='') $province = set_value('province');
							echo form_dropdown('province', $province_options, $province,'id="province" class="chzn-select" title="Pilih provinsi tempat tinggal anda"'); ?>
							
				</span>
			</div>

			<div class="row">
				<? if(form_error('kota') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('kota'); ?></div></span>
				<? }?>
				<label class="col1">Kota (*)</label>
				<span class="col2">
					<? $kota = 0; if(set_value('kota')!='') $kota = set_value('kota');
							echo form_dropdown('kota', $kota_options, $kota,'id="kota" class="chzn-select" data-allows-new-values="true" title="Pilih kota tempat tinggal anda"'); ?>
							
				</span>
			</div>
			<div class="row">
				<? if(form_error('alamat') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('alamat'); ?></div></span>
				<? }?>
				<label class="col1">Alamat (*)</label>
				<span class="col2">
					<textarea title="Iskan alamat tempat tinggal anda" name="alamat" ><?php echo set_value('alamat');?></textarea>
				</span>
			</div>			
			
			<div class="row">
				<? if(form_error('id_card') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('id_card'); ?></div></span>
				<? }?>
				<label class="col1">No Identitas (*)</label>
				<span class="col2">
					<input type="text" class="input_medium" name="id_card" title="Isikan 10 digit nomor identitas anda (contoh: KTP)" value="<?php echo set_value('id_card');?>"  />
				</span>
			</div>
	
			<div class="row">
				<? if(form_error('captcha') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('captcha'); ?></div></span>
				<? }?>
				<label class="col1">Verifikasi</label>
			
				<span class="col2">
					<p><?php echo $captcha; ?></p>
				</span>
			</div>
			<div class="row">
			
				<label class="col1"></label>
				<span class="col2">
					<input type="text" class="input_medium" name="captcha"  title="Isikan angka yang tertera pada gambar"  />
				</span>
			</div>

			<? if (isset($waiting)) {?>
				<input type="hidden" name="waiting" value="<? echo $waiting; ?>" />
				<div style="display: none;" >
					<input type="text" name="group" value="<?php echo $group; ?>" />
					<input type="text" name="program" value="<?php echo $program; ?>" />
					<input type="text" name="jml_adult" value="<?php echo $jml_adult; ?>" />
					<input type="text" name="with_bed" value="<?php echo $with_bed; ?>" />
					<input type="text" name="no_bed" value="<?php echo $no_bed; ?>" />
					<input type="text" name="infant" value="<?php echo $infant; ?>" />
							
					<? $no=0; foreach($room_choice2 as $row) {?>
							<input name="kamar[]" id="kamar<? echo $no;?>" value="<? echo $row['ID_ROOM_TYPE']; ?>" />
							<input name="jml_kamar[]" id="jml_kamar<? echo $no;?>" value="<? echo $row['JUMLAH'];?>" />
					<? $no++; }?>
				</div>
			<? }?>
		
			<div class="row">
				<label class="col1">&nbsp;</label>
				<span class="col2">
					<input type="submit" value="Daftar" class="submit_button" />
					<input type="reset" value="Reset" class="reset_button" onclick="reload()"/>
				</span>
			</div>
		<? echo form_close(); ?>
	
	</div>
	<!-- END LEFT SIDE -->
	
	<!-- RIGHT SIDE -->
	
	<!-- END RIGHT SIDE -->
</div>

<script>

	$("#province").bind('change',function get_group() 
	{	
		var prp = $("#province").val();
		
                $.ajax({
                        url: "<?=base_url();?>index.php/province/get_ajax_kota/",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_province="+ prp, //the name of the $_POST variable and its value
                        success: function (response) {
							 var bahan = response;
							 
                             //document.getElementById('info_jd').innerHTML = pecah[0];
							 document.getElementById('kota').innerHTML = response;
							 }
                });
				$("#kota").trigger("liszt:updated");
				return false;
	}
	);
	
	
	
</script>