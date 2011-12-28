<?
echo form_open($action);
?>
	
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">				
				
				<tr>
					<?php form_error('nama') == '' ? $class = 'inp-form':$class = 'inp-form-error';
                    ($is_edit) ? $value = $e_nama : $value = set_value('nama'); ?>
					<th valign="top">Nama Lengkap (*)</th>
					<td><input type="text" name="nama" value="<?php echo $value;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<?php form_error('username') == '' ? $class = 'inp-form':$class = 'inp-form-error';
                    ($is_edit) ? $value = $e_username : $value = set_value('username'); ?>
					<th valign="top">Username (*)</th>
					<td><input type="text" name="username" value="<?php echo $value;?>" class="<? echo $class;?>" <?php if($is_edit) echo 'readonly="readonly"'; ?> /></td>
					<td>
						<? if(form_error('username') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('username'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<?php form_error('pass') == '' ? $class = 'inp-form':$class = 'inp-form-error';
                    ($is_edit) ? $value = $e_pass : $value = set_value('pass'); ?>
					<th valign="top">Password (*)</th>
					<td><input type="password" name="pass" value="<?php echo $value;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('pass') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('pass'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<?php form_error('passconf') == '' ? $class = 'inp-form':$class = 'inp-form-error';
                    ($is_edit) ? $value = $e_pass : $value = set_value('passconf'); ?>
					<th valign="top">Konfirmasi Password (*)</th>
					<td><input type="password" name="passconf" value="<?php echo $value;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('passconf') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('passconf'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<th valign="top">Regional (*)</th>
					<td>	
						<?php ($is_edit) ? $value = $e_regional : $value = set_value('regional');
							echo form_dropdown('regional', $opsi_kota, $value,'id="regional" class="chzn-select inp-form-error"'); ?>
					</td>
					<td>
						<? if(form_error('regional') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('regional'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<?php form_error('ket') == '' ? $class = 'form-textarea':$class = 'form-textarea'; 
					($is_edit) ? $value = $e_ket : $value = set_value('ket'); ?>
					<th valign="top">Keterangan </th>
					<td><textarea name="ket" class="<? echo $class;?>"><?php echo $value;?></textarea></td>
					<td>
						<? if(form_error('ket') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('ket'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<th valign="top">Role (*)</th>
					<td>	
						<?php ($is_edit) ? $value = $e_role : $value = set_value('role');
							echo form_dropdown('role', $opsi_role, $value,'id="role" class="chzn-select"'); ?>
					</td>
					<td>
						<? if(form_error('role') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('role'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr height="50">
					<th>&nbsp;</th>
					<td valign="bottom">
						<input type="submit" value="" class="form-submit" />&nbsp;
						<input type="reset" value="" class="form-reset" />&nbsp;&nbsp;&nbsp;
						<div>&nbsp;&nbsp;&nbsp;<img src="<?php echo base_url().'images/flexigrid/close.png' ?>" alt="" style="cursor:pointer" onClick="window.location = '<?php echo site_url('admin/userman'); ?>'" /></div>
						<input type="hidden" name="is_edit" value="<?php echo $is_edit; ?>" class="form-submit" />
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

