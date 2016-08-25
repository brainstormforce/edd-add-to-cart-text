<?php
/*
Plugin Name: EDD - Add to cart Text
Plugin URL: http://easydigitaldownloads.com
Description: Add an "Add to cart text" option for your downloads to change the text on the "Add to cart" button for that particular download.
Version: 1.0.1
Author: Brainstorm Force
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


function edd_atc_new_tab_render_field( $post_id ) {
	$edd_atc_new_tab = get_post_meta( $post_id, '_edd_atc_new_tab', true );
?>
	<p><strong><?php _e( 'Open link in new Tab:', 'edd-atc-text' ); ?></strong></p>
	<label for="edd_atc_new_tab">
		<input type="checkbox" name="_edd_atc_new_tab" id="edd_atc_new_tab" <?php checked(1, $edd_atc_new_tab) ?> value='1' Add to cart"/>
		<?php _e( 'Open add to cart link in new tab for this download?', 'edd-atc-text' ); ?>
<?php

}

add_action( 'edd_meta_box_fields', 'edd_atc_new_tab_render_field', 91 );

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
	$fields[] = '_edd_atc_new_tab';

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

function edd_atc_new_tab_metabox_save( $new ) {

	// sanitize the field before saving into wp_postmeta table
	$new = esc_attr( $_POST[ '_edd_atc_new_tab' ] );

	// Return Title
	return $new;
}

add_filter( 'edd_metabox_save__edd_atc_new_tab', 'edd_atc_new_tab_metabox_save' );

/**
 * change button arguments to update "Add to cart text"
 *
 * Only affects products that have an Add to cart text specified
 *
 * @since  1.0.0
 *
 * @param  array    $args           Args passed from {@see edd_get_purchase_link()}
 * @return array                   Args modified if the button text is modified
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

/**
 * Override the default product purchase button with target="_blank"
 *
 * Only affects products that have an selected "Open link in new tab"
 *
 * @since  1.0.0
 *
 * @param  string    $purchase_form The concatenated markup for the purchase area
 * @param  array    $args           Args passed from {@see edd_get_purchase_link()}
 * @return string                   The potentially modified purchase area markup
 */
function edd_atc_new_tab_render( $purchase_form, $args ) {

	$download_id = $args['download_id'];

	$edd_atc_new_tab = get_post_meta( $download_id, '_edd_atc_new_tab', true ) ? get_post_meta( $download_id, '_edd_atc_new_tab', true ) : '';

	if ( isset( $edd_atc_new_tab ) && $edd_atc_new_tab !== '' ) {
		$purchase_form = preg_replace( '/(<a\b[^><]*)>/i', '$1 target="_blank">', $purchase_form );
	}

	return $purchase_form;
}

add_filter( 'edd_purchase_download_form', 'edd_atc_new_tab_render', 90, 2 );