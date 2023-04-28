<?php

if (class_exists('WooCommerce')) {

    class Digi_Nasa_Ext_WC_Query {

        private $_product;
        private $_woocommerce;

        public function __construct() {
            global $woocommerce;

            $this->_woocommerce = $woocommerce;
            $this->_product = $this->_woocommerce->query;
        }

        public function nasa_product_filter_variations() {
            if (version_compare($this->_woocommerce->version, '2.6.1', ">=")) {
                return;
            }

            if (is_active_widget(false, false, 'nasa_woocommerce_filter_variations', true) && !NASA_CORE_IN_ADMIN) {

                global $_chosen_attributes;

                if (!isset($_chosen_attributes)) {
                    $_chosen_attributes = array();
                }

                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if ($attribute_taxonomies) {
                    foreach ($attribute_taxonomies as $tax) {

                        $attribute = wc_sanitize_taxonomy_name($tax->attribute_name);
                        $taxonomy = wc_attribute_taxonomy_name($attribute);
                        $name = 'filter_' . $attribute;
                        $query_type_name = 'query_type_' . $attribute;

                        if (!empty($_REQUEST[$name]) && taxonomy_exists($taxonomy)) {

                            $_chosen_attributes[$taxonomy]['terms'] = explode(',', $_REQUEST[$name]);

                            $_chosen_attributes[$taxonomy]['query_type'] = (empty($_REQUEST[$query_type_name]) || !in_array(strtolower($_REQUEST[$query_type_name]), array('and', 'or'))) ?
                                apply_filters('woocommerce_layered_nav_default_query_type', 'and') :
                                strtolower($_REQUEST[$query_type_name]);
                        }
                    }
                }

                add_filter('loop_shop_post_in', array($this->_product, 'layered_nav_query'));
            }
        }

        public function nasa_product_filter_price() {
            if (is_active_widget(false, false, 'nasa_woocommerce_price_filter', true) && !NASA_CORE_IN_ADMIN) {
                if (version_compare($this->_woocommerce->version, '2.6.1', "<")) {
                    add_filter('loop_shop_post_in', array($this->_product, 'price_filter'));
                }
            }
        }

        /**
         * nasa_filter_by_variations
         *
         * @param array $_chosen_attributes
         * @param array $filtered_posts
         * @return array
         */
        public function nasa_filter_by_variations($_chosen_attributes, $filtered_posts = array()) {
            if (sizeof($_chosen_attributes) > 0) {

                $matched_products = array(
                    'and' => array(),
                    'or' => array()
                );
                $filtered_attribute = array(
                    'and' => false,
                    'or' => false
                );

                foreach ($_chosen_attributes as $attribute => $data) {
                    $matched_products_from_attribute = array();
                    $filtered = false;

                    if (sizeof($data['terms']) > 0) {
                        foreach ($data['terms'] as $value) {
                            $posts = get_posts(
                                array(
                                    'post_type' => 'product',
                                    'numberposts' => -1,
                                    'post_status' => 'publish',
                                    'fields' => 'ids',
                                    'no_found_rows' => true,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => $attribute,
                                            'terms' => $value,
                                            'field' => 'term_id'
                                        )
                                    )
                                )
                            );

                            if (!is_wp_error($posts)) {

                                $matched_products_from_attribute = (sizeof($matched_products_from_attribute) > 0 || $filtered) ?
                                    ($data['query_type'] == 'or' ? array_merge($posts, $matched_products_from_attribute) : array_intersect($posts, $matched_products_from_attribute)) : $posts;

                                $filtered = true;
                            }
                        }
                    }

                    if (sizeof($matched_products[$data['query_type']]) > 0 || $filtered_attribute[$data['query_type']] === true) {
                        $matched_products[$data['query_type']] = ( $data['query_type'] == 'or' ) ? array_merge($matched_products_from_attribute, $matched_products[$data['query_type']]) : array_intersect($matched_products_from_attribute, $matched_products[$data['query_type']]);
                    } else {
                        $matched_products[$data['query_type']] = $matched_products_from_attribute;
                    }

                    $filtered_attribute[$data['query_type']] = true;

                    $this->filtered_product_ids_for_taxonomy[$attribute] = $matched_products_from_attribute;
                }

                // Combine our AND and OR result sets
                $results = ($filtered_attribute['and'] && $filtered_attribute['or']) ?
                    array_intersect($matched_products['and'], $matched_products['or']) :
                    array_merge($matched_products['and'], $matched_products['or']);

                if ($filtered) {
                    $this->_product->layered_nav_post__in = $results;
                    $this->_product->layered_nav_post__in[] = 0;

                    $filtered_posts = (sizeof($filtered_posts) == 0) ? $results : array_intersect($filtered_posts, $results);
                    $filtered_posts[] = 0;
                }
            }

            return (array) $filtered_posts;
        }

        public function get_catalog_ordering_args($orderby = '', $order = '') {
            return $this->_product->get_catalog_ordering_args($orderby, $order);
        }

        public function price_filter($filtered_posts = array()) {
            if (version_compare($this->_woocommerce->version, '2.6.1', "<")) {
                return $this->_product->price_filter($filtered_posts);
            }

            return false;
        }

        public function get_Parent_Obj() {
            return $this->_product;
        }

        public function nasa_getPostSearch($s, $old = array()) {
            $posts = get_posts(
                array(
                    'post_type' => 'product',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'no_found_rows' => true,
                    's' => $s
                )
            );

            if (!is_wp_error($posts) && count($posts)) {
                foreach ($posts as $v) {
                    if (!in_array($v, $old)) {
                        $old[] = $v;
                    }
                }
            }

            return $old;
        }

    }

    $digi_wc_query = new Digi_Nasa_Ext_WC_Query();
}