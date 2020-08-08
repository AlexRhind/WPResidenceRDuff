<?php

namespace ILJ\Helper;

use  ILJ\Backend\MenuPage\Tools ;
use  ILJ\Backend\User ;
use  ILJ\Core\IndexBuilder ;
use  ILJ\Core\Options ;
use  ILJ\Database\Postmeta ;
use  ILJ\Helper\IndexAsset ;
use  ILJ\Database\Linkindex ;
use  ILJ\Backend\IndexRebuildNotifier ;
use  ILJ\Type\KeywordList ;
/**
 * Ajax toolset
 *
 * Methods for handling AJAX requests
 *
 * @package ILJ\Helper
 *
 * @since 1.0.0
 */
class Ajax
{
    const  ILJ_FILTER_AJAX_SEARCH_POSTS = 'ilj_ajax_search_posts' ;
    const  ILJ_FILTER_AJAX_SEARCH_TERMS = 'ilj_ajax_search_terms' ;
    /**
     * Searches the posts for a given phrase
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function searchPostsAction()
    {
        if ( !isset( $_POST['search'] ) && !isset( $_POST['per_page'] ) && !isset( $_POST['page'] ) ) {
            wp_die();
        }
        $search = sanitize_text_field( $_POST['search'] );
        $per_page = (int) $_POST['per_page'];
        $page = (int) $_POST['page'];
        $args = [
            "s"              => $search,
            "posts_per_page" => $per_page,
            "paged"          => $page,
        ];
        $query = new \WP_Query( $args );
        $data = [];
        foreach ( $query->posts as $post ) {
            $data[] = [
                "id"   => $post->ID,
                "text" => $post->post_title,
            ];
        }
        /**
         * Filters the output of ajax post search
         *
         * @since 1.1.6
         *
         * @param object $data The return data (found posts)
         * @param array  $args The arguments for the post query
         */
        $data = apply_filters( self::ILJ_FILTER_AJAX_SEARCH_POSTS, $data, $args );
        wp_send_json( $data );
        wp_die();
    }
    
    /**
     * Handles interactions with the rating notification
     *
     * @since  1.2.0
     * @return void
     */
    public static function ratingNotificationAdd()
    {
        if ( !isset( $_POST['days'] ) ) {
            wp_die();
        }
        $days = (int) $_POST['days'];
        
        if ( $days === -1 ) {
            User::unsetRatingNotification();
            wp_die();
        }
        
        $days_string = sprintf( '+%d days', $days );
        $notification_base_date = new \DateTime( 'now' );
        $notification_base_date->modify( $days_string );
        User::setRatingNotificationBaseDate( $notification_base_date );
        wp_die();
    }
    
    /**
     * Hides the promo box in the sidebar
     *
     * @since  1.1.2
     * @return void
     */
    public static function hidePromo()
    {
        User::update( 'hide_promo', true );
        wp_die();
    }
    
    /**
     * Handles upload of import files
     *
     * @since  1.2.0
     * @return void
     */
    public static function uploadImport()
    {
        if ( !isset( $_POST['nonce'] ) || !isset( $_POST['file_type'] ) ) {
            wp_send_json_error( null, 400 );
        }
        $nonce = $_POST['nonce'];
        $file_type = $_POST['file_type'];
        if ( !in_array( $file_type, [ 'settings', 'keywords' ] ) ) {
            wp_send_json_error( null, 400 );
        }
        if ( !wp_verify_nonce( $nonce, 'ilj-tools' ) || !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( null, 400 );
        }
        $uploaded_file = $_FILES['file_data'];
        $upload_overrides = [
            'test_form' => false,
            'test_type' => false,
        ];
        if ( $file_type == 'keywords' ) {
            $uploaded_file['name'] = uniqid( rand(), true ) . '.csv';
        }
        $file_upload = wp_handle_upload( $uploaded_file, $upload_overrides );
        if ( !$file_upload || isset( $file_upload['error'] ) ) {
            wp_send_json_error( __( 'Your web host does not allow file uploads. Please fix the problem and try again.', 'internal-links' ), 400 );
        }
        switch ( $file_type ) {
            case 'settings':
                $file_content = file_get_contents( $file_upload['file'] );
                unlink( $file_upload['file'] );
                $file_json = Encoding::jsonToArray( $file_content );
                if ( $file_json === false ) {
                    wp_send_json_error( null, 400 );
                }
                set_transient( 'ilj_upload_settings', $file_json, HOUR_IN_SECONDS * 12 );
                break;
            case 'keywords':
                set_transient( 'ilj_upload_keywords', $file_upload, HOUR_IN_SECONDS * 12 );
                break;
        }
        wp_send_json_success( null, 200 );
    }
    
    /**
     * Initiates the import of already uploaded and prepared files
     *
     * @since  1.2.0
     * @return void
     */
    public static function startImport()
    {
        if ( !isset( $_POST['nonce'] ) || !isset( $_POST['file_type'] ) ) {
            wp_send_json_error( null, 400 );
        }
        $nonce = $_POST['nonce'];
        $file_type = $_POST['file_type'];
        if ( !in_array( $file_type, [ 'settings', 'keywords' ] ) ) {
            wp_send_json_error( null, 400 );
        }
        if ( !wp_verify_nonce( $nonce, 'ilj-tools' ) || !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( null, 400 );
        }
        $upload_transient = get_transient( 'ilj_upload_' . $file_type );
        if ( !$upload_transient ) {
            wp_send_json_error( __( 'Timeout. Please try to upload again.', 'internal-links' ), 400 );
        }
        switch ( $file_type ) {
            case 'settings':
                $import_count = \ILJ\Core\Options::importOptions( $upload_transient );
                break;
            case 'keywords':
                if ( !isset( $upload_transient['file'] ) || !file_exists( $upload_transient['file'] ) ) {
                    wp_send_json_error( null, 400 );
                }
                $import_count = Keyword::importKeywordsFromFile( $upload_transient['file'] );
                unlink( $upload_transient['file'] );
                break;
        }
        if ( $import_count === 0 ) {
            wp_send_json_error( __( 'Nothing to import or no data for import found.', 'internal-links' ), 400 );
        }
        do_action( IndexBuilder::ILJ_ACTION_TRIGGER_BUILD_INDEX );
        delete_transient( 'ilj_upload_' . $file_type );
        wp_send_json_success( null, 200 );
    }

}