jQuery(document).ready(function ($) {
    'use strict';
    var wp = window.wp,
        $body = $('body');

    $('#term-color').wpColorPicker();

    // Toggle add new attribute term modal
    var $modal = $('#nasa-attr-ux-modal-container'),
            $spinner = $modal.find('.spinner'),
            $msg = $modal.find('.message'),
            $metabox = null;

    $body.on('click', '.nasa-attr-ux_add_new_attribute', function (e) {
        e.preventDefault();
        var $button = $(this),
        taxInputTemplate = wp.template('nasa-attr-ux-input-tax'),
        data = {
            type: $button.data('type'),
            tax: $button.closest('.woocommerce_attribute').data('taxonomy')
        };

        // Insert input
        $modal.find('.nasa-attr-ux-term-val').html($('#tmpl-nasa-attr-ux-input-' + data.type).html());
        $modal.find('.nasa-attr-ux-term-tax').html(taxInputTemplate(data));

        if ('color' == data.type) {
            $modal.find('input.nasa-attr-ux-input-color').wpColorPicker();
        }

        $metabox = $button.closest('.woocommerce_attribute.wc-metabox');
        $modal.show();
    }).on('click', '.nasa-attr-ux-modal-close, .nasa-attr-ux-modal-backdrop', function (e) {
        e.preventDefault();
        closeModal();
    });

    // Send ajax request to add new attribute term
    $body.on('click', '.nasa-attr-ux-new-attribute-submit', function (e) {
        e.preventDefault();

        var $button = $(this),
            type = $button.data('type'),
            error = false,
            data = {};

        // Validate
        $modal.find('.nasa-attr-ux-input').each(function () {
            var $this = $(this);

            if ($this.attr('name') != 'slug' && !$this.val()) {
                $this.addClass('error');
                error = true;
            } else {
                $this.removeClass('error');
            }

            data[$this.attr('name')] = $this.val();
        });

        if (error) {
            return;
        }

        // Send ajax request
        $spinner.addClass('is-active');
        $msg.hide();
        wp.ajax.send('nasa_attr_ux_add_new_attribute', {
            data: data,
            error: function (res) {
                $spinner.removeClass('is-active');
                $msg.addClass('error').text(res).show();
            },
            success: function (res) {
                $spinner.removeClass('is-active');
                $msg.addClass('success').text(res.msg).show();

                $metabox.find('select.attribute_values').append('<option value="' + res.id + '" selected="selected">' + res.name + '</option>');
                $metabox.find('select.attribute_values').change();

                closeModal();
            }
        });
    });

    /**
     * Close modal
     */
    function closeModal() {
        $modal.find('.nasa-attr-ux-term-name input, .nasa-attr-ux-term-slug input').val('');
        $spinner.removeClass('is-active');
        $msg.removeClass('error success').hide();
        $modal.hide();
    }
    
    /**
     * Image - Media
     */
    if(typeof wp !== 'undefined') {
        $('body').on('click', 'button.upload_image-tax', function (e) {
            e.preventDefault();
            var image = wp.media({ 
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
            .on('select', function(){
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                var imgObj = uploaded_image.toJSON();
                // imgObj.url, imgObj.id

                // Let's assign the url value to the input field
                $('#term-image').val(imgObj.id);
                $('#nasa-attr-img-view').attr('src', imgObj.url);
                $('.remove_image-tax').show();
            });
        });
        
        $('body').on('click', 'button.remove_image-tax', function(e) {
            e.preventDefault();
            $('#term-image').val('');
            $('#nasa-attr-img-view').attr('src', $(this).attr('data-no_img'));
            $(this).hide();
        });
    }
});