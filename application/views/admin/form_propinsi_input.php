<?
if(isset($e_id_propinsi)) { $id_propinsi = $e_id_propinsi; } else { $id_propinsi = NULL; }
echo form_open('/admin/master_propinsi/do_send/'.$id_propinsi);
echo $notifikasi;
?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				
				<tr>
					<? form_error('nama_propinsi') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama Propinsi (*)</th>
					<td><input type="text" name="nama_propinsi" value="<?php echo $e_nama;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_propinsi') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_propinsi'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? form_error('nama_propinsi') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Status (*)</th>
					<td><input type="checkbox" name="status" value="1" <?=($e_status==1)?'checked="checked"':''?> /> &nbsp;&nbsp;<strong>Aktif</strong></td>
					<td></td>
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

