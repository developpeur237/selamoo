<?php
/*
 * Product group button
 */
// $combo_show_type = !isset($combo_show_type) || $combo_show_type == 'rowdown' ? 'rowdown' : 'popup';
$combo_show_type = 'popup';
?>
<div class="product-summary">
    <div class="product-interactions">
        <?php do_action('nasa_before_show_buttons_loop'); // before loop btn ?>
        <?php do_action('nasa_show_buttons_loop', $combo_show_type); // loop btn ?>
        <?php do_action('nasa_after_show_buttons_loop'); // after loop btn ?>
    </div>
</div>