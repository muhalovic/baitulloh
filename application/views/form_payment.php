<?php 
if(isset($notifikasi)) { echo $notifikasi; }
if(isset($error_file)) { echo $error_file; }
?>

<div class="garis_pisah"> RINCIAN BIAYA</div>
<center>
<table width="100%" class="front_price" align="center">
	<tr height="30">
		<td width="300" align="center" class="bg_kolom"><h3>KONFIGURASI KAMAR</h3></td>
		<td width="100" align="center" class="bg_kolom"><h3>HARGA PER KAMAR</h3></td>
		<td width="100" align="center" class="bg_kolom"><h3>RINCIAN</h3></td>
		<td width="100" align="center" class="bg_kolom"><h3>JUMLAH</h3></td>
		<td width="100" align="center" class="bg_kolom"><h3>TOTAL</h3></td>
    </tr>	
	<? 
	if(isset($list_jamaah))
	{
		echo $list_jamaah;
	}
	?>	
	<tr height="30">
		<td align="left"><h4><span class="price_list_packet">Jasa Pengurusan Buku Maningtis</span></h4></td>
		<td align="center"><h4></h4></td>
		<td align="center"><h4>$ 20.00</h4></td>
		<td align="center"><h4><? if(isset($total_maningtis)) { echo $total_maningtis; }else{ echo 0; } ?> x</h4></td>
		<td align="left"><h4 class="price_list_packet">$ <? if(isset($total_maningtis)) { echo (20 * $total_maningtis); }else{ echo 0; } ?></h4></td>
    </tr>		
	<tr height="30" valign="bottom">
		<td class="bg_kolom"></td>
		<td class="bg_kolom"></td>
		<td class="bg_kolom"></td>
		<td class="bg_kolom"><strong>T O T A L &nbsp; B I A Y A</strong></td>
		<td class="bg_kolom" align="left"><h4>$ <? if(isset($total_harga_keseluruhan)) { echo $total_harga_keseluruhan; } ?></h4></td>
    </tr>
</table>
<br /><br /><br /><br />

<div class="garis_pisah"> RINCIAN PEMBAYARAN</div>

<!-- ------- Rincian Pembayaran --------->

<table width="100%" class="front_payment" align="center">
	<tr height="30">
		<td width="150" class="bg_kolom"></td>
		<td width="100" class="bg_kolom"><h3>BIAYA</h3></td>
		<td width="100" class="bg_kolom"><h3>NOMINAL<br />DIBAYARKAN</h3></td>
		<td width="100" class="bg_kolom"><h3>STATUS YANG DIBAYARKAN</h3></td>
		<td width="100" class="bg_kolom"><h3>TOTAL YANG DIBAYARKAN</h3></td>
		<td width="100" class="bg_kolom"><h3>STATUS LUNAS</h3></td>
		<td width="100" class="bg_kolom"><h3>KEKURANGAN</h3></td>
		<td width="100" class="bg_kolom"><h3>JATUH TEMPO</h3></td>
    </tr>	
	<tr height="30">
		<td class="bg_kolom_right front_payment_top" rowspan="<? if(isset($rowspan_uangmuka)) { echo $rowspan_uangmuka; } ?>">
        	<h4>Uang Muka (<font color="#707070"><i>
			<? if(isset($total_jamaah_per_kamar)) { echo $total_jamaah_per_kamar; } ?> x 1.100</i></font>)
            </h4>
        </td>
		<td align="center" rowspan="<? if(isset($rowspan_uangmuka)) { echo $rowspan_uangmuka; } ?>">
        	$ <? if(isset($biaya_uang_muka)) { echo $biaya_uang_muka; } ?>
        </td>
		<? if(isset($looping_uangmuka)) { echo $looping_uangmuka; } ?> 
		<td align="center" rowspan="<? if(isset($rowspan_uangmuka)) { echo $rowspan_uangmuka; } ?>">
			<? if(isset($tgl_uang_muka)) { echo $tgl_uang_muka; } ?>
        </td>
    </tr>
    <? if(isset($looping_uangmuka2)) { echo $looping_uangmuka2; } ?> 
	<tr height="30">
		<td class="bg_kolom_right"><h4>Sisa Pelunasan</h4></td>
		<td align="center">$ <? if(isset($biaya_pelunasan)) { echo $biaya_pelunasan; } ?> </td>
		<td align="center"><h4><? if(isset($jumlah_lunas)) { echo $jumlah_lunas; } ?> $</h4></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"><? if(isset($tgl_pelunasan)) { echo $tgl_pelunasan; } ?></td>
    </tr>	
	<tr height="30">
		<td class="bg_kolom_right"><h4>Airport Tax & Manasik</h4></td>
		<td align="center">Rp. 700.000</td>
		<td align="center"><h4>Rp. <? if(isset($jumlah_tax)) { echo $jumlah_tax; } ?></h4></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"><? if(isset($tgl_pelunasan)) { echo $tgl_pelunasan; } ?></td>
    </tr>	
	<tr height="30" valign="bottom">
		<td class="bg_kolom_right"><h4>T O T A L</h4></td>
		<td align="center"><h4>$ <? if(isset($total_harga_keseluruhan)) { echo $total_harga_keseluruhan; } ?> + Rp. 700.000</h4></td>
		<td align="center"><h4><? if(isset($total_pay_cek)) { echo $total_pay_cek; } ?> $ + Rp. <? if(isset($jumlah_tax)) { echo $jumlah_tax; } ?></h4></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td></td>
    </tr>
</table>
</center>

<!--
<br />
<table width="100%" class="front_payment" align="center">
	<tr height="30">
		<td width="150" class="bg_kolom"></td>
		<td width="150" class="bg_kolom"><h3>BIAYA</h3></td>
		<td width="150" class="bg_kolom"><h3>DIBAYARKAN</h3></td>
		<td width="150" class="bg_kolom"><h3>STATUS</h3></td>
		<td width="150" class="bg_kolom"><h3>JATUH TEMPO</h3></td>
    </tr>	
	<tr height="30">
		<td class="bg_kolom_right front_payment_top"><h4>Uang Muka (<font color="#707070"><i><? if(isset($jumlah_calon_jamaah)) { echo $jumlah_calon_jamaah; } ?> x 1.100</i></font>)</h4></td>
		<td align="center"><h4><? if(isset($hitung_dp_calon_jamaah)) { echo $hitung_dp_calon_jamaah; } ?> $</h4></td>
		<td align="center"><h4><? if(isset($jumlah_dp)) { echo $jumlah_dp; } ?> $</h4></td>
		<td align="center"><span class="box_status_<?=$css_dp?>"><? if(isset($status_dp)) { echo $status_dp; } ?></span></td>
		<td align="center"><h4><? if(isset($tgl_dp)) { echo $tgl_dp; } ?></h4></td>
    </tr>	
	<tr height="30">
		<td class="bg_kolom_right"><h4>Sisa Pelunasan</h4></td>
		<td align="center"><h4><? if(isset($total_pelunasan)) { echo $total_pelunasan; } ?> $</h4></td>
		<td align="center"><h4><? if(isset($jumlah_lunas)) { echo $jumlah_lunas; } ?> $</h4></td>
		<td align="center"><span class="box_status_<?=$css_lunas?>"><? if(isset($status_lunas)) { echo $status_lunas; } ?></span></td>
		<td align="center"><h4><? if(isset($tgl_lunas)) { echo $tgl_lunas; } ?></h4></td>
    </tr>	
	<tr height="30">
		<td class="bg_kolom_right"><h4>Airport Tax & Manasik</h4></td>
		<td align="center"><h4><i>Rp. 700.000</i></h4></td>
		<td align="center"><h4>Rp. <? if(isset($jumlah_tax)) { echo $jumlah_tax; } ?></h4></td>
		<td align="center"><span class="box_status_<?=$css_tax?>"><? if(isset($status_tax)) { echo $status_tax; } ?></span></td>
		<td align="center"><h4><? if(isset($tgl_lunas)) { echo $tgl_lunas; } ?></td>
    </tr>	
	<tr height="30" valign="bottom">
		<td class="bg_kolom_right"><h4>T O T A L</h4></td>
		<td align="center"><h4><? if(isset($total_biaya2)) { echo $total_biaya2; } ?> $ + <i>Rp. 700.000</i></h4></td>
		<td align="center"><h4><? if(isset($total_pay_cek)) { echo $total_pay_cek; } ?> $ + Rp. <? if(isset($jumlah_tax)) { echo $jumlah_tax; } ?></h4></td>
		<td align="center"><span class="box_status_<?=$css_total?>"><? if(isset($total_status)) { echo $total_status; } ?></span></td>
		<td></td>
    </tr>
</table>
</center>
<br /><br /><br /><br />

<div class="garis_pisah">FORM KONFIRMASI</div>

<!-- ------- form konfirmasi --------->

<!--
<? echo form_open_multipart('/payment/do_send'); ?>

<br />
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<? form_error('nama_rekening') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Rek. Atas Nama (*)</th>
					<td><input type="text" name="nama_rekening" value="<?php echo set_value('nama_rekening');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama_rekening') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama_rekening'); ?></div>
						<? }?>
					</td>
				</tr>
                <tr>
					<th valign="top">Tgl. Transfer (*)</th>
					<td>
                    	<input type="text" name="tgl_transfer" value="<?php echo set_value('tgl_transfer');?>" class="inp-form-disable" id="date_on" readonly="readonly" />
                        <a id="date-pick"><img src="<? echo base_url() ?>images/forms/icon_calendar.jpg" /></a>
                     </td>
					<td>
						<? if(form_error('tgl_transfer') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('tgl_transfer'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('bank') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama Bank (*)</th>
					<td><input type="text" name="bank" value="<?php echo set_value('bank');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('bank') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('bank'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('nominal') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Jumlah &nbsp;(*)</th>
					<td><input type="text" name="nominal" value="<?php echo set_value('nominal');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nominal') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nominal'); ?></div>
						<? }?>
					</td>
				</tr>
                <tr>
                    <td colspan="3"><img src="<?php echo base_url()?>images/shared/blank.gif" width="480" height="1" alt="blank" /></td>
                </tr>
                <tr height="40">
					<td colspan="3">
                    <div class="div_info_1"><strong>INFORMASI PEMBAYARAN PAKET UMRAH</strong></div>
                     <div class="div_info_2">- Jenis Pembayaran Uang Muka dan Pelunasan menggunakan US Dollar ($)</div> 
                     <div class="div_info_1">- Jenis Pembayaran Airport Tax & Manasik menggunakan Rupiah (Rp.)</div> 
                     <div class="div_info_2">- Format Penulisan Nominal : 1.100 USD ditulis 1100 atau Rp.700.000 ditulis 700000</div> 
                     </td>
				</tr>
			</table>
			<!-- end id-form  --><!--
		</td>
        <td>
        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<? form_error('metode') == '' ? $class = 'inp-form2':$class = 'inp-form-error2'; ?>
					<th valign="top">Jenis Pembayaran (*)</th>
					<td><? $metode = 0; if(set_value('metode')!='') $metode = set_value('metode');
							$metode_options = array(
							  '0'  => '-- Jenis Pembayaran --',
							  '1'  => 'Uang Muka',
							  '2'  => 'Sisa Pelunasan',
							  '3'  => 'Airport Tax dan Manasik',
							);
							
							echo form_dropdown('metode', $metode_options, $metode,'id="metode" class="styledselect_form_1"'); ?>
                        </td>
					<td>
						<? if(form_error('metode') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('metode'); ?></div>
						<? }?>
					</td>
				</tr>
                <tr>
					<th valign="top">Catatan</th>
					<td><textarea name="catatan" class="form-textarea-min" /><?php echo set_value('catatan');?></textarea>
                        </td>
					<td>
						<? if(form_error('catatan') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('catatan'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
                	<? form_error('foto') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th>Scan Bukti Pembayaran</th>
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
				<tr height="80">
					<th></th>
					<td valign="bottom">
						<input type="submit" value="" class="form-submit" />
						<input type="reset" value="" class="form-reset"  />
					</td>
					<td></td>
				</tr>
			</table>
         </td>
	</tr>
</table>
<? echo form_close(); ?>		 
<div class="clear"></div>
-->