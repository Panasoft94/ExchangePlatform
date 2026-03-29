$(document).ready(function(){
	
	$('#initialisation').click(function(e){
		e.preventDefault();
		var resultat = confirm("Voulez-vous vraiment réinitialiser les données de l'election ? si certaines données existent déjà il est conseillé d'effectuer une sauvegarde avant d'y procéder notez que toutes les données seront reinitialisées continuez ?");
		if(resultat){
			location.href = "init.php?go";
		}

	});

	
$('#search').keyup(function(){
		var val = $(this).val();
		$('.electeur').html("<p style = 'margin : 10% auto;' class = 'text-center'><img src = 'images/loading.gif' /></p>");
		$.post(
				'rechercher.php',
				{
					valeur : val
				},
				'html'
				).done(function(data){
					 $('.electeur').html(data);
				});
	});
	
	var compteur = 3; //nombre de seconde avant l'affichage du résultat


	function supprimer(identifiant,type){
			
		var confirmation = confirm("Etes-vous sûr de vouloir supprimer cet enregistrement ?");
		if(confirmation){
			location.href = "supprimer_"+type+".php?id="+identifiant;
		}
	}
	
	
