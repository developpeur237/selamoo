<?php

add_shortcode("nasa_search", "nasa_sc_search");
function nasa_sc_search() {
    return get_product_search_form();
}
