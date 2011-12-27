<!--  start step-holder -->
<div class="step-holder">
	<div class="step-no-off">1</div>
	<div class="step-light-left">Periksa Ketersediaan</a></div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">2</div>
	<div class="step-light-left">Hasil Ketersediaan</div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">3</div>
	<div class="step-light-left">Pendaftaran</div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no">4</div>
	<div class="step-dark-left">Selesai</div>
	<div class="step-dark-round">&nbsp;</div>
	<div class="clear"></div>
</div>
<!--  end step-holder -->

<div class="center">
	<table border="0" width="100%" class="info_shape" cellpadding="10">
		<tr>
			<td colspan="2">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<? if (isset($waiting) && $waiting){?>
							<span class="text_title">Terima Kasih, Data Telah <font color='3B619F'>Berhasil Masuk Daftar Tunggu</font> dalam Sistem</span>
					<? }else{ ?>
							<span class="text_title">Terima Kasih, Data Telah <font color='3B619F'>Berhasil Terdaftar</font> dalam Sistem</span>
					<? } ?>
				</div>
				<div class="repeat_hline"></div>
			</td>
		</tr>
		
		<tr>
			<td valign="top">
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/anak.png"/>&nbsp;Nama</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php echo $account['NAMA_USER']; ?></label>
					</span>
				</div>
				
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/mail.png"/>&nbsp;Email</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php echo $account['EMAIL']; ?></label>
					</span>
				</div>
				
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/telephone.png"/>&nbsp;Telp / Mobile</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php $account['TELP']!='' ? $separator='/ ' :$separator=''; echo $account['TELP'].' '.$separator.$account['MOBILE']; ?></label>
					</span>
				</div>
				
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/propinsi.png"/>&nbsp;Propinsi</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php echo $account['NAMA_PROPINSI']; ?></label>
					</span>
				</div>
			</td>
			<td valign="top">
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/kota.png"/>&nbsp;Kota</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php echo $account['KOTA']; ?></label>
					</span>
				</div>
				
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/home.png"/>&nbsp;Alamat</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php echo $account['ALAMAT']; ?></label>
					</span>
				</div>
				
				<div class="row">
					<label class="col1"><img src="<?php echo base_url();?>images/front/card.png"/>&nbsp;No Identitas</label>
					<span class="col2 viewdata">
						<label style="padding:25px 0 0 0;"><?php echo $account['NO_ID_CARD']; ?></label>
					</span>
				</div>
			</td>
		</tr>
	</table>
	
	<br/>
	
	<? //if (!isset($waiting) && !$waiting){?><!--
	<table border="0" width="100%" class="info_shape" cellpadding="10">
		<tr>
			<td valign="top">
				<div class="title">
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Informasi - Informasi <font color='A01040'>PENTING !!</font></span>
				</div>
				<div class="repeat_hline"></div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<ul style="font-family: 'Oswald', Arial, sans-serif;">
					<li>Sebelum Memenuhi Kesepakatan Pendaftaran (Uang Muka & Data Paspor), komitmen booking seat keberangkatan <font color="green">BELUM TERJADI</font>.</li>
					<li>Silakan <font color="green">DISEGERAKAN</font> untuk Memenuhi Kesepakatan Pendaftaran dan melakukan konfirmasi pembayaran, sehingga Data anda bisa segera di proses. Dan Status Pendaftaran akan kami Booked.</li>
					<li>Peserta <font color="green">BELUM TERDAFTAR</font> jika dana belum efektif masuk ke dalam rekening kamilah.</li>
					<li>Informasi Selengkapnya, Silakan <font color="green">CEK EMAIL </font>anda untuk melakukan Aktivasi akun dan prosedur selanjutnya.</li>
				</ul>
			</td>
		</tr>
	</table>-->
	<? //} ?>	
	
	<? if (isset($waiting) && $waiting){?>
		<table border="0" width="100%" class="info_shape" cellpadding="10">
			<tr>
				<td valign="top">
					<div class="title">
						<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
						<span class="text_title">Informasi - Informasi <font color='A01040'>DAFTAR TUNGGU !!</font></span>
					</div>
					<div class="repeat_hline"></div>	
				</td>
			</tr>
			<tr>
				<td>
					<ul style="font-family: 'Oswald', Arial, sans-serif;">
						<li>Dengan masuk ke daftar tunggu untuk sementara anda <font color="green">TIDAK BISA</font> menggunakan fitur-fitur sistem dashboard nantinya.</li>
						<li>Akun anda <font color="green">AKAN AKTIF</font> jika status daftar tunggu anda <font color="green">BERUBAH</font>.</li>
						<li>Informasi tentang update status akun anda akan dikirim melalui <font color="green">EMAIL</font>.</li>
					</ul>
				</td>
			</tr>
		</table>
	<? }else{ ?>
			<table border="0" width="100%" class="info_shape" cellpadding="10">
				<tr>
					<td valign="top">
						<div class="title">
							<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
							<span class="text_title">Informasi - Informasi <font color='A01040'>PENTING !!</font></span>
						</div>
						<div class="repeat_hline"></div>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<ul style="font-family: 'Oswald', Arial, sans-serif;">
							<li>Pendaftaran diatas <font color="green">HANYA</font> merupakan proses pembuatan akun di Kamilah Wisata dan penyimpanan data akun anda sebelum anda melakukan prosedur selanjutnya.</li>
					<li>Sebelum Memenuhi Kesepakatan Pendaftaran (Pembayaan Uang Muka & Upload Data Paspor), komitmen booking seat keberangkatan <font color="green">BELUM TERJADI</font> (Tidak terjadi pengurangan Quota Seat & Kamar).</li>
					<li>Paket yang diminta masih bisa di <font color="green">BOOKED</font> oleh calon lain jika calon lain tersebut lebih cepat memenuhi Kesepakatan Pendaftaran.</li>
					<li>Silakan <font color="green">DISEGERAKAN</font> untuk Memenuhi Kesepakatan Pendaftaran dan melakukan konfirmasi pembayaran ke sistem, sehingga Data anda bisa segera di proses</li>
					<li>Status peserta menjadi <font color="green">BOOKED</font> jika dana sudah efektif masuk ke dalam rekening kamilah.</li>
					<li>Informasi Selengkapnya, Silakan <font color="green">CEK EMAIL </font>anda untuk melakukan Aktivasi akun dan prosedur selanjutnya.</li>
						</ul>
					</td>
				</tr>
			</table>
	<? } ?>
</div>




