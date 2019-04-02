'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * CibFilePreview functionality
*/

var CibFilePreview = function(options) {
   return {
        init: function(formSelector, selectorHelper, filename, cssClass) {
            
            if (selectorHelper == true) {
                var helper = 'photo_file';
                formSelector = formSelector + '_' + helper;
            }
            
            var options = {
                'buttonContent': 'Télécharger un fichier'
            };
            if (filename) {
                options ['existingFiles'] = { "123":  filename };
            }
            
            $(formSelector).simpleFilePreview(options);
            
            if (cssClass != undefined) {
                $(formSelector).closest('div').addClass(cssClass);
                $(formSelector).addClass(cssClass);
                $(formSelector).next('img').addClass(cssClass);
            }
        }
   } 
}();






