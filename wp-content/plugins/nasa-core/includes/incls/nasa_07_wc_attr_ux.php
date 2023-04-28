<?php
/**
 * Class Nasa Woocommerce attributies UX
 */
class Nasa_WC_Attr_UX extends Nasa_Abstract_WC_Attr_UX {

    protected static $instance = null;
    
    protected $_max_show = 0;
    protected $_live_time = 1800;

    /**
     * Instance
     */
    public static function getInstance() {
        global $nasa_opt;
        if(isset($nasa_opt['enable_nasa_variations_ux']) && !$nasa_opt['enable_nasa_variations_ux']) {
            return null;
        }

        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Class constructor.
     */
    public function __construct() {
        global $nasa_opt;
        
        parent::__construct();
        
        $this->_max_show = (!isset($nasa_opt['limit_nasa_variations_ux']) || !$nasa_opt['limit_nasa_variations_ux']) ? 5 : (int) $nasa_opt['limit_nasa_variations_ux'];
        
        if (isset($nasa_opt['nasa_cache_expire']) && (int) $nasa_opt['nasa_cache_expire']) {
            $this->_live_time = (int) $nasa_opt['nasa_cache_expire'];
        }
        
        add_filter('woocommerce_dropdown_variation_attribute_options_html', array($this, 'get_nasa_attr_ux_html'), 100, 2);
        add_filter('nasa_attr_ux_html', array($this, 'nasa_attr_ux_html'), 5, 4);
        add_action('nasa_static_content', array($this, 'nasa_static_enable_attr_ux'), 99);

        add_filter('woocommerce_available_variation', array($this, 'nasa_image_size_catalog'));
        
        if(!isset($nasa_opt['load_variations_ux_ajax']) || $nasa_opt['load_variations_ux_ajax']) {
            add_action('woocommerce_before_shop_loop_item', array($this, 'add_product_type'), 999);
            add_action('wp_ajax_nasa_render_variables', array($this, 'render_variables'));
            add_action('wp_ajax_nopriv_nasa_render_variables', array($this, 'render_variables'));
        } else {
            add_action('woocommerce_before_shop_loop_item', array($this, 'product_content_variations_color_label'), 99);
        }
    }
    
    /**
     * Add to static position
     */
    public function nasa_static_enable_attr_ux() {
        echo '<input type="hidden" name="nasa_attr_ux" value="1" />';
        echo '<input type="hidden" name="add_to_cart_text" value="' . esc_html__('Add to cart', 'nasa-core') . '" />';
        echo '<input type="hidden" name="nasa_no_matching_variations" value="' . esc_html__('Sorry, no products matched your selection. Please choose a different combination.', 'nasa-core') . '" />';
    }
    
    /**
     * Image size
     */
    public function nasa_image_size_catalog($variation) {
        $image = wp_get_attachment_image_src($variation['image_id'], 'shop_catalog');
        $variation['image_catalog'] = isset($image[0]) ? $image[0] : '';
        return $variation;
    }
    
    /**
     * Add product type
     */
    public function add_product_type() {
        global $product;
        $product_type = $product->get_type();

        if($product_type != 'variable') {
            return;
        }
        
        $product_id = $product->get_id();
        $content = $this->get_cache_content($product_id);
        
        echo $content ? $content : '<div class="nasa-product-variable-call-ajax no-process nasa-product-variable-' . esc_attr($product_id) . ' hidden-tag" data-product_id="' . esc_attr($product_id) . '"></div>';
    }
    
    /**
     * render variable string to product
     */
    public function render_variables() {
        if(!isset($_POST['pids']) || empty($_POST['pids'])) {
            exit(json_encode(array('empty' => '1')));
        }
        $products = array();
        
        foreach ($_POST['pids'] as $pid) {
            $product = wc_get_product($pid);
            
            if($product->get_type() == 'variable') {
                $GLOBALS['product'] = $product;
                
                $products[$pid] = $this->product_content_variations_color_label(true);
            }
        }
        
        exit(json_encode(array('empty' => '0', 'products' => $products)));
    }
    
    /**
     * 
     * @global type $product
     * @global type $nasa_termmeta
     * @return void
     */
    public function product_content_variations_color_label($return = false) {
        global $nasa_opt, $product;
        
        $nasa_colors = self::get_tax_color();
        $nasa_labels = self::get_tax_labels();
        $nasa_images = self::get_tax_images();
        $nasa_selects = (isset($nasa_opt['enable_nasa_ux_select']) && !$nasa_opt['enable_nasa_ux_select']) ?
            null : self::get_tax_selects();
        $product_type = $product->get_type();
        
        if(
            $product_type != 'variable' || 
            (empty($nasa_colors) && empty($nasa_labels) && empty($nasa_images) && empty($nasa_selects))
        ) {
            return;
        }
        
        $productId = (int) $product->get_id();
        $content = $this->get_cache_content($productId);
        if(!$content) {
            global $nasa_termmeta;
            
            $content = '';
        
            $available_variations = $product->get_available_variations();
            if(empty($available_variations) && false !== $available_variations) {
                return;
            }

            $attributes = $product->get_variation_attributes();

            $outputs = array(
                self::_NASA_COLOR => array(),
                self::_NASA_LABEL => array(),
                self::_NASA_IMAGE => array(),
                self::_NASA_SELECT => array()
            );

            if(!isset($nasa_termmeta)) {
                $nasa_termmeta = array();
            }

            $productId = (int) $product->get_id();
            if(!isset($nasa_termmeta[$productId])) {
                $nasa_termmeta[$productId] = $outputs;
            }

            foreach ($attributes as $attribute_name => $options) {
                $attr_name = str_replace('pa_', '', $attribute_name);
                $default = $product->get_variation_default_attribute($attribute_name);

                /**
                 * Init select variations
                 */
                if($nasa_selects && in_array($attr_name, $nasa_selects)) {
                    foreach ($options as $option) {
                        if(!isset($nasa_termmeta[$productId][self::_NASA_SELECT][$attr_name][$option])) {
                            if($term = get_term_by('slug', $option, 'pa_' . $attr_name)) {
                                $active = $term->slug == $default ? true : false;
                                $term_meta = get_term_meta($term->term_id, self::_NASA_SELECT, true);
                                $term_meta = $term_meta ? $term_meta : $term->name;
                                $nasa_termmeta[$productId][self::_NASA_SELECT][$attr_name][$option] = $outputs[self::_NASA_SELECT][$attr_name][$option] = array('name' => $term->name, 'value' => $term_meta, 'active' => $active);
                            }
                        } else {
                            $outputs[self::_NASA_SELECT][$attr_name][$option] = $nasa_termmeta[$productId][self::_NASA_SELECT][$attr_name][$option];
                        }
                    }
                }

                /**
                 * Init colors variations
                 */
                if($nasa_colors && in_array($attr_name, $nasa_colors)) {
                    $k = 1;
                    foreach ($options as $option) {
                        if(!isset($nasa_termmeta[$productId][self::_NASA_COLOR][$attr_name][$option])) {
                            if($term = get_term_by('slug', $option, 'pa_' . $attr_name)) {
                                $active = $term->slug == $default ? true : false;
                                $term_meta = get_term_meta($term->term_id, self::_NASA_COLOR, true);
                                $term_meta = $term_meta ? $term_meta : $term->name;
                                $nasa_termmeta[$productId][self::_NASA_COLOR][$attr_name][$option] = $outputs[self::_NASA_COLOR][$attr_name][$option] = array('name' => $term->name, 'value' => $term_meta, 'active' => $active);

                                if($this->_max_show && $k >= $this->_max_show) {
                                    break;
                                }

                                $k++;
                            }
                        } else {
                            $outputs[self::_NASA_COLOR][$attr_name][$option] = $nasa_termmeta[$productId][self::_NASA_COLOR][$attr_name][$option];
                        }
                    }
                }

                /**
                 * Init labels variations
                 */
                if($nasa_labels && in_array($attr_name, $nasa_labels)) {
                    $k = 1;
                    foreach ($options as $option) {
                        if(!isset($nasa_termmeta[$productId][self::_NASA_LABEL][$attr_name][$option])) {
                            if($term = get_term_by('slug', $option, 'pa_' . $attr_name)) {
                                $active = $term->slug == $default ? true : false;
                                $term_meta = get_term_meta($term->term_id, self::_NASA_LABEL, true);
                                $term_meta = $term_meta ? $term_meta : $term->name;
                                $nasa_termmeta[$productId][self::_NASA_LABEL][$attr_name][$option] = $outputs[self::_NASA_LABEL][$attr_name][$option] = array('name' => $term->name, 'value' => $term_meta, 'active' => $active);

                                if($this->_max_show && $k >= $this->_max_show) {
                                    break;
                                }

                                $k++;
                            }
                        } else {
                            $outputs[self::_NASA_LABEL][$attr_name][$option] = $nasa_termmeta[$productId][self::_NASA_LABEL][$attr_name][$option];
                        }
                    }
                }

                /**
                 * Init images variations
                 */
                if($nasa_images && in_array($attr_name, $nasa_images)) {
                    $k = 1;
                    foreach ($options as $option) {
                        if(!isset($nasa_termmeta[$productId][self::_NASA_IMAGE][$attr_name][$option])) {
                            if($term = get_term_by('slug', $option, 'pa_' . $attr_name)) {
                                $active = $term->slug == $default ? true : false;
                                $term_meta = get_term_meta($term->term_id, self::_NASA_IMAGE, true);
                                $nasa_termmeta[$productId][self::_NASA_IMAGE][$attr_name][$option] = $outputs[self::_NASA_IMAGE][$attr_name][$option] = array(
                                    'name' => $term->name,
                                    'value' => $term_meta,
                                    'active' => $active
                                );

                                if($this->_max_show && $k >= $this->_max_show) {
                                    break;
                                }

                                $k++;
                            }
                        } else {
                            $outputs[self::_NASA_IMAGE][$attr_name][$option] = $nasa_termmeta[$productId][self::_NASA_IMAGE][$attr_name][$option];
                        }
                    }
                }
            }

            $GLOBALS['nasa_termmeta'] = $nasa_termmeta;

            /**
             * Open Wrap variations
             */
            $content .= '<div class="nasa-product-content-variable-warp" data-product_id="' . $productId . '" data-product_variations="' . htmlspecialchars(wp_json_encode($available_variations)) . '">';

            /**
             * Colors variations
             */
            if(!empty($outputs[self::_NASA_COLOR])) {
                $content .= '<div class="nasa-product-content-' . self::_NASA_COLOR . '-wrap">';

                $k = 1;
                foreach ($outputs[self::_NASA_COLOR] as $attr_name => $objs) {
                    $terms = wc_get_product_terms($productId, 'pa_' . $attr_name, array('fields' => 'all'));
                    $array_keys = array_keys($objs);
                    $content .= '<div class="nasa-product-content-child nasa-product-content-' . $attr_name . '-wrap-child">';
                    foreach ($terms as $term) {
                        if (in_array($term->slug, $array_keys)) {
                            $content .= sprintf(
                                '<a href="javascript:void(0);" class="nasa-attr-ux-item nasa-attr-ux-' . self::_NASA_COLOR . ' nasa-attr-ux-%s %s" data-value="%s" data-pa="%s" data-act="%s"><span style="background-color:%s;"></span><span class="nasa-attr-text">%s</span></a>',
                                esc_attr($term->slug),
                                $objs[$term->slug]['active'] ? 'nasa-active' : '',
                                esc_attr($term->slug),
                                sanitize_title($attr_name),
                                $objs[$term->slug]['active'] ? '1' : '0',
                                esc_attr($objs[$term->slug]['value']),
                                $objs[$term->slug]['name']
                            );

                            if($this->_max_show && $k >= $this->_max_show) {
                                break;
                            }
                            $k++;
                        }
                    }
                    $content .= '</div>';
                }
                $content .= '</div>';
            }

            /**
             * Images variations
             */
            if(!empty($outputs[self::_NASA_IMAGE])) {
                $content .= '<div class="nasa-product-content-' . self::_NASA_IMAGE . '-wrap">';

                $k = 1;
                foreach ($outputs[self::_NASA_IMAGE] as $attr_name => $objs) {
                    $terms = wc_get_product_terms($productId, 'pa_' . $attr_name, array('fields' => 'all'));
                    $array_keys = array_keys($objs);
                    $content .= '<div class="nasa-product-content-child nasa-product-content-' . $attr_name . '-wrap-child">';
                    foreach ($terms as $term) {
                        if (in_array($term->slug, $array_keys)) {
                            $image = $this->get_image_preview($objs[$term->slug]['value'], false, 15, 15);
                            $content .= sprintf(
                                '<a href="javascript:void(0);" class="nasa-attr-ux-item nasa-attr-ux-' . self::_NASA_IMAGE . ' nasa-attr-ux-%s %s" data-value="%s" data-pa="%s" data-act="%s"><span class="img-attr-wrap">%s</span></a>',
                                esc_attr($term->slug),
                                $objs[$term->slug]['active'] ? 'nasa-active' : '',
                                esc_attr($term->slug),
                                sanitize_title($attr_name),
                                $objs[$term->slug]['active'] ? '1' : '0',
                                $image
                            );

                            if($this->_max_show && $k >= $this->_max_show) {
                                break;
                            }
                            $k++;
                        }
                    }
                    $content .= '</div>';
                }
                $content .= '</div>';
            }

            /**
             * Labels variations
             */
            if(!empty($outputs[self::_NASA_LABEL])) {
                $content .= '<div class="nasa-product-content-' . self::_NASA_LABEL . '-wrap">';

                $k = 1;
                foreach ($outputs[self::_NASA_LABEL] as $attr_name => $objs) {
                    $terms = wc_get_product_terms($productId, 'pa_' . $attr_name, array('fields' => 'all'));
                    $array_keys = array_keys($objs);
                    $content .= '<div class="nasa-product-content-child nasa-product-content-' . $attr_name . '-wrap-child">';
                    foreach ($terms as $term) {
                        if (in_array($term->slug, $array_keys)) {
                            $content .= sprintf(
                                '<a href="javascript:void(0);" class="nasa-attr-ux-item nasa-attr-ux-' . self::_NASA_LABEL . ' nasa-attr-ux-%s %s" data-value="%s" data-pa="%s" data-act="%s">%s</a>',
                                esc_attr($term->slug),
                                $objs[$term->slug]['active'] ? 'nasa-active' : '',
                                esc_attr($term->slug),
                                sanitize_title($attr_name),
                                $objs[$term->slug]['active'] ? '1' : '0',
                                $objs[$term->slug]['value']
                            );

                            if($this->_max_show && $k >= $this->_max_show) {
                                break;
                            }
                            $k++;
                        }
                    }
                    
                    $content .= '</div>';
                }
                
                $content .= '</div>';
            }

            /**
             * Selects variations
             */
            if(!empty($outputs[self::_NASA_SELECT])) {
                $content .= '<div class="nasa-product-content-' . self::_NASA_SELECT . '-wrap">';

                $k = 0;
                foreach ($outputs[self::_NASA_SELECT] as $attr_name => $objs) {
                    $label = wc_attribute_label('pa_' . $attr_name);
                    $terms = wc_get_product_terms($productId, 'pa_' . $attr_name, array('fields' => 'all'));
                    $array_keys = array_keys($objs);
                    $content .= '<div class="nasa-product-content-child nasa-product-content-' . $attr_name . '-wrap-child">';
                    $class_toggle = $k == 0 ? ' nasa-show' : '';
                    $k++;
                    $content .= '<a class="nasa-toggle-attr-select' . $class_toggle . '" href="javascript:void(0);">' . $label . '<i class="pe-7s-angle-down"></i></a>';
                    
                    $content .= '<div class="nasa-toggle-content-attr-select' . $class_toggle . '">';

                    foreach ($terms as $term) {
                        if (in_array($term->slug, $array_keys)) {
                            $content .= sprintf(
                                '<a href="javascript:void(0);" class="nasa-attr-ux-item nasa-attr-ux-' . self::_NASA_SELECT . ' nasa-attr-ux-%s %s" data-value="%s" data-pa="%s" data-act="%s">%s</a>',
                                esc_attr($term->slug),
                                $objs[$term->slug]['active'] ? 'nasa-active' : '',
                                esc_attr($term->slug),
                                sanitize_title($attr_name),
                                $objs[$term->slug]['active'] ? '1' : '0',
                                $objs[$term->slug]['value']
                            );
                        }
                    }

                    $content .= '</div></div>';
                }
                
                $content .= '</div>';
            }
            
            /**
             * Close Wrap variations
             */
            $content .= '</div>';
            
            /**
             * Cache file
             */
            $this->set_cache_content($productId, $content);
        }
        
        if($return) {
            return $content;
        }
        
        echo $content;
    }
    
    /**
     * Set cache file for variation of Product
     */
    protected function set_cache_content($productId, $content) {
        return Nasa_Caching::set_content($productId, $content, 'products');
    }
    
    /**
     * Get cache variation of Product
     */
    protected function get_cache_content($productId) {
        return Nasa_Caching::get_content($productId, 'products');
    }

    /**
     * Filter function to add swatches bellow the default selector
     *
     * @param $html
     * @param $args
     *
     * @return string
     */
    public function get_nasa_attr_ux_html($html, $args) {
        $attr = self::get_tax_attribute($args['attribute']);

        // Return if this is normal attribute
        if (empty($attr)) {
            return $html;
        }

        if (!array_key_exists($attr->attribute_type, $this->types)) {
            return $html;
        }

        $options = $args['options'];
        $product = $args['product'];
        $attribute = $args['attribute'];
        $class = 'variation-selector variation-select-' . $attr->attribute_type;
        $nasa_attr_ux = '';

        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        if (array_key_exists($attr->attribute_type, $this->types)) {
            if (!empty($options) && $product && taxonomy_exists($attribute)) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all', 'orderby' => 'parent'));

                foreach ($terms as $term) {
                    if (in_array($term->slug, $options)) {
                        $nasa_attr_ux .= apply_filters('nasa_attr_ux_html', '', $term, $attr, $args);
                    }
                }
            }

            if (!empty($nasa_attr_ux)) {
                $class .= ' hidden-tag';

                $nasa_attr_ux = '<div class="nasa-attr-ux_wrap" data-attribute_name="attribute_' . esc_attr($attribute) . '">' . $nasa_attr_ux . '</div>';
                $html = '<div class="' . esc_attr($class) . '">' . $html . '</div>' . $nasa_attr_ux;
            }
        }

        return $html;
    }

    /**
     * Print HTML of a single swatch
     *
     * @param $html
     * @param $term
     * @param $attr
     * @param $args
     *
     * @return string
     */
    public function nasa_attr_ux_html($html, $term, $attr, $args) {
        $selected = sanitize_title($args['selected']) == $term->slug ? ' selected' : '';
        $name = esc_html(apply_filters('woocommerce_variation_option_name', $term->name));
        $term_meta = get_term_meta($term->term_id, $attr->attribute_type, true);
        switch ($attr->attribute_type) {
            case self::_NASA_COLOR:
                $html = sprintf(
                    '<a href="javascript:void(0);" class="nasa-attr-ux nasa-attr-ux-color nasa-attr-ux-%s' . $selected . '" style="background-color:%s;" title="%s" data-value="%s">%s</a>',
                    esc_attr($term->slug),
                    esc_attr($term_meta),
                    esc_attr($name),
                    esc_attr($term->slug),
                    $name
                );
                break;

            case self::_NASA_LABEL:
                $label = $term_meta ? $term_meta : $name;
                $html = sprintf(
                    '<a href="javascript:void(0);" class="nasa-attr-ux nasa-attr-ux-label nasa-attr-ux-%s' . $selected . '" title="%s" data-value="%s">%s</a>',
                    esc_attr($term->slug),
                    esc_attr($name),
                    esc_attr($term->slug),
                    esc_html($label)
                );
                break;
            
            case self::_NASA_IMAGE:
                $image = $this->get_image_preview($term_meta, false, 30, 30);
                $html = sprintf(
                    '<a href="javascript:void(0);" class="nasa-attr-ux nasa-attr-ux-image nasa-attr-ux-%s' . $selected . '" title="%s" data-value="%s"><span class="nasa-attr-bg-img">%s</span><span class="nasa-attr-text">%s</span></a>',
                    esc_attr($term->slug),
                    esc_attr($name),
                    esc_attr($term->slug),
                    $image,
                    $name
                );
                break;
        }

        return $html;
    }

}

/**
 * Instantiate Class
 */
add_action('init', array('Nasa_WC_Attr_UX', 'getInstance'));
