<? echo form_open_multipart('/admin/data_jamaah/do_daftar/'.$id_user.'/'.$kode_registrasi);

echo $error_file;?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<? form_error('nama_user') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama User</th>
					<td><input type="text" name="nama_user" readonly value="<?php echo $nama_user;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_user') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_user'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('email_user') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">E-mail User</th>
					<td><input type="text" name="email_user" readonly class="<? echo $class;?>" value="<?php echo $email_user; ?>" /></td>
					<td>
						<? if(form_error('email_user') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('email_user'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('telp_user') == '' || form_error('mobile_user') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Telp / Mobile User</th>
					<td><input type="text" name="telp_user" readonly value="<?php echo $telp_user;?>" class="<? echo $class;?>" /> &nbsp; / &nbsp;<input type="text" name="mobile_user" readonly value="<?php echo $mobile_user;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('telp_user') != '' || form_error('mobile_user') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('telp_user').' '.form_error('mobile_user'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('alamat_user') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Alamat User</th>
					<td><input type="text" name="alamat_user" readonly value="<?php echo $alamat_user;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('alamat_user') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('alamat_user'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('nama_lengkap') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama Lengkap (*)</th>
					<td><input type="text" name="nama_lengkap" value="<?php echo set_value('nama_lengkap');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_lengkap') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_lengkap'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('panggilan') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Panggilan</th>
					<td><input type="text" name="panggilan" value="<?php echo set_value('panggilan');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('panggilan') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('panggilan'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('gender') == '' ? $class = 'inp-form2':$class = 'inp-form-error2'; ?>
					<th valign="top">Jenis Kelamin (*)</th>
					<td><? $gender = 0; if(set_value('gender')!='') $gender = set_value('gender');
							$gender_options = array(
							  '0'  => '-- Jenis Kelamin --',
							  '1'  => 'Laki-Laki',
							  '2'  => 'Perempuan',
							);
							
							echo form_dropdown('gender', $gender_options, $gender,'id="gender" class="styledselect-biodata" onchange="SetMahram(this)" '); ?>
                        </td>
					<td>
						<? if(form_error('gender') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('gender'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('warga_negara') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Warga Negara (*)</th>
					<td><input type="text" name="warga_negara" value="<?php echo set_value('warga_negara');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('warga_negara') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('warga_negara'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('tempat_lahir') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Tempat Lahir (*)</th>
					<td><input type="text" name="tempat_lahir" value="<?php echo set_value('tempat_lahir');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('tempat_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tempat_lahir'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Tanggal Lahir (*)</th>
					<td><? 
							// list tgl
							$tgl_lahirs = 0; if(set_value('tgl_lahir')!='') $tgl_lahirs = set_value('tgl_lahir');
							
							$list_tgl['0'] = "Tgl";
							for($i=1;$i<=31;$i++)
							{
								$list_tgl[$i] = $i;
							}
							echo form_dropdown('tgl_lahir', $list_tgl, $tgl_lahirs,'id="tgl_lahir" class="styledselect-day"'); 
							
							// list bln
							$bln_lahirs = 0; if(set_value('bln_lahir')!='') $bln_lahirs = set_value('bln_lahir');
							
							$list_bln['0'] = "Bln";
							for($i=01;$i<=12;$i++)
							{
								$list_bln[$i] = $i;
							}
							echo form_dropdown('bln_lahir', $list_bln, $bln_lahirs,'id="bln_lahir" class="styledselect-day"');
							
							//list tahun
							$thn_lahirs = 0; if(set_value('thn_lahir')!='') $thn_lahirs = set_value('thn_lahir');
							
							$list_thn['0'] = "Thn";
							for($i=1920;$i<=date("Y");$i++)
							{
								$list_thn[$i] = $i;
							}
							echo form_dropdown('thn_lahir', $list_thn, $thn_lahirs,'id="thn_lahir" class="styledselect-day"');?>
                     
                    </td>
					<td>
						<? if(form_error('tgl_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_lahir'); ?></div>
						<? } elseif(form_error('bln_lahir') != '') { ?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('bln_lahir'); ?></div>
						<? }elseif(form_error('thn_lahir') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('thn_lahir'); ?></div>
						<? }?>
					</td>
				</tr>
				
				<tr>
					<th valign="top">Propinsi (*)</th>
					<td>	
						<? $province=''; if(set_value('province')!='') $province = set_value('province');
							echo form_dropdown('province', $province_options, $province,'id="province" class="chzn-select"'); ?>
					</td>
					<td>
						<? if(form_error('province') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('province'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('kota') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kota (*)</th>
					<td><?  $kota=''; if(set_value('kota')!='') $kota = set_value('kota');
							echo form_dropdown('kota', $kota_options, $kota,'id="kota" class="chzn-select" data-allows-new-values="true"'); ?></td>
					<td>
						<? if(form_error('kota') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kota'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('alamat') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Alamat (*)</th>
					<td><input type="text" name="alamat" value="<?php echo set_value('alamat');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('alamat') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('alamat'); ?></div>
						<? }?>
					</td>
				</tr>
                <tr>
					<? form_error('telp') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">No Telepon (*)</th>
					<td><input type="text" name="telp" value="<?php echo set_value('telp');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('telp') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('telp'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('hp') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Handphone</th>
					<td><input type="text" name="hp" value="<?php echo set_value('hp');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('hp') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('hp'); ?></div>
						<? }?>
					</td>
				</tr>
                <!--
				<tr>
					<? form_error('email') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Email</th>
					<td><input type="text" name="email" value="<?php echo set_value('email');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('email') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('email'); ?></div>
						<? }?>
					</td>
				</tr>-->
                
	<tr>
		<td colspan="3"><img src="<?=base_url()?>images/shared/blank.gif" width="480" height="1" alt="blank" /></td>
	</tr>	
    
                <tr height="40">
					<td colspan="2">
                    <div id="" style="border:1px #e0e0e0 solid; background:#f5f5f5; color:#707070; padding:6px 17px 6px 17px;margin-bottom:2px;"><strong>INFORMASI PERSYARATAN UMUM CALON JAMAAH</strong></div>
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- Calon Jamaah harus memiliki Passport asli minimal 6 bulan<br />&nbsp;&nbsp;masa berlaku dengan 3 suku kata.</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#f5f9fc; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- File Foto : Foto Formal setengah Badan</div> 
                     <div id="" style="border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;margin-bottom:2px;">- File Foto : Ukuran Foto 4x6</div>
                     </td>
                     <td><td>
				</tr>
			</table>
			<!-- end id-form  -->
		</td>
		
		<td>
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
			
				<tr>
					<? form_error('kamar') == '' ? $class = 'inp-form2':$class = 'inp-form-error2'; ?>
					<th valign="top">Pilihan Kamar (*)</th>
					<td><? $kamar = 0; if(set_value('kamar')!='') $kamar = set_value('kamar');
							echo form_dropdown('kamar', $kamar_options, $kamar,'id="kamar" class="styledselect-group"'); ?>
                        </td>
					<td>
						<? if(form_error('kamar') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kamar'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('baju') == '' ? $class = 'inp-form2':$class = 'inp-form-error2'; ?>
					<th valign="top">Ukuran Baju (*)</th>
					<td><? $baju = 0; if(set_value('baju')!='') $baju = set_value('baju');
							echo form_dropdown('baju', $chlothes_options, $baju,'id="baju" class="styledselect_form_1"'); ?>
                        </td>
					<td>
						<? if(form_error('baju') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('baju'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('ayah_kandung') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Ayah Kandung (*)</th>
					<td><input type="text" name="ayah_kandung" value="<?php echo set_value('ayah_kandung');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('ayah_kandung') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('ayah_kandung'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('relasi') == '' ? $class = 'inp-form2':$class = 'inp-form-error2'; ?>
					<th valign="top">Hubungan (*)</th>
					<td><? $relasi = 0; if(set_value('relasi')!='') $relasi = set_value('relasi');
							echo form_dropdown('relasi', $relasi_options, $relasi,'id="relasi" class="styledselect-biodata" onchange="SetMahram(this)"'); ?>
                        </td>
					<td>
						<? if(form_error('relasi') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('relasi'); ?></div>
						<? }?>
					</td>
				</tr>
                <tr height="40">
					<td colspan="3"><div id="cek_mahram" style="display:none"><input type="checkbox" name="mahram" value="1" <? echo set_checkbox('mahram', '1')?> /> &nbsp;&nbsp;<strong>Tidak ada Mahram</strong> <i>(Untuk Perempuan di bawah 45 tahun)</i></div>
                    <div id="cek_rifqah" style="display:none; border:1px #d8e1e9 solid; background:#e4edf5; color:#2e74b2; padding:6px 17px 6px 17px;">Rifqah = Calon Jamaah Perempuan di atas 45 Tahun</div></td>
					<td>
				</tr>
				<tr>
					<th valign="top" colspan="2">Permintaan Pelayanan Khusus</th>
					<td>
					</td>
				</tr>
				<tr height="40">
					<td>
                    <input type="checkbox" name="kursi_roda" value="1" <? echo set_checkbox('kursi_roda', '1')?> /> &nbsp;&nbsp;Kursi Roda</td>
					<td><input type="checkbox" name="asisten" value="2" <? echo set_checkbox('asisten', '2')?> /> &nbsp;&nbsp;Asistensi Anak yang khusus</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top" colspan="2">Perihal Pribadi</th>
					<td>
					</td>
				</tr>
				<tr>
					<td colspan="3"><table width="100%">
                    	<tr>
                          <td width="130">
                          <input type="checkbox" name="darah_tinggi" value="1" <? echo set_checkbox('darah_tinggi', '1')?> /> &nbsp;&nbsp;Darah Tinggi
                          </td>
                          <td width="150">
                          <input type="checkbox" name="takut_ketinggian" value="2" <? echo set_checkbox('takut_ketinggian', '2')?> /> &nbsp;&nbsp;Takut Ketinggian
                          </td>
                          <td>
                          <input type="checkbox" name="smooking_room" value="3" <? echo set_checkbox('smooking_room', '3')?> /> &nbsp;&nbsp;Perokok
                          </td>
                         </tr>
                       </table>
                     </td>
				</tr>
				<tr>
					<td colspan="3"><table width="100%">
                    	<tr>
                          <td width="130">
                          <input type="checkbox" name="jantung" value="4" <? echo set_checkbox('jantung', '4')?> /> &nbsp;&nbsp;Jantung
                          </td>
                          <td width="150">
                          <input type="checkbox" name="asma" value="5" <? echo set_checkbox('asma', '5')?> /> &nbsp;&nbsp;Asma
                          </td>
                          <td>
                          <input type="checkbox" name="mendengkur" value="6" <? echo set_checkbox('mendengkur', '6')?> /> &nbsp;&nbsp;Mendengkur
                          </td>
                         </tr>
                       </table>
                     </td>
				</tr>
				<tr>
                	<? form_error('foto') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th>Foto (*)</th>
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
			
				
				<tr height="50">
					<? form_error('jasa_maningtis') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
						<th valign="top">Jasa Tambahan</th>
					<td valign="top"><input type="checkbox" name="jasa_maningtis" value="1" <? echo set_checkbox('jasa_maningtis', '1')?> /> &nbsp;&nbsp;Jasa Maningtis</td>
					<td>
						<? if(form_error('jasa_maningtis') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('jasa_maningtis'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th></th>
					<td valign="top">
						<input type="submit" value="" class="form-submit" />
						<input type="reset" value="" class="form-reset"  />
					</td>
					<td></td>
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

function SetMahram(input)
{
	var jenkel = document.getElementById('gender').value;
	var relasi = document.getElementById('relasi').value;
	
	if(jenkel == 2 && relasi == 6)
	{
		document.getElementById('cek_mahram').style.display="inline";
	}else{
		document.getElementById('cek_mahram').style.display="none";
	}
	
	if(relasi == 5){
		document.getElementById('cek_rifqah').style.display="inline";
	}else{
		document.getElementById('cek_rifqah').style.display="none"
	}
}

function get_kamar() {
		
		var prp = $("#paket").val();
                $.ajax({
                        url: "<?=base_url();?>index.php/admin/data_jamaah/get_kamar",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_paket="+ prp, //the name of the $_POST variable and its value
                        success: function (response) {
						$('#kamar').html(response);
                        }
                });
              return false;
		
	}

	$("#province").bind('change',function get_group() 
	{	
		var prp = $("#province").val();
		
                $.ajax({
                        url: "<?=base_url();?>index.php/province/get_ajax_kota/",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_province="+ prp, //the name of the $_POST variable and its value
                        success: function (response) {
							 var bahan = response;
							 
                             //document.getElementById('info_jd').innerHTML = pecah[0];
							 document.getElementById('kota').innerHTML = response;
							 }
                });
				$("#kota").trigger("liszt:updated");
				return false;
	}
	);

</script>