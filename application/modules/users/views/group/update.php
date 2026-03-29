</div>
<div class = "row">
	<div class = "col-md-offset-4 col-md-8 col-md-offset-2">
		<?php echo form_open('', array('class' => 'well'));?>
			<?php echo form_fieldset('MISE A JOUR DES INFORMATIONS D\'UN GROUPE UTILISATEUR');?>
			<div class="formulaire">
				<div class="row">
					<div  class="col-md-3">
						<?php echo form_label('GROUPE : ', 'group_name');?>
					</div>
					<div  class="col-md-9">
						<input  value = "<?php echo set_value('group_name')?set_value('group_name'):$group->group_name;?>" type="text" name = "group_name" class="form-control tooltip-input" required title = "Entrez le nom du groupe." />
						<?php echo form_error('group_name');?>
					</div>
			    </div>
			
			       <div class="row">
						<div  class="col-md-3">
							   <?php echo form_label('PERMISSION DU GROUPE : <b class = "text-danger">*</b>', 'group_permissions');?>
						</div>
						 <div  class="col-md-9">
							<ul class="list-group">
								<?php 
									$permissions = get_all_permissions();
								?>
								<?php foreach($permissions as $key => $value):?>
								<li class="list-group-item">
									<?php echo $value;?> :
									<div class="btn-switch pull-right">
										<input id = "<?php echo $key;?>" name = "group_permissions[]" type="checkbox" <?php echo $group->{$key} == 1?'checked':'';?> value = "<?php echo $key;?>" class = "tooltip-input" title = 'Activer pour choisir cette permission' />
										<label for = "<?php echo $key;?>" class="label-success"></label>
									</div>
								</li>
								<?php endforeach;?>
						    </ul>
						</div>
			        </div>
			   
			</div>
			
			</br>
			   <div class = "form-group">
					<center>
						<?php echo form_submit('submit', 'Valider', array('class' => 'btn btn-primary'));?>
						<a href="<?php echo site_url('users/group'); ?>" class="btn btn-warning"><i class = "glyphicon glyphicon-circle-arrow-left"></i> Retour sur la liste des groupes</a>
					</center>
				</div>
				
			<?php echo form_fieldset_close();?>
		<?php echo form_close();?>
</div>
</div>
