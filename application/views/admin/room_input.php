<?
if(isset($e_id_room_ava)) { $id_room_ava = $e_id_room_ava; } else { $id_room_ava = NULL; }
echo form_open('/admin/master_room/do_send/'.$id_room_ava);
echo $notifikasi;
?>
	
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">				
				<tr>
					<th valign="top">Group (*)</th>
					<td>	
						<? $group = $e_group; if(set_value('group')!='') $group = set_value('group');
							echo form_dropdown('group', $group_options, $group,'id="group" class="styledselect_form_1"'); ?>
					</td>
					<td>
						<? if(form_error('group') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('group'); ?></div>
						<? }?>
					</td>
				</tr>	
				<tr>
					<th valign="top">Program (*)</th>
					<td>	
						<? $program = $e_program; if(set_value('program')!='') $program = set_value('program');
							echo form_dropdown('program', $program_options, $program,'id="program" class="styledselect_form_1"'); ?>
					</td>
					<td>
						<? if(form_error('program') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('program'); ?></div>
						<? }?>
					</td>
				</tr>			
				<tr>
					<th valign="top">Tipe Kamar (*)</th>
					<td>	
						<? $tipe_kamar = $e_tipe_kamar; if(set_value('tipe_kamar')!='') $tipe_kamar = set_value('tipe_kamar');
							echo form_dropdown('tipe_kamar', $room_type_options, $tipe_kamar,'id="tipe_kamar" class="styledselect_form_1"'); ?>
					</td>
					<td>
						<? if(form_error('tipe_kamar') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tipe_kamar'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('harga') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
                    <? $e_harga == NULL ? $set_harga = set_value('harga') : $set_harga = $e_harga; ?>
					<th valign="top">Harga (*)</th>
					<td><input type="text" name="harga" value="<?php echo $set_harga;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('harga') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('harga'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('jumlah') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
                    <? $e_jumlah == NULL ? $set_jumlah = set_value('jumlah') : $set_jumlah = $e_jumlah; ?>
					<th valign="top">Jumlah Kamar (*)</th>
					<td><input type="text" name="jumlah" value="<?php echo $set_jumlah;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('jumlah') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('jumlah'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr height="50">
					<th>&nbsp;</th>
					<td valign="bottom">
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

