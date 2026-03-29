	<section id="contact" class="contact2">
		<div class="container">
			<div class="row"></br>
			<?php
					$session = $this->session->userdata('users');
				?>
		
		<div class="col-md-offset-2 col-md-8 col-md-offset-3">
			<div class="well">
			<?php echo form_fieldset('');?>
	    			<p style="font-size:23px;color:blue;font-family:cambria;text-align:center;">Envoie d'un nouveau message</p>
	    			<?php echo form_open('users/envoie_du_nouveau_message');?>
	    				<div class="error-container"></div>
						<div class="well">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><font color="black">DESTINATAIRE </font></label>
									<input type="text" value="<?php echo set_value('email_expeditaire');?>" class="form-control form-control-email" name="email_expeditaire" id="email" placeholder="adresse email........................................">
								   <font color="red"><?php echo form_error('email_expeditaire');?></font>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><font color="black">OBJET</font></label>
									<input type="text" value="<?php echo set_value('objet_message');?>" name="objet_message" class="form-control form-control-subject"  id="subject" placeholder="objet de votre message..............................................">
								   <font color="red"><?php echo form_error('objet_message');?></font>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label><font color="black">CONTENU</font></label>
							<textarea value="<?php echo set_value('contenu_message');?>" name="contenu_message" class="form-control form-control-message"  id="message" placeholder="votre message............................" rows="10"></textarea>
						    <font color="red"><?php echo form_error('contenu_message');?></font>
						</div>
						
						<div class="text-left">
						<?php echo form_submit('submit', 'Envoyer', array('class' => 'btn btn-success solid blank'));?>
						<a href="<?php echo site_url('users/boite_de_reception_des_messages'); ?>" class="btn btn-danger"><i class = "glyphicon glyphicon-remove"></i> Annuler</a></p>
						<?php echo form_fieldset_close();?>
						<?php echo form_close();?>
	    		</div>
			
			</div>
		</div>
		</div>
	</section><!-- Cotact form end -->
	 </br>