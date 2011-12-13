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
						<a href="<?php echo site_url('admin/data_jamaah')?>"><b>Data Registrasi</b><!--[if IE 7]><!--></a><!--<![endif]-->
					
							<div class="select_sub show">
									<ul class="sub">
										<li class="<?=($this->uri->segment(2)==='')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/data_akun/">Daftar Akun Jamaah</a></li>
										<li class="<?=($this->uri->segment(2)==='list_jamaah')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/data_jamaah/">Daftar Jamaah Keseluruhan</a></li>
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
				<ul class="<?=(strpos($this->uri->segment(2),'master')!==false)?'current':'select'?>">
					<li>
						<a href="#"><b>Data Master</b><!--[if IE 7]><!--></a><!--<![endif]-->
					
							<div class="select_sub show">
									<ul class="sub">
										<li class="<?=($this->uri->segment(2)==='master_group_departure')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_group_departure/">Group Keberangkatan</a></li>
										<li class="<?=($this->uri->segment(2)==='master_program_class')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_program_class/">Kelas Program</a></li>
                                        <li class="<?=($this->uri->segment(2)==='master_room')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_room/">Kamar</a></li>
                                        <li class="<?=($this->uri->segment(2)==='master_room_type')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_room_type/">Tipe Kamar</a></li>
                                        <li class="<?=($this->uri->segment(2)==='master_relation')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_relation/">Relasi</a></li>
                                        <li class="<?=($this->uri->segment(2)==='master_propinsi')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_propinsi/">Propinsi</a></li>
                                        <li class="<?=($this->uri->segment(2)==='master_clothes_size')?'sub_current':''?>"><a href="<? echo site_url() ?>/admin/master_clothes_size/">Ukuran Pakaian</a></li>

										
									</ul>
							</div>
								
					</li>
				<div class="nav-divider">&nbsp;</div>
			</div>
			<div class="clear"></div>
		</div>
	<? }?>
</div>
