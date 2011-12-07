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
	
	<!-- RIGHT SIDE -->
	<div class="content_left">
		<div class="info_shape_t">
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Informasi Registrasi</span>
			</div>
			
			<div align="center">
				<p>"Calon Jamaah harus memiliki Paspor asli minimal <strong class="bold">6 bulan</strong> masa berlaku dengan nama <strong class="bold">3 suku kata.</strong>"</p>
			</div>
		
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Berdasarkan Paket Pilihan</span>
			</div>
			
			<div>
				<p>
					<div>Grup Keberangkatan : <strong><? if(isset($kode_group)) { echo $kode_group; } ?></strong></div>
					<div>Kelas Program : <strong><? if(isset($nama_program)) { echo $nama_program; } ?></strong></div>
				</p>
			</div>
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Calon Jamaah</span>
			</div>
			
			<div>
				<p>
					<div>Jumlah Dewasa : <strong><? if(isset($jml_adult)) { echo $jml_adult; } ?></strong></div>
					<div>Anak Dengan Ranjang : <strong><? if(isset($with_bed)) { echo $with_bed; } ?></strong></div>
					<div>Anak Tanpa Ranjang : <strong><? if(isset($no_bed)) { echo $no_bed; } ?></strong></div>
					<div>Bayi : <strong><? if(isset($infant)) { echo $infant; } ?></strong></div>
				</p>
			</div>
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Permintaan Konfigurasi Kamar</span>
			</div>
			
			<div>
				<p>
					<span><? if(isset($konfig_kamar)) { echo $konfig_kamar; } ?></span>
				</p>
			</div>
			
		</div>
		
	</div>
	<!-- END RIGHT SIDE -->
	
	<!-- RIGHT SIDE -->
	<div class="content_right" >
		<div class="info_shape_t">
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Keterangan Grup & Kelas Keberangkatan</span>
			</div>
			
			<div>
				<p>
					<div>Keberangkatan : <strong><? if(isset($info_berangkat)) { echo $info_berangkat; } ?></strong></div>
					<div>Maskapai : <strong><? if(isset($maskapai)) { echo $maskapai; } ?></strong></div>
					<div>Hotel Makkah : <strong><? if(isset($hotel_mk)) { echo $hotel_mk; } ?></strong></div>
					<div>Hotel Madinah : <strong><? if(isset($hotel_md)) { echo $hotel_md; } ?></strong></div>
					<div>Transportasi : <strong><? if(isset($transportasi)) { echo $transportasi; } ?></strong></div>
					<div>Kamar : <strong><? if(isset($info_jumlah_kamar)) { echo $info_jumlah_kamar; } ?></strong></div>
				</p>
			</div>
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Informasi Batas Akhir</span>
			</div>

			<div>
				<p>
					<div>Batas Akhir Uang Muka : <strong><? if(isset($uang_muka)) { echo $uang_muka; } ?></strong></div>
					<div>Batas Akhir Pelunasan : <strong><? if(isset($pelunasan)) { echo $pelunasan; } ?></strong></div>
					<div>Upload Data Paspor : <strong><? if(isset($jatu_tempo)) { echo $jatu_tempo; } ?></strong></div>
					<div>Pengumpulan Berkas Fisik : <strong><? if(isset($kirim_berkas)) { echo $kirim_berkas; } ?></strong></div>
				</p>
			</div>
			
		</div>
		
	</div>
	<!-- END RIGHT SIDE -->
	
	
	<h3 align="center" class="clear">
		"Anda bisa melakukan registrasi online terlebih dahulu, untuk mencatatkan data ke dalam sistem kami. <br/>
		Silahkan login jika anda sudah memiliki akun."
	</h3>
    
    <?
	if($waiting_list == 1)
	{
		echo '<h3 align="center" class="clear">
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
				</div>
				<h3 align="center" class="clear">
				<input name="waiting" id="waiting" type="checkbox" value="1" onchange="enableSubmit(this);" />&nbsp;
				<label for="waiting">Menginginkan masuk Daftar Tunggu</label>
				</h3>
				<center><input type="submit" value="Submit" id="submit_button" class="submit_button" disabled="disabled" /></center>
				<br />';
        echo form_close(); 
	
	} else {
    
		echo '<div class="registration_link">
				<a href="'.site_url().'/registration" class="a-btn" align="center">
					<span class="a-btn-text">Registrasi Online</span> 
					<span class="a-btn-slide-text">Dapatkan Diskon $70</span>
					<span class="a-btn-icon-right"><span></span></span>
				</a>
			</div>';
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