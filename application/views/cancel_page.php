<?php
$msg = "PEMBATALAN

a. Pembatalan 30 hari sebelum keberangkatan 25% dari biaya Umrah

b. Pembatalan 14 hari sebelum keberangkatan 50% dari biaya Umrah

c. Pembatalan 07 hari sebelum keberangkatan 75% dari biaya Umrah

d. Pembatalan 03 hari sebelum keberangkatan 100% dari biaya Umrah";

echo $notifikasi;

echo form_open('cancel/do_send'); ?>

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
                <? if($cek_valid) { ?>
				<tr height="50">
					<td>
                    <input type="checkbox" name="cek_setuju" id="cek_setuju" value="1" onchange="goSubmit(this)"/>  
                    &nbsp;&nbsp; <strong><label style="color:#000;" for="cek_setuju">Yakin dan Setuju</label></strong></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<th></th>
					<td valign="top">
						<input type="submit" id="submit" value="" class="form-submit-front" disabled="disabled"/>
					</td>
					<td></td>
				</tr>
                <? } else { ?>
				<tr height="50">
					<td><label><strong>Anda Sudah Melakukan Pembatalan.</strong></label></td>
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
<? echo form_close(); ?>	

<script type="text/javascript">

function goSubmit(input)
{
	if(input.checked)
	{
		document.getElementById('submit').disabled=false;
	} else {
		document.getElementById('submit').disabled=true;
	}
}

</script>	 