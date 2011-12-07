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
	<!-- LEFT SIDE -->
	<div class="content_left">
		<?php echo form_open('registration/do_register'); ?>
			<div class="row">
				<? if(form_error('nama') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('nama'); ?></div></span>
				<? }?>
				<label class="col1">Nama (*)</label>
				<span class="col2">
					<input type="text" name="nama" value="<?php echo set_value('nama');?>"  />
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('email') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('email'); ?></div></span>
				<? }?>
				<label class="col1">Email (*)</label>
				<span class="col2">
					<input type="text" name="email" value="<?php echo set_value('email');?>"  />
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('telp') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('telp'); ?></div></span>
				<? }?>
				<label class="col1">Telepon</label>
				<span class="col2">
					<input type="text" name="telp" value="<?php echo set_value('telp');?>"  />
				</span>
			</div>

			<div class="row">
				<? if(form_error('mobile') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('mobile'); ?></div></span>
				<? }?>
				<label class="col1">Mobile</label>
				<span class="col2">
					<input type="text" name="mobile" value="<?php echo set_value('mobile');?>"  />
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
							echo form_dropdown('province', $province_options, $province,'id="province" class="chzn-select"'); ?>
							
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
							echo form_dropdown('kota', $kota_options, $kota,'id="kota" class="chzn-select" data-allows-new-values="true"'); ?>
							
				</span>
			</div>
			<div class="row">
				<? if(form_error('alamat') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('alamat'); ?></div></span>
				<? }?>
				<label class="col1">Alamat (*)</label>
				<span class="col2">
					<input type="text" name="alamat" value="<?php echo set_value('alamat');?>"  />
				</span>
			</div>			
			
			<div class="row">
				<? if(form_error('id_card') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('id_card'); ?></div></span>
				<? }?>
				<label class="col1">No ID Card (contoh KTP) (*)</label>
				<span class="col2">
					<input type="text" name="id_card" value="<?php echo set_value('id_card');?>"  />
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('captcha') != '' ) {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('captcha'); ?></div></span>
				<? }?>
				<label class="col1">Verifikasi</label>
			
				<span class="col2">
					<?php echo $captcha; ?>
				</span>
			</div>
			<div class="row">
			
				<label class="col1"></label>
				<span class="col2">
					<input type="text" name="captcha"   />
				</span>
			</div>


		
			<div class="row">
				<label class="col1">&nbsp;</label>
				<span class="col2">
					<input type="submit" value="Daftar" class="submit_button" />
					<input type="reset" value="Reset" class="reset_button" />
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