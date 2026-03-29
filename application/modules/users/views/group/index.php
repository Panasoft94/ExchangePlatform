<span class = 'page-header'>
	<h3><b>GESTION DES GROUPES UTILISATEURS</b></h3>
</span>

	<p class = "text-right">
		<a href="<?php echo site_url('users/add_group'); ?>" class="btn  btn-sm btn-success"><i class = "glyphicon glyphicon-plus-sign"></i> Ajouter un groupe</a>
		<?php if(is_allowed('user')):?>
			<a href = "<?php echo site_url('users/');?>" class = "btn btn-sm btn-default"><i class = "glyphicon glyphicon-user"></i> Liste des utilisateurs</a>
		<?php endif;?>
	</p>
	<?php if(!empty($liste_groups)):?>
		<table class="table table-striped table-condensed table-bordered table-responsive" id = 'myDatatable'>
		  <thead>
		  <tr>
				<td>ID</td>
				<td>Nom du groupe</td>
				<td>Action</td>
			</tr>
		</thead>
			<?php foreach($liste_groups as $l): ?>
			<tr>
				<td><?php echo $l->group_id; ?></td>
				<td><?php echo $l->group_name; ?></td>
				<td>
					<?php if($l->is_system == 0):?>
						<a href="<?php echo site_url('users/update_group/'.$l->group_id); ?>" class="btn  btn-sm btn-info"><i class = "glyphicon glyphicon-edit"></i> Modifier</a>
						<a href="<?php echo site_url('users/delete_group/'.$l->group_id); ?>" onClick = "return confirm('Etes-vous sûr de vouloir supprimer cette donnée ?')" class="btn btn-sm btn-danger"><i class = "glyphicon glyphicon-trash"></i> Supprimer</a>
					<?php endif;?>
					<a href="<?php echo site_url('users/group_permissions/'.$l->group_id); ?>" class="btn  btn-sm btn-primary"><i class = "glyphicon glyphicon-eye-open"></i> Permissions</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	<?php else:?>
		<p class = "alert alert-info">Aucune donnée disponible pour le moment</p>
	<?php endif;?>
