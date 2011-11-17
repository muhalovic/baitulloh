<form method="post" action="" >
	
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				
				<tr>
					<? form_error('kode_group') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kode Grup (*)</th>
					<td><input type="text" name="kode_group" value="<?php echo $KODE_GROUP;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('kode_group') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kode_group'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? form_error('pagu_sv') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Pagu Saudi Arabia Airlines (*)</th>
					<td><input type="text" name="pagu_sv" value="<?php echo $PAGU_SV;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('pagu_sv') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('pagu_sv'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('pagu_ga') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Pagu Garuda Indonesia Airlines (*)</th>
					<td><input type="text" name="pagu_ga" value="<?php echo $PAGU_GA;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('pagu_ga') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('pagu_ga'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<? form_error('hari') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Jumlah Hari (*)</th>
					<td><input type="text" name="hari" value="<?php echo $HARI;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('hari') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('hari'); ?></div>
						<? }?>
					</td>
				</tr>		
				
								<tr>
					<? form_error('keterangan') == '' ? $class = 'form-textarea':$class = 'form-textarea'; ?>
					<th valign="top">Keterangan (*)</th>
					<td><textarea name="keterangan" class="<? echo $class;?>"><?php echo $KETERANGAN;?></textarea></td>
					<td>
						<? if(form_error('keterangan') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('keterangan'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
				<? form_error('kode_group') == '' ? $class = 'inp-form-small':$class = 'inp-form-small-error'; ?>
					<th valign="top">Kamar</th>
					
					<td>
					<?php
                        // if(!is_array($TGL_LHR) ){
                        // $tanggal = explode('-', $TGL_LHR);

                        // }
                        // else{
                            // $tanggal=$TGL_LHR;
                        // }
                        ?>

                        
                          
                 

					<input id="date-pick" type="text" name="infant" value="<?php echo set_value('infant');?>" class="<? ;?>" /> - 
					<input type="text" name="infant" maxlength="2" value="<?php echo set_value('infant');?>" class="<? echo $class;?>" /> -
					<input type="text" name="infant" maxlength="4" value="<?php echo set_value('infant');?>" class="<? echo $class;?>" />
					</td>
									
								
					<td>
						<? if(form_error('kode_group') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kode_group'); ?></div>
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

