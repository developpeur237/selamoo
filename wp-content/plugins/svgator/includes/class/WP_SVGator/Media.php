<?php

namespace WP_SVGator;

class Media {
	/**
	 * @param $params
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function create( $params ) {
		$uploadDir = wp_upload_dir();

		$basePath = $uploadDir['basedir'] . '/';
		$baseUrl  = $uploadDir['baseurl'] . '/';
		if ( wp_mkdir_p( $uploadDir['path'] ) ) {
			$basePath = $uploadDir['path'] . '/';
			$baseUrl  = $uploadDir['url'] . '/';
		}

		$sanitizedTitle = sanitize_file_name( $params['project']->title );
		$file           = $basePath . $sanitizedTitle . '.svg';
		$url            = $baseUrl . $sanitizedTitle . '.svg';

		$i = 1;
		while ( file_exists( $file ) ) {
			$file = $basePath . $sanitizedTitle . '-' . $i . '.svg';
			$url  = $baseUrl . $sanitizedTitle . '-' . $i . '.svg';
			$i ++;
		}

		//$params['content'] = self::fixResponsiveSvg( $params['content'] );

		$res = file_put_contents( $file, $params['content'] );

		if ( ! $res ) {
			throw new \Exception( 'Could not write file to media library.' );
		}

		$attachment = [
			'post_mime_type' => 'svgator/svg+xml',
			'post_title'     => $params['project']->title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		];

		$attachment = wp_insert_attachment( $attachment, $file );

		$attachment_data = wp_generate_attachment_metadata( $attachment, $file );
		if ( ! $attachment_data ) {
			$attachment_data = [];
		}
		$attachment_data['svgatorid'] = $params['project']->id;
		wp_update_attachment_metadata( $attachment, $attachment_data );

		return [
			'attachment' => $attachment,
			'url'        => $url,
		];
	}

	/*
	static function fixResponsiveSvg( $svgContent ) {
		$support    = Svg_Support::run();
		$dimensions = $support->getSvgDimensions( $svgContent );
		if ( empty( $dimensions ) ) {
			return $svgContent;
		}

		$svgContent = $support->keepAttribute(
			'width', $svgContent, intval( $dimensions['width'] ) );
		$svgContent = $support->keepAttribute(
			'height', $svgContent, intval( $dimensions['height'] ) );

		return $svgContent;
	}
	*/
}
