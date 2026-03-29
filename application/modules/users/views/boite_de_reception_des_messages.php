</div>
			<!-- Post comment start -->
			<div class = "row">
					<div id="comments" class="comments-area">
					<div class="well">
					<p class="col-md-offset-0 col-md-2 col-md-offset-2">
						<?php if(is_allowed('envoyer_message')):?>
							<a href="<?php echo base_url('users/envoie_du_nouveau_message/');?>"  class="btn btn-success solid blank"><i class="glyphicon glyphicon-plus"></i> Envoyer nouveau message</a>
					    <?php endif;?>
					</p>
                      <?php if(!empty($les_messages_utilisateur)):?>
					  <h3 class="title-border-left" style="font-size:28px;font-family:georgian;color:blue;text-align:center;">BOITE DE RECEPTION DES MESSAGES</h3>
						  <?php foreach($les_messages_utilisateur as $mess):?>
						<div class="well">
						  <div class="rows">
						   <ul class="comments-list">
						   <?php echo form_open('message/delete', array('class' => 'form'));?>
							<li>
									  <?php if($mess->photo_profil && file_exists('assets/img/avatar/'.$mess->photo_profil)):?>
									   <img class="comment-avatar pull-left" alt="" src = "<?php echo base_url('assets/img/avatar/'.$mess->photo_profil);?>" class = "img-circle" width = "210" height = "200" style = "margin:auto;float : center;margin-right : 8%;"/>
									   <?php else:?>
											<img class="comment-avatar pull-left" alt="" src = "<?php echo base_url('assets/img/avatar/default.jpg');?>" class = "img-circle" width = "210" height = "200" style = "margin:auto;float : center;margin-right : 8%;"/>
									   <?php endif;?>
		                              </br></br></br></br>
										<div class="meta-data">
											<span class="comment-author"><I>Envoyé par : <font color="blue"><?php echo $mess->users_nom.' '.$mess->users_prenom;?></font><I></span>
											<span class="comment-date pull-right" style="font-size:16px;color:black;">Date d'envoie : <font color="red"><?php echo $mess->date_envoie_message;?></font></span>
										</div>
										<div class="comment-content">
										<p style="color:black;font-size:18px;">Obejt : <font color="green"><?php echo $mess->objet_message;?></font></p></div>
										<p style="color:black;font-size:18px;text-align:justify;"><?php echo $mess->contenu_message;?></p>
										<div class="text-left"><a href="#">Email: <?php echo $mess->email_impeditaire;?></a></div>	</br>
										
										<div class="text-center ">
											<a href ="#" class="btn btn-default">
											<?php if(is_allowed('delete_message')):?>
												<?php echo form_checkbox('message_code[]', $mess->message_code);?>
												<?php endif;?>
											</a>	
											<?php if(is_allowed('repondre_message')):?>
											<a href="<?php echo base_url('users/repondre_un_message/'.$mess->message_code);?>"  class="btn btn-info solid blank"><i class="fa fa-reply-all"></i> Répondre</a>
											<?php endif;?>
										</div>	
									</div>
								</div><!-- Comments end -->
								
                           <?php endforeach; ?>
						   <div class="text-center ">
								 <div class="well">
									   <?php if(!empty($les_messages_utilisateur)):?>
											 <?php if(is_allowed('delete_message')):?>
												<?php echo form_submit('submit', 'Supprimer', array('class' => 'btn btn-sm btn-danger', 'onclick' => "return confirm('Etes-vous sûr de vouloir supprimer les messages selectionnés ?')"));?>
											<?php endif;?>
										<?php endif;?>
										<a href="<?php echo site_url('home'); ?>" class="btn  btn-sm btn-warning"><i class = "glyphicon glyphicon-remove"></i> Quitter</a>
								  </div>			
							</div>
							
							</div>			
							<?php else:?>
							 </br>
							<p class = "alert alert-info" style="font-size:16px;text-align:center;"><I>Votre boite de reception des messages est vide !!<I></p>
							</br></br></br></br>
							</div><!-- Comments end -->
							
						   <?php endif;?>
						   
									
							</li><!-- Comments-list li end -->
						</ul><!-- Comments-list ul end -->
					</div><!-- Post comment end -->
				</div>
		    </div>  
