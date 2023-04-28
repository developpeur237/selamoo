<?php

namespace WP_SVGator;

class Main {
	const FORCE_DEV = false;
	//const FORCE_DEV = 'https://app.svgator.net/app-auth';

	private function __construct() {
		$sdkAutoload = WP_SVGATOR_PLUGIN_DIR . 'sdk/autoload.php';
		if ( ! file_exists( $sdkAutoload ) ) {
			add_action( 'admin_notices', function(){
				?>
				<div class="error notice">
					<p>SVGator SDK could not be loaded.</p>
				</div>
				<?php
			});
		}

		require $sdkAutoload;

		Block::run();
		Svg_Support::run();
		Custom_Media::run();

		add_filter( 'wp_prepare_attachment_for_js', [$this, 'prepareAttachmentJs'], 1, 3);

		add_action( 'admin_menu', [ $this, 'adminMenu' ] );

		add_action( 'wp_ajax_svgator_saveToken', [ $this, 'saveToken' ] );
		add_action( 'wp_ajax_svgator_getProjects', [ $this, 'getProjects' ] );
		add_action( 'wp_ajax_svgator_importProject', [ $this, 'importProject' ] );
		add_action( 'wp_ajax_svgator_logOut', [ $this, 'svgatorLogOut' ] );
		add_action('plugins_loaded', [ $this, 'pluginUpdateCheck' ]);
	}

	/**
	 * @param array $response
	 * @param \WP_Post $attachment
	 * @param $meta
	 *
	 * @return mixed
	 */
	public function prepareAttachmentJs( $response, $attachment, $meta) {

		if ($response['mime'] !== 'svgator/svg+xml') {
		    return $response;
        }
		$response['icon']  = $response['url'];
		$response['image'] = $response['url'];

		$file = get_attached_file($attachment->ID);
		$svg = file_exists($file) ? @file_get_contents($file) : false;

		if ($svg) {
		    $attributes = Svg_Support::parseAttributes($svg);
		    if ($attributes['width'] && $attributes['height']) {
		        $response['width'] = $attributes['width'];
			    $response['height'] = $attributes['height'];
			    $response['responsive'] = false;
            } else {
			    $response['responsive'] = true;
            }
        }

		return $response;
	}

	public function pluginUpdateCheck() {
		if ( get_option( "WP_SVGATOR_VERSION" ) != WP_SVGATOR_VERSION) {
			$this->pluginUpdateRun();
			update_option( "WP_SVGATOR_VERSION", WP_SVGATOR_VERSION );
		}
	}

	private function pluginUpdateRun(){
		global $wpdb;
	    $args  = [
			'nopaging'       => true,
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image/svg+xml',
		];
		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			$posts = $query->get_posts();
			foreach($posts as $aPost) {
				$file = get_attached_file($aPost->ID);
				$content = file_exists($file) ? @file_get_contents($file) : false;
				if ($content && Svg_Support::run()->belongsToSVGator($content)) {
					$wpdb->update(
                        $wpdb->prefix . 'posts',
                        ['post_mime_type' => 'svgator/svg+xml'],
                        ['ID' => $aPost->ID]
                    );
                }
            }
		}
		wp_reset_postdata();
    }

    public function registerScripts(){
	    wp_register_style(
		    'WP_SVGatorAdmin',
		    WP_SVGATOR_PLUGIN_URL . 'admin/css/svgator.css',
		    [],
		    WP_SVGATOR_VERSION
	    );

	    wp_register_script(
		    'WP_SVGatorWidget',
		    WP_SVGATOR_PLUGIN_URL . "/admin/js/WP_SVGatorWidget.js",
		    [ 'WP_SVGatorMedia' ],
		    WP_SVGATOR_VERSION,
		    true
	    );

	    wp_register_script(
		    'WP_SVGatorBlock',
		    WP_SVGATOR_PLUGIN_URL . 'admin/js/WP_SVGatorBlock.js',
		    ['WP_SVGatorMedia', 'wp-blocks', 'wp-element'],
		    WP_SVGATOR_VERSION,
		    true
	    );

	    wp_register_script(
		    'WP_SVGatorMedia',
		    WP_SVGATOR_PLUGIN_URL . "/admin/js/WP_SVGatorMedia.js",
		    [ 'wp-mediaelement', 'jquery' ],
		    WP_SVGATOR_VERSION,
		    true
	    );

	    wp_register_script(
		    'WP_SVGatorFrontend',
		    'https://cdn.svgator.com/sdk/svgator-frontend.latest.js',
		    [],
		    WP_SVGATOR_VERSION
	    );
	    wp_register_script(
		    'WP_SVGatorMenu',
		    WP_SVGATOR_PLUGIN_URL . 'admin/js/WP_SVGatorMenu.js',
		    [ 'WP_SVGatorFrontend', 'jquery' ],
		    WP_SVGATOR_VERSION
	    );
    }

    public function enqueueScripts($list = null){
	    if (empty($list)) {
		    $list = [
		        'WP_SVGatorMedia',
                'WP_SVGatorBlock',
                'WP_SVGatorFrontend',
                'WP_SVGatorMenu',
                'WP_SVGatorWidget',
            ];
        } elseif (is_string($list)) {
		    $list = [$list];
        }
	    return function() use ($list){
	        $this->registerScripts();
	        foreach($list as $aScript) {
		        wp_enqueue_script( $aScript );
	        }
	        wp_enqueue_style('WP_SVGatorAdmin');
        };
    }

	public static function run() {
		static $inst = null;
		if ($inst === null) {
			$inst = new self();
		}
		return $inst;
	}

	function adminMenu() {
		$menu = new Menu();
		$menu->run();
	}

	static function deactivator() {
		Deactivator::deactivate();
	}

	function saveToken() {
		try {
			if ( empty( $_POST['auth_code'] ) ) {
				throw new \Exception( 'An auth_code was not provided.' );
			}

			$userOptions = get_user_option( 'svgator_api' );

			if ( self::FORCE_DEV ) {
				$userOptions['endpoint'] = Main::FORCE_DEV . '/';
			}

			$authCode = sanitize_key( $_POST['auth_code'] );
			$appId    = 'dynamic';
			if ( ! empty( $_POST['app_id'] ) ) {
				$appId = sanitize_key( $_POST['app_id'] );
			}
			$userOptions['app_id'] = $appId;

			$svg    = new \SVGatorSDK\Main( $userOptions );
			$params = $svg->getAccessToken( $authCode );

			$requiredKeys = [ 'app_id', 'access_token', 'customer_id' ];
			if ( empty( $userOptions['secret_key'] ) ) {
				$requiredKeys[] = 'secret_key';
			}
			$userOptionsToSave = [];
			foreach ( $requiredKeys as $requiredKey ) {
				if ( empty( $params[ $requiredKey ] ) ) {
					throw new \Exception( 'Could not retrieve "' . $requiredKey . '"' );
				}

				$userOptionsToSave[ $requiredKey ] = $params[ $requiredKey ];
			}
			update_user_option( get_current_user_id(), 'svgator_api', $userOptionsToSave );

			\SVGatorSDK\Response::send( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			\SVGatorSDK\Response::send( [
				'success' => false,
				'error'   => $e->getMessage(),
			] );
		}

		wp_die();
	}

	function getProjects() {
		$userOptions = get_user_option( 'svgator_api', get_current_user_id() );

		try {
			if ( empty( $userOptions ) ) {
				throw new \Exception( 'User tokes are not set. Please log in again.' );
			}

			if ( self::FORCE_DEV ) {
				$userOptions['endpoint'] = Main::FORCE_DEV . '/';
			}

			$svgator  = new \SVGatorSDK\Main( $userOptions );
			$projectList = array_merge(
			        array(
			            'projects' => array(),
                        'limits' =>  array(),
                    ),
			        $svgator->projects()->getAll()
            );

			\SVGatorSDK\Response::send([
				'success'  => true,
				'response' => $projectList['projects'],
                'limits' => $projectList['limits'],
			]);
		} catch ( \Exception $e ) {
			\SVGatorSDK\Response::send( [
				'success' => false,
				'error'   => 'Failed to load projects. Please try to log in again.',
			] );
		}

		wp_die();
	}

	function svgatorLogOut() {
		delete_user_option( get_current_user_id(), 'svgator_api' );

		\SVGatorSDK\Response::send( [
			'success' => true,
		] );

		wp_die();
	}

	private function addProjectId( &$svg, $project_id ) {
		if ( ! $svg || ! $project_id ) {
			return;
		}

		$project_id = sanitize_key( $project_id );

		if ( ! $project_id ) {
			return;
		}

		$svg = preg_replace_callback(
			'@<svg.*?>@',
			function ( $match1 ) use ( $project_id ) {
				if ( ! preg_match( '@data-svgatorid=["\'](.+?)["\']@', $match1[0], $match2 ) ) {
					return str_replace( '>', ' data-svgatorid="' . $project_id . '">', $match1[0] );
				}
				if ( $match2[1] === $project_id ) {
					return $match1[0];
				}

				return str_replace( $match2[0], 'data-svgatorid="' . $project_id . '"', $match1[0] );
			},
			$svg );
	}

	function importProject() {
		$userOptions = get_user_option( 'svgator_api', get_current_user_id() );

		try {
			$project_id = sanitize_key( $_POST['project_id'] );
			$svgator    = new \SVGatorSDK\Main( $userOptions );
			$project    = $svgator->projects()->get( $project_id );
			$svg        = $svgator->projects()->export( $project_id, 'web' );

            $limits = !empty($svg['limits']) ? $svg['limits'] : null;
            if (empty($svg['content'])) {
                $svg['content'] = '';
            }

			$this->addProjectId( $svg['content'], $project_id );

			$resp = Media::create( [
				'content' => $svg['content'],
				'project' => $project,
			] );

			$attachment = wp_prepare_attachment_for_js($resp['attachment']);

			\SVGatorSDK\Response::send( [
				'success'  => true,
				'response' => [
				    'attachment' => $attachment,
					'id'      => intval( $resp['attachment'] ),
					'html'    => wp_get_attachment_image( $resp['attachment'] ),
					'url'     => $resp['url'],
					'content' => $svg['content'],
					'project' => $project,
                    'limits'  => $limits,
				],
			] );
        } catch (\SVGatorSDK\ExportException $e) {
            \SVGatorSDK\Response::send([
                'success' => false,
                'error'   => $e->getMessage(),
                'data' => $e->getData()
            ]);
		} catch ( \Exception $e ) {
			\SVGatorSDK\Response::send( [
				'success' => false,
				'error'   => $e->getMessage(),
			] );
		}

		wp_die();
	}
}
