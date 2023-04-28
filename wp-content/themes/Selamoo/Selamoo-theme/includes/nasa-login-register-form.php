<?php
/* 
 * Login form.
 */

$loginAjax = (bool) $prefix;
$prefix = $prefix ? 'nasa_' : '';

$nasa_keyUserName = $prefix . 'username';
$nasa_keyPass = $prefix . 'password';
$nasa_keyEmail = $prefix . 'email';
$nasa_keyLogin = $prefix . 'login';
$nasa_keyRememberme = $prefix . 'rememberme';

$nasa_keyRegUsername = $prefix . 'reg_username';
$nasa_keyRegEmail = $prefix . 'reg_email';
$nasa_keyRegPass = $prefix . 'reg_password';
// $nasa_keyRegEmail2 = $prefix . 'email_2';
$nasa_keyReg = $prefix . 'register';

$nasa_register = get_option('woocommerce_enable_myaccount_registration') == 'no' ? true : false;
?>

<div class="row" id="<?php echo esc_attr($prefix); ?>customer_login">
    <div class="large-12 columns <?php echo esc_attr($prefix); ?>login-form">
        <h2 class="nasa-form-title"><?php esc_html_e('Great to have you back !', 'digi-theme'); ?></h2>
        <form method="post" class="login">
            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="form-row form-row-wide">
                <span>
                    <label for="<?php echo esc_attr($nasa_keyUserName); ?>" class="inline-block left">
                        <?php esc_html_e('Username or email', 'digi-theme'); ?> <span class="required">*</span>
                    </label>

                    <label for="<?php echo esc_attr($nasa_keyRememberme); ?>" class="inline-block right">
                        <input name="<?php echo esc_attr($nasa_keyRememberme); ?>" type="checkbox" id="<?php echo esc_attr($nasa_keyRememberme); ?>" value="forever" /> <?php esc_html_e('Remember', 'digi-theme'); ?>
                    </label>
                </span>
                <input type="text" class="input-text" name="<?php echo esc_attr($nasa_keyUserName); ?>" id="<?php echo esc_attr($nasa_keyUserName); ?>" />
            </p>
            <p class="form-row form-row-wide">
                <span>
                    <label for="<?php echo esc_attr($nasa_keyPass); ?>" class="inline-block left">
                        <?php esc_html_e('Password', 'digi-theme'); ?> <span class="required">*</span>
                    </label>
                    <a class="lost_password inline-block right" href="<?php echo esc_url(wc_lostpassword_url()); ?>"><?php esc_html_e('Lost?', 'digi-theme'); ?></a>
                </span>
                
                <input class="input-text" type="password" name="<?php echo esc_attr($nasa_keyPass); ?>" id="<?php echo esc_attr($nasa_keyPass); ?>" />
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <p class="form-row row-submit">
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <input type="submit" class="button" name="<?php echo esc_attr($nasa_keyLogin); ?>" value="<?php esc_html_e('Sign in to your account', 'digi-theme'); ?>" />
            </p>

            <?php do_action('woocommerce_login_form_end'); ?>
        </form>
        
        <?php if ($nasa_register) : ?>
            <p class="nasa-switch-form">
                <?php esc_html_e('New here? ', 'digi-theme'); ?>
                <a class="nasa-switch-register" href="javascript:void(0);">
                    <?php esc_html_e('Create an account', 'digi-theme'); ?>
                </a>
            </p>
        <?php endif; ?>
    </div>

    <?php if ($nasa_register) : ?>
        <div class="large-12 columns <?php echo esc_attr($prefix); ?>register-form">

            <h2 class="nasa-form-title"><?php esc_html_e('Great to see you here !', 'digi-theme'); ?></h2>
            <form method="post" class="register">
                
                <?php do_action('woocommerce_register_form_start'); ?>
                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                    <p class="form-row form-row-wide">
                        <label for="<?php echo esc_attr($nasa_keyRegUsername); ?>">
                            <?php esc_html_e('Username', 'digi-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" class="input-text" name="<?php echo esc_attr($nasa_keyUserName); ?>" id="<?php echo esc_attr($nasa_keyRegUsername); ?>" value="<?php echo !empty($_POST[$nasa_keyUserName]) ? esc_attr($_POST[$nasa_keyUserName]) : ''; ?>" />
                    </p>

                <?php endif; ?>

                <p class="form-row form-row-wide">
                    <label for="<?php echo esc_attr($nasa_keyRegEmail); ?>">
                        <?php esc_html_e('Email address', 'digi-theme'); ?> <span class="required">*</span>
                    </label>
                    
                    <input type="email" class="input-text" name="<?php echo esc_attr($nasa_keyEmail); ?>" id="<?php echo esc_attr($nasa_keyRegEmail); ?>" value="<?php echo !empty($_POST[$nasa_keyEmail]) ? esc_attr($_POST[$nasa_keyEmail]) : ''; ?>" />
                </p>

                <?php if('yes' === get_option('woocommerce_registration_generate_password')) : ?>
                    <p class="form-row form-row-wide">
                        <label for="<?php echo esc_attr($nasa_keyRegPass); ?>">
                            <?php esc_html_e('Password', 'digi-theme'); ?> <span class="required">*</span>
                        </label>

                        <input type="password" class="input-text" name="<?php echo esc_attr($nasa_keyPass); ?>" id="<?php echo esc_attr($nasa_keyRegPass); ?>" value="<?php echo !empty($_POST['password']) ? esc_attr($_POST['password']) : ''; ?>" />
                    </p>
                <?php else : ?>
                    <p class="form-row form-row-wide"><?php esc_html_e( 'A password will be sent to your email address.', 'digi-theme' ); ?></p>
                <?php endif; ?>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="form-row">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <input type="submit" class="button" name="<?php echo esc_attr($nasa_keyReg); ?>" value="<?php esc_html_e('Setup your account', 'digi-theme'); ?>" />
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>
            
            <p class="nasa-switch-form">
                <?php esc_html_e('Already got a Selamoo account? ', 'digi-theme'); ?>
                <a class="nasa-switch-login" href="javascript:void(0);">
                    <?php esc_html_e('Sign in here', 'digi-theme'); ?>
                </a>
            </p>
        </div>
    <?php endif; ?>
</div>