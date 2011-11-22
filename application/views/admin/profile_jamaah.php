<style>
body {
	margin:auto;
	padding:auto;
	font-family:calibri, verdana;
}
table.base {
	font-size:10pt;
	font-family:calibri, verdana;
	line-height:1.3em;
	color:#333333;
}
table.base td {
	padding: 7px;
	border-top:1px solid #d9d9d9;
}
.collage {
	color:#999999;
	font-weight:bold;
}
.no_border {
	padding: -7px !important;
	border-top:none !important;
}
.no_magrin {
	margin:-7px;
}
div.thumb {
	margin: 10px;
	border: 1px solid #A0ACC0;
	height: auto;
	float: left;
}
.thumb img{
	display: inline;
	margin: 4px;
	border: 1px solid #A0ACC0;
}
.thumb a:hover img {border: 1px solid #5E708C;}
.thumb:hover { border: 1px solid #5E708C; }

.info {
	border-bottom:1px solid #d9d9d9;
	background:#f2f2f2;
	padding:3px 3px 3px 10px;
	font-weight:bold;
	color:#333333;
}
.blok {
	background:#f2f2f2;
	font-weight:bold;
	color:#333333;
	padding:2px 5px 2px 5px;
}
</style>
<?
if(!isset($foto)) { $foto = NULL; $data = NULL; }
if(!isset($khusus_kursi)) { $khusus_kursi = 0; }
if(!isset($khusus_asisten)) { $khusus_asisten = 0; }
if(!isset($perihal_darah)) { $perihal_darah = 0; }
if(!isset($perihal_tinggi)) { $perihal_tinggi = 0; }
if(!isset($perihal_smooking)) { $perihal_smooking = 0; }
if(!isset($perihal_jantung)) { $perihal_jantung = 0; }
if(!isset($perihal_asma)) { $perihal_asma = 0; }
if(!isset($perihal_mendengkur)) { $perihal_mendengkur = 0; }
if(!isset($id_candidate)) { $id_candidate = NULL; }
if(!isset($kode_reg)) { $kode_reg = NULL; }

$ceklist = "<img src='".base_url()."images/shared/icon_checklist_16px.gif' align='left'>";
$unceklist = "<img src='".base_url()."images/shared/icon_unchecklist_16px.gif' align='left'>";

$file_gambar = './images/upload/foto/'.$foto;
if(is_file($file_gambar))
{ 
	$url_gambar = base_url().'images/upload/foto/'.$foto;
	$url_gambar2 = $url_gambar;
}else{
	$url_gambar = base_url().'images/shared/user_x.png'; 
	$url_gambar2 = "#";
}
?>
<title><? if(isset($nama_jamaah)) { echo $nama_jamaah; } ?> bin <? if(isset($ayah_kandung)) { echo $ayah_kandung; } ?></title>
<div class="info">Profil Jamaah</div>
<table align="center">
<tr><td><div class="thumb">
  <img src="<? echo $url_gambar; ?><? if(isset($hotel_md)) { echo $hotel_md; } ?>" height="150" width="120" border="2" />
</div></td></tr>
</table><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="base">
  <tr>
    <td width="25%" class="collage">Nama</td>
    <td><? if(isset($nama_jamaah)) { echo $nama_jamaah; } ?></td>
  </tr>
  <tr>
    <td class="collage">Jenis Kelamin</td>
    <td><? if(isset($jenkel)) { echo $jenkel; } ?></td>
  </tr>
  <tr>
    <td class="collage">Tempat, Tgl Lahir</td>
    <td><? if(isset($tempat_lahir)) { echo $tempat_lahir; } ?>, <? if(isset($tgl_lahir)) { echo $tgl_lahir; } ?></td>
  </tr>
  <tr>
    <td class="collage">Ayah Kandung</td>
    <td><? if(isset($ayah_kandung)) { echo $ayah_kandung; } ?></td>
  </tr>
  <tr>
    <td class="collage">Alamat</td>
    <td><? if(isset($alamat)) { echo $alamat; } ?> - <? if(isset($kota)) { echo $kota; } ?></td>
  </tr>
  <tr>
    <td class="collage">Warga Negara</td>
    <td><? if(isset($warga_negara)) { echo $warga_negara; } ?></td>
  </tr>
  <tr>
    <td class="collage">Telp / HP</td>
    <td><? if(isset($tlp)) { echo $tlp; } ?> / <? if(isset($hp)) { echo $hp; } ?></td>
  </tr>
  <tr>
    <td class="collage">Ukuran Baju</td>
    <td><? if(isset($ukuran_baju)) { echo $ukuran_baju; } ?></td>
  </tr>
  <tr>
    <td class="collage">Pelayanan Khusus</td>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="base no_magrin">
      <tr>
        <td width="31%" class="no_border"><? echo ($khusus_kursi!=0) ? $ceklist : $unceklist ?>Kursi Roda</span></td>
        <td class="no_border"><? echo ($khusus_asisten!=0) ? $ceklist : $unceklist ?>Asisten Anak Khusus</span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class="collage">Perihal Pribadi</td>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="base no_magrin">
      <tr>
        <td class="no_border"><? echo ($perihal_darah!=0) ? $ceklist : $unceklist ?>Darah Tinggi</td>
        <td class="no_border"><? echo ($perihal_tinggi!=0) ? $ceklist : $unceklist ?>Takut Ketinggian</td>
        <td class="no_border"><? echo ($perihal_smooking!=0) ? $ceklist : $unceklist ?>Perokok</td>
        </tr>
      <tr>
        <td class="no_border"><? echo ($perihal_jantung!=0) ? $ceklist : $unceklist ?>Jantung</td>
        <td class="no_border"><? echo ($perihal_asma!=0) ? $ceklist : $unceklist ?>Asma</td>
        <td class="no_border"><? echo ($perihal_mendengkur!=0) ? $ceklist : $unceklist ?>Mendengkur</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td class="collage">Mahram</td>
    <td><? if(isset($nama_relasi)) { echo $nama_relasi; } ?></td>
  </tr>
  <tr>
    <td class="collage">Status</td>
    <td><? if(isset($status)) { echo $status; } ?></td>
  </tr>
  <tr>
    <td class="collage">Paspor</td>
    <td><a style="cursor:pointer" onClick="window.open('<?php echo site_url().'/admin/data_jamaah/paspor_view/'.$id_candidate.'/'.$kode_reg; ?>','paspor','width=600,height=210,left=350,top=100,screenX=350,screenY=100')">Lihat Paspor</a></td>
  </tr>
</table>