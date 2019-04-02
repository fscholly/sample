'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Fonctions utiles
*/

var CibUtils = function(options) {
    
    /*
     * Permet d'afficher ou cacher un loader 
     * 
     * @param String loaderId
     * @param String divId
     * @param Boolean hide
     */
    var showLoader = function(loaderId, divId, hide) {
        // Cacher le message d'erreur
        $(loaderId+"_error").hide();
        
        if (hide) {
            //On cache le loader
            $(loaderId).hide();
            $(divId).show();
        }
        else {
            //On affiche le loader
            $(divId).hide();
            $(loaderId).show();
        }
    };
    
    /*
     * Permet de réinitialiser les tooltips
     * 
     */
    var reInitTooltips = function(selector) {
      $(selector + ' .tooltipped').tooltip({
        delay: 50
      });
    };
    
     var reInitDropdowns = function (selector) {
        $(selector + ' .dropdown-button').each(function() {
            var hover = $(this).attr('data-hover') == "true" || false;
            var constrainWidth = $(this).attr('data-constrainwidth') == "false" || true;
            var inDuration = $(this).attr('data-induration') || 300;
            var outDuration = $(this).attr('data-outduration') || 300;
            $(this).dropdown({
                hover: hover,
                constrain_width: constrainWidth,
                inDuration: inDuration,
                outDuration: outDuration
            });
        });
    };
    
    /*
     * Calcul un prix HT
     */
    var checkPriceForHt = function(selectors) {
        var pHt = $(selectors.pHt).val();
        var tvaRateVal = $(selectors.tvaRate).val();
        var tvaRate = tvaRateVal ? parseFloat(tvaRateVal) : 0;
        var pTtc = pHt ? parseFloat(pHt) + parseFloat(pHt) * tvaRate : '';
//        pTtc = pTtc ? pTtc.toFixed(3) : '';
        
        $(selectors.pTtc).val(pTtc);
    };

    /*
     * Calcul un prix TTC
     */
    var checkPriceForTtc = function(selectors) {
        var pTtc = $(selectors.pTtc).val();
        var tvaRateVal = $(selectors.tvaRate).val();
        var tvaRate = tvaRateVal ? parseFloat(tvaRateVal) : 0;
        var pHt = pTtc ? parseFloat(pTtc) / (1 + tvaRate) : '';
//        pHt = pHt ? pHt.toFixed(3) : '';
        
        $(selectors.pHt).val(pHt);
    };
    
   return {
        init: function(options) {
            //Fixed navbar
            $('.fixed-nav').pushpin({
              top: 70,
              //bottom: 1000,
              offset: 65
            });

            $('.fixed-nav > ul > li').click(function () {
                $('.fixed-nav > ul > li').removeClass('active');
                $(this).addClass('active');
            });
            
            //Ouverture de la barre d'action
            $('.fixed-action-btn').openFAB();

            //Icons d'aide
            $('.icon-help').click(function () {
                var message = $(this).data('helpMessage');
                CibConfirm.showHelp(message);
            });
            
        },
        showLoader : function(loaderId, divId, hide) {
            showLoader(loaderId, divId, hide);
        },
        reInitTooltips : function(selector) {
            reInitTooltips(selector);
        },
        reInitDropdowns : function(selector) {
            reInitDropdowns(selector);
        },
        checkPriceForHt : function(selectors) {
            checkPriceForHt(selectors);
        },
        checkPriceForTtc : function(selectors) {
            checkPriceForTtc(selectors);
        }   
    } 
}();






