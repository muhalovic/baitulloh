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
		<?php echo form_open('check_availability/do_check'); ?>
			<div class="row">
				<label class="col1">Grup Keberangkatan</label>
				<span class="col2">
					<?php 
						$group = 0; if(set_value('group')!='') $group = set_value('group');
						echo form_dropdown('group', $group_options, $group,'id="group" class="dropdown_medium" onChange="get_group();"'); 
					?>
				</span>
				<? if(form_error('group') != '') {?>
					<label class="col1"> &nbsp; </label>
					<span class="col2"><div class="error"><?php echo form_error('group'); ?></div></span>
				<? }?>
			</div>

			<div class="row">
				<label class="col1">Kelas Program</label>
				<span class="col2">
					<?php 
						$program = 0; if(set_value('program')!='') $program = set_value('program');
						echo form_dropdown('program', $program_options, $program,'id="program" class="dropdown_medium"'); 
					?>
				</span>
			</div>
			
			<div class="row">
				<label class="col1">Jumlah Dewasa</label>
				<span class="col2">
					<input type="text" name="jml_adult" value="<?php echo set_value('jml_adult');?>" class="input_small" />
					<label>( di atas 11 tahun )</label>
				</span>
			</div>
			
			<div class="row">
				<label class="col1">Anak Dengan Ranjang</label>
				<span class="col2">
					<input type="text" name="with_bed" value="<?php echo set_value('with_bed');?>" class="input_small" />
					<label>( 23 Bulan - 11 Tahun )</label>
				</span>
			</div>
			
			<div class="row">
				<label class="col1">Anak Tanpa Ranjang</label>
				<span class="col2">
					<input type="text" name="no_bed" value="<?php echo set_value('no_bed');?>" class="input_small" />
					<label>( 23 Bulan - 11 Tahun )</label>
				</span>
			</div>
			
			<div class="row">
				<label class="col1">Bayi</label>
				<span class="col2">
					<input type="text" name="infant" value="<?php echo set_value('infant');?>" class="input_small" />
					<label>( 0 - 23 Bulan )</label>
				</span>
			</div>
			
			<div class="row">
				<label class="col1">Konfigurasi Kamar</label>
				<span class="col2">
					<div id="dvFile">
					<?php $kamar = 0; if(set_value('kamar')!='') $kamar = set_value('kamar');?>
						<select name="kamar[]" id="kamar" class="dropdown_small">
							<option value="0">-- Pilih Kamar --</option>
							<?php foreach ($room_options as $key=>$value){ ?>
								<option value="<?=$key?>"><?=$value?></option>
							<? } ?>
						</select>
					<label>Jumlah</label>
					<select name="jml_kamar[]" id="jml_kamar" class="dropdown">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>								
					<a href="javascript:_add_more();" id="add-more"><img src="<?php echo base_url();?>images/front/icon_plus.gif" width="16" height="16" valign="middle" alt="" /></a>	
					</div>
				</span>
			</div>
			
			<div class="row">
				<label class="col1">&nbsp;</label>
				<span class="col2">
					<input type="submit" value="Periksa" class="submit_button" />
					<input type="reset" value="Reset" class="reset_button" />
				</span>
			</div>
		<? echo form_close(); ?>
	</div>
	<!-- END LEFT SIDE -->
	
	<!-- RIGHT SIDE -->
	<div class="content_right">
		<div class="info_shape">
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Informasi Registrasi</span>
			</div>
			
			<div align="center">
				<p>"Calon Jamaah harus memiliki Paspor asli minimal <strong class="bold">6 bulan</strong> masa berlaku dengan nama <strong class="bold">3 suku kata.</strong>"</p>
			</div>
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Keterangan Grup & Kelas Keberangkatan</span>
			</div>
			
			<div align="center">
				<p>"Pilih Dahulu Grup Keberangkatan & Kelas Program"</p>
			</div>
			
			<!-- On change bagian ini -->
			<div>
				<p>
					<div>Keberangkatan :</div>
					<div>Maskapai :</div>
					<div>Hotel :</div>
					<div>Transportasi :</div>
					<div>Kamar :</div>
				</p>
			</div>
			<!----->
			
			<div class="title">
				<img src="<?php echo base_url();?>images/front/title.png" width="16" height="16" alt="" />
				<span class="text_title">Informasi Batas Akhir</span>
			</div>
			
			<div align="center">
				<p>"Pilih Dahulu Grup Keberangkatan & Kelas Program"</p>
			</div>
			
			<!-- On change bagian ini -->
			<div>
				<p>
					<div>Batas Akhir Uang Muka :</div>
					<div>Batas Akhir Pelunasan :</div>
					<div>Upload Data Paspor :</div>
					<div>Pengumpulan Berkas Fisik :</div>
				</p>
			</div>
			<!----->
			
		</div>
		
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
                $.ajax({
                        url: "<?=base_url();?>index.php/check_availability/getGroup/",
                        global: false,
                        type: "POST",
                        async: false,
                        dataType: "html",
                        data: "id_group="+ prp, //the name of the $_POST variable and its value
                        success: function (response) {
							 var bahan = response;
							 var pecah = bahan.split("#");
							 
                             //document.getElementById('info_jd').innerHTML = pecah[0];
							 document.getElementById('info_mk').innerHTML = pecah[1];
							 document.getElementById('info_paspor').innerHTML = pecah[2];
							 document.getElementById('info_lunas').innerHTML = pecah[3];
							 document.getElementById('info_dp').innerHTML = pecah[4];
							 document.getElementById('info_berkas').innerHTML = pecah[5];
							 document.getElementById('info_kode').innerHTML = pecah[6];
							 document.getElementById('info_ket').innerHTML = pecah[7];
                                                         document.getElementById('info_ga').innerHTML = pecah[8]+" Seat(s)";
                                                         document.getElementById('info_sv').innerHTML = pecah[9]+" Seat(s)";
                        }
                });
				return false;
	}
</script>