'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Pikaday functionality
*/

var CibPikaday = function(options) {
    var initPikaday = function(options) {
      
    // init pikaday
    var i18n = {
          previousMonth	: 'Mois précédent',
          nextMonth	: 'Mois prochain',
          months 		: ['Janvier','Février', 'Mars','Avril','Mai','Juin','Juillet','Août','Septembre',"Octobre","Novembre","Décembre"],
          weekdays	: ['dimanche'," lundi "," mardi "," mercredi "," jeudi "," vendredi "," samedi "],
          weekdaysShort	: ['Dim', 'Mon', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
      };

    if(typeof $.fn.pikaday !== 'undefined') {
      $(options.formSelector + ' .pikaday').pikaday({
          format: "DD/MM/YYYY", //adjust to your liking
          changeMonth: true,
          changeYear: true,
          i18n: i18n,
          firstDay:1,
          yearRange: 100,
      });
    }
        
    };
   
   return {
      init: function(options) {
        initPikaday(options);
      }
   } 
}();






