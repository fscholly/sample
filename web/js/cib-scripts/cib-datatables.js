    'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * Datable functionality
*/

var CibDt = function(options) {
    
    var initDatatable = function(options) {
        
        if (options.hiddenCols == undefined) {
            options.hiddenCols = [];
        }


        var dom = "<'row'<'col s3 pb-5'f><'col s5 pb-5'<'alert grey lighten-4 black-text' i>><'col s4 pb-5' <'right' B>>>" +
                   "<'row'<'col s12'tr>>" +
                   "<'row'<'col s6'l><'col s6'p>>";
        var default_options = {
            dom: dom,
            paging: true,
            pageLength: 50,
            info: true,
            searching: true,
            searchHighlight: true,
            autoWidth: false,
            colReorder: true,
            stateSave: true,
            buttons: [
                { 
                    extend: 'colvis', 
                    text: '<i class="fa fa-cog mr-5"></i> Colonnes',
                    className: 'btn btn-small white grey-text pl-10 pr-10 mt-20',
                    columns: ':not(:first-child):not(:last-child)'
                },
                {
                    text: '<i class="fa fa-filter mr-5"></i> Filtres',
                    className: 'btn btn-small white grey-text pr-10 pl-10 mt-20 ml-5 dt-toogle-filters',
                }
            ],
            language: {
                "sProcessing":     'Chargement',    
                "sSearch":         "Rechercher&nbsp;:",    
                "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",    
                "sInfo":           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",    
                "sInfoEmpty":      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",    
                "sInfoFiltered":   "( _MAX_ &eacute;l&eacute;ments au total)",    
                "sInfoPostFix":    "",    
                "sLoadingRecords": 'Chargement en cours...',    
                "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",    
                "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {        
                    "sFirst":      "Premier",        
                    "sPrevious":   "<<",        
                    "sNext":       ">>",        
                    "sLast":       "Dernier"    
                },
                "oAria": {        
                    "sSortAscending":  ": activer pour trier la colonne par ordre croissant",        
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"    
                }},
                columnDefs: [
                    {
                        targets: options.hiddenCols,
                        visible: false
                    }
                ]
            }; 
  

        //Initialisation du datatable (on merge les options par défaut et les options passées en paramètre)
        $.extend(default_options, options);

        var dt= $(options.selector).DataTable(default_options);

        return dt;
    };
    
    
    var prepareFilters = function(options){
        // ajouter un footer qui va contenir les filtres
        $(options.selector + ' thead').after('<tfoot><tr class="dt-cibfilter hidden"></tr></tfoot>');
        
        // générer autant de filtres qu'il y a de colonnes dans le tableau
        // et les placer dans le footter
        $(options.selector + ' thead th').each( function () {
            var title = $(this).text();
            if($(this).hasClass("nofilter")){
                $('.dt-cibfilter').append('<td>&nbsp;</td>');
            }
            else{
                //$('.dt-cibfilter').append('<td><input type="text" placeholder="Rechercher '+title+'" /></td>');
                $('.dt-cibfilter').append('<td><input type="text" placeholder="Rechercher" /></td>');
            }
        } );

    };
    
    var activeFilters = function (dt, options){
        // déplacer les éléments du footer dans le header
        $(options.selector + ' tfoot tr').appendTo(options.selector + ' thead');
    
        // bind de la recherche pour chaque filtre
        dt.columns().every( function () {
            var that = this;
            
            // on écoute les éléments qui sont dans le footer 
            // Attention : pour l'objet Datatable, les filtres sont dans le footer
            // alors qu'en réalité on les a déplacé dans le <thead>
            $( 'input', this.footer()  ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            } );
        } );
    };
    
   return {
      init: function(options) {
        if(options.selector == undefined){
            options.selector = '#datatable';
        }  
        
        // Préparer les filtres
        if(options["cibFilter"] == true){
            prepareFilters(options);
        }
        
        var dt = initDatatable(options);
        
        dt.on( 'draw', function () {
            var body = $( dt.table().body() );
            body.unhighlight();
            body.highlight( dt.search() );  
        } );
        
        // Activer les filtres
        if(options["cibFilter"] == true){
            activeFilters(dt, options);
        }
        
        //Bouton qui permet d'afficher les filtres
        $('.dt-toogle-filters').click( function () {
            var filters = $(options.selector + ' thead tr.dt-cibfilter');
            if (filters.hasClass('hidden')) {
                filters.removeClass('hidden');
            }
            else {
                filters.addClass('hidden');
            }
        });
        
        // Ne pas sauvegarder les filtres de recherche
        dt.on( 'stateSaveParams.dt', function (e, settings, data) {
            // raz des filtres sur les colonnes
            data.columns.forEach(function(element) {
                element.search.search = "";
            });
            // raz du filtre sur le tableau entier
            data.search.search = "";
        } );

        return dt;
      }
   } 
}();
