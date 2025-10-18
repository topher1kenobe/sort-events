<?php
/**
 * Plugin Name: Global Event Sorting
 * Description: Sorts all 'event' post type queries by ACF start_date (soonest first), including Gutenberg Query Blocks.
 * Version: 1.1
 * Author: Topher
 */

add_action( 'pre_get_posts', function( $query ) {

    // Only frontend queries
    if ( is_admin() ) {
        return;
    }

    // Determine if this query is for 'event' post type
    $post_type = $query->get( 'post_type' );

    $is_event_query = false;
    if ( $post_type === 'event' ) {
        $is_event_query = true;
    } elseif ( is_array( $post_type ) && in_array( 'event', $post_type, true ) ) {
        $is_event_query = true;
    } elseif ( empty( $post_type ) && $query->is_post_type_archive( 'event' ) ) {
        $is_event_query = true;
    }

    if ( ! $is_event_query ) {
        return;
    }

    // --- FORCE ORDERING --- 
    // Remove any conflicting 'orderby'
    $query->set( 'meta_key', 'start_date' );
    $query->set( 'meta_type', 'DATETIME' ); // change to NUMERIC if Date Picker
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'order', 'ASC' );

}, 20 );
