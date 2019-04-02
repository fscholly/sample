//Date Fr custom (avec gestion des &nbsp;)
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "custom-pre": function ( a ) {
        var ukDatea = a.split('/');
        if(a === "&nbsp;") return -1;
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },
 
    "custom-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
 
    "custom-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );

//Auto detect
jQuery.fn.dataTableExt.aTypes.unshift(
    function ( sData )
    {
        if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
        {
            return 'custom';
        }
        else if (sData === "&nbsp;")
        {
            return 'custom';
        }
        return null;
    }
);