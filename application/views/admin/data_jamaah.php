<?=$added_js?>
<?=$js_grid;?>
<table id="flex1" style="display:none"></table>

<div class="clear"></div>
<br /><br /><br /><br />
<div class="garis_pisah"> CETAK PRAMANIFEST</div>
<? $attr = array('name' => 'cetak'); echo form_open('/admin/data_jamaah/cetak/manifest/1', $attr); ?>

<table width="100%" align="left">
  <tr>
    <td width="420">	
        <? $group = 0; if(set_value('group')!='') $group = set_value('group');
            echo form_dropdown('group', $group_options, $group,'id="group" class="styledselect-biodata"') . " &nbsp;";
			
		   $program = 0; if(set_value('program')!='') $program = set_value('program');
			echo form_dropdown('program', $program_options, $program,'id="program" class="styledselect-biodata"'); 
		?>
    </td>
    <td>
    	<input type="button" name="btn" class="form-submit-pdf" onclick="jumptolink(document.cetak.group, document.cetak.program)" />
        &nbsp;&nbsp;&nbsp;
    	<input type="button" name="btn" class="form-submit-excel" onclick="jumptolink2(document.cetak.group, document.cetak.program)" />
    </td>
  </tr>
</table>
<? echo form_close() ?>

<script type="text/javascript">

function jumptolink(group, program)
{
	var selectedopt=group.options[group.selectedIndex];
	var selectedopt2=program.options[program.selectedIndex];
	
	if (+selectedopt.value != 0 && +selectedopt2.value != 0)
	window.location="<? echo site_url().'/admin/laporan/cetak/' ?>"+selectedopt.value+"/"+selectedopt2.value+"/1";
}

function jumptolink2(group, program)
{
	var selectedopt=group.options[group.selectedIndex];
	var selectedopt2=program.options[program.selectedIndex];
	
	if (+selectedopt.value != 0 && +selectedopt2.value != 0)
	window.location="<? echo site_url().'/admin/laporan/cetak_excel/' ?>"+selectedopt.value+"/"+selectedopt2.value;
}

</script>
