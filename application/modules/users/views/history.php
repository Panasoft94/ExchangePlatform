
	
<?php 
    $l_utilisateur = array('' => '--- Tous les utilisateurs ---');
    foreach($utilisateurs as $u){
        $l_utilisateur[$u->users_id] = $u->users_nom.' '.$u->users_prenom;
		$id = $u->users_id;
    }
?>

</div>
<div class = "well">
<div class = "row text-right">

	<div class = "col-md-6">
       <?php echo form_open('', array('class' => 'form-inline', 'method' => 'get'));?>
                      <?php echo form_label('UTILISATEUR', 'utilisateur');?>
                      <?php echo form_dropdown('utilisateur', $l_utilisateur, $this->input->get('utilisateur'), array('class' => 'form-control form-control-sm'));?>
              
			  <?php echo form_label('ANNEE :', 'annee');?>
				<?php
					$annee_actuelle = date('Y');
					$annees = array();
					for($i = (int)$annee_actuelle - 10; $i<= (int)$annee_actuelle; $i++){
						$annees[$i] = $i;
					}
					echo form_dropdown('ANNEE', $annees, $this->input->get('annee')?$this->input->get('annee'):$annee_actuelle , array('class' => "form-control"));
				?>
			  <?php echo form_submit('submit', 'Valider', array('class' => 'btn btn-sm btn-success'));?>
         <?php echo form_close();?>
    </div>
		<div class = "col-md-3">
			<?php echo form_open('', array('class' => 'form-inline', 'method' => 'get'));?>
				<?php echo form_label('DATE :', 'jours');?>
				<?php echo form_input('jours', $this->input->get('jours')?$this->input->get('jours'):'', array('class' => "input-sm form-control tooltip-input datepicker-tiret-us", 'required' => true, 'placeholder' => 'AAAA-MM-JJ', 'title' => 'Veuillez choisir une date'));?>
				<?php echo form_submit('submit', 'Valider', array('class' => 'btn btn-sm btn-success'));?>
			<?php echo form_close();?>
		</div>

		<div class = "col-md-3">
			<?php echo form_open('', array('class' => 'form-inline', 'method' => 'get'));?>
				<?php echo form_label('MOIS :', 'mois');?>
				<?php echo form_input('mois', $this->input->get('mois')?$this->input->get('mois'):'', array('class' => "input-sm form-control tooltip-input datepicker-mois-us", 'required' => true, 'placeholder' => 'AAAA-MM', 'title' => 'Veuillez choisir un mois'));?>
				<?php echo form_submit('submit', 'Valider', array('class' => 'btn btn-sm btn-success'));?>
			<?php echo form_close();?>
		</div>


		
	</div>
	</div>
<div class="well">
	<?php if(!empty($liste_history) && !empty($user)):?>
	<p class = "text-right">
		<?php if(is_allowed('view_history')):?>
			<a href="<?php echo site_url('users/download_history/'.$user->users_id.'/?'.$_SERVER['QUERY_STRING']); ?>" class="btn  btn-sm btn-primary"><i class = "glyphicon glyphicon-cloud-download"></i> Télécharger le fichier pdf</a>
	<?php endif;?>	
   </p>
   <?php endif;?>	
  
<span class = 'page-header'>
	<h3 style="font-size:28px;font-family:combria;color:blue;"><b>JOURNAL SYSTEME POUR <?php echo $date.' '.(!empty($user_trace)?'('.$user_trace.')':''); ?></b></h3>
</span>
	<?php if(!empty($liste_history)):?>
	<?php echo form_open('users/remove');?>
			<table class="table table-striped table-condensed table-bordered table-responsive" id = 'myDatatable'>
		  <thead>
		  <tr>
				<td>DATE</td>
				<td>ACTIVITE</td>
				<td>UTILISATEUR</td>
				<td>Action</td>
			</tr>
		</thead>
			<?php foreach($liste_history as $l): ?>
			<tr>
				<td style="font-size:14px;"><?php echo date('d/m/Y H:i', strtotime($l->history_date)); ?></td>
				<td style= "text-align:left;"><?php echo $l->history_action; ?></td>
				<td><?php echo $l->history_users; ?></td>
				<td>
					<input  value = "<?php echo $l->history_id;?>" type="checkbox" name = "history_id[]"  class="form-control tooltip-input"  />
				</td>
			</tr>
			<?php endforeach; ?>
			
		</table>
		</br>
			<div class = "form-group text-right">
			 <?php echo form_submit('submit', 'Supprimer', array('class' => 'btn btn-danger', 'onClick' => "return confirm('Etes-vous sûr de vouloir  supprimer les historiques des actions selectionnées ?')"));?>
			</div>
		
	<?php else:?>
		<p class = "alert alert-info">Aucune donnée disponible pour le moment</p>
	<?php endif;?>
</div>