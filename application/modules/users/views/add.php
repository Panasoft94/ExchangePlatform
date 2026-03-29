<div class = "row">
	<div class = "col-md-offset-3 col-md-10 col-md-offset-2">
		<?php echo form_open('', array('class' => 'well'));?>
			<?php echo form_fieldset('CREATION D\'UN NOUVEAU COMPTE UTILISATEUR');?>
		<div class = 'row'>
			<div class = "col-md-11">
			<div class = "well">
				<div class="formulaire">
					<div class="row">
						<div  class="col-md-3">
						   <?php echo form_label('NOM D\'UTILISATEUR : <b class = "text-danger">*</b>', 'users_username');?>
						</div>
						<div  class="col-md-9">
							<input  value = "<?php echo set_value('users_username');?>" type="text" name = "users_username" class="form-control tooltip-input"  required title = "Entrez le nom d'utilisateur." />
							<?php echo form_error('users_username');?>
						</div>
					</div>
				
				    <div class="row">
						<div  class="col-md-3">
						  <?php echo form_label('ADRESSE E-MAIL : <b class = "text-danger">*</b>', 'users_email');?>
						</div>
						<div  class="col-md-9">
							<input  value = "<?php echo set_value('users_email');?>" type="email" name = "users_email" class="form-control tooltip-input"  required title = "Entrez l'adresse E-mail." />
							<?php echo form_error('users_email');?>
					   </div>
				    </div>
				
				    <div class="row">
						<div  class="col-md-3">
						<?php echo form_label('NOM(S) DE LA FAMILLE : <b class = "text-danger">*</b>', 'users_nom');?>
						</div>
						<div  class="col-md-9">
							<input  value = "<?php echo set_value('users_nom');?>" type="text" name = "users_nom" class="form-control tooltip-input" required title = "Entrez le nom de famille." />
							<?php echo form_error('users_nom');?>
						</div>
				    </div>
				
				 <div class="row">
					<div  class="col-md-3">
					   <?php echo form_label('PRENOM(S) : <b class = "text-danger">*</b>', 'users_prenom');?>
					</div>
				    <div  class="col-md-9">
						<input  value = "<?php echo set_value('users_prenom');?>" type="text" name = "users_prenom" class="form-control tooltip-input"  required title = "Entrez le prénom." />
						<?php echo form_error('users_prenom');?>
				    </div>
				 </div>
				
				<div class="row">
					<div  class="col-md-3">
					   <?php echo form_label('FONCTION OU RÔLE : ', 'users_role');?>
					</div>
					<div  class="col-md-9">
					<input  value = "<?php echo set_value('users_role');?>" type="text" name = "users_role" class="form-control tooltip-input"   title = "Entrez le rôle ou fonction." />
					<?php echo form_error('users_role');?>
				   </div>
				</div>
		
				<div class="row">
					<div  class="col-md-3">
					  <?php echo form_label('CHOIX DU GROUPE : <b class = "text-danger">*</b>', 'group_ids');?>
					</div>
					 <div  class="col-md-9">
						<ul class="list-group">
							<?php foreach($liste_group as $group):?>
							<li class="list-group-item">
								<?php echo $group->group_name;?> :
								<div class="btn-switch pull-right">
									<input id = "<?php echo $group->group_id;?>" name = "group_ids[]" type="checkbox" value = "<?php echo $group->group_id;?>" class = "tooltip-input" title = 'Activer pour choisir ce groupe' />
									<label for = "<?php echo $group->group_id;?>" class="label-success"></label>
								</div>
							</li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>
			</div>
			</div>
			
			<div class = "form-group">
				<center>
					<?php echo form_submit('submit', 'Valider', array('class' => 'btn btn-sm btn-primary'));?>
					<a href="<?php echo site_url('users'); ?>" class="btn btn-sm btn-danger"><i class = "glyphicon glyphicon-remove"></i> Annuler </a>
				</center>
			</div>
			
		</div>
			
		<?php echo form_fieldset_close();?>
		<?php echo form_close();?>
	</div>
	
</div>