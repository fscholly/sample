var i18n = {
	previousMonth	: 'Mois précédent',
	nextMonth		: 'Mois prochain',
	months 			: ['Janvier','Février', 'Mars','Avril','Mai','Juin','Juillet','Août','Septembre',"Octobre","Novembre","Décembre"],
	weekdays		: ['dimanche'," lundi "," mardi "," mercredi "," jeudi "," vendredi "," samedi "],
	weekdaysShort	: ['Dim', 'Mon', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
};
$("#date").pikaday({
	format: "YYYY-MM-DD", //adjust to your liking
	changeMonth: true,
	changeYear: true,
	maxDate: moment().toDate(),
	yearRange: [2010,2020],
	i18n: i18n,
	firstDay:1
});