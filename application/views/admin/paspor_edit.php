

<? 
echo form_open_multipart('/admin/data_jamaah/paspor_edit_db');
echo $notifikasi;
echo $error_file;
?>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<td></td>
                    <td colspan="2">
                       <?
					   $file_gambar = './images/upload/foto/'.$e_pas_foto;
					   if(is_file($file_gambar))
                       { 
					   		$url_gambar = base_url().'images/upload/foto/'.$e_pas_foto;
							$url_gambar2 = $url_gambar;
					   }else{
							$url_gambar = base_url().'images/shared/user_x.png'; 
							$url_gambar2 = "#";
					   }
					   
					   ?>
                      <div class="thumb">
                       <a href="<? echo $url_gambar2; ?>" title="Klik untuk memperbesar foto">
                        <img src="<? echo $url_gambar; ?>" height="120" width="100" border="2" />
                       </a>
                       </div>
                       <?					   
					   $file_paspor = './images/upload/paspor/'.$e_scan_paspor;
					   if(is_file($file_paspor))
                       { 
					   		$url_paspor = base_url().'images/upload/paspor/'.$e_scan_paspor;
							$url_paspor2 = $url_paspor;
					   }else{
							$url_paspor = base_url().'images/shared/book_x.png'; 
							$url_paspor2 = "#";
					   }
					   
					   ?>
                       <div class="thumb">
                       <a href="<? echo $url_paspor2; ?>" title="Klik untuk memperbesar paspor">
                        <img src="<? echo $url_paspor; ?>" height="120" width="100" border="2" />
                       </a>
                      </div>
                   <input type="hidden" name="foto_edit" value="<? echo $e_pas_foto; ?>" />
				</tr>
				<tr>
					<th valign="top">Nama Lengkap</th>
					<td>: <?php echo $e_nama_lengkap; ?></td>
					<td></td>
                </tr>
				<tr>
					<th valign="top">Jenis Kelamin</th>
					<td>: <?php echo $e_gender;?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Tanggal Lahir</th>
					<td>: <? echo $e_tempat_lahir.", ".$tgl_lahir; ?> </td>
					<td></td>
				</tr>
				<? if($tipe == 0) { ?>
				<tr>
					<? form_error('nama_paspor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama pada Paspor</th>
					<td><input type="text" name="nama_paspor" value="<?php echo set_value('nama_paspor'); ?>" class="<? echo $class;?>" /></td>
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
					<td>
						<input type="text" class="datepicker" name="tgl_keluar" value="<?php echo $this->input->post('tgl_keluar') ; ?>">
                    </td>
					<td>
						<? if(form_error('tgl_keluar') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_keluar'); ?></div>
						<? } ?> 
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Berakhir (*)</th>
					<td>
						<input type="text" class="datepicker" name="tgl_berakhir" value="<?php echo $this->input->post('tgl_berakhir') ; ?>">
                    </td>
					<td>
						<? if(form_error('tgl_berakhir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_berakhir'); ?></div>
						<? } ?> 
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
					<th valign="top">Nama pada Paspor</th>
					<td><input type="text" name="nama_paspor" value="<?php if(isset($_POST['nama_paspor'])){echo set_value('nama_paspor');} else{ echo $e_nama_paspor;} ?>" class="<? echo $class;?>" /></td>
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
					<td><input type="text" name="no_paspor" value="<?php if(isset($_POST['no_paspor'])){echo set_value('no_paspor');} else{ echo $e_no_paspor;} ?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('no_paspor') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('no_paspor'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Dikeluarkan (*)</th>
					<td>
						<input type="text" class="datepicker" name="tgl_keluar" value="<?php if(isset($_POST['tgl_keluar'])){echo set_value('tgl_keluar');}else{ echo $e_tgl_keluar;} ; ?>">
                    </td>
					<td>
						<? if(form_error('tgl_keluar') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_keluar'); ?></div>
						<? } ?> 
					</td>
				</tr>
				<tr>
					<th valign="top">Tgl. Berakhir (*)</th>
					<td>
						<input type="text" class="datepicker" name="tgl_berakhir" value="<?php if(isset($_POST['tgl_berakhir'])){echo set_value('tgl_berakhir');}else{ echo $e_tgl_berakhir;} ; ?>">
                    </td>
					<td>
						<? if(form_error('tgl_berakhir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_berakhir'); ?></div>
						<? } ?> 
					</td>
				</tr>
				<tr>
					<? form_error('kantor') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kantor yg Mengeluarkan</th>
					<td><input type="text" name="kantor" value="<?php if(isset($_POST['kantor'])){echo set_value('kantor');} else{ echo $e_kantor;}?>" class="<? echo $class;?>" /></td>
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
                
			</table>
			<!-- end id-form  -->
		</td>
		
		<td>
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
            	<tr>
                    <td colspan="3"><img src="<?=base_url()?>images/shared/blank.gif" width="480" height="1" alt="blank" /></td>
                </tr>
                <tr height="40">
					<td colspan="2">
                    <div id="" style="border:1px #e0e0e0 solid; background:#f5f5f5; color:#707070; padding:6px 17px 6px 17px;margin-bottom:2px;"><strong>INFORMASI PENGUMPULAN BERKAS FISIK / DOKUMEN ASLI KE KANTOR KAMILAH</strong></div>
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Dokumen Asli harus diserahkan 3 minggu sebelum keberangkatan</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#f5f9fc; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Pasport asli minimal 6 bulan masa berlaku dengan 3 suku kata. (contoh: Toni Budi bin Ahmad)</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Buku Nikah asli bagi Suami / Istri</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#f5f9fc; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Kartu Keluarga asli bagi Keluarga dengan Istri dan Anak-Anaknya</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Akte Lahir asli bagi yang membawa Anak-Anak</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#f5f9fc; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- KTP/KK/Buku Nikah asli bagi yang sudah berusia 45th ke atas</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- copy KTP, copy Akte Lahir, copy Kartu Keluarga</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#f5f9fc; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Akte Lahir Asli, copy KTP, copy KK bagi Wanita yang berangkat tanpa Muhrimnya</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Buku Kuning / Buku Maningtis</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#f5f9fc; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Foto Ukuran 3x4 (10 lembar) , 4x6 (5 lembar)</div> 
                     </td>
                     <td><td>
				</tr>
			</table>
			<!-- end id-form  -->
		</td>
	</tr>
</table>
<? echo form_close(); ?>
<div class="clear"></div>

<script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "d-m-yy",
			showOn: "button",
			buttonImage: "<?php echo base_url()?>/images/front/calendar.png",
			buttonImageOnly: true
		});
	});
</script>
