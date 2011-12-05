<style>
body {
	color:#000;
	font-family:calibri, verdana;
}
table {
	font-size:8pt;
}
table.base {
	border-width: 1px;
	border-spacing: 1px;
	border-style: solid;
	border-color: #000;
	border-collapse: collapse;
}
table.base td {
	padding: 2px 1px 2px 5px;
	border-width: 1px;
	border-style: solid;
	border-color: #000;
}

.head {
	background:#d4d4d4;
	font-weight:bold;
}
.logo_bg {
	/*background:url('<? echo base_url().'/images/shared/header-email.png'; ?>') left no-repeat;*/
	float:right;
	width:226px;
	height:50px;
}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15%" align="left" valign="middle"></td>
	<td width="40%" align="center" valign="middle">
		<strong>PRAMANIFEST UMRAH KAMILAH WISATA<br />
		<? if(isset($nama_group)) { echo $nama_group; } ?> - <? if(isset($nama_program)) { echo $nama_program; } ?><br>
      JKT-JED <? if(isset($tgl_berangkat)) { echo $tgl_berangkat; } ?> - JED-JKT <? if(isset($tgl_pulang)) { echo $tgl_pulang; } ?></strong></td>
    <td width="30%" align="right" valign="middle"><div class="logo_bg"><img src="<? echo base_url().'/images/shared/header-logo.png'; ?>" height="40" width="200"></div></td>
  </tr>
</table>
<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td><table width="48%" border="0" cellspacing="0" cellpadding="0" class="base">
      <tr>
        <td rowspan="2" align="left" valign="middle" width="50%"><strong>
          <? if(isset($maskapai)) { print_r($maskapai); } ?>
        </strong></td>
        <td align="left" valign="middle" width="15%">&nbsp;</td>
        <td align="left" valign="middle" width="15%">&nbsp;</td>
        <td align="left" valign="middle" width="20%">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle">&nbsp;</td>
        <td align="left" valign="middle">&nbsp;</td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>Hotel Makkah</strong></td>
        <td colspan="3" align="left" valign="middle"><? if(isset($hotel_mk)) { echo $hotel_mk; } ?></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>Hotel Madinah</strong></td>
        <td colspan="3" align="left" valign="middle"><? if(isset($hotel_md)) { echo $hotel_md; } ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="base">
    <tr class="head">
      <strong><td align="center" valign="middle" width="4%">No</td>
      <td align="center" valign="middle" width="20%">Nama</td>
      <td align="center" valign="middle" width="5%">Gender</td>
      <td align="center" valign="middle" width="10%">Place of Birth</td>
      <td align="center" valign="middle" width="9%">Date of Birth</td>
      <td align="center" valign="middle" width="10%">No. Pasport</td>
      <td align="center" valign="middle" width="9%">Date of Issue</td>
      <td align="center" valign="middle" width="9%">Date of Expired</td>
      <td align="center" valign="middle" width="12%">Issuing Office</td>
      <td align="center" valign="middle" width="12%">Mahram</td></strong>
    </tr>
    <? echo $list_jamaah; ?>
</table>

<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" valign="middle" width="90%">Jakarta, <? echo date("d M Y"); ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="60" align="center" valign="middle">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center" valign="middle"><u> <strong>RICKY SANJAYA</strong></u></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>