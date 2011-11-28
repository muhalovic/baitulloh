
<form method="post" action="" >
	<?php if(isset($notification)) {?>
<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Kelas Program Berhasil <?php echo $type ?>.</td>
								<td class="green-right"><a class="close-green"><img src="<?php echo base_url().'images/table/icon_close_green.gif' ?>"   alt="" onclick="window.location = '<?php echo base_url().'index.php/admin/master_program_class/' ?>'" /></a></td>
							</tr>
						</table><br>
					</div>
<?php }?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				
				<tr>
					<? form_error('nama_program') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama Program (*)</th>
					<td><input type="text" name="nama_program" value="<?php echo $NAMA_PROGRAM;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_program') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_program'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? form_error('keterangan') == '' ? $class = 'form-textarea':$class = 'form-textarea'; ?>
					<th valign="top">Keterangan </th>
					<td><textarea name="keterangan" class="<? echo $class;?>"><?php echo $KETERANGAN;?></textarea></td>
					<td>
						<? if(form_error('keterangan') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('keterangan'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? 
					form_error('hotel_makkah') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Hotel Makkah (*)</th>
					<td><input type="text" name="hotel_makkah" value="<?php echo $HOTEL_MAKKAH;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('hotel_makkah') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('hotel_makkah'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? form_error('hotel_madinah') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Hotel Madinah (*)</th>
					<td><input type="text" name="hotel_madinah" value="<?php echo $HOTEL_MADINAH;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('hotel_madinah') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('hotel_madinah'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? form_error('maskapai') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Maskapai (*)</th>
					<td><input type="text" name="maskapai" value="<?php echo $MASKAPAI;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('maskapai') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('maskapai'); ?></div>
						<? }?>
					</td>
				</tr>		
				
				<tr>
					<? form_error('transportasi') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Transportasi (*)</th>
					<td><input type="text" name="transportasi" value="<?php echo $TRANSPORTASI;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('transportasi') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('transportasi'); ?></div>
						<? }?>
					</td>
				</tr>		
					

				<tr>
					<? //form_error('status') == '' ? $class = 'form-textarea':$class = 'form-textarea'; ?>
					<th valign="top">Status (*)</th>
					<td>
						
					    <input type="radio" value=1 name="status" <?php if( $STATUS == 1){echo 'checked';}?> class="<? //echo $class;?>" /> aktif &nbsp;&nbsp;&nbsp;
					    <input type="radio" value=0 name="status" <?php if( $STATUS == 0){echo 'checked';}?> class="<? //echo $class;?>" /> tidak aktif</td>
					
					<td>
						<? if(form_error('status') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('status'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<td valign="top">
						<input type="submit" value="" class="form-submit" />
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

