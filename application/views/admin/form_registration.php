<form method="post" action="" >
<table border="0" width="100%" cellpadding="0" cellspacing="0" onload="showRecaptcha('recaptcha_div');">
	<tr valign="top">
		<td>

			
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				<tr>
					<? form_error('nama') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Nama (*)</th>
					<td><input type="text" name="nama" value="<?php echo $NAMA_USER;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('nama') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('nama'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('email') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">E-mail (*)</th>
					<td><input type="text" name="email" class="<? echo $class;?>" value="<?php echo $EMAIL; ?>" /></td>
					<td>
						<? if(form_error('email') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('email'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('telp') == '' || form_error('mobile') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Telp / Mobile (*)</th>
					<td><input type="text" name="telp" value="<?php echo $TELP?>" class="<? echo $class;?>" /> &nbsp; / &nbsp;<input type="text" name="mobile" value="<?php echo $MOBILE;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('telp') != '' || form_error('mobile') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('telp').' '.form_error('mobile'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Propinsi (*)</th>
					<td>	
						<? $province = 0; if($ID_PROPINSI!='') $province = $ID_PROPINSI;
						
							echo form_dropdown('province', $province_options, $province,'id="province" class="styledselect_form_1"'); ?>
					</td>
					<td>
						<? if(form_error('province') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('province'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('kota') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Kota (*)</th>
					<td><input type="text" name="kota" value="<?php echo $KOTA;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('kota') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kota'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('alamat') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">Alamat (*)</th>
					<td><input type="text" name="alamat" value="<?php echo $ALAMAT;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('alamat') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('alamat'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<? form_error('id_card') == '' ? $class = 'inp-form':$class = 'inp-form-error'; ?>
					<th valign="top">No ID Card (i.e. KTP *)</th>
					<td><input type="text" name="id_card" value="<?php echo $NO_ID_CARD;?>" class="<? echo $class;?>" /></td>
					<td>
						<? if(form_error('id_card') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('id_card'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th valign="top">Group</th>
					<td>	
						<? $group = 0; if($ID_GROUP!='') $group = $ID_GROUP;
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
						<? $program = 0; if($ID_PROGRAM !='') $program = $ID_PROGRAM;
							echo form_dropdown('program', $program_options, $program,'id="program" class="styledselect_form_1"'); ?>
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
					<th valign="top">Jumlah Dewasa (*)</th>
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
					<th valign="top">Anak Dengan Ranjang</th>
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
					<th valign="top">Anak Tanpa Ranjang</th>
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
					<th valign="top">Bayi</th>
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
										<select name="kamar[]" id="kamar" class="styledselect-kamar" onchange="selected()">
											<option value="0">-- Pilih Jenis Kamar</option>
											<?php foreach ($room_options as $key=>$value){ ?>
												<option value="<?=$key?>"><?=$value?></option>
											<? } ?>
										</select>
										<div id="dvFile2"></div>
									</td>
									<td>
										&nbsp; Jumlah 
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
						<? if(form_error('kamar[]') != '') {?>
						<div class="error-left"></div>
						<div class="error-inner"><?php echo form_error('kamar[]'); ?></div>
						<? }?>
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td valign="top">
						<input type="submit" value="" class="form-submit" />
					</td>
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

<? echo form_close(); ?>

<script>
function _add_more() {
		var index = document.getElementsByName('kamar[]');
		if (index.length < 5){
		var txt = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr valign=\"middle\"><td><select name=\"kamar[]\" id=\"kamar"+index.length+
					"\" class=\"styledselect-kamar\"><option value=\"0\">-- Pilih Jenis Kamar --</option></select></td>"+
					"<td>&nbsp; Jumlah <select name=\"jml_kamar[]\" id=\"jml_kamar"+index.length+"\" class=\"styledselect-day\">"+
					"<option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option></select></td></tr></table>";
					
		document.getElementById("dvFile").innerHTML += txt;		
		loadkamar();
		}
	}
	
	function loadkamar() {
		var count = document.getElementsByName('kamar[]');
	    $.ajax({
	           url: "<?=base_url();?>index.php/check_availability/getKamar/",
	           global: false,
	           type: "POST",
	           async: false,
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
</script>
		 