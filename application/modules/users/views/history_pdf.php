<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE></TITLE>
	<META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
	<META NAME="AUTHOR" CONTENT="tnh">
	<META NAME="CREATED" CONTENT="20161105;54300000000000">
	<META NAME="CHANGEDBY" CONTENT="tnh">
	<META NAME="CHANGED" CONTENT="20161107;83800000000000">
	<META NAME="AppVersion" CONTENT="12.0000">
	<META NAME="DocSecurity" CONTENT="0">
	<META NAME="HyperlinksChanged" CONTENT="false">
	<META NAME="LinksUpToDate" CONTENT="false">
	<META NAME="ScaleCrop" CONTENT="false">
	<META NAME="ShareDoc" CONTENT="false">
	<STYLE TYPE="text/css">
	<!--
		@page{
			margin: 2px;
			padding: 2px;
		}

		body{
			padding : 0.5%;
		}

		.entete{
			line-height : 1px;
		}

		table{
			width : 100%;
			border-spacing: -1px;
		}

		th,td{
			text-align : center;
			padding : 2px;
			font-size: 14px;
		}

		.table_entete{
			border: none;
		}

		.table_entete th, .table_entete td{
			text-align : center;
			padding : 1px;
		}

		.client_info p{
			text-align:justify;
			font-size: 13px;
			font-weight : bold;
		}

		.entreprise_info p{
			text-align:justify;
			font-size: 13px;
			font-style : italic;
		}

		.facture_bottom p{
			font-size: 12px;
			text-align: center;
		}

		.header,
		.footer{
		    width: 100%;
		    text-align: center;
		    position: fixed;
		}
		.header {
		    top: 0px;
			left: 0px;
			right: 0px;
		}
		.footer{
		    bottom: 200px;
			left: 0px;
			right: 0px;
		}
		.pagenum:before {
		    content: counter(page);
		}

	-->
	</STYLE>
</HEAD>
<BODY LANG="fr-FR" TEXT="#00000a" LINK="#0000ff" DIR="LTR">
<div class = "entete">
	<table class="table_entete">
		<tr>
			<td  class = "entreprise_info">
				<?php
					$logo = config('config_logo');
					if($logo && file_exists('assets/img/'.$logo)){
						echo '<p><img src = "'.base_url('assets/img/'.$logo).'" width = "100px" height = "100px"/></p>';
					}
				?>
				<p><?php echo config('config_etablissement');?></p>
				<p>Adresse : <?php echo config('config_adresse');?></p>
				<p>Tél : (+236) <?php echo config('config_tel1');?>
				<?php
					if(!empty(config('config_tel2'))){
						echo '/'.config('config_tel2');
					}
				?>
				</p>
				<p>E-mail : <?php echo config('config_email');?></p>
				<p>BP : <?php echo config('config_code_postal');?></p>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			<td class = "client_info">
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="3"><h2 style = "text-align:center"><strong>JOURNA SYSTEME  POUR <?php echo $date;?></strong></h2></td>
		</tr>
	</table>
</div>
<br />
<br />
<br />
<?php if(!empty($liste_history)):?>
	<table border = "0.1">
		<thead>
		  <tr>
				<th>Date</th>
				<th>Action</th>
				<th>Utilisateur</th>
		  </tr>
		</thead>
		<?php foreach($liste_history as $l):?>
			<tr>
				<td><?php echo date('d/m/Y H:i', strtotime($l->history_date));?></td>
				<td style ="text-align:left;"><?php echo $l->history_action;?></td>
				<td><?php echo $l->history_users;?></td>
			</tr>
		<?php endforeach;?>
		</tr>
	</table>
<?php endif;?>
</BODY>
</HTML>
