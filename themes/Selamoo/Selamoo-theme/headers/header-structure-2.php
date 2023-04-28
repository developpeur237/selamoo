<div class="header-wrapper header-type-2<?php echo esc_attr($header_classes); ?>">
    <?php //!-- Top bar --
    do_action('before');
    $topbar_file = DIGI_CHILD_PATH . '/headers/top-bar-2.php';
    include is_file($topbar_file) ? $topbar_file : DIGI_THEME_PATH . '/headers/top-bar-2.php';
    //!-- End Top bar --
    
    //!-- Masthead --?>
    <div class="sticky-wrapper">
        <header id="masthead" class="site-header">
            <div class="row">
                <div class="large-12 columns header-container">
                    <!-- Mobile Menu -->
                    <div class="mobile-menu">
                        <?php digi_mobile_header(); ?>
                    </div>
                    <div class="row nasa-hide-for-mobile">
                        <div class="large-9 columns">
                            <div class="row">
                                <!-- Logo -->
                                <div class="logo-wrapper large-3 columns">
                                    <?php echo digi_logo(); ?>
                                </div>
                                <div class="search-tatal-wrapper large-9 columns"><?php digi_search('full'); ?></div>
                            </div>
                        </div>
                        <div class="large-3 columns">
                            <?php echo digi_header_icons(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nasa-mgr-top-20 nasa-hide-for-mobile">
                <div class="large-12">
                    <!-- Main navigation - Full width style -->
                    <div class="wide-nav nasa-bg-wrap<?php echo esc_attr($menu_warp_class); ?>">
                        <div class="row">
                            <div class="large-12 columns nasa-menus-wrapper-reponsive" data-padding_y="<?php echo (int) $data_padding_y; ?>" data-padding_x="<?php echo (int) $data_padding_x; ?>">
                                <div id="nasa-menu-vertical-header">
                                    <?php digi_get_vertical_menu(); ?>
                                </div>
                                <?php digi_get_main_menu(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
</div>