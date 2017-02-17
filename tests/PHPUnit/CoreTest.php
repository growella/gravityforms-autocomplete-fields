<?php
/**
 * Tests for the plugin's core functionality.
 *
 * @package Growella\GravityForms\AutocompleteFields;
 */

namespace Growella\GravityForms\AutocompleteFields\Core;

use WP_Mock as M;
use Mockery;

class CoreTest extends \Growella\GravityForms\AutocompleteFields\TestCase {

	protected $testFiles = array(
		'core.php',
	);

	public function testGetAutocompleteValues() {
		M::wpFunction( '_x' );

		$values = get_autocomplete_values();

		$this->assertInternalType( 'array', $values );
		$this->assertNotEmpty( $values );
	}

	public function testRenderAutocompleteAttrSetting() {
		M::wpFunction( __NAMESPACE__ . '\get_autocomplete_values', array(
			'return' => array(
				'foo' => 'Foo',
				'bar' => 'Bar',
			),
		) );

		M::wpFunction( 'gform_tooltip', array(
			'times'  => 1,
			'args'   => array( 'form_field_autocomplete_attr' ),
		) );

		M::wpPassthruFunction( 'esc_attr' );
		M::wpPassthruFunction( 'esc_html' );
		M::wpPassthruFunction( 'esc_html_e' );

		ob_start();
		render_autocomplete_attr_setting( 150 );
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertContains( '<li class="autocomplete_attr_setting field_setting">', $output );
		$this->assertContains( 'onchange="SetFieldProperty(\'autocompleteAttr\', this.value)"', $output );
	}

	public function testRenderAutocompleteAttrSettingReturnsEarlyOnWrongPriority() {
		render_autocomplete_attr_setting( 500 );

		$this->assertEmpty( $this->getActualOutput() );
	}

	public function testRenderTooltips() {
		M::wpPassthruFunction( '__' );
		M::wpPassthruFunction( '_x' );

		$this->assertArrayHasKey( 'form_field_autocomplete_attr', render_tooltips( array() ) );
	}
}
