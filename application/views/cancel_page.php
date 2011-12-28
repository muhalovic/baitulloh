<?php
$msg = "PEMBATALAN

a. Pembatalan 30 hari sebelum keberangkatan 25% dari biaya Umrah

b. Pembatalan 14 hari sebelum keberangkatan 50% dari biaya Umrah

c. Pembatalan 07 hari sebelum keberangkatan 75% dari biaya Umrah

d. Pembatalan 03 hari sebelum keberangkatan 100% dari biaya Umrah";

echo $notifikasi;

?>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>			
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<td colspan="3">
                    <textarea class="form-textarea-front" disabled="disabled">
                    	<? echo ($msg); ?>
                    </textarea>
                      </td>
				</tr>
                <tr>
                	<td colspan="3">
                    	<br /><br /><br />
                        <div class="garis_pisah"> PEMBATALAN JAMAAH</div>
                    </td>
                </tr>
                <? echo form_open('cancel/do_send_jamaah/'); ?>
                <? echo $data_jamaah; ?>
                
				<tr height="90">
					<th></th>
					<td>
					  <input type="submit" id="submit" value="" class="form-submit-front" <? if (isset($disabled)) echo $disabled ?>/>
					</td>
					<td></td>
				</tr>
                
                <? echo form_close(); ?>
                
                
                <tr>
                	<td colspan="3">
                    	<br /><br /><br />
                        <div class="garis_pisah"> PEMBATALAN PAKET</div>
                    </td>
                </tr>
                
                <? echo form_open('cancel/do_send_packet/'); ?>
                
                <tr>
					<td colspan="3" align="left">
                    	<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
                            <tr>
                                <th valign="top">Paket</th>
                                <td>	
                                    <strong><? if (isset($nama_group)) echo $nama_group ?> - 
									<? if (isset($nama_program)) echo $nama_program ?></strong>
                                </td>
                                <td></td>
                            </tr>
                            <tr>					
                                <th valign="top">Jumlah Dewasa (*)</th>
                                <td>	
                                    <? if (isset($adult)) echo $adult ?> Orang
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th valign="top">Anak Dengan Ranjang</th>
                                <td>	
                                    <? if (isset($with_bed)) echo $with_bed ?> Orang
                                </td>
                                <td></td>
                            </tr> 
                            <tr>
                                <th valign="top">Anak Tanpa Ranjang</th>
                                <td>	
                                    <? if (isset($no_bed)) echo $no_bed ?> Orang
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th valign="top">Bayi</th>
                                <td>	
                                    <? if (isset($infant)) echo $infant ?> Orang
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th valign="top">Kamar</th>
                                <td class="noheight">
                                    <div id="dvFile">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <?php foreach ($room as $row) {?>
                                            <tr valign="middle">
                                                <td><strong><? echo $row->JENIS_KAMAR." &nbsp; "; ?></strong></td>
                                                <td>untuk <? echo $row->JUMLAH; ?> Orang</td>
                                            </tr>
                                            <? }?>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <td valign="top"></td>
                                <td></td>
                            </tr>
                        </table>
					</td>
				</tr>
				<tr height="90">
					<th></th>
					<td>
                    	<input type="hidden" name="id_packet" value="<? if (isset($id_packet)) echo $id_packet ?>" />
						<input type="submit" id="submit" value="" class="form-submit-front"/>
					</td>
					<td></td>
				</tr>
                
                <? echo form_close(); ?>
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
							<h5>Ketentuan Pembatalan Akun <div id="div_room"></div></h5>							
						</div>
											
						
						<div class="right" id="div_prev">
							<ul class="greyarrow"><li>Hati-Hati melakukan pembatalan Akun, harap dibaca dan dipelajari keterangan dan syarat-syarat disamping. </li></ul>
                            <ul class="greyarrow"><li>Pastikan Anda benar-benar akan membatalkan paket umrah dan semua calon jamaah.</li></ul>
                            <ul class="greyarrow"><li>Anda akan menerima Email informasi pembatalan sesaat setelah menyetujui dan mengklik tombol Submit. </li></ul>
						</div>
							
						<div class="clear"></div>
						<div class="lines-dotted-short"></div>	
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

<script type="text/javascript">

function goSubmit(input)
{
	alert(+input.valu);
	if(input.checked)
	{
		document.getElementById('submit').disabled=false;
	} else {
		document.getElementById('submit').disabled=true;
	}
}



function CekList(input)
{
	var pid = input.value;
	
	if(input.checked)
	{
		document.getElementById('keterangan_'+pid).value='';
		document.getElementById('keterangan_'+pid).disabled=false;
	} else {
		document.getElementById('keterangan_'+pid).value='';
		document.getElementById('keterangan_'+pid).disabled=true;
	}
}

</script>	 