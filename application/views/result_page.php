<!-- CSS EXTEND -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/button_reg.css" />

<!--  start step-holder -->
<div class="step-holder">
	<div class="step-no">1</div>
	<div class="step-light-left">Periksa Ketersediaan</a></div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">2</div>
	<div class="step-dark-left">Hasil Ketersediaan</div>
	<div class="step-dark-right">&nbsp;</div>
	<div class="step-no-off">3</div>
	<div class="step-light-left">Pendaftaran</div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">4</div>
	<div class="step-light-left">Selesai</div>
	<div class="step-light-round">&nbsp;</div>
	<div class="clear"></div>
</div>
<!--  end step-holder -->

<div class="center">
	<!-- RESULT TABLE -->
	<table border="0" width="100%" class="info_shape" cellpadding="10">
		<tr>
			<td valign="top" width="56%">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Keterangan Grup & Kelas Keberangkatan</span>
				</div>
				
				<div class="repeat_hline"></div>
				
				<div>
					<p>
						<table>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Keberangkatan</td>
								<td>:</td>
								<td><strong><? if(isset($info_berangkat)) { echo $info_berangkat; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/plane.png"/>&nbsp;Maskapai</td>
								<td>:</td>
								<td><strong><? if(isset($maskapai)) { echo $maskapai; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/building.png"/>&nbsp;Hotel Makkah</td>
								<td>:</td>
								<td><strong><? if(isset($hotel_mk)) { echo $hotel_mk; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/building.png"/>&nbsp;Hotel Madinah</td>
								<td>:</td>
								<td><strong><? if(isset($hotel_md)) { echo $hotel_md; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/car.png"/>&nbsp;Transportasi</td>
								<td>:</td>
								<td><strong><? if(isset($transportasi)) { echo $transportasi; } ?></strong></td>
							</tr>
							<tr>
								<td valign="top"><img src="<?php echo base_url();?>images/front/poin.png"/>&nbsp;Kamar</td>
								<td valign="top">:</td>
								<td><ul><? if(isset($info_jumlah_kamar)) { echo $info_jumlah_kamar; } ?></ul></td>
							</tr>
						</table>
					</p>
				</div>
			</td>
				
			<td valign="top">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Informasi Batas Akhir</span>
				</div>
				
				<div class="repeat_hline"></div>
				
				<div>
					<p>
						<table>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Batas Akhir Uang Muka</td>
								<td>:</td>
								<td><strong><? if(isset($uang_muka)) { echo $uang_muka; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Batas Akhir Pelunasan</td>
								<td>:</td>
								<td><strong><? if(isset($pelunasan)) { echo $pelunasan; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Upload Data Paspor</td>
								<td>:</td>
								<td><strong><? if(isset($jatu_tempo)) { echo $jatu_tempo; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Pengumpulan Berkas Fisik</td>
								<td>:</td>
								<td><strong><? if(isset($kirim_berkas)) { echo $kirim_berkas; } ?></strong></td>
							</tr>
						</table>
					</p>
				</div>
			</td>
		</tr>
	</table>
	
	<br/>
	
	<table border="0" width="100%" class="info_shape" cellpadding="20">
		<tr>
			<td valign="top">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Berdasarkan Paket Pilihan</span>
				</div>
				
				<div class="repeat_hline"></div>
				
				<div>
					<p>
						<table>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/grup.png"/>&nbsp;Grup Keberangkatan</td>
								<td>:</td>
								<td><strong><? if(isset($kode_group)) { echo $kode_group; } ?></strong></td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/kelas.png"/>&nbsp;Kelas Program</td>
								<td>:</td>
								<td><strong><? if(isset($nama_program)) { echo $nama_program; } ?></strong></td>
							</tr>
						</table>
					</p>
				</div>
			</td>
			
			<td valign="top">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Jumlah Calon Jamaah</span>
				</div>
				
				<div class="repeat_hline"></div>
				
				<div>
					<p>
						<table>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/dewasa.png"/>&nbsp;Jumlah Dewasa</td>
								<td>:</td>
								<td><strong><? if(isset($jml_adult)) { echo $jml_adult; } ?></strong> orang</td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/anak.png"/>&nbsp;Anak Dengan Ranjang</td>
								<td>:</td>
								<td><strong><? if(isset($with_bed)) { echo $with_bed; } ?></strong> orang</td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/anak.png"/>&nbsp;Anak Tanpa Ranjang</td>
								<td>:</td>
								<td><strong><? if(isset($no_bed)) { echo $no_bed; } ?></strong> orang</td>
							</tr>
							<tr>
								<td><img src="<?php echo base_url();?>images/front/bayi.png"/>&nbsp;Bayi</td>
								<td>:</td>
								<td><strong><? if(isset($infant)) { echo $infant; } ?></strong> orang</td>
							</tr>
						</table>
					</p>
				</div>
			</td>
			
			<td valign="top">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Permintaan Konfigurasi Kamar</span>
				</div>
				
				<div class="repeat_hline"></div>
				
				<div>
					<p>
						<table>
							<? if(isset($konfig_kamar)) { echo $konfig_kamar; } ?>
						</table>
					</p>
				</div>
			</td>
		</tr>
	</table>
	
	<br/>
	
	<table border="0" width="100%" class="info_shape" cellpadding="20">
		<tr>
			<td>
				<div class="title" style="text-align:center;">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Resume Hasil Ketersediaan</span>
				</div>
				
				<div class="repeat_hline"></div>
				
				<div style="text-align:center;">
					<p>
						<div>
							<? 
								if(isset($msg_box1)) { echo $msg_box1; } 
								if(isset($msg_box2)) { echo $msg_box2; } 
								if(isset($msg_box3)) { echo $msg_box3; } 
								if(isset($msg_box4)) { echo $msg_box4; } 
								if(isset($status_waiting)) { echo $status_waiting; } 
							?>                        
						</div>
					</p>
				</div>
			</td>
		</tr>
	</table>
	
	<?
	if($waiting_list != 1)
	{
		echo '<h3 align="center" class="clear">
				"Anda bisa melakukan registrasi online terlebih dahulu, untuk mencatatkan data ke dalam sistem kami. <br/>
				Atau silahkan <a href="'.site_url().'/login"><font color="#A01040"><u>LOGIN</u></font></a> jika anda sudah memiliki akun."
			</h3>';
	}
	?>
    
    <?
	if($waiting_list == 1)
	{
		echo '
			<h3 align="center" class="clear">
				<img src="'.base_url().'images/front/question.png"/>&nbsp;<font color="#3B619F">Penawaran Masuk Daftar Tunggu</font>
			</h3>
			<h3 align="center" class="clear">
				"Anda dapat mencentang poin di bawah ini, jika bersedia dimasukkan ke dalam daftar tunggu untuk pilihan paket di atas."
			</h3>';

		echo form_open('/registration',array('name' => 'form_registrasi', 'style' => 'width:100%'));
		
        echo '<div style="display: none;" >
					<input type="text" name="group" value="'.$group.'" />
					<input type="text" name="program" value="'.$program.'" />
					<input type="text" name="jml_adult" value="'.$jml_adult.'" />
					<input type="text" name="with_bed" value="'.$with_bed.'" />
					<input type="text" name="no_bed" value="'.$no_bed.'" />
					<input type="text" name="infant" value="'.$infant.'" />
					<input type="text" name="waiting_list" value="'.$waiting_list.'" />
				</div>
				<h3 align="center" class="clear">
				<input name="waiting" id="waiting" type="checkbox" value="1" onchange="enableSubmit(this);" />&nbsp;
				<label for="waiting">Menginginkan masuk Daftar Tunggu</label>
				</h3>
				<center>
					<a href="'.site_url().'" class="link_step_kembali"><< Kembali</a>
					<input type="submit" value="Lanjut >>" id="submit_button" class="submit_button" disabled="disabled" />
				</center>
				<br />';
        echo form_close(); 
	
	} else {
    
		echo '
			<center style="margin:20px 0 20px 0">
				<a href="'.site_url().'" class="link_step_kembali"><< Kembali</a>
				<a href="'.site_url().'/registration" class="link_step_lanjut">Lanjut >></a>
			</center>
			';
	}
	?>
	
</div>

<script>
	function enableSubmit(val){
		
		if (val.checked){
			document.getElementById('submit_button').disabled = false;
		}else{
			document.getElementById('submit_button').disabled = true;
		}
	}
</script>