<?php

namespace ILJ\Helper;

use  ILJ\Core\Options ;
use  ILJ\Posttypes\CustomLinks ;
/**
 * Toolset for linkindex assets
 *
 * Methods for handling linkindex data
 *
 * @package ILJ\Helper
 * @since   1.1.0
 */
class IndexAsset
{
    const  ILJ_FILTER_INDEX_ASSET = 'ilj_index_asset_title' ;
    /**
     * Returns all meta data to a specific asset from index
     *
     * @since  1.1.0
     * @param  int    $id   The id of the asset
     * @param  string $type The type of the asset (post, term or custom)
     * @return object
     */
    public static function getMeta( $id, $type )
    {
        
        if ( 'post' == $type ) {
            $post = get_post( $id );
            if ( !$post ) {
                return null;
            }
            $asset_title = $post->post_title;
            $asset_url = get_the_permalink( $post->ID );
            $asset_url_edit = get_edit_post_link( $post->ID );
        }
        
        $meta_data = (object) [
            'title'    => $asset_title,
            'url'      => $asset_url,
            'url_edit' => $asset_url_edit,
        ];
        /**
         * Filters the index asset
         *
         * @since 1.6.0
         *
         * @param object $meta_data The index asset
         * @param string $type The asset type
         * @param int $id The asset id
         */
        $meta_data = apply_filters(
            self::ILJ_FILTER_INDEX_ASSET,
            $meta_data,
            $type,
            $id
        );
        return $meta_data;
    }
    
    /**
     * Returns all relevant posts for linking
     *
     * @since  1.2.0
     * @return array
     */
    public static function getPosts()
    {
        $whitelist = Options::getOption( \ILJ\Core\Options\Whitelist::getKey() );
        if ( !count( $whitelist ) ) {
            return [];
        }
        $args = [
            'posts_per_page'   => -1,
            'post__not_in'     => Options::getOption( \ILJ\Core\Options\Blacklist::getKey() ),
            'post_type'        => $whitelist,
            'post_status'      => [ 'publish' ],
            'suppress_filters' => true,
        ];
        $query = new \WP_Query( $args );
        return $query->posts;
    }

}