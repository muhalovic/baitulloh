
<form method="post" action="" >
	<?php if(isset($notification)) {?>
<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Ukuran Pakaian Berhasil <?php echo $type ?>.</td>
								<td class="green-right"><a class="close-green"><img src="<?php echo base_url().'images/table/icon_close_green.gif' ?>"   alt="" onclick="window.location = '<?php echo base_url().'index.php/admin/master_clothes_size/' ?>'" /></a></td>
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
					<? form_error('size') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Ukuran Pakaian (*)</th>
					<td><input type="text" name="size" value="<?php echo $SIZE;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('size') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('size'); ?></div>
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

