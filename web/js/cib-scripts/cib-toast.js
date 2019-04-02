'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Toast functionality
*/

var CibToast = function(options) {
   
   return {
      showToast: function(type, message) {
          
          var duration = 5000;
          var css = "alert blue lighten-2 white-text";
          var title = "Info";
          
          switch(type) {
              case 'success' : 
                    css = 'alert green lighten-2 white-text';
                    title = 'Succès ';
                    break;
              case 'error' : 
                    css = 'alert red lighten-2 white-text';
                    title = 'Erreur ';
                    break;
              case 'warning' : 
                    css = 'alert orange lighten-2 white-text';
                    title = 'Info ';
                    break;
          }
          Materialize.toast('<b>'+title+'</b> : '+ message, duration , css);
      }
   } 
}();






