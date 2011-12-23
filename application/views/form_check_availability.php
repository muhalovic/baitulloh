<!--  start step-holder -->
<div class="step-holder">
	<div class="step-no">1</div>
	<div class="step-dark-left">Periksa Ketersediaan</a></div>
	<div class="step-dark-right">&nbsp;</div>
	<div class="step-no-off">2</div>
	<div class="step-light-left">Hasil Ketersediaan</div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">3</div>
	<div class="step-light-left">Pendaftaran</div>
	<div class="step-light-right">&nbsp;</div>
	<div class="step-no-off">4</div>
	<div class="step-light-left">Selesai</div>
	<div class="step-light-round">&nbsp;</div>
	<div class="clear"></div>
</div>
<!--  end step-holder -->

<div class="center">
	<!-- LEFT SIDE -->
	<div class="content_left">
		<?php $attr = array('name' => 'check', 'id' => 'myform'); echo form_open('hasil', $attr); ?>
			<div class="row">
				<? if(form_error('group') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('group'); ?></div></span>
				<? }?>
				<label class="col1">Grup Keberangkatan</label>
				<span class="col2">
					<?php 
						$group = 0; if(set_value('group')!='') $group = set_value('group');
						echo form_dropdown('group', $group_options, $group,'id="group" class="chzn-select" onChange="get_group();" title="Nama Group dan Tanggal Keberangkatan"'); 
					?>
				</span>
			</div>

			<div class="row">
				<? if(form_error('program') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('program'); ?></div></span>
				<? }?>
				<label class="col1">Kelas Program</label>
				<span class="col2">
					<?php 
						$program = 0; if(set_value('program')!='') $program = set_value('program');
						echo form_dropdown('program', $program_options, $program,'id="program" class="dropdown_medium"  onChange="get_program();" title="Nama Kelas Program"'); 
					?>
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('jml_adult') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('jml_adult'); ?></div></span>
				<? }?>
				<label class="col1">Jumlah Dewasa</label>
				<span class="col2">
					<input type="text" name="jml_adult" value="<?php echo set_value('jml_adult');?>" class="input_small"  title="Harap diisi dengan angka" />
					<label><img src="<?php echo base_url();?>images/front/dewasa.png"/>&nbsp;( di atas 11 tahun )</label>
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('with_bed') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('with_bed'); ?></div></span>
				<? }?>
				<label class="col1">Anak Dengan Ranjang</label>
				<span class="col2">
					<input type="text" name="with_bed" value="<?php echo set_value('with_bed');?>" class="input_small" title="Harap diisi dengan angka" />
					<label><img src="<?php echo base_url();?>images/front/anak.png"/>&nbsp;( 23 bulan - 11 tahun )</label>
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('no_bed') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('no_bed'); ?></div></span>
				<? }?>
				<label class="col1">Anak Tanpa Ranjang</label>
				<span class="col2">
					<input type="text" name="no_bed" value="<?php echo set_value('no_bed');?>" class="input_small" title="Harap diisi dengan angka" />
					<label><img src="<?php echo base_url();?>images/front/anak.png"/>&nbsp;( 23 bulan - 11 tahun )</label>
				</span>
			</div>
			
			<div class="row">
				<? if(form_error('infant') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('infant'); ?></div></span>
				<? }?>
				<label class="col1">Bayi</label>
				<span class="col2">
					<input type="text" name="infant" value="<?php echo set_value('infant');?>" class="input_small" title="Harap diisi dengan angka" />
					<label><img src="<?php echo base_url();?>images/front/bayi.png"/>&nbsp;( 0 - 23 bulan )</label>
				</span>
			</div>
			
			<div class="row">
            	<? if(form_error('jml_kamar[]') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error_validation"><?php echo form_error('jml_kamar[]'); ?></div></span>
				<? }?>
				<label class="col1">Konfigurasi Kamar</label>
				<span class="col2">
				<table width="100%">
				<?php echo $room_options; ?>
				</table>
                <br />
				</span>
			</div>
			
			<div class="row">
				<label class="col1">&nbsp;</label>
				<span class="col2">
					<input type="submit" value="Lanjut >>" class="submit_button" />
					<input type="reset" value="Reset" class="reset_button" />
				</span>
			</div>
		<? echo form_close(); ?>
	</div>
	<!-- END LEFT SIDE -->
	
	<!-- RIGHT SIDE -->
	<div class="content_right">
		<div class="info_shape">
			<div align="right">
				<p><img src="<?php echo base_url();?>images/front/promo.png"/></p>
			</div>
		
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Informasi Registrasi</span>
			</div>
			
			<div align="center">
				<p><i>"Calon Jamaah harus memiliki Paspor asli minimal <strong class="bold">6 bulan</strong> masa berlaku dengan nama <strong class="bold">3 suku kata.</strong>"</i></p>
			</div>
			
			<div class="repeat_hline"></div>
			
			<div class="title">
				<p>
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Keterangan Grup & Kelas Keberangkatan <label style="color:#3b619f;">( Real Time )</label></span>
				</p>
			</div>
			
			<div align="center">
				<p><i>"Pilih Dahulu Grup Keberangkatan & Kelas Program"</i></p>
			</div>
			
			<!-- On change bagian ini -->
			<div id='front_keterangan' class="front_keterangan" style="display:none">
               <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="25%"><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Keberangkatan</td>
                    <td>: <strong><span id="info_mk"></span></strong></td>
                  </tr>
				  <tr>
                    <td><img src="<?php echo base_url();?>images/front/plane.png"/>&nbsp;Maskapai</td>
                    <td>: <strong><span id="maskapai"></span></strong></td>
                  </tr>
				  <tr>
                    <td><img src="<?php echo base_url();?>images/front/building.png"/>&nbsp;Hotel Makkah</td>
                    <td>: <strong><span id="hotel_mk"></span></strong></td>
                  </tr>
				  <tr>
                    <td><img src="<?php echo base_url();?>images/front/building.png"/>&nbsp;Hotel Madinah</td>
                    <td>: <strong><span id="hotel_jd"></span></strong></td>
                  </tr>
				  <tr>
                    <td><img src="<?php echo base_url();?>images/front/car.png"/>&nbsp;Transportasi</td>
                    <td>: <strong><span id="transportasi"></span></strong></td>
                  </tr>
				  <tr>
                    <td valign="top"><img src="<?php echo base_url();?>images/front/poin.png"/>&nbsp;Kamar</td>
                    <td>: <span id="info_kamar"></span><span id="info_kamar_2"></span></td>
                  </tr>
                </table>
				<div class="clear"></div>
			</div>
			<!----->
			
			<div class="repeat_hline"></div>
			
			<div class="clear"></div>
			
			<div class="title">
				<p>
					<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
					<span class="text_title">Informasi Batas Akhir</span>
				</p>
			</div>
			
			<div align="center">
				<p><i>"Pilih Dahulu Grup Keberangkatan & Kelas Program"</i></p>
			</div>

			<!-- On change bagian ini -->
			<div id='front_informasi' style="display:none">
                   <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="40%"><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Batas Akhir Uang Muka</td>
                        <td>: <strong><span id="info_dp"></span></strong></td>
                      </tr>
					  <tr>
                        <td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Batas Akhir Pelunasan</td>
                        <td>: <strong><span id="info_lunas"></span></strong></td>
                      </tr>
					  <tr>
                        <td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Upload Data Paspor</td>
                        <td>: <strong><span id="info_paspor"></span></strong></td>
                      </tr>
					  <tr>
                        <td><img src="<?php echo base_url();?>images/front/calendar.png"/>&nbsp;Pengumpulan Berkas Fisik</td>
                        <td>: <strong><span id="info_berkas"></span></strong></td>
                      </tr>
                    </table>
					<div class="clear"></div>
			</div>
			<!----->
			
			<div class="repeat_hline"></div>
			
			<div class="clear"></div>
			
		</div>
		<!-- END INFO SHAPE -->
		
	</div>
	<!-- END RIGHT SIDE -->
</div>

<script>
	function _add_more() 
	{
		var index = document.getElementsByName('kamar[]');
		if (index.length < 5){
		var txt = "<select name=\"kamar[]\" id=\"kamar"+index.length+"\" class=\"dropdown_small\"><option value=\"0\">-- Pilih Kamar --</option></select>"+
					"<label>&nbsp;Jumlah</label> <select name=\"jml_kamar[]\" id=\"jml_kamar"+index.length+"\" class=\"dropdown\">"+
					"<option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option></select>";
					
		document.getElementById("dvFile").innerHTML += txt;		
		loadkamar();
		}
	}
	
	function loadkamar() 
	{
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
	
	function get_group() 
	{	
		var prp = $("#group").val();
		var cek_program = document.getElementById('program').value;
		
                $.ajax({
                        url: "<?=base_url();?>index.php/check_availability/getGroup/",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_group="+ prp +"&id_program="+ cek_program, //the name of the $_POST variable and its value
                        success: function (response) {
							 var bahan = response;
							 var pecah = bahan.split("#");
							 
                             //document.getElementById('info_jd').innerHTML = pecah[0];
							 document.getElementById('info_mk').innerHTML = pecah[1];
							 document.getElementById('info_paspor').innerHTML = pecah[2];
							 document.getElementById('info_lunas').innerHTML = pecah[3];
							 document.getElementById('info_dp').innerHTML = pecah[4];
							 document.getElementById('info_berkas').innerHTML = pecah[5];
							 document.getElementById('info_kamar').innerHTML = pecah[6];
							 }
                });
					 
				document.getElementById('program').value=0;
				document.getElementById('front_keterangan').style.display="none";
				document.getElementById('front_informasi').style.display="none";
				/*if(+prp !=  0 && cek_program != 0)
					{
						document.getElementById('front_keterangan').style.display="inline";
						document.getElementById('front_informasi').style.display="inline";
					}else{
						document.getElementById('front_keterangan').style.display="none";
						document.getElementById('front_informasi').style.display="none";
					}
				return false;*/
	}
	
	function get_program() 
	{	
		var prp = $("#program").val();
		var cek_group = document.getElementById('group').value;
		
                $.ajax({
                        url: "<?=base_url();?>index.php/check_availability/getProgram/",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_program="+ prp +"&id_group="+ cek_group, //the name of the $_POST variable and its value
                        success: function (response) {
							 var bahan = response;
							 var pecah = bahan.split("#");
							 
                           document.getElementById('maskapai').innerHTML = pecah[0];
                           document.getElementById('hotel_mk').innerHTML = pecah[1];
                           document.getElementById('hotel_jd').innerHTML = pecah[2];
                           document.getElementById('transportasi').innerHTML = pecah[3];
                           document.getElementById('info_kamar').innerHTML = pecah[4];
									
									if(+prp != 0 && cek_group !=  0)
									{
										document.getElementById('front_keterangan').style.display="inline";
										document.getElementById('front_informasi').style.display="inline";
									}else{
										document.getElementById('front_keterangan').style.display="none";
										document.getElementById('front_informasi').style.display="none";
									}
                        }
                });
				return false;
	}
</script>