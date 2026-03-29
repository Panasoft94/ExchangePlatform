<div id="banner-area" class="banner-area" style="background-image:url(<?php echo base_url('assets/images/banner/banner3.jpg');?>)">
		<div class="banner-text">
     		<div class="container">
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<div class="banner-heading"></br></br>
	        				<h1 class="banner-title title-border-left">Boite de reception des messages</h1>
	        			</div>
	        		</div><!-- Col end -->
	        	</div><!-- Row end -->
       	</div><!-- Container end -->
    	</div><!-- Banner text end -->
	</div><!-- Banner area end --> 


	<section id="main-container" class="main-container">
		<div class="container">
		
		         <?php
					$session = $this->session->userdata('users');
				?>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class="sidebar sidebar-left">
						<div class="widget">
							<ul class="nav nav-tabs nav-stacked service-menu">
								<li class="active"><a href="<?php echo base_url('users/users_profile');?>"><i class="fa fa-windows"></i> Panel utilisateur</a></li>
								<li><a href="<?php echo base_url('users/update/'.$session->users_id);?>"><i class="fa fa-edit"></i> Editer vos informations</a></li>
								<li><a href="<?php echo base_url('users/edition_photo_profile');?>"><i class="fa fa-photo"></i> Editer votre photo du profile</a></li>
								<li><a href="<?php echo base_url('users/envoie_du_nouveau_message');?>"><i class="fa fa-plus-square"></i> Envoyer nouveau message</a></li>
								<li><a href="<?php echo base_url('users/boite_de_reception_des_messages');?>"><i class="fa fa-envelope"></i> Boite de reception des messages</a></li>
								 <?php if(is_allowed('voir_message_contact')):?>
								<li><a href="<?php echo base_url('users/vos_differents_messages');?>"><b class = "badge" style="font-size:12px;"><?php echo $nb_message;?></b> message<?php if($nb_message >1){ echo 's';};?> du contact</a></li>
								<?php endif;?>
								<li><a href="<?php echo base_url('users/les_membres_du_site');?>"><i class="fa fa-user"></i> Tous les membres du site</a></li>
								<li><a href="#"><i class="fa fa-bell-o"></i> Vos notifications</a></li>
							</ul>
						</div><!-- Widget end -->
					</div><!-- Sidebar end -->
				</div><!-- Sidebar Col end -->
			
			</hr>
			
			<!-- Post comment start -->
			<div class="col-lg-8 col-md-8 col-sm-12">
					<div id="comments" class="comments-area">
						<h3 class="title-border-left" style="font-size:25px;font-family:cambria;"> Boite de reception des messages du contact</h3>

                      <?php if(!empty($liste_message)):?>
						  <?php foreach($liste_message as $mess):?>
						<ul class="comments-list">
							<li>
								<div class="comment">
									<img class="comment-avatar pull-left" alt="" src="<?php echo base_url('assets/images/avatar/user.jpg')?>">
									<div class="comment-body">
										<div class="meta-data">
											<span class="comment-author"><I>Envoyé par : <font color="blue"><?php echo $mess->users_nom;?></font><I></span>
											<span class="comment-date pull-right" style="font-size:16px;color:black;"><?php $date_pos=date('d-m-Y'); if($date_pos==$mess->contact_date){echo "Aujourd'hui le : ";}else {echo 'Dépuis le : ';};?> <font color="red"><?php echo $mess->contact_date;?></font></span>
										</div>
										<div class="comment-content">
										<p style="color:black;font-size:18px;">Obejt : <font color="green"><?php echo $mess->contact_objet_message;?></font></p></div>
										<p style="color:black;font-size:18px;"><?php echo $mess->contact_message;?></p></div>
										<div class="text-left"><a href="#">Email : <?php echo $mess->users_email;?></a></div>	
										<div class="text-right "><a href="<?php echo base_url('contact/delete/'.$mess->contact_code);?>" onClick = "return confirm('Etes-vous sûr de vouloir supprimer ce message ?')" class="btn btn-danger solid blank">Supprimer ce message</a></div>	
									</div>
								</div><!-- Comments end -->
                         <?php endforeach; ?>
							<?php else:?>
							<p class = "alert alert-info" style="font-size:16px;"><I>Votre boite de reception est vide !!<I></p>
						   <?php endif;?>
									
							</li><!-- Comments-list li end -->
						</ul><!-- Comments-list ul end -->
					</div><!-- Post comment end -->
					</div>
			  

		</div><!-- Conatiner end -->
	</section><!-- Main container end -->
