<?php
namespace AIOSEO\Plugin\Common\Api;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Route class for the API.
 *
 * @since 4.0.0
 */
class Analyze {
	/**
	 * Analyzes the site for SEO.
	 *
	 * @since 4.0.0
	 *
	 * @param  \WP_REST_Request  $request The REST Request
	 * @return \WP_REST_Response          The response.
	 */
	public static function analyzeSite( $request ) {
		return new \WP_REST_Response( [ 'success'  => true, 'response' => '' ], 200 );

		$body             = $request->get_json_params();
		$analyzeUrl       = ! empty( $body['url'] ) ? esc_url_raw( urldecode( $body['url'] ) ) : null;
		$refreshResults   = ! empty( $body['refresh'] ) ? (bool) $body['refresh'] : false;
		$analyzeOrHomeUrl = ! empty( $analyzeUrl ) ? $analyzeUrl : home_url();
		$responseCode     = null === aioseo()->cache->get( 'analyze_site_code' ) ? [] : aioseo()->cache->get( 'analyze_site_code' );
		$responseBody     = null === aioseo()->cache->get( 'analyze_site_body' ) ? [] : aioseo()->cache->get( 'analyze_site_body' );
		if (
			empty( $responseCode ) ||
			empty( $responseCode[ $analyzeOrHomeUrl ] ) ||
			empty( $responseBody ) ||
			empty( $responseBody[ $analyzeOrHomeUrl ] ) ||
			$refreshResults
		) {
			$token      = aioseo()->internalOptions->internal->siteAnalysis->connectToken;
			$license    = aioseo()->options->has( 'general' ) && aioseo()->options->general->has( 'licenseKey' )
				? aioseo()->options->general->licenseKey
				: '';
			$url        = defined( 'AIOSEO_ANALYZE_URL' ) ? AIOSEO_ANALYZE_URL : 'https://analyze.aioseo.com';
			$response   = wp_remote_post( $url . '/v1/analyze/', [
				'headers' => [
					'X-AIOSEO-Key'     => $token,
					'X-AIOSEO-License' => $license,
					'Content-Type'     => 'application/json'
				],
				'body'    => wp_json_encode( [
					'url' => $analyzeOrHomeUrl
				] ),
				'timeout' => 60
			] );

			$responseCode[ $analyzeOrHomeUrl ] = wp_remote_retrieve_response_code( $response );
			$responseBody[ $analyzeOrHomeUrl ] = json_decode( wp_remote_retrieve_body( $response ) );

			aioseo()->cache->update( 'analyze_site_code', $responseCode, 10 * MINUTE_IN_SECONDS );
			aioseo()->cache->update( 'analyze_site_body', $responseBody, 10 * MINUTE_IN_SECONDS );
		}

		if ( 200 !== $responseCode[ $analyzeOrHomeUrl ] || empty( $responseBody[ $analyzeOrHomeUrl ]->success ) || ! empty( $responseBody[ $analyzeOrHomeUrl ]->error ) ) {
			if ( ! empty( $responseBody[ $analyzeOrHomeUrl ]->error ) && 'invalid-token' === $responseBody[ $analyzeOrHomeUrl ]->error ) {
				aioseo()->internalOptions->internal->siteAnalysis->reset();
			}
			return new \WP_REST_Response( [
				'success'  => false,
				'response' => $responseBody[ $analyzeOrHomeUrl ]
			], 400 );
		}

		if ( $analyzeUrl ) {
			$competitors = aioseo()->internalOptions->internal->siteAnalysis->competitors;
			$competitors = array_reverse( $competitors, true );

			if ( empty( $competitors[ $analyzeUrl ] ) ) {
				$competitors[ $analyzeUrl ] = '';
			}

			$competitors[ $analyzeUrl ] = wp_json_encode( $responseBody[ $analyzeOrHomeUrl ] );

			$competitors = array_reverse( $competitors, true );

			// Reset the competitors.
			aioseo()->internalOptions->internal->siteAnalysis->competitors = $competitors;

			return new \WP_REST_Response( $competitors, 200 );
		}

		aioseo()->internalOptions->internal->siteAnalysis->score   = $responseBody[ $analyzeOrHomeUrl ]->score;
		aioseo()->internalOptions->internal->siteAnalysis->results = wp_json_encode( $responseBody[ $analyzeOrHomeUrl ]->results );

		return new \WP_REST_Response( $responseBody[ $analyzeOrHomeUrl ], 200 );
	}

	/**
	 * Analyzes the title for SEO.
	 *
	 * @since 4.1.2
	 *
	 * @param  \WP_REST_Request  $request The REST Request
	 * @return \WP_REST_Response          The response.
	 */
	public static function analyzeHeadline( $request ) {
		$body  = $request->get_json_params();
		$title = ! empty( $body['title'] ) ? sanitize_text_field( $body['title'] ) : '';

		if ( ! $title ) {
			return new \WP_REST_Response( [
				'success' => false,
				'message' => 'Title is missing.'
			], 400 );
		}

		$result = aioseo()->headlineAnalyzer->getResult( $title );

		return new \WP_REST_Response( $result, 200 );
	}

	/**
	 * Deletes the analyzed site for SEO.
	 *
	 * @since 4.0.0
	 *
	 * @param  \WP_REST_Request  $request The REST Request
	 * @return \WP_REST_Response          The response.
	 */
	public static function deleteSite( $request ) {
		$body       = $request->get_json_params();
		$analyzeUrl = ! empty( $body['url'] ) ? esc_url_raw( urldecode( $body['url'] ) ) : null;

		$competitors = aioseo()->internalOptions->internal->siteAnalysis->competitors;

		unset( $competitors[ $analyzeUrl ] );

		// Reset the competitors.
		aioseo()->internalOptions->internal->siteAnalysis->competitors = $competitors;

		return new \WP_REST_Response( $competitors, 200 );
	}
}