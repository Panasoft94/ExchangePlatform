<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Logiciel developpée par ACM sous contrôle de l'ingénieur Anicet DJIMTOLOUMA">
    <meta name="author" content="ENTREPRISE ACM">

    <title>GIRPE</title>
	
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/img/arm.ico');?>" />
    <link href="<?php echo base_url('assets/css/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" type = "text/css" >
	<link href="<?php echo base_url('assets/datatables/datatables.bootstrap.css');?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/bootstrap-datepicker3.standalone.min.css');?>" rel="stylesheet" type = "text/css">
    <link href="<?php echo base_url('assets/css/styles.css');?>" rel="stylesheet" type = "text/css">
  </head>
  <body>
	  <div class="container">
			<br /><br /><br /><br /><br /><br /><br />
			<?php
				if($this->session->flashdata('success')){
					echo "<p class = 'alert alert-success'><i class = 'glyphicon glyphicon-ok-sign'></i> ".$this->session->flashdata('success')."</p>";
				}
				if($this->session->flashdata('info')){
					echo "<p class = 'alert alert-info'><i class = 'glyphicon glyphicon-info-sign'></i> ".$this->session->flashdata('info')."</p>";
				}
				if($this->session->flashdata('error')){
					echo "<p class = 'alert alert-danger'><i class = 'glyphicon glyphicon-remove-sign'></i> ".$this->session->flashdata('error')."</p>";
				}
				if($this->session->flashdata('warning')){
					echo "<p class = 'alert alert-warning'><i class = 'glyphicon glyphicon-ban-circle'></i> ".$this->session->flashdata('warning')."</p>";
				}
			?>
			<div class = "row">
			
				
				
				<div class = "col-md-offset-2 col-md-8 col-md-offset-4">
					<div class="well">
					<div class = "col-md-offset-1 col-md-4 col-md-offset-0">
					<center><img  width = "80%" height ="80%" src = "<?php echo base_url('assets/img/login.png');?>" class = "img-responsive" /></center>
				</div>
					<?php echo form_open('users/login/?'.$_SERVER['QUERY_STRING'], array('class' => ''));?>
						<?php echo form_fieldset('Authentification GIRPE');?>
						<div class = "form-group">
							<?php echo form_input('users_username', set_value('users_username'), array('class' => "form-control tooltip-input", 'placeholder' => 'Nom d\'utilisateur', 'title' => 'Veuillez saisir votre nom d\'utilisateur'));?>
							<?php echo form_error('users_username');?>
						</div>
						<div class = "form-group">
							<?php echo form_password('users_password', null, array('class' => "form-control tooltip-input",   'placeholder' => 'Mot de passe', 'title' => 'Veuillez saisir votre mot de passe personnel'));?>
							<?php echo form_error('users_password');?>
						</div>
					     <?php echo form_submit('submit', 'Se connecter', array('class' => 'btn-sm btn-success'));?>
						 <hr>
						 <p style="font-family:cambria;font-size:15px;color:green;"><em>Copyright &copy; <?php echo date('Y');?>  Tous Droits Reservés ACM. Version 1.6.2, Lab ACM</em></p>
						</div>
						
				</div>
					
					<?php echo form_fieldset_close();?>
					<?php echo form_close();?>
				
			
			</div>
		</div><!-- ./container-->
		<script src="<?php echo base_url('assets/js/jquery.js');?>"></script>
		<script src="<?php echo base_url('assets/css/bootstrap/js/bootstrap.min.js');?>"></script>
		<script src="<?php echo base_url('assets/datatables/jquery.datatables.fr.js') ?>"></script>
		<script src="<?php echo base_url('assets/datatables/datatables.bootstrap.js') ?>"></script>
		<script src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/bootstrap-datepicker.fr.min.js');?>"></script>
		<script src="<?php echo base_url('assets/js/script.js');?>"></script>
	</body>
</html>
