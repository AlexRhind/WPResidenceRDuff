<?php

namespace ILJ\Helper;

use  ILJ\Type\KeywordList ;
/**
 * Export toolset
 *
 * Methods for data export
 *
 * @since   1.2.0
 * @package ILJ\Helper
 */
class Export
{
    const  ILJ_EXPORT_CSV_FORMAT_HEADLINE = '"%1$s";"%2$s";"%3$s";"%4$s";"%5$s"' ;
    const  ILJ_EXPORT_CSV_FORMAT_LINE = '"%1$d";"%2$s";"%3$s";"%4$s";"%5$s"' ;
    /**
     * Prints the headline for keyword export as CSV
     *
     * @since  1.2.0
     * @return void
     */
    public static function printCsvHeadline()
    {
        printf(
            self::ILJ_EXPORT_CSV_FORMAT_HEADLINE,
            "ID",
            "Type",
            "Keywords (ILJ)",
            "Title",
            "Url"
        );
    }
    
    /**
     * Prints out all index relevant posts as CSV line
     *
     * @since  1.2.0
     * @param  bool $empty Flag for output of empty entries
     * @return void
     */
    public static function printCsvPosts( $empty )
    {
        $posts = IndexAsset::getPosts();
        foreach ( $posts as $post ) {
            $keyword_list = KeywordList::fromMeta( $post->ID, 'post' );
            if ( $empty && !$keyword_list->getCount() ) {
                continue;
            }
            printf( "\n" );
            printf(
                self::ILJ_EXPORT_CSV_FORMAT_LINE,
                $post->ID,
                'post',
                $keyword_list->encoded(),
                $post->post_title,
                get_permalink( $post->ID )
            );
        }
    }

}