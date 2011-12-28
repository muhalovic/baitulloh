<?php echo $added_js; ?>
<?php echo $js_grid;
	if(isset($notifikasi)){
	
	 echo '<div id="message-green">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="green-left">'.$notifikasi.'</td>
				<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif" alt="" /></a></td>
			</tr>
		</table><br>
	</div>';
	}
	
	if(isset($error)){
	  echo '<div id="message-yellow">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="yellow-left">'.$error.'</td>
				<td class="yellow-right"><a class="close-yellow"><img src="'.base_url().'images/table/icon_close_yellow.gif" alt="" /></a></td>
			</tr>
		</table><br>
	</div>';
	}
 ?>
<div id="contentgrid">
<table id="flex1" style="display:none"></table>
</div>
