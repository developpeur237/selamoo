<?php

namespace WP_SVGator;

class Menu {
	function run() {
		$menu = add_menu_page(
			'SVGator',
			'SVGator',
			'manage_options',
			'svgator',
			[ $this, 'renderMenu' ],
			WP_SVGATOR_PLUGIN_URL . 'animated-logo.svg'
		);

		add_action( 'admin_print_scripts-' . $menu, Main::run()->enqueueScripts('WP_SVGatorMenu') );
		if ( Main::FORCE_DEV ) {
            add_action( 'admin_print_scripts-' . $menu, function(){
                wp_localize_script( 'WP_SVGatorMenu', 'svgator_options', [ 'endpoint' => Main::FORCE_DEV ] );
            } );
		}

		add_filter( 'script_loader_tag', [ $this, 'addModuleToJs' ], 10, 3 );
	}

	function renderMenu() {
		( new View( 'admin/projects' ) )->render();
	}

	function addModuleToJs( $tag, $handle, $src ) {
		switch ( $handle ) {
			case 'WP_SVGatorMenu':
				$tag = '<script type="module" src="' . $src . '" id="' . $handle . '"></script>' . PHP_EOL;
				break;
		}

		return $tag;
	}
}
