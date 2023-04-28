<?php 
$topbar_left = (!isset($nasa_opt['topbar_left_show']) || $nasa_opt['topbar_left_show'] == 1) ? ((isset($nasa_opt['topbar_left']) && $nasa_opt['topbar_left'] != '') ? do_shortcode($nasa_opt['topbar_left']) : '<i style="font-size: 130%; vertical-align: text-top; margin-right: 8px;" class="icon pe-7s-smile primary-color"></i>' . esc_html__('ADD SOMETHING HERE...', 'digi-theme')) : '';
?>
<div id="top-bar" class="top-bar top-bar-type-2">
    <div class="row">
        <div class="large-12 columns">
            <div class="left-text left">
                <div class="inner-block">
                    <?php echo $topbar_left; ?>
                </div>
            </div>
            <div class="right-text right">
                <div class="topbar-menu-container">
                    <?php digi_get_menu('topbar-menu', 'nasa-topbar-menu', 1); ?>
                    <?php echo digi_language_flages(); ?>
                    <?php echo digi_tiny_account(true); ?>
                </div>
            </div>
        </div>
    </div>
</div>