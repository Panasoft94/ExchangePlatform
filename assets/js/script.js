$(document).ready(function(){

	$(".tooltip-input").tooltip({
		html:true,
		placement : "right auto",
		container : "body",
		trigger : "focus",
		animation : false
	});


	$('[data-toggle="tooltip"]').tooltip({
		html:true,
		placement : "auto right",
		container : "body",
		trigger : "hover focus",
		animation : false
	});



	$("#myDatatable").dataTable({
			"language": {
						"sProcessing":     "Traitement en cours...",
						"sSearch":         "Rechercher&nbsp;: ",
						"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
						"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
						"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
						"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
						"sInfoPostFix":    "",
						"sLoadingRecords": "Chargement en cours...",
						"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
						"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
						"oPaginate": {
							"sFirst":      "Premier",
							"sPrevious":   "Pr&eacute;c&eacute;dent",
							"sNext":       "Suivant",
							"sLast":       "Dernier"
						},
						"oAria": {
							"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
							"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
						}
			 },
				"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Tous"]]
	});



	$(".datepicker").datepicker({
		format: "dd MM yyyy",
		startView: "year",
		autoclose: true,
		todayHighlight: true,
		viewMode: "year",
		language: "fr",
		todayBtn: "linked"
	});

	$(".datepicker-tiret-us").datepicker({
		format: "yyyy-mm-dd",
		startView: "weeks",
		autoclose: true,
		todayHighlight: true,
		viewMode: "weeks",
		language: "fr",
		todayBtn: "linked"
	});

	$(".datepicker-month").datepicker({
		format: "MM",
		startView: "month",
		autoclose: true,
		todayHighlight: true,
		viewMode: "month",
		language: "fr",
		todayBtn: "linked"
	});

	$(".datepicker-mois").datepicker({
		format: "MM yyyy",
		autoclose: true,
		todayHighlight: true,
		language: "fr",
		todayBtn: "linked",
		viewMode: "year"
	});


		$(".submenu > a").click(function(e) {
				e.preventDefault();
				var $li = $(this).parent("li");
				var $ul = $(this).next("ul");

				if($li.hasClass("open")) {
				  $ul.slideUp(350);
				  $li.removeClass("open");
				} else {
				  $(".nav > li > ul").slideUp(350);
				  $(".nav > li").removeClass("open");
				  $ul.slideDown(350);
				  $li.addClass("open");
				}
		});




});
