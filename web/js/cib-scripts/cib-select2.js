'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Select2 functionality
*/

var CibSelect2 = function(options) {
    var initSelect2 = function(options, fieldOnly) {
      
       var placeHolder = options['placeHolder'];

       var selector = '.select2';
       if (options.fieldSelector && fieldOnly){
           selector = options.fieldSelector;
       } 
       else if (options.formSelector) {
           selector = options.formSelector + ' ' + selector;
       }
       
       $(selector).each(function() {
            // si le select2 est obligatoire on ne peut pas le vider
            var allowClear = !($(this).prop('required'));
            var placeholder = false;
            if ((allowClear) && (placeHolder !== undefined)) {
                placeholder=  placeHolder;
            }
           
            $(this).select2({
                width: '100%',
                placeholder: placeholder,
                allowClear: allowClear
            });    
       });

    };
    
    var selectValue = function(options){
        var selectedDatas =  $(options.fieldSelector).val();
        
        // Déselectionner l'ancienne valeur
        if (options.valueToRemove) {
            var i = selectedDatas.indexOf(options.valueToRemove);
            if (i != -1) {
                selectedDatas.splice(i,1);
            }
        }
        
        // Sélectionner la valeur
        if(selectedDatas){
            selectedDatas.push(options.value);
        }
        else{
            selectedDatas = [options.value];
        }
        $(options.fieldSelector).val(selectedDatas);
        
        initSelect2(options, true);
    }
    
    return {
        init: function(options, fieldOnly) {
            initSelect2(options, fieldOnly);
        },
        addButtonNew : function(options) {
            $(options.fieldSelector).data('select2').$results.parent('.select2-results').append('<hr class="m-0"><button id="' + options.addButtonId + '" class="btn btn-small m-5"><i class="fa fa-plus"></i> Ajouter</button>');
        },
        selectValue: function(options){
            selectValue(options);
        }
   } 
}();






