<?php echo form_open('beranda/do_check'); ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<th valign="top">Group</th>
					<td>	
						<? $group = 0; if(set_value('group')!='') $group = set_value('group');
							echo form_dropdown('group', $group_options, $group,'id="group" class="styledselect-group" onChange="get_group();"'); ?>
					</td>
					<td>
						<? if(form_error('group') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('group'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Kelas Program</th>
					<td>	
						<? $program = 0; if(set_value('program')!='') $program = set_value('program');
							echo form_dropdown('program', $program_options, $program,'id="program" class="styledselect-group"'); ?>
					</td>
					<td>
						<? if(form_error('program') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('program'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('jml_adult') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Jumlah Adult (*)</th>
					<td><input type="text" name="jml_adult" value="<?php echo set_value('jml_adult');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('jml_adult') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('jml_adult'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('with_bed') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Child With Bed</th>
					<td><input type="text" name="with_bed" value="<?php echo set_value('with_bed');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('with_bed') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('with_bed'); ?></div>
						<? }?>
					</td>
				</tr> 
				<tr>
					<? form_error('no_bed') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Child No Bed</th>
					<td><input type="text" name="no_bed" value="<?php echo set_value('no_bed');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('no_bed') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('no_bed'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('infant') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Infant</th>
					<td><input type="text" name="infant" value="<?php echo set_value('infant');?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('infant') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('infant'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Kamar</th>
					<td class="noheight">
						<div id="dvFile">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr valign="middle">
									<td>
									<? $kamar = 0; if(set_value('kamar')!='') $kamar = set_value('kamar');
										//echo form_dropdown('kamar', $room_options, $kamar,'id="kamar" class="styledselect_form_1"'); ?>
										<select name="kamar[]" id="kamar" class="styledselect-kamar">
											<?php foreach ($room_options as $key=>$value){ ?>
												<option value="<?=$value?>"><?=$value?></option>
											<? } ?>
										</select>
										<div id="dvFile2"></div>
									</td>
									<td>
										&nbsp; Jumlah :
										<select name="jml_kamar[]" id="jml_kamar" class="styledselect-day">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
										</select>&nbsp;										
										<a href="javascript:_add_more();" id="add-more"><img src="<?php echo base_url();?>images/forms/icon_plus.gif" alt="" /></a>										
										<div id="dvFile3"></div>
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td>
						<? if(form_error('kamar') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kamar'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr height="50">
					<th valign="top"></th>
					<td>
                    <input type="checkbox" name="cek_setuju" value="1"  class="<? echo $class;?>" <? echo set_checkbox('cek_setuju', '1'); ?> /> 
                    <? $text_cek = "Ya, Saya Setuju"; form_error('cek_setuju') == '' ? $msg = $text_cek:$msg = '<label>'.$text_cek.'</label>'; ?>
                    	&nbsp;&nbsp;<strong><? echo $msg; ?></strong></td>
					<td>
						<? if(form_error('cek_setuju') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('cek_setuju'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th></th>
					<td valign="top">
						<input type="submit" value="" class="form-submit" />
					</td>
					<td></td>
				</tr>
			</table>
			<!-- end id-form  -->
		</td>
		
		<td>
			<!--  start related-activities -->
			<div id="related-activities">
				
				<!--  start related-act-top -->
				<div id="related-act-top-front"><span class="related-act-top-front">Information</span>
				</div>
				<!-- end related-act-top -->
					
				<!--  start related-act-bottom -->
				<div id="related-act-bottom">
					<!--  start related-act-inner -->
					<div id="related-act-inner">
						<div class="left"><a href=""><img src="<?php echo base_url();?>images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
						<div class="right">
							<h5>Keterangan Group <a id="info_kode"></a></h5>
                            <br /><i><div id="info_ket"></div></i>
								
                                <br />Keberangkatan <strong>Jeddah</strong> :
							<ul class="greyarrow">
								<li><a id="info_jd"></a>&nbsp;</li> 
							</ul>
                              <br />  Keberangkatan <strong>Mekkah</strong> :
							<ul class="greyarrow">
								<li><a id="info_mk"></a>&nbsp;</li> 
							</ul>
                            <div class="lines-dotted-short"></div>	
                               Batas Akhir Pembayaran Uang Muka:
							<ul class="greyarrow">
								<li><a id="info_dp"></a>&nbsp;</li> 
							</ul>
                            <div class="lines-dotted-short"></div>	
                               Batas Akhir Pelunasan:
							<ul class="greyarrow">
								<li><a id="info_lunas"></a>&nbsp;</li> 
							</ul>
                            <div class="lines-dotted-short"></div>	
                                Batas Akhir Upload Data Passport:
							<ul class="greyarrow">
								<li><a id="info_paspor"></a>&nbsp;</li> 
							</ul>
                            <div class="lines-dotted-short"></div>	
                                Batas Akhir Pengumpulan Berkas Fisik:
							<ul class="greyarrow">
								<li><a id="info_berkas"></a>&nbsp;</li> 
							</ul>
						</div>
							
						<div class="clear"></div>
						<div class="lines-dotted-short"></div>
							
						<div class="left"><a href=""><img src="<?php echo base_url();?>images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
						<div class="right">
							<h5>Total Biaya</h5>
							<ul class="greyarrow">
								<li>Rp. 30.000.000,-</li>
							</ul>
						</div>
						
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

<script>
	function _add_more() {
		var index = document.getElementsByName('kamar[]');
		var txt = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr valign=\"middle\"><td><select name=\"kamar[]\" id=\"kamar"+index.length+
					"\" class=\"styledselect-kamar\"><option value=\"0\">-- Pilih Jenis Kamar --</option></select></td>"+
					"<td>&nbsp; Jumlah :&nbsp;<select name=\"jml_kamar[]\" id=\"jml_kamar"+index.length+"\" class=\"styledselect-day\">"+
					"<option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option></select></td></tr></table>";
		
		var txt2 = "<select name=\"kamar[]\" id=\"kamar"+index.length+
					"\" class=\"styledselect-kamar\"><option value=\"0\">-- Pilih Jenis Kamar --</option></select><br/>";
					
		var txt3 = "&nbsp; Jumlah :<select name=\"jml_kamar[]\" id=\"jml_kamar"+index.length+"\" class=\"styledselect-day\">"+
					"<option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option></select><br/>";
		document.getElementById("dvFile").innerHTML += txt;
		//document.getElementById("dvFile2").innerHTML += txt2;
		//document.getElementById("dvFile3").innerHTML += txt3;
		
		loadkamar();
	}
	
	function loadkamar() {
		var count = document.getElementsByName('kamar[]');
	    $.ajax({
	           url: "<?=base_url();?>index.php/check_availability/getKamar/",
	           global: false,
	           type: "POST",
	           async: false,
	           //dataType: "html",
	           //data: "produsen="+produsen +"&no_serti="+noserti +"&varietas="+varietas +"&kls_benih="+kls_benih, //the name of the $_POST variable and its value
	           success: function (response) //'response' is the output provided by the controller method prova()
	                    {
							//counts the number of dynamically generated options
							var dynamic_options = $("*").index( $('.dynamic4')[0] );
							//removes previously dynamically generated options if they exists (not equal to 0)
							if ( dynamic_options != (-1)) $(".dynamic4").remove();
								
							for (i = 0; i < count.length; i++){
								$("select#kamar"+i).append(response);
							}
							
		                    $(".selected").attr({selected: ' selected'});
	                   }
	                   
	          });
	    
	          return false;
	}
	
	function get_group() {
		
		var prp = $("#group").val();
                $.ajax({
                        url: "<?=base_url();?>index.php/beranda/getGroup/",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_group="+ prp, //the name of the $_POST variable and its value
                        success: function (response) {
							 var bahan = response;
							 var pecah = bahan.split("#");
							 
                             document.getElementById('info_jd').innerHTML = pecah[0];
							 document.getElementById('info_mk').innerHTML = pecah[1];
							 document.getElementById('info_paspor').innerHTML = pecah[2];
							 document.getElementById('info_lunas').innerHTML = pecah[3];
							 document.getElementById('info_dp').innerHTML = pecah[4];
							 document.getElementById('info_berkas').innerHTML = pecah[5];
							 document.getElementById('info_kode').innerHTML = pecah[6];
							 document.getElementById('info_ket').innerHTML = pecah[7];
                        }
                });
              return false;
		
	}
	
</script>