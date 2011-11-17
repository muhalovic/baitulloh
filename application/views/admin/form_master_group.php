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
					<th valign="top">Tanggal Keberangkatan Jeddah (*)</th>
					<td><? 
							// list tgl
							
							if($TANGGAL_KEBERANGKATAN_JD!=""){
							$tanggal_keberangkatan_jd = explode('-',$TANGGAL_KEBERANGKATAN_JD);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_keberangkatan_jd', $list_tgl, $tanggal_keberangkatan_jd[2],'id="tgl_lahir" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_keberangkatan_jd', $list_bln, $tanggal_keberangkatan_jd[1],'id="bln_lahir" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_keberangkatan_jd', $list_thn, $tanggal_keberangkatan_jd[0],'id="thn_lahir" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_keberangkatan_jd') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_keberangkatan_jd'); ?></div>
						<? } elseif(form_error('bln_keberangkatan_jd') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('bln_keberangkatan_jd'); ?></div>
						<? }elseif(form_error('thn_keberangkatan_jd') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('thn_keberangkatan_jd'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Keberangkatan Mekkah (*)</th>
					<td><? 
							// list tgl
							
							if($TANGGAL_KEBERANGKATAN_MK!=""){
							$tanggal = explode('-',$TANGGAL_KEBERANGKATAN_MK);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_keberangkatan_mk', $list_tgl, $tanggal[2],'id="tgl" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_keberangkatan_mk', $list_bln, $tanggal[1],'id="bln" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_keberangkatan_mk', $list_thn, $tanggal[0],'id="thn" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_keberangkatan_mk') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_keberangkatan_mk'); ?></div>
						<? } elseif(form_error('bln_keberangkatan_mk') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('bln_keberangkatan_mk'); ?></div>
						<? }elseif(form_error('thn_keberangkatan_mk') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('thn_keberangkatan_mk'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Jatuh Tempo Paspor (*)</th>
					<td><? 
							// list tgl
							
							if($JATUH_TEMPO_PASPOR!=""){
							$tanggal_jatuh_tempo_paspor = explode('-',$JATUH_TEMPO_PASPOR);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_jatuh_tempo_paspor', $list_tgl, $tanggal_jatuh_tempo_paspor[2],'id="tgl" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_jatuh_tempo_paspor', $list_bln, $tanggal_jatuh_tempo_paspor[1],'id="bln" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_jatuh_tempo_paspor', $list_thn, $tanggal_jatuh_tempo_paspor[0],'id="thn" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_jatuh_tempo_paspor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_jatuh_tempo_paspor'); ?></div>
						<? } ?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Jatuh Tempo Uang Muka (*)</th>
					<td><? 
							// list tgl
							
							if($JATUH_TEMPO_UANG_MUKA!=""){
							$tanggal_jatuh_tempo_uang_muka = explode('-',$JATUH_TEMPO_UANG_MUKA);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_jatuh_tempo_uang_muka', $list_tgl, $tanggal_jatuh_tempo_uang_muka[2],'id="tgl" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_jatuh_tempo_uang_muka', $list_bln, $tanggal_jatuh_tempo_uang_muka[1],'id="bln" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_jatuh_tempo_uang_muka', $list_thn, $tanggal_jatuh_tempo_uang_muka[0],'id="thn" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_jatuh_tempo_uang_muka') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_jatuh_tempo_uang_muka'); ?></div>
						<? } ?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Jatuh Tempo Pelunasan (*)</th>
					<td><? 
							// list tgl
							
							if($JATUH_TEMPO_PELUNASAN!=""){
							$tanggal_jatuh_tempo_pelunasan = explode('-',$JATUH_TEMPO_PELUNASAN);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_jatuh_tempo_pelunasan', $list_tgl, $tanggal_jatuh_tempo_pelunasan[2],'id="tgl" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_jatuh_tempo_pelunasan', $list_bln, $tanggal_jatuh_tempo_pelunasan[1],'id="bln" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_jatuh_tempo_pelunasan', $list_thn, $tanggal_jatuh_tempo_pelunasan[0],'id="thn" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_jatuh_tempo_pelunasan') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_jatuh_tempo_pelunasan'); ?></div>
						<? } ?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Jatuh Tempo Berkas (*)</th>
					<td><? 
							// list tgl
							
							if($JATUH_TEMPO_BERKAS!=""){
							$tanggal_jatuh_tempo_berkas = explode('-',$JATUH_TEMPO_BERKAS);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_jatuh_tempo_berkas', $list_tgl, $tanggal_jatuh_tempo_berkas[2],'id="tgl" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_jatuh_tempo_berkas', $list_bln, $tanggal_jatuh_tempo_berkas[1],'id="bln" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_jatuh_tempo_berkas', $list_thn, $tanggal_jatuh_tempo_berkas[0],'id="thn" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_jatuh_tempo_berkas') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_jatuh_tempo_berkas'); ?></div>
						<? } ?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Batas Waiting List (*)</th>
					<td><? 
							// list tgl
							
							if($BATAS_WAITING_LIST!=""){
							$tanggal_batas_waiting_list = explode('-',$BATAS_WAITING_LIST);
							}							
							
							$list_tgl[''] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_batas_waiting_list', $list_tgl, $tanggal_batas_waiting_list[2],'id="tgl" class="styledselect-day"'); 
							
							
							$list_bln[''] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_batas_waiting_list', $list_bln, $tanggal_batas_waiting_list[1],'id="bln" class="styledselect-day"');
							
							
							
							$list_thn[''] = "Thn";
							for($i=2000;$i<=date("Y")+10;$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_batas_waiting_list', $list_thn, $tanggal_batas_waiting_list[0],'id="thn" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_batas_waiting_list') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_batas_waiting_list'); ?></div>
						<? } ?>
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

