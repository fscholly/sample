'use strict';
/*
 * Copyright (c) 2016 CORESONANCE;
*/

/*
 * CibFilePreview functionality
*/

var CibCropit = function(options) {
    
    var prepareSubmit = function(options) {
        var file = $(options.formSelector + ' .cropit-image-input').val();
        var range = $(options.formSelector + ' .cropit-image-zoom-input').val();
        // Vérifier qu'on ne redimensionne pas l'image de base
        if(file || range != 0) {
            // Move cropped image data to hidden input
            var imageData = $(options.formSelector + ' .image-cropper').cropit('export',{
                type: 'image/png'
            });
            $(options.formSelector + ' .hidden-image-data').val(imageData);
        }
    };
    
    return {
        prepareSubmit: function(options) {
            prepareSubmit(options);
        },
        init: function(options) {
            $(options.formSelector + ' .image-cropper').cropit({
                imageBackground: false,
                width : options.cropitWidth,
                height: options.cropitHeight,
                exportZoom: options.cropitExportZoom,
                smallImage: 'allow',
                freeMove: true,
                maxZoom: 10,
                minZoom: 'fit',
// pour développement et débuggage
//                onFileChange: function(e){
//                },
//                onImageLoading: function(e){
//                },
//                onImageLoaded: function(e){
//                },
//                onImageError: function(e){
//                },
//                onFileReaderError: function(e){
//                },
            });

            // In the demos I'm passing in an imageState option
            // so it renders an image by default:
            $(options.formSelector + ' .cropit-select-image').click(function() {
                $(options.formSelector + ' .cropit-image-input').click();
            });

            // Prévisualisation de l'image de base
            if (options.cropitImageSrc) {
                $(options.formSelector + ' .image-cropper').cropit('imageSrc', options.cropitImageSrc);
            }
            
            //Bouton submit
            $(options.formSelector).on("submit", function() {
                prepareSubmit(options);
            });
        }
   } 
}();






