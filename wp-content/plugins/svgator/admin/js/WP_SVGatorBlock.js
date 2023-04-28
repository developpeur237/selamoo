(function (wp, $) {
    let SVGatorEditorBlock = function() {
        let createEl = wp.element.createElement;

        const BlockControls = wp.blockEditor.BlockControls;
        const ResizableBox = wp.components.ResizableBox;
        const Button = wp.components.Button;

        let plcHandler = {
            empty: function(){
                return createEl(
                    'div',
                    {
                        key: 'placeholder',
                        className: 'wp-svgator-image',
                    },
                    'Please select a SVG.'
                );
            },
            preview: function(props){
                let attr = props.attributes;
                let svg = plcHandler.save(attr);
                let initialSize = {};
                let elProps = {
                    key: 'placeholder-resizer',
                    showHandle: props.isSelected,
                    lockAspectRatio: true,
                    onResizeStart: function(e, direction, ref) {
                        let $img = $(ref).find('.wp-svgator-image');
                        initialSize.width = $img.width();
                        initialSize.height = $img.height();
                    },
                    onResizeStop: function(e, direction, ref, d) {
                        props.setAttributes({
                            responsive: '',
                            width: initialSize.width + d.width,
                            height: initialSize.height + d.height,
                        });
                    }
                };

                if (!attr.responsive && attr.width && attr.height) {
                    elProps.size = {
                        width: attr.width,
                        height: attr.height,
                    };
                } else {
                    elProps.size = {
                        width: '100%',
                        height: '100%',
                    };
                }

                return createEl(
                    ResizableBox,
                    elProps,
                    svg
                );
            },
            save: function(attr){
                if (!attr.src) {
                    return false;
                }

                let elProps = {
                    src: attr.src,
                    'data-attachment-id': attr.attachmentId,
                    className: 'wp-svgator-image',
                };

                if (!attr.responsive && attr.width && attr.height) {
                    elProps.width = attr.width;
                    elProps.height = attr.height;
                    elProps.responsive = '';
                } else {
                    elProps.responsive = 'true';
                }

                let img = createEl(
                    'img',
                    elProps
                );

                return createEl(
                    'div',
                    {
                        key: 'placeholder',
                        className: 'wp-svgator-container',
                    },
                    img
                );
            },
        };

        function createBlockControlButton(text, callback){
            let key = text.toLowerCase().replace(/[^a-z0-9\-]+/, '-');
            key = key.replace(/^-+|-+$/i, '');
            return createEl(
                Button,
                {
                    key,
                    onClick: function(){
                        callback();
                    },
                },
                text
            )
        }

        let svgatorMedia = new SVGatorMedia({
            onSelect: function() {}
        });

        let icon = createEl(
            'img',
            {
                src: wp_svgator.plugin_logo,
                width: 24,
            }
        );

        function registerBlock()
        {
            wp.blocks.registerBlockType(
                'wp-svgator/insert-svg',
                {
                    title: 'SVGator',
                    icon: icon,
                    category: 'media',
                    supports: {
                        // Remove support for an HTML mode.
                        html: false,
                        alignWide: true,
                        className: false,
                        customClassName: false,
                        defaultStylePicker: false,

                    },
                    attributes: {
                        responsive: {
                            type: 'string',
                            source: 'attribute',
                            selector: 'img.wp-svgator-image',
                            attribute: 'data-responsive',
                        },
                        width: {
                            type: 'string',
                            source: 'attribute',
                            selector: 'img.wp-svgator-image',
                            attribute: 'width',
                        },
                        height: {
                            type: 'string',
                            source: 'attribute',
                            selector: 'img.wp-svgator-image',
                            attribute: 'height',
                        },
                        src: {
                            type: 'string',
                            source: 'attribute',
                            selector: 'img.wp-svgator-image',
                            attribute: 'src',
                        },
                        attachmentId: {
                            type: 'string',
                            source: 'attribute',
                            selector: 'img.wp-svgator-image',
                            attribute: 'data-attachment-id',
                        }
                    },
                    edit: function(props) {
                        svgatorMedia.setOptions({
                            onSelect: function(attachment) {
                                let attrs = {
                                    src: attachment.icon,
                                    attachmentId: attachment.ID,
                                    responsive: 'true',
                                };
                                if (!attachment.responsive && attachment.width && attachment.height) {
                                    attrs.width = attachment.width;
                                    attrs.height = attachment.height;
                                    attrs.responsive = '';
                                }
                                props.setAttributes(attrs);
                            }
                        });

                        let placeholder = plcHandler.preview(props) || plcHandler.empty();

                        if (props.isSelected && !props.attributes.src) {
                            svgatorMedia.open();
                        }

                        let childElements = [];
                        childElements.push(createBlockControlButton(
                            'Replace',
                            function(){
                                svgatorMedia.open();
                            })
                        );

                        if (!props.attributes.responsive && props.attributes.width && props.attributes.height) {
                            childElements.push(createBlockControlButton(
                                'Responsive',
                                function(){
                                    props.setAttributes({
                                        width: '',
                                        height: '',
                                        responsive: 'true',
                                    });
                                })
                            );
                        }

                        return [
                            createEl(
                                BlockControls,
                                { key: 'controls' },
                                childElements
                            ),
                            placeholder
                        ];
                    },
                    save: function(props) {
                        return plcHandler.save(props.attributes);
                    },
                }
            );
        }

        this.registerBlock = registerBlock;
    };

    let svgator_bp = new SVGatorEditorBlock();
    svgator_bp.registerBlock();
})(window.wp, jQuery);
