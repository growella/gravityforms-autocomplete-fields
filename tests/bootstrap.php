<?php
/**
 * Bootstrap the test suite.
 *
 * @package Growella\GravityForms\AutocompleteFields
 */

if ( ! defined( 'PROJECT' ) ) {
	define( 'PROJECT', __DIR__ . '/../includes/' );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/test-tools/' );
}

if ( ! file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	throw new PHPUnit_Framework_Exception(
		'ERROR: You must use Composer to install the test suite\'s dependencies!' . PHP_EOL
	);
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/test-tools/TestCase.php';

WP_Mock::setUsePatchwork( true );
WP_Mock::bootstrap();
WP_Mock::tearDown();
