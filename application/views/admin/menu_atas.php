<div class="nav-outer"> 
	<? if($this->session->userdata('id_user') != NULL){ ?>
		<!-- start nav-right -->
		<div id="nav-right">
			<div class="nav-divider">&nbsp;</div>
			<div class="showhide-account"><img src="<?php echo base_url();?>images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
			<div class="nav-divider">&nbsp;</div>
			<a href="<? echo site_url() ?>/admin/logout" id="logout"><img src="<?php echo base_url();?>images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
			<div class="clear">&nbsp;</div>
		</div>
		<!-- end nav-right -->
								
		<!--  start nav -->
		<div class="nav">
			<div class="table">
				<div class="nav-divider">&nbsp;</div>
				<ul class="<?=($this->uri->segment(2)==='data_jamaah')?'current':'select'?>">
					<li>
						<a href="<?php echo site_url('admin/data_jamaah')?>"><b>Data Jama'ah</b><!--[if IE 7]><!--></a><!--<![endif]-->
					
							<div class="select_sub show">
									<ul class="sub">
										<li class="<?=($this->uri->segment(2)==='list_jamaah')?'sub_current':''?>"><a href="<? echo site_url() ?>/biodata/list_jamaah">Daftar Calon Jamaah</a></li>
										<li class="<?=($this->uri->segment(2)==='input')?'sub_current':''?>"><a href="<? echo site_url() ?>/biodata/input">Form Tambah Calon Jamaah</a></li>
									</ul>
							</div>
								
					</li>
                </ul>
				<div class="nav-divider">&nbsp;</div>
				<ul class="<?=($this->uri->segment(2)==='konfirmasi')?'current':'select'?>">
					<li>
						<a href="<?php echo site_url('admin/konfirmasi')?>"><b>Konfirmasi Pembayaran</b><!--[if IE 7]><!--></a><!--<![endif]-->
					</li>
                </ul>
				<div class="nav-divider">&nbsp;</div>
                                <ul class="<?=($this->uri->segment(2)==='waiting_list')?'current':'select'?>">
					<li>
						<a href="<?php echo site_url('admin/waiting_list')?>"><b>Daftar Tunggu</b><!--[if IE 7]><!--></a><!--<![endif]-->
					</li>
                </ul>
				<div class="nav-divider">&nbsp;</div>
			</div>
			<div class="clear"></div>
		</div>
	<? }?>
</div>
