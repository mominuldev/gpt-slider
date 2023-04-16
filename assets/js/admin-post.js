// jQuery(document).ready(function ($) {
//     $('#add-item').on('click', function (e) {
//         e.preventDefault();
//         var item = $('.gpt-slider-item:first').clone(true);
//         item.find('input').val('');
//         item.appendTo('#gpt-slider-items');
//     });
//
//     $('.remove-item').on('click', function (e) {
//         e.preventDefault();
//         $(this).parents('.gpt-accordion-item').remove();
//     });
//
//     // Copy gpt-slider-item
//     $('.copy-item').on('click', function (e) {
//         e.preventDefault();
//         var item = $(this).parents('.gpt-slider-item').clone(true);
//         item.appendTo('#gpt-slider-items');
//         console.log(item);
//     });
//
//
//
//     $('.list-heading').on('click', function(e) {
//         e.preventDefault();
//         if ($(this).hasClass('active')) {
//             $(this).removeClass('active');
//             $(this).next()
//                 .stop()
//                 .slideUp(300);
//         } else {
//             $(this).addClass('active');
//             $(this).next()
//                 .stop()
//                 .slideDown(300);
//         }
//     });
//
//
//     // $('#my_image_upload_button').click(function() {
//     //     var mediaUploader = wp.media({
//     //         title: 'Upload Image',
//     //         button: {
//     //             text: 'Use this image'
//     //         },
//     //         multiple: false
//     //     });
//     //     mediaUploader.on('select', function() {
//     //         var attachment = mediaUploader.state().get('selection').first().toJSON();
//     //         $('#my_image_url').val(attachment.url);
//     //     });
//     //     mediaUploader.open();
//     // });
//     //
//     // // Remove image
//     // $('#remove-image').on('click', function(e) {
//     //     e.preventDefault();
//     //     $('#my_image_url').val('');
//     // }   );
// });
//
//
// jQuery(document).ready(function($) {
//     var customUploader;
//
//     $('.gpt-slider-image').on('click', '.custom_upload_image_button', function (e) {
//         e.preventDefault();
//
//         var $this = $(this);
//
//         // If the uploader object has already been created, reopen the dialog
//         if (customUploader) {
//             customUploader.open();
//             return;
//         }
//
//         // Extend the wp.media object
//         customUploader = wp.media.frames.file_frame = wp.media({
//             title: 'Choose Image',
//             button: {
//                 text: 'Choose Image'
//             },
//             multiple: false
//         });
//
//         // When a file is selected, grab the URL and set it as the text field's value
//         customUploader.on('select', function() {
//             var attachment = customUploader.state().get('selection').first().toJSON();
//             $('#custom-image-upload').val(attachment.url);
//             $('.custom_remove_image_button').show(); // show the remove button after an image is uploaded
//         });
//
//         // Open the uploader dialog
//         customUploader.open();
//     });
//
//
//
//     // $('.gpt-slider-image').on('click', '.custom_remove_image_button', function (e) {
//     //     e.preventDefault();
//     //
//     //     var $this = $(this);
//     //     $this.next('.cbxtourmetabox_wrap').toggle();
//     // });
//
//     $('.custom_remove_image_button').click(function() {
//         $('#custom-image-upload').val('');
//         $(this).hide(); // hide the remove button after the image is removed
//
//         // remove the image preview
//         $('.slider-image-preview').attr('src', '');
//     });
// });
//
//
//
