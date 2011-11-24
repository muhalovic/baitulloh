
<form method="post" action="" >
	<?php if(isset($notification)) {?>
<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Jenis Kamar Berhasil <?php echo $type ?>.</td>
								<td class="green-right"><a class="close-green"><img src="<?php echo base_url().'images/table/icon_close_green.gif' ?>"   alt="" onclick="window.location = '<?php echo base_url().'index.php/admin/master_room_type/' ?>'" /></a></td>
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
					<? form_error('jenis_kamar') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Jenis Kamar (*)</th>
					<td><input type="text" name="jenis_kamar" value="<?php echo $JENIS_KAMAR;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('jenis_kamar') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('jenis_kamar'); ?></div>
						<? }?>
					</td>
				</tr>
				
				
				<tr>
					<? 
					form_error('capacity') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kapasitas (*)</th>
					<td><input type="text" name="capacity" value="<?php echo $CAPACITY;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('capacity') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('capacity'); ?></div>
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

