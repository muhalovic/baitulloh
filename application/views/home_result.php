<!-- CSS EXTEND -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/button_reg.css" />

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
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Hasil Ketersediaan</span>
			</div>

			<div>
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
			
		</div>
		
	</div>
	<!-- END RIGHT SIDE -->
	
    <? echo form_open('/beranda/choose_packet',array('name' => 'form_registrasi', 'style' => 'width:100%')); ?>
    <div style="display: none;" >
        <input type="text" name="group" value="<? if(isset($group)) { echo $group; } ?>" />
        <input type="text" name="program" value="<? if(isset($program)) { echo $program; } ?>" />
        <input type="text" name="jml_adult" value="<? if(isset($jml_adult)) { echo $jml_adult; } ?>" />
        <input type="text" name="with_bed" value="<? if(isset($with_bed)) { echo $with_bed; } ?>" />
        <input type="text" name="no_bed" value="<? if(isset($no_bed)) { echo $no_bed; } ?>" />
        <input type="text" name="infant" value="<? if(isset($infant)) { echo $infant; } ?>" />
        <input type="text" name="waiting_list" value="<? if(isset($waiting_list)) { echo $waiting_list; } ?>" />
        <? if(isset($input_kamar)) { echo $input_kamar; } ?>
    </div>
    
	<? if($waiting_list == 0) { ?>
	<h3 align="center" class="clear">
		"Pastikan anda memeriksa ulang pilihan paket dan konfigurasi kamar. 
        <br /> sistem akan otomatis menyimpan sesaat setelah anda mengklik tombol di bawah ini. "
	</h3>
    
    <div class="registration_link">
        <center><input type="submit" value="Konfirmasi" id="submit_button" class="submit_button"/></center>
        <br />
    </div>
	
	<? } echo form_close(); ?>
	
</div>