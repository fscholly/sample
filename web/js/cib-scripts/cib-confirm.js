'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Confirm functions
*/

var CibConfirm = function(options) {
   
   return {
        getDeleteOptions: function() {
            return {
              confirmButton: '<i class="fa fa-check"></i> Supprimer',
              confirmButtonClass: 'btn red',
              cancelButton: '<i class="fa fa-reply"></i> Annuler',
              cancelButtonClass: 'btn grey',
              cancel: function(){
                  //Rien
              }
            }
        },
        getConfirmOptions: function() {
            return {
              confirmButton: '<i class="fa fa-check"></i> Valider',
              confirmButtonClass: 'btn green',
              cancelButton: '<i class="fa fa-reply"></i> Annuler',
              cancelButtonClass: 'btn grey',
              cancel: function(){
                  //Rien
              }
            }
        },
        showHelp: function(message, title) {

            if (title == undefined) {
                title = "Aide";
            }  
            var confirmOptions = {
                title: title,
                icon: 'fa fa-question-circle',
                closeIcon: true,
                closeIconClass: 'fa fa-close',
                backgroundDismiss: true,
                columnClass: 'small',
                content: message,
                confirmButton: '<i class="fa fa-check"></i> OK',
                confirmButtonClass: 'btn green'
            };

            $.alert(confirmOptions);

        }
   } 
}();






