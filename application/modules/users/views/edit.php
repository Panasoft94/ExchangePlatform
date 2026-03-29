<?php
	$session = $this->session->userdata('users');
?>
<div class = "row">
	<div class = "col-md-offset-2 col-md-9 col-md-offset-2">
	<div class = "well">
	<ul class="list-group">
		  <?php if($users->photo_profil && file_exists('assets/img/avatar/'.$users->photo_profil)):?>
				<img src = "<?php echo base_url('assets/img/avatar/'.$users->photo_profil);?>" class = "img-circle" width = "250" height = "250" style = "margin:auto;float : center;margin-right : 8%;"/>
		   <?php else:?>
				<img src = "<?php echo base_url('assets/img/avatar/default.jpg');?>" class = "img-circle" width = "250" height = "250" style = "margin:auto;float : center;margin-right : 8%;"/>
	       <?php endif;?>
			
			<li class="list-group-item">
				NOM D'UTILISATEUR : <b><?php echo $users->users_username;?></b>
			</li>
			<li class="list-group-item">
				NOM(S) & PRENOM(S) : <b><?php echo $users->users_nom.' '.$users->users_prenom;?></b>
			</li>
			<li class="list-group-item">
				ADRESSE E-MAIL : <b><?php echo $users->users_email;?></b>
			</li>
			<li class="list-group-item">
				FONCTION : <b><?php echo $users->users_role;?></b>
			</li>
			<li class="list-group-item">
				DATE DE CREATION : <b><?php echo $users->create_at;?></b>
			</li>
			<li class="list-group-item">
				STATUS : <?php if($users->etat_online == 1){ echo '<span class = "label label-success"><i class = "glyphicon glyphicon-ok-sign"></i> En ligne </span>';} else {echo '<span class = "label label-danger"><i class = "glyphicon glyphicon-remove"></i> Hors line </span>';}?>
			</li>
		</ul>
		
		<?php echo form_open_multipart('users/edit', array('class' => 'well'));?>
			<center><?php echo form_fieldset('Mise à jour des informations de votre compte');?></center>
			
		<div class = "col-md-12">
			<div class = "well">
			<div class="formulaire">
			<div class="row">
					<div  class="col-md-3">
				     <?php echo form_label('NOM D\'UTILISATEUR : <b class = "text-danger">*</b>', 'users_username');?>
					</div>
					<div  class="col-md-9">
						<input  value = "<?php echo set_value('users_username')?set_value('users_username'):$users->users_username;?>" type="text" name = "users_username" class="form-control tooltip-input"  required title = "Entrez le nom d'utilisateur." />
						<?php echo form_error('users_username');?>
				   </div>
			</div>
			
			<div class="row">
				<div  class="col-md-3">
				    <?php echo form_label('NOUVEAU MOT DE PASSE : <b class = "text-danger">*</b>', 'users_password');?>
			   </div>
				<div  class="col-md-9">
				<input  value = "" type="password" name = "users_password" class="form-control tooltip-input"   title = "Entrez votre nouveau mot de passe ou laissez vide pour ne pas changer." />
				<?php echo form_error('users_password');?>
			   </div>
			</div>
								
			<div class="row">
			  <div  class="col-md-3">
				<?php echo form_label('ADRESSE E-MAIL : <b class = "text-danger">*</b>', 'users_email');?>
			  </div>
				<div  class="col-md-9">
					<input  value = "<?php echo set_value('users_email')?set_value('users_email'):$users->users_email;?>" type="email" name = "users_email" class="form-control tooltip-input"  title = "Entrez l'adresse E-mail." />
					<?php echo form_error('users_email');?>
				</div>
			</div>
			
			<div class="row">
				  <div  class="col-md-3">
					<?php echo form_label('NOM(S) DE LA FAMILLE :  <b class = "text-danger">*</b>', 'users_nom');?>
				  </div>
				 <div  class="col-md-9">
					<input  value = "<?php echo set_value('users_nom')?set_value('users_nom'):$users->users_nom;?>" type="text" name = "users_nom" class="form-control tooltip-input"  title = "Entrez le nom de famille." />
					<?php echo form_error('users_nom');?>
				</div>
			</div>
			
			<div class="row">
				  <div  class="col-md-3">
					<?php echo form_label('PRENOM(S): <b class = "text-danger">*</b>', 'users_prenom');?>
				  </div>
				<div  class="col-md-9">
					<input  value = "<?php echo set_value('users_prenom')?set_value('users_prenom'):$users->users_prenom;?>" type="text" name = "users_prenom" class="form-control tooltip-input"  title = "Entrez le prénom." />
					<?php echo form_error('users_prenom');?>
				  </div>
			</div>
			
			<div class="row">
				<div  class="col-md-3">
				  <?php echo form_label('PHOTO PROFIL ? : ', 'photo_profil');?>
				</div>
				<div  class="col-md-9">	
					<?php echo form_upload('photo_profil', array('class' => "form-control tooltip-input", 'accept' => 'image/*'));?>
					 </br>
				</div>
			</div>
			</div>
			
			</div>
		</div>
			
			<div class = "form-group">
			<center>
				<?php echo form_submit('submit', 'Confirmer ?', array('class' => 'btn btn-success'));?>
				<a href="<?php echo site_url('home'); ?>" class="btn btn-danger"><i class = "glyphicon glyphicon-remove"></i> Annuler</a>
				</center>
			</div>
			<?php echo form_fieldset_close();?>
		<?php echo form_close();?>
	</div>
</div>
</div>