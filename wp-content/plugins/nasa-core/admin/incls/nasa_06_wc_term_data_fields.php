<?php
defined('ABSPATH') or die(); // Exit if accessed directly

/**
 * @class 		Nasa_WC_Term_Data_Fields
 * @version		1.0
 * @author 		nasaTheme
 */
if (!class_exists('Nasa_WC_Term_Data_Fields')) {

    class Nasa_WC_Term_Data_Fields {
        
        private $_cat_header = 'cat_header';
        private $_cat_bread_bg = 'cat_breadcrumb_bg';
        private $_cat_bread_text = 'cat_breadcrumb_text_color';
        private $_cat_sidebar = 'cat_sidebar_override';

        private static $_instance = null;

        /*
         * Intance start contructor
         */

        public static function getInstance() {
            if (!class_exists('WooCommerce') || !function_exists('get_term_meta')) {
                return null;
            }

            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /*
         * Contructor
         */
        public function __construct() {
            // Cat header
            add_action('product_cat_add_form_fields', array($this, 'taxonomy_cat_header'), 10, 1);
            add_action('product_cat_edit_form_fields', array($this, 'taxonomy_cat_header'), 10, 1);

            // Cat breadcrumb
            add_action('product_cat_add_form_fields', array($this, 'taxonomy_background_breadcrumb_create'), 10, 1);
            add_action('product_cat_edit_form_fields', array($this, 'taxonomy_background_breadcrumb_edit'), 10, 1);
            
            // Override sidebar for Category
            add_action('product_cat_add_form_fields', array($this, 'taxonomy_cat_sidebar'), 10, 1);
            add_action('product_cat_edit_form_fields', array($this, 'taxonomy_cat_sidebar'), 10, 1);

            add_action('created_term', array($this, 'save_taxonomy_custom_fields'), 10, 3);
            add_action('edit_term', array($this, 'save_taxonomy_custom_fields'), 10, 3);
        }
        
        /*
         * Create custom Override sidebar
         */
        public function taxonomy_cat_sidebar($term = null) {
            if (is_object($term) && $term) {
                if (!$cat_sidebar = get_term_meta($term->term_id, $this->_cat_sidebar)) {
                    $cat_sidebar = add_term_meta($term->term_id, $this->_cat_sidebar, '0');
                }
                ?>
                <tr class="form-field">
                    <th scope="row" valign="top">
                        <label for="<?php echo $this->_cat_sidebar; ?>"><?php esc_html_e('Override Shop Sidebar', 'nasa-core'); ?></label>
                        
                    </th>
                    <td>             
                        <?php
                        $checked = isset($cat_sidebar[0]) && $cat_sidebar[0] == '1' ? ' checked' : '';
                        echo '<p><input type="checkbox" id="' . $this->_cat_sidebar . '" name="' . $this->_cat_sidebar . '" value="1"' . $checked . ' />' . '<label for="' . $this->_cat_sidebar . '" style="display: inline;">' . esc_html__('Yes, please!', 'nasa-core') . '</label></p>';
                        ?>
                        <p><?php esc_html_e('Please checked, save and built sidebar at: Appearance > Widgets', 'nasa-core'); ?></p>
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <div class="form-field term-cat_header-wrap">
                    <label for="<?php echo $this->_cat_sidebar; ?>"><?php esc_html_e('Override Shop Sidebar', 'nasa-core'); ?></label>
                    <p><input type="checkbox" id="<?php echo $this->_cat_sidebar; ?>" name="<?php echo $this->_cat_sidebar; ?>" value="1" /><label for="<?php echo $this->_cat_sidebar; ?>" style="display: inline;"><?php esc_html_e('Yes, please!', 'nasa-core'); ?></label></p>
                    <p><?php esc_html_e('Please checked, save and built sidebar at: Appearance > Widgets', 'nasa-core'); ?></p>
                </div>
                <?php
            }
        }

        /*
         * Create custom cat header
         */
        public function taxonomy_cat_header($term = null) {
            if (is_object($term) && $term) {
                if (!$cat_header = get_term_meta($term->term_id, $this->_cat_header)) {
                    $cat_header = add_term_meta($term->term_id, $this->_cat_header, '');
                }
                ?>
                <tr class="form-field">
                    <th scope="row" valign="top">
                        <label for="<?php echo $this->_cat_header; ?>"><?php esc_html_e('Top Content', 'nasa-core'); ?></label>
                    </th>
                    <td>             
                        <?php
                        $content = isset($cat_header[0]) ? $cat_header[0] : '';
                        echo '<textarea id="' . $this->_cat_header . '" name="' . $this->_cat_header . '">' . $content . '</textarea>';
                        ?>
                        <p class="description"><?php esc_html_e('Enter a value for this field. Shortcodes are allowed. This will be displayed at top of the category.', 'nasa-core'); ?></p>
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <div class="form-field term-cat_header-wrap">
                    <label for="<?php echo $this->_cat_header; ?>"><?php esc_html_e('Top Content', 'nasa-core'); ?></label>
                    <textarea id="<?php echo $this->_cat_header; ?>" name="<?php echo $this->_cat_header; ?>"></textarea>
                    <p class="description"><?php esc_html_e('Enter a value for this field. Shortcodes are allowed. This will be displayed at top of the category.', 'nasa-core'); ?></p>
                </div>
                <?php
            }
        }

        /*
         * Create custom breadcrumb
         * Case create category
         */

        public function taxonomy_background_breadcrumb_create() {
            ?>
            <div class="form-field term-breadcrumb_bg-wrap">
                <label><?php _e('Background Breadcrumb', 'nasa-core'); ?></label>
                <div id="breadcrumb_bg_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="<?php echo $this->_cat_bread_bg; ?>" name="<?php echo $this->_cat_bread_bg; ?>" />
                    <button type="button" class="upload_image_button_bread button"><?php _e('Upload/Add image', 'nasa-core'); ?></button>
                    <button type="button" class="remove_image_button_bread button"><?php _e('Remove image', 'nasa-core'); ?></button>
                </div>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        // Only show the "remove image" button when needed
                        if (!$('#<?php echo $this->_cat_bread_bg; ?>').val()) {
                            $('.remove_image_button_bread').hide();
                        }

                        // Uploading files
                        var file_frame_bread;

                        $('body').on('click', '.upload_image_button_bread', function (event) {

                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if (file_frame_bread) {
                                file_frame_bread.open();
                                return;
                            }

                            // Create the media frame.
                            file_frame_bread = wp.media.frames.downloadable_file = wp.media({
                                title: '<?php _e("Choose an image", "nasa-core"); ?>',
                                button: {
                                    text: '<?php _e("Use image", "nasa-core"); ?>'
                                },
                                multiple: false
                            });

                            // When an image is selected, run a callback.
                            file_frame_bread.on('select', function () {
                                var attachment = file_frame_bread.state().get('selection').first().toJSON();
                                var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                                $('#<?php echo $this->_cat_bread_bg; ?>').val(attachment.id);
                                $('#breadcrumb_bg_thumbnail').find('img').attr('src', attachment_thumbnail.url);
                                $('.remove_image_button_bread').show();
                            });

                            // Finally, open the modal.
                            file_frame_bread.open();
                        });

                        $('body').on('click', '.remove_image_button_bread', function () {
                            $('#breadcrumb_bg_thumbnail').find('img').attr('src', '<?php echo esc_js(wc_placeholder_img_src()); ?>');
                            $('#<?php echo $this->_cat_bread_bg; ?>').val('');
                            $('.remove_image_button_bread').hide();
                            return false;
                        });

                        $(document).ajaxComplete(function (event, request, options) {
                            if (request && 4 === request.readyState && 200 === request.status
                                && options.data && 0 <= options.data.indexOf('action=add-tag')) {

                                var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');
                                if (!res || res.errors) {
                                    return;
                                }
                                // Clear Thumbnail fields on submit
                                $('#breadcrumb_bg_thumbnail').find('img').attr('src', '<?php echo esc_js(wc_placeholder_img_src()); ?>');
                                $('#<?php echo $this->_cat_bread_bg; ?>').val('');
                                $('.remove_image_button_bread').hide();
                                // Clear Display type field on submit
                                $('#display_type').val('');
                                return;
                            }
                        });
                    });
                </script>
                <div class="clear"></div>
            </div>
                
            <div class="form-field term-breadcrumb_text_color-wrap">
                <label><?php _e('Text color breadcrumb', 'nasa-core'); ?></label>
                <div class="nasa_p_color">
                    <input type="text" class="widefat nasa-color-field" id="<?php echo $this->_cat_bread_text; ?>" name="<?php echo $this->_cat_bread_text; ?>" value="" />
                </div>
                <div class="clear"></div>
            </div>
        <?php
        }

        /*
         * Create custom breadcrumb
         * Case edit category
         */

        public function taxonomy_background_breadcrumb_edit($term) {
            $thumbnail_id = get_term_meta($term->term_id, $this->_cat_bread_bg);
            $thumbnail_id = isset($thumbnail_id[0]) && (int) $thumbnail_id[0] ? (int) $thumbnail_id[0] : '0';
            $image = $thumbnail_id ? wp_get_attachment_thumb_url($thumbnail_id) : wc_placeholder_img_src();
            ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label><?php _e('Background Breadcrumb', 'nasa-core'); ?></label></th>
                <td>
                    <div id="breadcrumb_bg_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url($image); ?>" width="60px" height="60px" /></div>
                    <div style="line-height: 60px;">
                        <input type="hidden" id="<?php echo $this->_cat_bread_bg; ?>" name="<?php echo $this->_cat_bread_bg; ?>" value="<?php echo $thumbnail_id; ?>" />
                        <button type="button" class="upload_image_button_bread button"><?php _e('Upload/Add image', 'nasa-core'); ?></button>
                        <button type="button" class="remove_image_button_bread button"><?php _e('Remove image', 'nasa-core'); ?></button>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            // Only show the "remove image" button when needed
                            if ('0' === $('#<?php echo $this->_cat_bread_bg; ?>').val()) {
                                $('.remove_image_button_bread').hide();
                            }

                            // Uploading files
                            var file_frame_bread;

                            $('body').on('click', '.upload_image_button_bread', function (event) {

                                event.preventDefault();

                                // If the media frame already exists, reopen it.
                                if (file_frame_bread) {
                                    file_frame_bread.open();
                                    return;
                                }

                                // Create the media frame.
                                file_frame_bread = wp.media.frames.downloadable_file = wp.media({
                                    title: '<?php _e("Choose an image", "nasa-core"); ?>',
                                    button: {
                                        text: '<?php _e("Use image", "nasa-core"); ?>'
                                    },
                                    multiple: false
                                });

                                // When an image is selected, run a callback.
                                file_frame_bread.on('select', function () {
                                    var attachment = file_frame_bread.state().get('selection').first().toJSON();
                                    var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                                    $('#<?php echo $this->_cat_bread_bg; ?>').val(attachment.id);
                                    $('#breadcrumb_bg_thumbnail').find('img').attr('src', attachment_thumbnail.url);
                                    $('.remove_image_button_bread').show();
                                });

                                // Finally, open the modal.
                                file_frame_bread.open();
                            });

                            $('body').on('click', '.remove_image_button_bread', function () {
                                $('#breadcrumb_bg_thumbnail').find('img').attr('src', '<?php echo esc_js(wc_placeholder_img_src()); ?>');
                                $('#<?php echo $this->_cat_bread_bg; ?>').val('');
                                $('.remove_image_button_bread').hide();
                                return false;
                            });
                        });
                    </script>
                    <div class="clear"></div>
                </td>
            </tr>
            
            <?php
            $text_color = get_term_meta($term->term_id, $this->_cat_bread_text, true);
            $text_color = !$text_color ? '' : $text_color;
            ?>
            <tr>
                <th scope="row" valign="top"><label><?php _e('Text color breadcrumb', 'nasa-core'); ?></label></th>
                <td>
                    <div class="nasa_p_color">
                        <input type="text" class="widefat nasa-color-field" id="<?php echo $this->_cat_bread_text; ?>" name="<?php echo $this->_cat_bread_text; ?>" value="<?php echo $text_color; ?>" />
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
            <?php
        }

        /*
         * Save taxonomy custom fields
         */
        public function save_taxonomy_custom_fields($term_id, $tt_id = '', $taxonomy = '') {
            if('product_cat' == $taxonomy) {
                /*
                 * Cat header
                 */
                if (isset($_POST[$this->_cat_header])) {
                    update_term_meta($term_id, $this->_cat_header, $_POST[$this->_cat_header]);
                }

                /*
                 * Cat breadcrumb Background
                 */
                if (isset($_POST[$this->_cat_bread_bg])) {
                    update_term_meta($term_id, $this->_cat_bread_bg, absint($_POST[$this->_cat_bread_bg]));
                }

                /*
                 * Cat breadcrumb text color
                 */
                if (isset($_POST[$this->_cat_bread_text])) {
                    update_term_meta($term_id, $this->_cat_bread_text, $_POST[$this->_cat_bread_text]);
                }
                
                /*
                 * Cat Override sidebar
                 */
                $value = isset($_POST[$this->_cat_sidebar]) && $_POST[$this->_cat_sidebar] == '1' ? '1' : '0';
                update_term_meta($term_id, $this->_cat_sidebar, $value);

                $term = get_term($term_id , 'product_cat');
                if($term) {
                    $sidebar_cats = get_option('nasa_sidebars_cats');
                    $sidebar_cats = empty($sidebar_cats) ? array() : $sidebar_cats;

                    if($value === '1' && !isset($sidebar_cats[$term->slug])) {
                        $sidebar_cats[$term->slug] = array(
                            'slug' => $term->slug,
                            'name' => $term->name
                        );
                    } else if($value === '0' && isset($sidebar_cats[$term->slug])) {
                        unset($sidebar_cats[$term->slug]);
                    }

                    update_option('nasa_sidebars_cats', $sidebar_cats);
                }
                
                /**
                 * Delete old sidebar
                 */
                $this->delete_sidebar_cats();
            }
        }
        
        /*
         * Check term and delete sidebar category not exist
         */
        protected function delete_sidebar_cats() {
            $sidebar_cats = get_option('nasa_sidebars_cats');
            
            if(!empty($sidebar_cats)) {
                foreach ($sidebar_cats as $sidebar) {
                    if(!term_exists($sidebar['slug'])) {
                        unset($sidebar_cats[$sidebar['slug']]);
                    }
                }
                
                update_option('nasa_sidebars_cats', $sidebar_cats);
            }
        }

    }

    /**
     * Instantiate Class
     */
    add_action('init', array('Nasa_WC_Term_Data_Fields', 'getInstance'), 0);
}