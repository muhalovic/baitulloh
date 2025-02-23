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
<div class="info">Paket Jamaah</div>
<table align="center">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<th valign="top">Nama User</th>
					<td>	
						<? echo $NAMA_USER; ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Alamat</th>
					<td>	
						<? echo $ALAMAT; ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Telepon / Mobile</th>
					<td>	
						<? echo $TELP.' / '.$MOBILE; ?>
					</td>
					<td></td>
				</tr>
				<?php 
				$counter = 1;
				foreach($paket as $row){?>
				<tr>
					<th valign="top">Grup<?php if(count($paket)>1) {echo '-'.$counter;}?></th>
					<td>	
						<?  echo $row->KODE_GROUP; ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Kelas Program<?php if(count($paket)>1) {echo '-'.$counter;}?></th>
					<td>	
						<?  echo $row->NAMA_PROGRAM; ?>
					</td>
					<td></td>
				</tr>
				<tr>					
					<th valign="top">Jumlah Dewasa (*)</th>
					<td>	
						<?  echo $row->JUMLAH_ADULT ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Anak Dengan Ranjang</th>
					<td>	
						<?  echo $row->CHILD_WITH_BED ?>
					</td>
					<td></td>
				</tr> 
				<tr>
					<th valign="top">Anak Tanpa Ranjang</th>
					<td>	
						<?  echo $row->CHILD_NO_BED ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Bayi</th>
					<td>	
						<?  echo $row->INFANT ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<th valign="top">Kamar</th>
					<td class="noheight">
						<div id="dvFile">
							<table border="0" cellpadding="0" cellspacing="0">
								<?php foreach ($room[$counter-1] as $room_row) {?>
								<tr valign="middle">
									<td><? echo $room_row->JENIS_KAMAR." - "; ?></td>
									<td>Jumlah : <? echo $room_row->JUMLAH; ?></td>
								</tr>
								<? }?>
							</table>
						</div>
					</td>
					<td></td>
				</tr>
				<?php 
				$counter++;
				} ?>
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
				<tr>
					<th></th>
					<td valign="top"></td>
					<td></td>
				</tr>
			</table>
			<!-- end id-form  -->
		</td>
		
		
	</tr>
	<tr>
		<td><img src="<?php echo base_url();?>images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
		<td></td>
	</tr>
</table>
		 
<div class="clear"></div>