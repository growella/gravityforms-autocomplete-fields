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

	public function testInjectAutocompleteAttribute() {
		$field  = new \stdClass;
		$field->autocompleteAttr = 'email';

		M::wpPassthruFunction( 'esc_attr' );

		$this->assertEquals(
			'<input type="email" autocomplete="email">',
			inject_autocomplete_attribute( '<input type="email">', $field )
		);
	}

	public function testInjectAutocompleteAttributeHandlesSelfClosingElements() {
		$field  = new \stdClass;
		$field->autocompleteAttr = 'email';

		M::wpPassthruFunction( 'esc_attr' );

		$this->assertEquals(
			'<input type="email" autocomplete="email" />',
			inject_autocomplete_attribute( '<input type="email" />', $field )
		);
	}

	public function testInjectAutocompleteAttributeWithRealEmailInput() {
		$field  = new \stdClass;
		$field->autocompleteAttr = 'email';
		$markup = <<<EOT
<label class='gfield_label' for='input_7_1' >Email<span class='gfield_required'>*</span></label>
<div class='ginput_container ginput_container_email'>
	<input name='input_1' id='input_7_1' type='email' value='' class='medium' tabindex='1'   placeholder='Email'/>
</div>
<div class='gfield_description'>Input description</div>
EOT;

		M::wpPassthruFunction( 'esc_attr' );

		$this->assertContains(
			"<input name='input_1' id='input_7_1' type='email' value='' class='medium' tabindex='1'   placeholder='Email' autocomplete=\"email\"/>",
			inject_autocomplete_attribute( $markup, $field )
		);
	}

	public function testInjectAutocompleteAttributeChecksThatAutocompleteAttrIsSet() {
		$field  = new \stdClass;

		$this->assertEquals(
			'<input type="email" />',
			inject_autocomplete_attribute( '<input type="email" />', $field )
		);
	}

	public function testInjectAutocompleteAttributeChecksThatAutocompleteAttrIsNotEmpty() {
		$field  = new \stdClass;
		$field->autocompleteAttr = '';

		$this->assertEquals(
			'<input type="email" />',
			inject_autocomplete_attribute( '<input type="email" />', $field )
		);
	}

	public function testInjectAutocompleteAttributeReturnsEarlyIfNoInputIsFound() {
		$field  = new \stdClass;
		$field->autocompleteAttr = 'email';
		$markup = uniqid();

		$this->assertEquals(
			$markup,
			inject_autocomplete_attribute( $markup, $field )
		);
	}

	public function testInjectAutocompleteAttributeAppliesFilter() {
		$field  = new \stdClass;
		$field->autocompleteAttr = 'email';

		M::wpPassthruFunction( 'esc_attr' );

		M::onFilter( 'gform_autocomplete_attribute' )
			->with( 'email', $field )
			->reply( 'special email' );

		$this->assertEquals(
			'<input type="email" autocomplete="special email">',
			inject_autocomplete_attribute( '<input type="email">', $field )
		);
	}
}
