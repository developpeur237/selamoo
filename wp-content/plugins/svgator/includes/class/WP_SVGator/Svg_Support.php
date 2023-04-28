<?php

namespace WP_SVGator;

class Svg_Support {
	private function __construct() {
		add_filter( 'mime_types', [ $this, 'wp_addMimeType' ] );
		add_filter( 'upload_mimes', [ $this, 'wp_addMimeType' ] );
		if ( ! is_admin() ) {
			add_filter( 'the_content', [ $this, 'wp_svgInlineReplace' ] );
		}
		add_filter( 'post_thumbnail_html', [ $this, 'wp_svgInlineReplace' ] );
	}

	public static function run() {
		static $inst = null;
		if ($inst === null) {
			$inst = new self();
		}
		return $inst;
	}

	function wp_addMimeType( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		$mimes['svgator']  = 'svgator/svg+xml';

		return $mimes;
	}

	function wp_svgInlineReplace( $content ) {
		if ( empty( $content ) ) {
			return $content;
		}

		$content = preg_replace_callback(
			'/(<img[^>]*?>)/',
			[ $this, 'getImageReplacement' ],
			$content
		);

		return $content;
	}

	function getImageReplacement( $imgMatch ) {
		if ( empty( $imgMatch ) || empty( $imgMatch[0] ) ) {
			return '';
		}

		$imgTag = $imgMatch[0];

		$svgContent = $this->getSvg( $imgTag );
		$svgContent = static::fixScript( $svgContent );
		$svgContent = trim( $svgContent );
		$svgContent = $this->keepAttributes( $imgTag, $svgContent );

		return $svgContent;
	}

	function keepAttribute( $svgContent, $attrName, $newValue = false ) {

		return preg_replace_callback(
			'@<svg[^>]*>@',
			function($match) use ($attrName, $newValue){
				$svgTag     = $match[0];
				$quotedAttr = preg_quote( $attrName, '/' );

				if ($attrName === 'style') {
				    if (!$newValue) {
				        return $svgTag;
                    }
                    if (preg_match( '@\b' . $quotedAttr . '(?:=["\'](.*?)["\'])?@', $svgTag, $match )) {
				        $newValue = $match[1] . $newValue;
                    }
                }

				// removing existing attribute
				$svgTag = preg_replace( '@\b' . $quotedAttr . '(?:=["\'].*?["\'])?@', '', $svgTag );

				// adding back needed value, if needed
				if ( $newValue ) {
					$svgTag = str_replace(
						'<svg',
						'<svg ' . htmlspecialchars( $attrName ) . '="' . htmlspecialchars( $newValue ) . '"',
						$svgTag
					);
				}

				return $svgTag;
			},
			$svgContent
		);
	}

	function keepAttributes( $imgTag, $svgContent ) {
		$imgAttributes = static::parseAttributes($imgTag);

		$attrToKeeps = [ 'width', 'height', 'class', 'style' ];

		foreach ( $attrToKeeps as $attrName ) {
			$newValue = !empty($imgAttributes[$attrName]) ? $imgAttributes[$attrName] : false;
			$svgContent = $this->keepAttribute( $svgContent, $attrName, $newValue );
		}

		return $svgContent;
	}

	/**
	 * @param string $content
	 *
	 * @return array|false
	 */
	public static function parseAttributes($content) {
		if (!$content || !preg_match('@<(img|svg)\b[^>]*>@', $content, $match)) {
			return false;
		};
		$svg = strpos($match[0], '/>') === false ? str_replace('>', '/>', $match[0]) : $match[0];
		$svg = @simplexml_load_string($svg);
		$attrs = $svg ? $svg->attributes() : false;

		$attrs = $attrs ? (array)$attrs : false;

		return $attrs && !empty($attrs['@attributes']) ? $attrs['@attributes'] : [];
	}

	public static function fixScript( $svgContent ) {
		//Remove CDATA, since Wordpress does not allow it inside the content
		$svgContent = str_replace( '<![CDATA[', '', $svgContent );
		$svgContent = str_replace( ']]>', '', $svgContent );

		$startOfScript = strpos( $svgContent, '<script>' );
		if ( $startOfScript === false ) {
			return $svgContent;
		}

		//add a space after the < char if it is followed by a letter
		$startOfScript += strlen( '<script>' );
		$endOfScript   = strpos( $svgContent, '</script>' );
		$scriptContent = substr( $svgContent, $startOfScript, $endOfScript - $startOfScript );
		$scriptContent = preg_replace( '/<([a-z])/', '< $1', $scriptContent );
		$svgContent    = substr_replace( $svgContent, $scriptContent, $startOfScript, $endOfScript - $startOfScript );

		return $svgContent;
	}

	function getSvg( $imgTag ) {
		$src = preg_match( '/src="([^"]+)"/', $imgTag, $srcMatch );
		if ( ! $src || empty( $srcMatch ) || empty( $srcMatch[1] ) ) {
			return $imgTag;
		}

		$srcUrl = parse_url( $srcMatch[1], PHP_URL_PATH );
		$srcExt = pathinfo( $srcUrl, PATHINFO_EXTENSION );
		if ( 'svg' !== $srcExt ) {
			return $imgTag;
		}

		$svgHost  = parse_url( $srcMatch[1], PHP_URL_HOST );
		$thisHost = parse_url( get_site_url(), PHP_URL_HOST );
		if ( $thisHost !== $svgHost ) {
			return $imgTag;
		}

		$mainPath     = parse_url( trailingslashit( get_site_url() ), PHP_URL_PATH );
		$relativePath = preg_replace( '@^' . preg_quote( $mainPath, '@' ) . '@', '', $srcUrl, 1 );
		$svgLocalPath = ABSPATH . $relativePath;

		if ( ! file_exists( $svgLocalPath ) ) {
			return $imgTag;
		}

		$svgContent = file_get_contents( $svgLocalPath );
		if ( ! $svgContent || ! $this->belongsToSVGator( $svgContent ) ) {
			return $imgTag;
		}

		return $svgContent;
	}

	public function belongsToSVGator( &$svgContent ) {
		if ( strpos( $svgContent, '__SVGATOR_PLAYER__' ) !== false ) {
			return true;
		}

		preg_match( '@<svg.*?>@', $svgContent, $match );

		if ( $match && ! empty( $match[0] ) && strpos( $match[0], 'data-svgatorid' ) !== false ) {
			return true;
		}

		return false;
	}

	/*
	public function getSvgDimensions( $svgContent ) {
		$svg = @simplexml_load_file( $svgContent );
		if ( ! $svg ) {
			$svg = @simplexml_load_string( $svgContent );
		}

		$return = [
			'width'       => 0,
			'height'      => 0,
			'orientation' => 'portrait',
		];

		if ( ! $svg ) {
			return false;
		}

		$attributes = $svg->attributes();

		if ( ! empty( $attributes->width ) && ! empty( $attributes->height ) ) {
			$return['width']  = floatval( $attributes->width );
			$return['height'] = floatval( $attributes->height );
		} elseif ( ! empty( $attributes->viewBox ) ) {
			$sizes = explode( ' ', $attributes->viewBox );
			if ( ! empty( $sizes[2] ) && ! empty( $sizes[3] ) ) {
				$return['width']  = floatval( $sizes[2] );
				$return['height'] = floatval( $sizes[3] );
			}
		}

		return $return;
	}
	*/
}
