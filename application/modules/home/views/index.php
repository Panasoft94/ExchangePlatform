<div class="row">
<?php if(is_allowed('user')):?>
	 <div class="col-md-3">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-3">
						<i class="fa fa-lock fa-5x"></i>
					</div>
					<div class="col-lg-9 text-right">
						<div class="huge"></div>
						<div>Vous avez au total <b class = "badge"><?php echo $nb_users;?></b> utilisateurs dans votre système</div>
					</div>
				</div>
			</div>
			<a href="<?php echo site_url('users');?>">
				<div class="panel-footer">
					<span class="pull-left">Liste des utilisateurs</span>
					<span class="pull-right"><i class="glyphicon  glyphicon-ok-sign"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<?php endif;?>
</div>






<div class = "row panel">
	<div class = "col-md-6">
		<span class = 'page-header'>
			<h3>Les derniers utilisateurs créés</h3>
		</span>
		<?php if(!empty($users)):?>
			<table class="table table-striped table-condensed table-bordered table-responsive">
			  <thead>
				<tr>
					<td>ID</td>
					<td>Nom d'utilisateur</td>
					<td>Nom</td>
					<td>Prénom</td>
					<td>E-mail</td>
				</tr>
			</thead>
				<?php foreach($users as $l): ?>
				<tr>
					<td><?php echo $l->users_id;?></td>
					<td><?php echo $l->users_username;?></td>
					<td><?php echo $l->users_nom;?></td>
					<td><?php echo $l->users_prenom;?></td>
					<td><?php echo $l->users_email;?></td>
				</tr>
				<?php endforeach; ?>
			</table>

		<?php else:?>
			<p class = "alert alert-info">Aucune donnée disponible pour le moment</p>
		<?php endif;?>
	</div>
</div>
