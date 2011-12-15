<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<!--  start step-holder -->
			<div id="step-holder">
				<div class="step-no-off">1</div>
				<div class="step-light-left">Cek Ketersediaan</a></div>
				<div class="step-light-right">&nbsp;</div>
				<div class="step-no-off">2</div>
				<div class="step-light-left">Hasil Pengecekan</div>
				<div class="step-light-right">&nbsp;</div>
				<div class="step-no-off">3</div>
				<div class="step-light-left">Pendaftaran</div>
				<div class="step-light-right">&nbsp;</div>
				<div class="step-no">4</div>
				<div class="step-dark-left">Notifikasi</div>
				<div class="step-dark-round">&nbsp;</div>
				<div class="clear"></div>
			</div>
			<!--  end step-holder -->
			
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<th valign="top">Nama</th>
					<td><?php echo $account['NAMA_USER']; ?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">E-mail</th>
					<td><?php echo $account['EMAIL']; ?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Telp / Mobile</th>
					<td><?php $account['TELP']!='' ? $separator='/ ' :$separator=''; echo $account['TELP'].' '.$separator.$account['MOBILE']; ?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Propinsi</th>
					<td><?php echo $account['NAMA_PROPINSI']; ?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Kota</th>
					<td><?php echo $account['KOTA']; ?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Alamat</th>
					<td><?php echo $account['ALAMAT']; ?></td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">No ID Card (i.e. KTP)</th>
					<td><?php echo $account['NO_ID_CARD']; ?></td>
					<td></td>
				</tr>
				<? if (isset($waiting) && $waiting){?>
				<tr height="50">
					<th valign="top">Keterangan</th>
					<td>						
						<div class="error-left"></div>
						<div class="error-repeat">
							Anda masuk dalam daftar tunggu untuk pilihan paket di atas.
						</div>		
						<div class="error-inner"></div>
					</td>
					<td></td>
				</tr>
				<? } ?>
			</table>
			<!-- end id-form  -->
		</td>
		
		<td>
			<!--  start related-activities -->
			<div id="related-activities">
				
				<!--  start related-act-top -->
				<div id="related-act-top">
					<img src="<?php echo base_url();?>images/forms/header_related_act.gif" width="271" height="43" alt="" />
				</div>
				<!-- end related-act-top -->
					
				<!--  start related-act-bottom -->
				<div id="related-act-bottom">
					<!--  start related-act-inner -->
					<div id="related-act-inner">
						<div class="left"><img src="<?php echo base_url();?>images/forms/icon_edit.gif" width="21" height="21" alt="" /></div>
						<div class="right">
							<h5>Assalamualaikum Wr Wb</h5>
								Terima kasih telah melakukan pendaftaran keberangkatan Umroh pada Umrah Kamilah.
						</div>
							
						<div class="clear"></div>
						<div class="lines-dotted-short"></div>
							
						<div class="left"><img src="<?php echo base_url();?>images/forms/icon_edit.gif" width="21" height="21" alt="" /></div>
						<div class="right">
							<h5>Informasi Pendaftaran</h5>
								Data Pendaftaran anda telah masuk ke dalam sistem kami.
						</div>
							
						<div class="clear"></div>
						<div class="lines-dotted-short"></div>
							
						<div class="left"><img src="<?php echo base_url();?>images/forms/icon_plus.gif" width="21" height="21" alt="" /></div>
						<div class="right">
							<h5>Proses Selanjutnya</h5>
								Silakan Cek Email anda untuk melakukan Aktivasi akun dan prosedur selanjutnya.
						</div>
						
						<div class="left"><img src="<?php echo base_url();?>images/forms/icon_plus.gif" width="21" height="21" alt="" /></div>
						<div class="right">
							<h5><strong>Catatan: </strong></h5>
							<p align="justify">
                                Sebelum Memenuhi Kesepakatan Pendaftaran, komitmen booking seat keberangkatan <strong style="color:green;">belum terjadi</strong>.<br>
								Silakan <strong style="color:green;">disegerakan</strong> untuk Memenuhi Kesepakatan Pendaftaran dan melakukan konfirmasi pembayaran, sehingga Data anda bisa segera di proses. Dan Status Pendaftaran akan kami Booked.<br>
								Peserta <strong style="color:green;">belum terdaftar</strong> jika dana belum efektif masuk ke dalam rekening kamilah.
							</p>	
							
						</div>
						
						<div class="clear"></div>
						<div class="lines-dotted-short"></div>
						
						<? if (isset($waiting) && $waiting){?>
						<div class="left"><img src="<?php echo base_url();?>images/forms/icon_plus.gif" width="21" height="21" alt="" /></div>
						<div class="right">
							<h5>Info Waiting List</h5>							
							<ul class="greyarrow">
							<li>Dengan masuk ke daftar tunggu untuk sementara anda tidak bisa menggunakan fitur-fitur sistem registrasi online ini.</li>
							<li>Akun anda akan aktif kembali jika status daftar tunggu anda berubah.</li>
							<li>Informasi tentang update status akun anda akan dikirim melalui email.</li>
							</ul>								
						</div>
						<? } ?>	
							<div class="clear"></div>						
					</div>
					<!-- end related-act-inner -->
						
					<div class="clear"></div>			
				</div>
				<!-- end related-act-bottom -->
			</div>
			<!-- end related-activities -->
		</td>
	</tr>
	<tr>
		<td><img src="<?php echo base_url();?>images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
		<td></td>
	</tr>
</table>
		 
<div class="clear"></div>

