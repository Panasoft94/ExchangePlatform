<div class = "row">
<?php if(!empty($group)):?>
	<div class = "col-md-offset-1 col-md-10 col-md-offset-1">
		<div class = "panel">
			<div class = "panel-body">
			<h3 style="font-size:28px;font-family:georgian;text-align:center;">LES PERMISSIONS DU GROUPE : <b><?php echo $group->group_name;?></b></h3>
			<div class="col-md-offset-0 col-md-6 col-md-offset-0">
				<ul class="list-group">
					<?php 
						$permissions = get_all_permissions();
					?>
					<?php foreach($permissions as $key => $value):?>
					<?php if($group->{$key} == 1):?>
					<li class="list-group-item">
						<?php echo $value;?>
					</li>
					<?php endif;?>
					</div><div class="col-md-6">
				<?php endforeach;?>
				</ul></br></br>
				<p class = "text-right">
					<?php if($group->is_system == 0):?>
						<a href="<?php echo site_url('users/update_group/'. $group->group_id); ?>" class="btn  btn-sm btn-success"><i class = "glyphicon glyphicon-refresh"></i> Mettre à jour la configuration</a> 
					<?php endif;?>	
						<a href="<?php echo site_url('users/group'); ?>" class="btn  btn-sm btn-primary"><i class = "glyphicon glyphicon-circle-arrow-left"></i> Retour en arrière</a>
				</p>
			</div>
		</div>
	</div>
<?php else:?>
	<p class = "alert alert-info">Aucune configuration trouvée veuillez dabord ajouter une année académique</p>
<?php endif;?>
</div>
</div>