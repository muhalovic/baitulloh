		<style type="text/css">
			body {
				margin:auto;
				padding:auto;
				font-family:Calibri, Verdana;
				font-size:9pt;
				color:#333;
			}
			table.front_price {
			  border-collapse: collapse;
			}
			
			table.front_price td {
			  border-bottom:1px solid #e9e9e9;
			  padding: 5px;
			  -moz-border-radius: ;
			}
			
			.bg_head {
				background:#F7F7F7;
			}
			
			.bg_head2 {
				background:#FCFCFC;
			}
			
			table.front_payment {
				border-width: 1px;
				border-spacing: 1px;
				border-style: solid;
				border-color: gray;
				border-collapse: collapse;
			}
			table.front_payment th {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: gray;
				-moz-border-radius: ;
			}
			table.front_payment td {
				border-width: 1px;
				padding: 5px;
				border-style: solid;
				border-color: #CCC;
				-moz-border-radius: ;
			}
			.garis_pisah {
				border-bottom:1px solid #CCC;
				margin:0px 0px 20px 0px;
				text-align:left;
				font-size:14px;
				font-weight:bold;
				padding:0px 0px 5px 5px;
			}
		</style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
<td width="70%" align="center" valign="middle"><strong><h3>DAFTAR RINCIAN BIAYA DAN PEMBAYARAN <br> PAKET UMRAH a.n <? if(isset($nama_user)) { echo strtoupper($nama_user); } ?></h3></strong></td>
    <td width="30%" align="right" valign="middle"><div class="logo_bg"><img src="<? echo base_url().'/images/shared/header-logo.png'; ?>" height="40" width="200"></div></td>
  </tr>
</table>
<br><br>
<br />
<div class="garis_pisah"> RINCIAN BIAYA</div>
																
<table width="100%" class="front_price" align="center">
	<tr height="30">
		<td width="300" class="bg_head" align="center"><strong>PAKET / NAMA KAMAR</strong></td>
		<td width="150" class="bg_head" align="center"><strong>CALON JAMAAH</strong></td>
		<td width="150" class="bg_head" align="center"><strong>HARGA</strong></td>
		<td width="150" class="bg_head" align="center"><strong>TOTAL</strong></td>
    </tr>	
	<? 
	if(isset($row_price))
	{
		echo $row_price;
	}
	?>	
	<tr height="30">
		<td align="left" class="front_price_no_border">Jasa Tambah Nama</td>
		<td align="center"></td>
		<td align="center"><? if(isset($hitung_jasa_nama)) { echo $hitung_jasa_nama; } ?> X 20.00 $</td>
		<td align="center"><? if(isset($hitung_total)) { echo $hitung_total; } ?> $</td>
    </tr>	
	<tr height="30">
		<td align="left" class="front_price_no_border">Jasa Pengurusan Buku Maningtis</td>
		<td align="center"></td>
		<td align="center"><? if(isset($hitung_jasa_maningtis)) { echo $hitung_jasa_maningtis; } ?> X 20.00 $</td>
		<td align="center"><? if(isset($hitung_total_maningtis)) { echo $hitung_total_maningtis; } ?> $</td>
    </tr>	
	<tr height="30" valign="bottom">
		<td class="bg_head"></td>
		<td class="bg_head"></td>
		<td class="bg_head"><strong>TOTAL BIAYA</strong></td>
		<td class="bg_head" align="center"><strong><? if(isset($total_biaya2)) { echo $total_biaya2; } ?> $</strong></td>
    </tr>
</table>
<br />
<br />
<div class="garis_pisah"> RINCIAN PEMBAYARAN</div>

    <? 
	if($jenis == 1)
	{
		$jenis_e = "UANG MUKA";
		$mata_uang = "$";
		$mata_dolar = " $";
		$mata_rupiah = NULL;
	}elseif($jenis == 2)
	{
		$jenis_e = "PELUNASAN";
		$mata_uang = "$";
		$mata_dolar = " $";
		$mata_rupiah = NULL;
	}elseif($jenis == 3)
	{
		$jenis_e = "Airport Tax dan Manasik";
		$mata_uang = "Rp";
		$mata_dolar = ",-";
		$mata_rupiah = "Rp.";
	}
	?>
    
<table width="100%" class="front_price" align="center">
	<tr height="30">
		<td width="200" class="bg_head">Bank pengirim</td>
		<td width="10" class="bg_head"><strong>:</strong></td>
		<td width="400" class="bg_head"><? echo strtoupper($nama_bank); ?></td>
    </tr>
	<tr height="30">
		<td width="150" class="bg_head2">Rek. Atas Nama</td>
		<td width="10" class="bg_head2"><strong>:</strong></td>
		<td width="500" class="bg_head2"><? echo strtoupper($nama_rekening); ?></td>
    </tr>
	<tr height="30">
		<td width="200" class="bg_head">Jumlah (<? echo $mata_uang; ?>)</td>
		<td width="10" class="bg_head"><strong>:</strong></td>
		<td width="400" class="bg_head"><? echo $mata_rupiah.$jumlah.$mata_dolar; ?></td>
    </tr>
	<tr height="30">
		<td width="150" class="bg_head2">Tgl. Transfer</td>
		<td width="10" class="bg_head2"><strong>:</strong></td>
		<td width="500" class="bg_head2"><? echo $tgl_transfer; ?></td>
    </tr>
	<tr height="30">
		<td width="200" class="bg_head">Tipe Pembayaran</td>
		<td width="10" class="bg_head"><strong>:</strong></td>
		<td width="400" class="bg_head"><? echo $tipe_pembayaran;?></td>
    </tr>
	<tr height="30">
		<td width="150" class="bg_head2">Catatan</td>
		<td width="10" class="bg_head2"><strong>:</strong></td>
		<td width="500" class="bg_head2"><? echo $catatan; ?></td>
    </tr>
</table>
<br /><br />