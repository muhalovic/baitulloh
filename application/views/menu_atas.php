<div class="nav-outer"> 
				<? if($this->session->userdata('email') != NULL){ ?>
				<!-- start nav-right -->
				<div id="nav-right">
					<div class="nav-divider">&nbsp;</div>
					<div class="showhide-account"><img src="<?php echo base_url();?>images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
					<div class="nav-divider">&nbsp;</div>
					<a href="<? echo site_url() ?>/logout" id="logout"><img src="<?php echo base_url();?>images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
					<div class="clear">&nbsp;</div>
				
					<!--  start account-content -->	
					<div class="account-content">
					<div class="account-drop-inner">
						<a href="<?php echo site_url();?>/useraccount" id="acc-settings">Ubah Data Profil</a>
						<div class="clear">&nbsp;</div>
						<div class="acc-line">&nbsp;</div>
						<a href="<?php echo site_url();?>/useraccount/editpassword" id="acc-settings">Ubah Password</a>
					</div>
					</div>
					<!--  end account-content -->
				</div>
				<!-- end nav-right -->
				<? }?>
								
				<!--  start nav -->
				<div class="nav">
					<div class="table">
                    
                    	<? if($this->session->userdata('email') == NULL) { ?>
						<ul class="<?=($this->uri->segment(1)==='check_availability' || $this->uri->segment(1)=='')?'current':'select'?>">
                          <li>
                          	<a href="<?php echo site_url('check_availability')?>"><b>Check Order Availability</b><!--[if IE 7]><!--></a><!--<![endif]-->
                           </li>
                          </ul>
						<div class="nav-divider">&nbsp;</div>
						<ul class="<?=($this->uri->segment(1)==='login') || ($this->uri->segment(1)==='forgot')?'current':'select'?>">
                          <li>
                            <a href="<?php echo site_url('login')?>"><b>Login</b><!--[if IE 7]><!--></a><!--<![endif]-->
                          </li>
                        </ul>
						<? } ?>
                        
						<div class="nav-divider">&nbsp;</div>
                        
						<? if($this->session->userdata('email') != NULL){ ?>
                        <ul class="<?=($this->uri->segment(1)==='welcome')?'current':'select'?>">
                          <li>
                        	<a href="<? echo site_url().'/welcome' ?>"><b>Informasi</b><!--[if IE 7]><!--></a><!--<![endif]-->
                          </li>
                        </ul> 
                        <div class="nav-divider">&nbsp;</div>
						<ul class="<?=($this->uri->segment(1)==='beranda')?'current':'select'?>">
                          <li>
                        	<a href="<? echo site_url().'/beranda' ?>"><b>Paket</b><!--[if IE 7]><!--></a><!--<![endif]-->
                          </li>
                        </ul>                                                
						<div class="nav-divider">&nbsp;</div>	
						<ul class="<?=($this->uri->segment(1)==='biodata' || $this->uri->segment(1)==='paspor')?'current':'select'?>">
							<li><a href="<? echo site_url() ?>/biodata"><b>Data Jamaah</b></a></li>
						</ul>
						<div class="nav-divider">&nbsp;</div>
						<ul class="<?=($this->uri->segment(1)==='payment')?'current':'select'?>"><li><a href="<? echo site_url() ?>/payment"><b>Pembayaran</b><!--[if IE 7]><!--></a><!--<![endif]--></li></ul>
						<div class="nav-divider">&nbsp;</div>
					
						<ul class="<?=($this->uri->segment(1)==='cancel')?'current':'select'?>"><li><a href="<? echo site_url() ?>/cancel"><b>Pembatalan</b><!--[if IE 7]><!--></a><!--<![endif]--></li></ul>
						<div class="nav-divider">&nbsp;</div>
						<ul class="<?=($this->uri->segment(1)==='rooming')?'current':'select'?>"><li><a href="<? echo site_url() ?>/rooming""><b>Ruang Kamar</b><!--[if IE 7]><!--></a><!--<![endif]--></li></ul>
						<div class="nav-divider">&nbsp;</div>

						<div class="clear"></div>
                                                <? }?>
					</div>
					<div class="clear"></div>
				</div>
</div>
