<?php
/*
Plugin Name: Easy Digital Downloads - Add to cart Text
Plugin URL: http://easydigitaldownloads.com
Description: Add an "Add to cart text" option for your downloads to change the text on the "Add to cart" button for that particular download.
Version: 1.0.0
Author: brainstormforce, Nikschavan
Author URI: https://www.brainstormforce.com/
*/

/**
 * Add to Cart button Text meta field
 *
 * Adds field do the EDD Downloads meta box for specifying the "Add to cart text"
 *
 * @since 1.0.0
 * @param integer $post_id Download (Post) ID
 */
function edd_atc_text_render_field( $post_id ) {
	$edd_atc_text = get_post_meta( $post_id, '_edd_atc_text', true );
?>
	<p><strong><?php _e( 'Add to cart text:', 'edd-atc-text' ); ?></strong></p>
	<label for="edd_atc_text">
		<input type="text" name="_edd_atc_text" id="edd_atc_text" value="<?php echo esc_attr( $edd_atc_text ); ?>" size="80" Add to cart"/>
		<br/><?php _e( 'Change the Add to cart button text for this product, leave blank to use default text instead.', 'edd-atc-text' ); ?>
	</label>
<?php
}
add_action( 'edd_meta_box_fields', 'edd_atc_text_render_field', 90 );

/**
 * Add the _edd_atc_text field to the list of saved product fields
 *
 * @since  1.0.0
 *
 * @param  array $fields The default product fields list
 * @return array         The updated product fields list
 */
function edd_atc_text_save( $fields ) {

	// Add our field
	$fields[] = '_edd_atc_text';

	// Return the fields array
	return $fields;
}
add_filter( 'edd_metabox_fields_save', 'edd_atc_text_save' );

/**
 * Sanitize metabox field
 *
 * @since 1.0.0
*/
function edd_atc_text_metabox_save( $new ) {

	// sanitize the field before saving into wp_postmeta table
	$new = esc_attr( $_POST[ '_edd_atc_text' ] );

	// Return Title
	return $new;

}
add_filter( 'edd_metabox_save__edd_atc_text', 'edd_atc_text_metabox_save' );

/**
 * Override the default product purchase button with an external anchor
 *
 * Only affects products that have an Add to cart text specified
 *
 * @since  1.0.0
 *
 * @param  string    $purchase_form The concatenated markup for the purchase area
 * @param  array    $args           Args passed from {@see edd_get_purchase_link()}
 * @return string                   The potentially modified purchase area markup
 */
function edd_atc_text( $args ) {

	$download_id = $args['download_id'];

	$edd_atc_text = get_post_meta( $download_id, '_edd_atc_text', true ) ? get_post_meta( $download_id, '_edd_atc_text', true ) : '';

	if ( isset( $edd_atc_text ) && $edd_atc_text !== '' ) {
		$args['text'] = $edd_atc_text;
	}

	return $args;
}
add_filter( 'edd_purchase_link_args', 'edd_atc_text' );
