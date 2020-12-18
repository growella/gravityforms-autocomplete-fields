<?php
/**
 * Core functionality for Gravity Forms: Autocomplete Fields.
 *
 * @package Growella\GravityForms\AutocompleteFields
 */

namespace Growella\GravityForms\AutocompleteFields\Core;

/**
 * Retrieve an array of all legal autocomplete values, per the WHATWG specification.
 *
 * @link https://html.spec.whatwg.org/multipage/forms.html#autofill
 *
 * @return array A flat, associative array of valid autocomplete attribute values.
 */
function get_autocomplete_values() {
	$values = array(
		'name'                 => _x( 'Full name', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'given-name'           => _x( 'First/given name', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'family-name'          => _x( 'Last/family name', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'nickname'             => _x( 'Nickname/handle', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'organization-title'   => _x( 'Job title', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'username'             => _x( 'Username', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'new-password'         => _x( 'New password', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'current-password'     => _x( 'Current password', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'organization'         => _x( 'Company/group', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'street-address'       => _x( 'Street address', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'address-level1'       => _x( 'State/Province', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'address-level2'       => _x( 'City', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'address-level3'       => _x( 'address-level3', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'address-level4'       => _x( 'address-level4', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'country'              => _x( 'Country code', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'country-name'         => _x( 'Country name', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'postal-code'          => _x( 'ZIP/Postal code', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'cc-name'              => _x( 'Name on credit card', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'cc-number'            => _x( 'Credit card number', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'cc-exp'               => _x( 'Credit card expiration date', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'cc-csc'               => _x( 'Credit card security code', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'cc-type'              => _x( 'Credit card type', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'transaction-currency' => _x( 'Transaction currency', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'transaction-amount'   => _x( 'Transaction amount (bid, sale price, etc.)', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'language'             => _x( 'Preferred language', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'bday'                 => _x( 'Birthday', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'sex'                  => _x( 'Gender Identity', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'url'                  => _x( 'Website or other URL', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'photo'                => _x( 'Photo, avatar', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'tel'                  => _x( 'Phone number', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'tel-extension'        => _x( 'Phone extension', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'email'                => _x( 'Email address', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
		'impp'                 => _x( 'Instant Messenger protocol', 'autocomplete attribute label', 'gravityforms-autocomplete-fields' ),
	);

	/**
	 * Filter the available autocomplete attribute values.
	 *
	 * @param array $values Available values for the 'autocomplete' attribute.
	 */
	return apply_filters( 'gform_autocomplete_attribute_values', $values );
}

/**
 * Render the autocomplete attribute setting.
 *
 * @param int $position The current property position. We'll be looking for 150, which is just
 *                      below "default value" on the advanced tab.
 */
function render_autocomplete_attr_setting( $position ) {
	if ( 150 !== $position ) {
		return;
	}
?>

	<li class="autocomplete_attr_setting field_setting">
		<ul>
			<li>
				<label for="field_autocomplete_attr" class="section_label">
					<?php esc_html_e( 'Autocomplete Attribute', 'gravityforms-autocomplete-fields' ); ?>
					<?php gform_tooltip( 'form_field_autocomplete_attr' ); ?>
				</label>
				<select id="field_autocomplete_attr" onchange="SetFieldProperty('autocompleteAttr', this.value)">
					<?php foreach ( get_autocomplete_values() as $val => $label ) : ?>

						<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>

					<?php endforeach; ?>
				</select>
			</li>
		</ul>
	</li>

<?php
}
add_action( 'gform_field_advanced_settings', __NAMESPACE__ . '\render_autocomplete_attr_setting' );

/**
 * Print the custom scripting necessary for the autocompleteAttr property.
 */
function editor_script() {
?>

	<script type="text/javascript">
		jQuery.map( fieldSettings, function ( el, i ) {
			fieldSettings[ i ] += ', .autocomplete_attr_setting';
		} );

		// Populate field settings on initialization.
		jQuery( document ).on( 'gform_load_field_settings', function( ev, field ) {
			var opt = document.querySelector( '#field_autocomplete_attr option[value="' + field.autocompleteAttr + '"]' );

			if ( opt ) {
				opt.setAttribute( 'selected', true );
			}
		} );
	</script>

<?php
}
add_action( 'gform_editor_js', __NAMESPACE__ . '\editor_script' );

/**
 * Register a tooltip for the autocomplete attribute field.
 *
 * @param array $tooltips Existing Gravity Forms tooltips.
 * @return array The $tooltips array with a new key for form_field_autocomplete_attr.
 */
function render_tooltips( $tooltips ) {
	$tooltips['form_field_autocomplete_attr'] = sprintf(
		'<h6>%s</h6>%s',
		_x( 'Autocomplete Attribute', 'tooltip title', 'gravityforms-autocomplete-fields' ),
		__( 'Assign a value to the "autocomplete" attribute for this field.', 'gravityforms-autocomplete-fields' )
	);

	return $tooltips;
}
add_filter( 'gform_tooltips', __NAMESPACE__ . '\render_tooltips' );

/**
 * Inject autocomplete attributes into individual form fields.
 *
 * @param string   $markup The markup for an individual field.
 * @param GF_Field $field  The Gravity Forms Field object.
 */
function inject_autocomplete_attribute( $markup, $field ) {
	if ( ! isset( $field->autocompleteAttr ) || ! $field->autocompleteAttr ) {
		return $markup;
	}

	// Find the first input, select, or textarea element.
	$regex = '/\<(?:input|select|textarea)\s+[^\>]+?(\s*\/?\>){1}/im';

	if ( ! preg_match( $regex, $markup, $input ) ) {
		return $markup;
	}

	/**
	 * Filter the autocomplete attribute before it's injected into an input.
	 *
	 * @param string   $attribute The autocomplete attribute for this input.
	 * @param GF_Field $field     The Gravity Forms field object.
	 */
	$attribute = apply_filters( 'gform_autocomplete_attribute', $field->autocompleteAttr, $field );

	$autocomplete = sprintf( ' autocomplete="%s"', esc_attr( $attribute ) );
	$element      = str_replace( $input[1], $autocomplete . $input[1], $input[0] );

	return str_replace( $input[0], $element, $markup );
}
add_filter( 'gform_field_content', __NAMESPACE__ . '\inject_autocomplete_attribute', 10, 2 );
