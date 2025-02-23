
<?php echo form_open('registration/do_register'); ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" onload="showRecaptcha('recaptcha_div');">
	<tr valign="top">
		<td>
			<!--  start step-holder -->
			<div id="step-holder">
				<div class="step-no-off">1</div>
				<div class="step-light-left">Cek Ketersediaan</a></div>
				<div class="step-light-right">&nbsp;</div>
				<div class="step-no-off">2</div>
				<div class="step-light-left">Hasil Pengecekan</div>
				<div class="step-light-right">&nbsp;</div>
				<div class="step-no">3</div>
				<div class="step-dark-left">Pendaftaran</div>
				<div class="step-dark-right">&nbsp;</div>
				<div class="step-no-off">4</div>
				<div class="step-light-left">Notifikasi</div>
				<div class="step-light-round">&nbsp;</div>
				<div class="clear"></div>
			</div>
			<!--  end step-holder -->
			
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<? form_error('nama') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama (*)</th>
					<td><input type="text" name="nama" value="<?php echo set_value('nama');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('email') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">E-mail (*)</th>
					<td><input type="text" name="email" class="<? echo $class;?>" value="<?php echo set_value('email'); ?>" /></td>
					<td>
						<? if(form_error('email') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('email'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('telp') == '' || form_error('mobile') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Telp / Mobile (*)</th>
					<td><input type="text" name="telp" value="<?php echo set_value('telp');?>" class="<? echo $class;?>" /> &nbsp; / &nbsp;<input type="text" name="mobile" value="<?php echo set_value('mobile');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('telp') != '' || form_error('mobile') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('telp').' '.form_error('mobile'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Propinsi (*)</th>
					<td>	
						<? $province = 0; if(set_value('province')!='') $province = set_value('province');
							echo form_dropdown('province', $province_options, $province,'id="province" class="chzn-select"'); ?>
					</td>
					<td>
						<? if(form_error('province') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('province'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('kota') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kota (*)</th>
					<td><input type="text" name="kota" value="<?php echo set_value('kota');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('kota') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kota'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('alamat') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Alamat (*)</th>
					<td><input type="text" name="alamat" value="<?php echo set_value('alamat');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('alamat') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('alamat'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('id_card') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">No ID Card (i.e. KTP *)</th>
					<td><input type="text" name="id_card" value="<?php echo set_value('id_card');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('id_card') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('id_card'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th>Kode Verifikasi (*)</th>
					<td>
						<script type="text/javascript"
						   src="http://www.google.com/recaptcha/api/challenge?k=6LcqPskSAAAAAMj9vcsmOtZDhk0JrkPLCNjJ-FVw">
						</script>
						<noscript>
						   <iframe src="http://www.google.com/recaptcha/api/noscript?k=6LcqPskSAAAAAMj9vcsmOtZDhk0JrkPLCNjJ-FVw"
							   height="300" width="500" frameborder="0"></iframe><br>
						   <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
						   <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
						</noscript>
					</td>
					<td>
						<? if(form_error('recaptcha_response_field') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('recaptcha_response_field'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td valign="top">
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
						<input type="submit" value="" class="form-submit" />
						<input type="reset" value="" class="form-reset"  />
					</td>
					<td></td>
				</tr>
			</table>
			<!-- end id-form  -->
		</td>
	</tr>
	<tr>
		<td><img src="<?php echo base_url();?>images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
		<td></td>
	</tr>
</table>
		 
<div class="clear"></div>

<? echo form_close(); ?>
		 