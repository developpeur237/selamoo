<?php
if (class_exists('YITH_WCWL')) {
    class Digi_YITH_WCWL extends YITH_WCWL {

        /**
         * Constructor.
         */
        public function __construct($details) {
            // set details for actions
            $this->details = $details;

            // init main plugin classes
            add_action('wp_ajax_nasa_add_to_wishlist', array($this, 'add_to_wishlist_ajax'));
            add_action('wp_ajax_nopriv_nasa_add_to_wishlist', array($this, 'add_to_wishlist_ajax'));
        }

    }

}