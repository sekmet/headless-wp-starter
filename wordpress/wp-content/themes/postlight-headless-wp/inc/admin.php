<?php
/**
 * Admin filters.
 *
 * @package  Postlight_Headless_WP
 */

/**
 * By default, in Add/Edit Post, WordPress moves checked categories to the top of the list and unchecked to the bottom.
 * When you have subcategories that you want to keep below their parents at all times, this makes no sense.
 * This function removes automatic reordering so the categories widget retains its order regardless of checked state.
 * Thanks to https://stackoverflow.com/a/12586404
 *
 * @param arr $args Array of arguments.
 * @return arr
 */
function taxonomy_checklist_checked_ontop_filter( $args ) {
    $args['checked_ontop'] = false;
    return $args;
}

add_filter( 'wp_terms_checklist_args', 'taxonomy_checklist_checked_ontop_filter' );

/**
 * Customize the preview button in the WordPress admin to point to the headless client.
 *
 * @param  str $link The WordPress preview link.
 * @return str The headless WordPress preview link.
 */
function set_headless_preview_link( $link ) {
    $post = get_post( $post );
    if ( ! $post ) {
        return $link;
    }
 
    $frontend    = get_frontend_origin();
    $parent_id   = $post->post_parent;
    $revision_id = $post->ID;
    $type        = get_post_type( $parent_id );
    $nonce       = wp_create_nonce( 'wp_rest' );

    return "$frontend/_preview/$parent_id/$revision_id/$type/$nonce";
}

add_filter( 'preview_post_link', 'set_headless_preview_link' );
