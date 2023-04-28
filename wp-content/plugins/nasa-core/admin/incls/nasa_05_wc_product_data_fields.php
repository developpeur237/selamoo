<?php

defined('ABSPATH') or die(); // Exit if accessed directly

/**
 * @class 		Nasa_WC_Product_Data_Fields
 * @version		1.0
 * @author 		nasaTheme
 */
if (!class_exists('Nasa_WC_Product_Data_Fields')) {

    class Nasa_WC_Product_Data_Fields {

        protected static $_instance = null;
        public static $plugin_prefix = 'wc_productdata_options_';
        
        protected $_custom_fields = array();
        
        public static function getInstance() {
            if(!class_exists('WooCommerce')) {
                return null;
            }
            
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            
            return self::$_instance;
        }

        /**
         * Gets things started by adding an action to initialize this plugin once
         * WooCommerce is known to be active and initialized
         */
        public function __construct() {
            $custom_fields = array();
            
            /* ============= Additional ================================= */
            $custom_fields['key'][0] = array(
                'tab_name'    => esc_html__('Additional', 'nasa-core'),
                'tab_id'      => 'additional'
            );
            
            $custom_fields['value'][0][] = array(
                'id'          => '_bubble_hot',
                'type'        => 'text',
                'label'       => esc_html__('Custom Badge Title', 'nasa-core'),
                'placeholder' => esc_html__('HOT', 'nasa-core'),
                'class'       => 'large',
                'style'       => 'width: 100%;',
                'description' => esc_html__('Enter badge label (NEW, HOT etc...).', 'nasa-core')
            );
            
            $custom_fields['value'][0][] = array(
                'id'          => '_product_video_link',
                'type'        => 'text',
                'placeholder' => 'https://www.youtube.com/watch?v=link-test',
                'label'       => esc_html__('Product Video Link', 'nasa-core'),
                'style'       => 'width:100%;',
                'description' => esc_html__('Enter a Youtube or Vimeo Url of the product video here.', 'nasa-core')
            );

            $custom_fields['value'][0][] = array(
                'id'          => '_product_video_size',
                'type'        => 'text',
                'label'       => esc_html__('Product Video Size', 'nasa-core'),
                'placeholder' => esc_html__('800x800', 'nasa-core'),
                'class'       => 'large',
                'style'       => 'width:100%;',
                'description' => esc_html__('Default is 800x800. (Width X Height)', 'nasa-core')
            );

            /* ============= Specifications ================================= */
            $custom_fields['key'][1] = array(
                'tab_name'    => esc_html__('Specifications', 'nasa-core'),
                'tab_id'      => 'specifications'
            );

            $custom_fields['value'][1][] = array(
                'id'          => 'nasa_specifications',
                'type'        => 'editor',
                'label'       => esc_html__('Technical Specifications', 'nasa-core'),
                'description' => esc_html__('Technical Specifications', 'nasa-core')
            );
            
            $this->_custom_fields = $custom_fields;
            
            add_action('woocommerce_init', array(&$this, 'init'));
        }
        
        /**
         * Init WooCommerce Custom Product Data Fields extension once we know WooCommerce is active
         */
        public function init() {
            add_action('woocommerce_product_write_panel_tabs', array($this, 'product_write_panel_tab'));
            add_action('woocommerce_product_data_panels', array($this, 'product_write_panel'));
            add_action('woocommerce_process_product_meta', array($this, 'product_save_data'), 10, 2);
            
            /**
             * Bought together
             */
            add_action('woocommerce_product_options_related', array($this, 'nasa_accessories_product'));
        }

        /**
         * Adds a new tab to the Product Data postbox in the admin product interface
         */
        public function product_write_panel_tab() {
            $fields = $this->_custom_fields;
            foreach ($fields['key'] as $field) {
                echo '<li class="wc_productdata_options_tab"><a href="#wc_tab_' . $field['tab_id'] . '">' . $field['tab_name'] . '</a></li>';
            }
        }

        /**
         * Adds the panel to the Product Data postbox in the product interface
         */
        public function product_write_panel() {
            global $post;
            // Pull the field data out of the database
            $available_fields = array();
            $available_fields[] = maybe_unserialize(get_post_meta($post->ID, 'wc_productdata_options', true));
            if ($available_fields) {
                $fields = $this->_custom_fields;
                // Display fields panel
                foreach ($available_fields as $available_field) {
                    foreach ($fields['value'] as $key => $values) {
                        echo '<div id="wc_tab_' . $fields['key'][$key]['tab_id'] . '" class="panel woocommerce_options_panel">';
                        foreach ($values as $v) {
                            $this->wc_product_data_options_fields($v);
                        }
                        echo '</div>';
                    }
                }
            }
        }

        /**
         * Create Fields
         */
        public function wc_product_data_options_fields($field) {
            global $thepostid, $post;

            $fieldtype = isset($field['type']) ? $field['type'] : '';
            $field_id = isset($field['id']) ? $field['id'] : '';
            $thepostid = empty($thepostid) ? $post->ID : $thepostid;
            $options_data = maybe_unserialize(get_post_meta($thepostid, 'wc_productdata_options', true));

            switch ($fieldtype) {
                case 'number':
                    $field['placeholder'] = isset($field['placeholder']) ? $field['placeholder'] : '';
                    $field['class'] = isset($field['class']) ? $field['class'] : 'short';
                    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
                    $field['value'] = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);
                    $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
                    $field['type'] = isset($field['type']) ? $field['type'] : 'text';

                    $inputval = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';

                    echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($inputval) . '" placeholder="' . esc_attr($field['placeholder']) . '"' . (isset($field['style']) ? ' style="' . $field['style'] . '"' : '') . ' /> ';

                    if (!empty($field['description'])) {

                        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
                        } else {
                            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
                        }
                    }
                    echo '</p>';
                    break;

                case 'textarea':
                    if (!isset($field['placeholder'])){
                        $field['placeholder'] = '';
                    }
                    if (!isset($field['class'])){
                        $field['class'] = 'short';
                    }
                    if (!isset($field['value'])){
                        $field['value'] = get_post_meta($thepostid, $field['id'], true);
                    }
                    
                    $inputval = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';

                    echo '<p class="form-field ' . $field['id'] . '_field"><label for="' . $field['id'] . '">' . $field['label'] . '</label><textarea class="' . $field['class'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '" rows="2" cols="20"' . (isset($field['style']) ? ' style="' . $field['style'] . '"' : '') . '">' . esc_textarea($inputval) . '</textarea>';

                    if (!empty($field['description'])) {
                        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
                        } else {
                            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
                        }
                    }
                    echo '</p>';
                    break;

                case 'editor' :
                    $inputval   = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';
                    $height     = isset($field['height']) && (int) $field['height'] ? (int) $field['height'] : 200;
                    wp_editor($inputval, $field['id'], array('editor_height' => $height));
                    break;

                case 'checkbox':
                    $field['class']         = isset($field['class']) ? $field['class'] : 'checkbox';
                    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
                    $field['value']         = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';
                    $field['cbvalue']       = isset($field['cbvalue']) ? $field['cbvalue'] : '"yes"';
                    $field['name']          = isset($field['name']) ? $field['name'] : $field['id'];

                    echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><input type="checkbox" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . ' /> ';

                    if (!empty($field['description'])) {

                        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
                        } else {
                            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
                        }
                    }
                    echo '</p>';
                    break;

                case 'select':
                    $field['class'] = isset($field['class']) ? $field['class'] : 'select short';
                    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
                    $field['value'] = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';

                    echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" class="' . esc_attr($field['class']) . '">';

                    foreach ($field['options'] as $key => $value) {
                        echo '<option value="' . esc_attr($key) . '" ' . selected(esc_attr($field['value']), esc_attr($key), false) . '>' . esc_html($value) . '</option>';
                    }

                    echo '</select> ';

                    if (!empty($field['description'])) {
                        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
                        } else {
                            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
                        }
                    }
                    echo '</p>';
                    break;

                case 'radio':
                    $field['class']         = isset($field['class']) ? $field['class'] : 'select short';
                    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
                    $field['value']         = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';
                    $field['name']          = isset($field['name']) ? $field['name'] : $field['id'];

                    echo '<fieldset class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><legend style="float:left; width:150px;">' . wp_kses_post($field['label']) . '</legend><ul class="wc-radios" style="width: 25%; float:left;">';
                    foreach ($field['options'] as $key => $value) {
                        echo '<li style="padding-bottom: 3px; margin-bottom: 0;"><label style="float:none; width: auto; margin-left: 0;"><input name="' . esc_attr($field['name']) . '" value="' . esc_attr($key) . '" type="radio" class="' . esc_attr($field['class']) . '" ' . checked(esc_attr($field['value']), esc_attr($key), false) . ' /> ' . esc_html($value) . '</label></li>';
                    }
                    echo '</ul>';

                    if (!empty($field['description'])) {
                        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
                        } else {
                            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
                        }
                    }

                    echo '</fieldset>';
                    break;

                case 'hidden':
                    $field['value'] = isset($field['value']) ? $field['value'] : $options_data[0][$field_id];
                    $field['class'] = isset($field['class']) ? $field['class'] : '';

                    echo '<input type="hidden" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['id']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" /> ';

                    break;

                case 'text':
                default :
                    $field['placeholder']   = isset($field['placeholder']) ? $field['placeholder'] : '';
                    $field['class']         = isset($field['class']) ? $field['class'] : 'short';
                    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
                    $field['value']         = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);
                    $field['name']          = isset($field['name']) ? $field['name'] : $field['id'];
                    $field['type']          = isset($field['type']) ? $field['type'] : 'text';

                    $inputval = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';

                    echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($inputval) . '" placeholder="' . esc_attr($field['placeholder']) . '"' . (isset($field['style']) ? ' style="' . $field['style'] . '"' : '') . ' /> ';

                    if (!empty($field['description'])) {
                        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
                            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
                        } else {
                            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
                        }
                    }
                    echo '</p>';
                    break;
            }
        }

        /**
         * Saves the data inputed into the product boxes, as post meta data
         * identified by the name 'wc_productdata_options'
         *
         * @param int $post_id the post (product) identifier
         * @param stdClass $post the post (product)
         */
        public function product_save_data($post_id, $post) {
            /** field name in pairs array * */
            $data_args = array();
            $fields = $this->_custom_fields;

            foreach ($fields['value'] as $key => $datas) {
                foreach ($datas as $k => $data) {
                    if (isset($data['id'])) {
                        $data_args[$data['id']] = stripslashes($_POST[$data['id']]);
                    }
                }
            }

            $options_value = array($data_args);

            // save the data to the database
            update_post_meta($post_id, 'wc_productdata_options', $options_value);
            
            /**
             * Accessories for product
             */
            if (isset($_POST['accessories_ids'])) {
                update_post_meta($post_id, '_accessories_ids', $_POST['accessories_ids']);
            } else {
                update_post_meta($post_id, '_accessories_ids', null);
            }
            
            /**
             * Delete cache by post id
             */
            nasa_del_cache_by_product_id($post_id);
        }
        
        /**
         * HTML Bought together of Product
         */
        public function nasa_accessories_product() {
            global $post, $thepostid, $product_object;
            $product_ids = $this->get_accessories_ids($thepostid);
            include NASA_CORE_PLUGIN_PATH . 'admin/views/html-accessories-product.php';
        }
        
        /**
         * Bought together Post ids
         * 
         * @param type $post_id
         * @return type
         */
        protected function get_accessories_ids($post_id) {
            $ids = get_post_meta($post_id, '_accessories_ids', true);
            
            return $ids;
        }

    }
    
    /**
     * Instantiate Class
     */
    add_action('init', array('Nasa_WC_Product_Data_Fields', 'getInstance'), 0);
}