'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Forms functionality
*/

var CibForm = function(options) {
    
    /*
     * Permet d'initialiser les listeners du formulaire  
     * 
     */
    var initFormListeners = function(options) {
        
        //Select2
        CibSelect2.init(options);

        //Pikaday
        CibPikaday.init(options);
        
        
        //CibMaterianote
        var smOptions = {
            'selector' : options.formSelector + ' .materialnote',
            'simple' : false
        };
        CibMaterialnote.init(smOptions);
       
        //CibMaterianote simple
        smOptions = {
            'selector' : options.formSelector + ' .materialnote-simple',
            'simple' : true,
        };
        CibMaterialnote.init(smOptions);
        
        // Cropit (redimensionnement d'image)
        CibCropit.init(options);
    };

    /*
     * Permet d'activer ou désactiver un formulaire  
     * 
     * @param Boolean enable
     * @param String formSelector
     */
    var enableForm = function(formSelector, enable) {
        if (enable) {
            //Activation
            $(formSelector + " input").attr("disabled", false);
            $(formSelector + " select").attr("disabled", false);
            $(formSelector + " textarea").attr("disabled", false);
        }
        else {
            //Désactivation
            $(formSelector + " input").attr("disabled", "disabled");
            $(formSelector + " select").attr("disabled", "disabled");
            $(formSelector + " textarea").attr("disabled", "disabled");
        }
    };
    
    /*
     * Permet d'initialiser le mode édition et les boutons associés  
     * 
     * @param Boolean enable
     * @param String formSelector
     */
    var initModeEdit = function(formSelector) {
        //Mode édition (désactivé par défaut)
        modeEdit(formSelector, false);

        $('.edit-mode-button').click(function () {
            var isEnabled = isModeEditEnabeled();
            modeEdit(formSelector, !isEnabled);
        });
    };
    
    /*
     * Permet de passer en mode édition (enable = true):  
     * 
     * @param Boolean enable
     * @param String formSelector
     */
    var modeEdit = function(formSelector, enable) {
        if (enable) {
            // on active le mode édition
            $('body').addClass('mode-edit');

            //Activation du formulaire
            enableForm(formSelector, true);

            //Affichage des éléments du mode édition
            $('.hide-on-edit-mode').hide();
            $('.show-on-edit-mode').show(); 
        }
        else {
            //On désactive le mode édition
            $('body').removeClass('mode-edit');
            //désactivation du formulaire
            enableForm(formSelector, false);

            //Masquage des éléments du mode édition
            $('.show-on-edit-mode').hide();
            $('.hide-on-edit-mode').show();
        }
    };
    
    /*
     * Permet de savoir si le mode édition est activé  
     * 
     * @returns Boolean isEnabled
     */
    var isModeEditEnabeled = function()
    {
        var isEnabled = false;
        if ($('body').hasClass('mode-edit')) {
            isEnabled = true;
        }
        return isEnabled; 
    };
    
    /*
     * Active la soumission du formulaire lors de l'appui sur le bouton "Entrer"  
     * 
     */
    var submitOnEnter = function(formSelector) {
        $(document).keypress(function(e) {
            if(e.which == 13) {
                $(formSelector).submit();
            }
        });
    }
    
    var setFocus = function(formSelector, index) {
        if (index == undefined ) {
            index = 0;
        }
        
        setTimeout(function() {
            $($(formSelector)[0][index]).focus();
        }, 200);
    }
   return {
        init: function(options) {
            initFormListeners(options);
            setFocus(options.formSelector);
        },
        initFormListeners : function(options) {
            initFormListeners(options);
        },
        initModeEdit : function(formSelector) {
            initModeEdit(formSelector);
        },
        modeEdit : function(formSelector, enable) {
            modeEdit(formSelector, enable);
        },
        submitOnEnter : function(formSelector) {
            submitOnEnter(formSelector);
        },
        setFocus: function(formSelector) {
            setFocus(formSelector);
        }
    } 
}();






