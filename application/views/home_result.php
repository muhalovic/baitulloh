<!-- CSS EXTEND -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/button_reg.css" />

<div class="center">
<? 
	if(isset($msg_box1)) { echo $msg_box1; } 
	if(isset($msg_box2)) { echo $msg_box2; } 
	if(isset($msg_box3)) { echo $msg_box3; } 
	if(isset($status_waiting)) { echo $status_waiting; } 
?>                        

	
    <? echo form_open('/beranda/choose_packet',array('name' => 'form_registrasi', 'style' => 'width:100%')); ?>
    <div style="display: none;" >
        <input type="text" name="group" value="<? if(isset($group)) { echo $group; } ?>" />
        <input type="text" name="program" value="<? if(isset($program)) { echo $program; } ?>" />
        <input type="text" name="jml_adult" value="<? if(isset($jml_adult)) { echo $jml_adult; } ?>" />
        <input type="text" name="with_bed" value="<? if(isset($with_bed)) { echo $with_bed; } ?>" />
        <input type="text" name="no_bed" value="<? if(isset($no_bed)) { echo $no_bed; } ?>" />
        <input type="text" name="infant" value="<? if(isset($infant)) { echo $infant; } ?>" />
        <input type="text" name="packet" value="<? if(isset($packet)) { echo $packet; } ?>" />
        <input type="text" name="waiting_list" value="<? if(isset($waiting_list)) { echo $waiting_list; } ?>" />
        <? if(isset($input_kamar)) { echo $input_kamar; } ?>
    </div>
    
	<? if($waiting_list == 0) { ?>
    <br /><br />
    <div class="registration_link">
            <input type="submit" value="Proses" name="submit"/>
             <input type="button" value="Kembali" onclick="javascript:window.location='<? echo site_url().'/beranda'; ?>';"/>
        </center>
        <br />
    </div>
	
	<? } echo form_close(); ?>
	
</div>