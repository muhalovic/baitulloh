<? 
echo form_open_multipart('/paspor/do_edit');
echo $notifikasi;
echo $error_file;
?>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
            	<? if($tipe == 0) { ?>
				<tr>
					<? form_error('nama_paspor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama Sesuai <br />Passpor (*)</th>
					<td><input type="text" name="nama_paspor" value="<?php echo set_value('nama_paspor');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_paspor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_paspor'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('no_paspor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">No Paspor (*)</th>
					<td><input type="text" name="no_paspor" value="<?php echo set_value('no_paspor');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('no_paspor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('no_paspor'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Dikeluarkan (*)</th>
					<td><? 
							// list tgl
							$k_tgl_lahirs = 0; if(set_value('k_tgl_lahir')!='') $k_tgl_lahirs = set_value('k_tgl_lahir');
							
							$k_list_tgl['0'] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$k_list_tgl[$i] = $i;
							}
							echo form_dropdown('k_tgl_lahir', $k_list_tgl, $k_tgl_lahirs,'id="k_tgl_lahir" class="styledselect-day"'); 
							
							// list bln
							$k_bln_lahirs = 0; if(set_value('k_bln_lahir')!='') $k_bln_lahirs = set_value('k_bln_lahir');
							
							$k_list_bln['0'] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$k_list_bln[$i] = $i;
							}
							echo form_dropdown('k_bln_lahir', $k_list_bln, $k_bln_lahirs,'id="k_bln_lahir" class="styledselect-day"');
							
							//list tahun
							$k_thn_lahirs = 0; if(set_value('k_thn_lahir')!='') $k_thn_lahirs = set_value('k_thn_lahir');
							
							$k_list_thn['0'] = "Thn";
							for($i=2006;$i<=date("Y");$i++)
							{
								$k_list_thn[$i] = $i;
							}
							echo form_dropdown('k_thn_lahir', $k_list_thn, $k_thn_lahirs,'id="k_thn_lahir" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('k_tgl_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('k_tgl_lahir'); ?></div>
						<? } elseif(form_error('k_bln_lahir') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('k_bln_lahir'); ?></div>
						<? }elseif(form_error('k_thn_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('k_thn_lahir'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Berakhir (*)</th>
					<td><? 
							// list tgl
							$b_tgl_lahirs = 0; if(set_value('b_tgl_lahir')!='') $b_tgl_lahirs = set_value('b_tgl_lahir');
							
							$b_list_tgl['0'] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$b_list_tgl[$i] = $i;
							}
							echo form_dropdown('b_tgl_lahir', $b_list_tgl, $b_tgl_lahirs,'id="b_tgl_lahir" class="styledselect-day"'); 
							
							// list bln
							$b_bln_lahirs = 0; if(set_value('b_bln_lahir')!='') $b_bln_lahirs = set_value('b_bln_lahir');
							
							$b_list_bln['0'] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$b_list_bln[$i] = $i;
							}
							echo form_dropdown('b_bln_lahir', $b_list_bln, $b_bln_lahirs,'id="b_bln_lahir" class="styledselect-day"');
							
							//list tahun
							$b_thn_lahirs = 0; if(set_value('b_thn_lahir')!='') $b_thn_lahirs = set_value('b_thn_lahir');
							
							$b_list_thn['0'] = "Thn";
							for($i=(date("Y")-1);$i<=(date("Y")+5);$i++)
							{
								$b_list_thn[$i] = $i;
							}
							echo form_dropdown('b_thn_lahir', $b_list_thn, $b_thn_lahirs,'id="b_thn_lahir" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('b_tgl_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('b_tgl_lahir'); ?></div>
						<? } elseif(form_error('b_bln_lahir') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('b_bln_lahir'); ?></div>
						<? }elseif(form_error('b_thn_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('b_thn_lahir'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('kantor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kantor yg Mengeluarkan</th>
					<td><input type="text" name="kantor" value="<?php echo set_value('kantor');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('kantor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kantor'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
                	<? form_error('foto') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th>Scan Paspor (*)</th>
					<td><input type="file" class="file_1" name="foto" value="<? echo set_value('foto') ?>" /></td>
					<td>
						<? if(form_error('foto') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('foto'); ?></div>
						<? } else { ?>
                        <div class="bubble-left"></div>
						<div class="bubble-inner">JPEG, PNG 5MB max per image</div>
						<div class="bubble-right"></div>
                        <? } ?>
					</td>
				</tr>
                
                <? } elseif($tipe == 1) { ?>
                
                <tr>
					<? form_error('nama_paspor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama Sesuai <br /> Passpor (*)</th>
					<td><input type="text" name="nama_paspor" value="<?php echo $e_nama_paspor; ?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_paspor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_paspor'); ?></div>
						<? }?>
					</td>
				</tr>
                <tr>
					<? form_error('no_paspor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">No Paspor (*)</th>
					<td><input type="text" name="no_paspor" value="<?php echo $e_no_paspor; ?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('no_paspor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('no_paspor'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Dikeluarkan (*)</th>
					<td><? 
							// list tgl
							$k_tgl_lahirs = $e_k_tgl; if(set_value('k_tgl_lahir')!='') $k_tgl_lahirs = set_value('k_tgl_lahir');
							
							$k_list_tgl['0'] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$k_list_tgl[$i] = $i;
							}
							echo form_dropdown('k_tgl_lahir', $k_list_tgl, $k_tgl_lahirs,'id="k_tgl_lahir" class="styledselect-day"'); 
							
							// list bln
							$k_bln_lahirs = $e_k_bln; if(set_value('k_bln_lahir')!='') $k_bln_lahirs = set_value('k_bln_lahir');
							
							$k_list_bln['0'] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$k_list_bln[$i] = $i;
							}
							echo form_dropdown('k_bln_lahir', $k_list_bln, $k_bln_lahirs,'id="k_bln_lahir" class="styledselect-day"');
							
							//list tahun
							$k_thn_lahirs = $e_k_thn; if(set_value('k_thn_lahir')!='') $k_thn_lahirs = set_value('k_thn_lahir');
							
							$k_list_thn['0'] = "Thn";
							for($i=2006;$i<=date("Y");$i++)
							{
								$k_list_thn[$i] = $i;
							}
							echo form_dropdown('k_thn_lahir', $k_list_thn, $k_thn_lahirs,'id="k_thn_lahir" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('k_tgl_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('k_tgl_lahir'); ?></div>
						<? } elseif(form_error('k_bln_lahir') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('k_bln_lahir'); ?></div>
						<? }elseif(form_error('k_thn_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('k_thn_lahir'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Berakhir (*)</th>
					<td><? 
							// list tgl
							$b_tgl_lahirs = $e_b_tgl; if(set_value('b_tgl_lahir')!='') $b_tgl_lahirs = set_value('b_tgl_lahir');
							
							$b_list_tgl['0'] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$b_list_tgl[$i] = $i;
							}
							echo form_dropdown('b_tgl_lahir', $b_list_tgl, $b_tgl_lahirs,'id="b_tgl_lahir" class="styledselect-day"'); 
							
							// list bln
							$b_bln_lahirs = $e_b_bln; if(set_value('b_bln_lahir')!='') $b_bln_lahirs = set_value('b_bln_lahir');
							
							$b_list_bln['0'] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$b_list_bln[$i] = $i;
							}
							echo form_dropdown('b_bln_lahir', $b_list_bln, $b_bln_lahirs,'id="b_bln_lahir" class="styledselect-day"');
							
							//list tahun
							$b_thn_lahirs = $e_b_thn; if(set_value('b_thn_lahir')!='') $b_thn_lahirs = set_value('b_thn_lahir');
							
							$b_list_thn['0'] = "Thn";
							for($i=(date("Y")-1);$i<=(date("Y")+5);$i++)
							{
								$b_list_thn[$i] = $i;
							}
							echo form_dropdown('b_thn_lahir', $b_list_thn, $b_thn_lahirs,'id="b_thn_lahir" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('b_tgl_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('b_tgl_lahir'); ?></div>
						<? } elseif(form_error('b_bln_lahir') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('b_bln_lahir'); ?></div>
						<? }elseif(form_error('b_thn_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('b_thn_lahir'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('kantor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kantor yg Mengeluarkan</th>
					<td><input type="text" name="kantor" value="<?php echo $e_kantor; ?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('kantor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kantor'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
                	<? form_error('foto') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th>Scan Paspor (*)</th>
					<td>
                      <input type="file" class="file_1" name="foto" value="<? echo set_value('foto') ?>" />
                      <input type="hidden" name="paspor_edit" value="<? echo $e_scan_paspor; ?>" />
                    </td>
					<td>
						<? if(form_error('foto') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('foto'); ?></div>
						<? } else { ?>
                        <div class="bubble-left"></div>
						<div class="bubble-inner">JPEG, PNG 5MB max per image</div>
						<div class="bubble-right"></div>
                        <? } ?>
					</td>
				</tr>
                
                <? } else { redirect(site_url()."/paspor"); } ?>
				<tr height="70">
					<th></th>
					<td valign="bottom">
                    	<input type="hidden" value="<? echo $e_id_candidate ?>" name="id_candidate" />
                    	<input type="hidden" value="<? echo $e_id_account ?>" name="id_account" />
                    	<input type="hidden" value="<? echo $tipe ?>" name="id_tipe" />
						<input type="submit" value="" class="form-submit" />
						<input type="reset" value="" class="form-reset"  />
					</td>
					<td></td>
				</tr>
                <tr>
                    <td colspan="3"><img src="<?=base_url()?>images/shared/blank.gif" width="480" height="1" alt="blank" /></td>
                </tr>
			</table>
			<!-- end id-form  -->
		</td>
		
		<td>
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
                <tr height="40">
					<td colspan="3">
                    <div class="div_info_1"><strong>INFORMASI PENGUMPULAN BERKAS FISIK / DOKUMEN ASLI KE KANTOR KAMILAH</strong></div>
                     <div class="div_info_2">- Dokumen Asli harus diserahkan 3 minggu sebelum keberangkatan</div> 
                     <div class="div_info_1">- Pasport asli minimal 6 bulan masa berlaku dengan 3 suku kata. (contoh: Toni Budi bin Ahmad)</div> 
                     <div class="div_info_2">- Buku Nikah asli bagi Suami / Istri</div> 
                     <div class="div_info_1">- Kartu Keluarga asli bagi Keluarga dengan Istri dan Anak-Anaknya</div> 
                     <div class="div_info_2">- Akte Lahir asli bagi yang membawa Anak-Anak</div> 
                     <div class="div_info_1">- KTP/KK/Buku Nikah asli bagi yang sudah berusia 45th ke atas</div> 
                     <div class="div_info_2">- copy KTP, copy Akte Lahir, copy Kartu Keluarga</div> 
                     <div class="div_info_1">- Akte Lahir Asli, copy KTP, copy KK bagi Wanita yang berangkat tanpa Muhrimnya</div> 
                     <div class="div_info_2">- Buku Kuning / Buku Maningtis</div> 
                     <div class="div_info_1">- Foto Ukuran 3x4 (10 lembar) , 4x6 (5 lembar)</div> 
                     </td>
				</tr>
			</table>
			<!-- end id-form  -->
		</td>
	</tr>
</table>
<? echo form_close(); ?>
<div class="clear"></div>


<script type="text/javascript">

function jasaPaspor(input)
{
	if(input.checked)
	{
		document.getElementById('jasa_paspor_nama').value='';
		document.getElementById('jasa_paspor_nama').disabled=false;
	} else {
		document.getElementById('jasa_paspor_nama').value='';
		document.getElementById('jasa_paspor_nama').disabled=true;
	}
}

</script>