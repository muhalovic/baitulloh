<?php echo $added_js; ?>
<?php echo $js_grid;
	if(isset($notifikasi)){
	
	 echo '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">'.$notifikasi.'</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" onclick = "window.location  = \''.base_url().'index.php/admin/data_jamaah'.'\'" /></a></td>
							</tr>
						</table><br>
					</div>';
							}	
	 if(isset($error)){
	  echo '<div id="message-blue">
								<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td class="blue-left">'.$error.'</td>
										<td class="blue-right"><a class="close-blue"><img src="'.base_url().'images/table/icon_close_blue.gif"   alt="" /></a></td>
									</tr>
								</table><br>
							</div>';
							}
 ?>
<div id="contentgrid">
<table id="flex1" style="display:none"></table>
</div>
