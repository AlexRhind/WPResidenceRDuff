<?php

namespace ILJ\Backend\MenuPage;

use  ILJ\Backend\MenuPage\Includes\Postbox ;
use  ILJ\Helper\Help ;
use  ILJ\Helper\Statistic ;
use  ILJ\Backend\AdminMenu ;
use  ILJ\Helper\IndexAsset ;
use  ILJ\Backend\Environment ;
use  ILJ\Backend\MenuPage\Includes\Sidebar ;
use  ILJ\Backend\MenuPage\Includes\Headline ;
/**
 * The dashboard page
 *
 * Responsible for displaying the dashboard
 *
 * @package ILJ\Backend\Menupage
 * @since   1.0.0
 */
class Dashboard extends AbstractMenuPage
{
    const  ILJ_FILTER_DASHBOARD_PLUGIN_RELATED = 'ilj_dashboard_plugin_related' ;
    public function __construct()
    {
        $this->page_title = __( 'Dashboard', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->addSubMenuPage( true );
        $this->addAssets( [
            'tipso'         => ILJ_URL . 'admin/js/tipso.js',
            'ilj_statistic' => ILJ_URL . 'admin/js/ilj_statistic.js',
            'ilj_promo'     => ILJ_URL . 'admin/js/ilj_promo.js',
        ], [
            'tipso'         => ILJ_URL . 'admin/css/tipso.css',
            'ilj_ui'        => ILJ_URL . 'admin/css/ilj_ui.css',
            'ilj_grid'      => ILJ_URL . 'admin/css/ilj_grid.css',
            'ilj_statistic' => ILJ_URL . 'admin/css/ilj_statistic.css',
        ] );
    }
    
    /**
     * @inheritdoc
     */
    public function render()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        echo  '<div class="wrap ilj-menu-dashboard">' ;
        $this->renderHeadline( __( 'Dashboard', 'internal-links' ) );
        echo  '<div class="row">' ;
        echo  '<div class="col-9">' ;
        $related = '<p><strong>' . __( 'Installed version', 'internal-links' ) . ':</strong> ' . $this->getVersion() . '</p>';
        $related .= $this->getHelpRessources();
        $this->renderPostbox( [
            'title'   => __( 'Plugin related', 'internal-links' ),
            'content' => $related,
        ] );
        $this->renderPostbox( [
            'title'   => __( 'Linkindex info', 'internal-links' ),
            'content' => $this->getIndexMeta(),
        ] );
        $statistic_args = [
            'title'   => __( 'Statistics', 'internal-links' ),
            'content' => $this->getStatistics(),
            'class'   => 'ilj-statistic-wrap',
        ];
        $this->renderPostbox( $statistic_args );
        echo  '</div>' ;
        echo  '<div class="col-3">' ;
        $this->renderSidebar();
        echo  '</div>' ;
        echo  '</div>' ;
        echo  '</div>' ;
    }
    
    /**
     * Generates the help links
     *
     * @since  1.2.0
     * @return string
     */
    protected function getHelpRessources()
    {
        $output = '';
        $url_manual = Help::getLinkUrl(
            null,
            null,
            'docs',
            'dashboard'
        );
        $url_tour = add_query_arg( [
            'page' => AdminMenu::ILJ_MENUPAGE_SLUG . '-' . Tour::ILJ_MENUPAGE_TOUR_SLUG,
        ], admin_url( 'admin.php' ) );
        $url_plugins_forum = 'https://wordpress.org/support/plugin/internal-links/';
        $output .= '<ul class="ilj-ressources divide">';
        $output .= '<li>';
        $output .= '<span class="dashicons dashicons-book-alt"></span>';
        $output .= '<a href="' . $url_manual . '" target="_blank" rel="noopener"><strong>' . __( 'Docs & How To', 'internal-links' ) . '</strong><br>' . __( 'Learn how to use the plugin', 'internal-links' ) . '</a>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<span class="dashicons dashicons-welcome-learn-more"></span>';
        $output .= '<a href="' . $url_tour . '"><strong>' . __( 'Interactive Tour', 'internal-links' ) . '</strong><br>' . __( 'A quick guided tutorial', 'internal-links' ) . '</a>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<span class="dashicons dashicons-testimonial"></span>';
        $output .= '<a href="' . $url_plugins_forum . '" target="_blank" rel="noopener"><strong>' . __( 'Request support', 'internal-links' ) . '</strong><br>' . __( 'Get help through our forum', 'internal-links' ) . '</a>';
        $output .= '</li>';
        $output .= '</ul>';
        return $output;
    }
    
    /**
     * Generates the statistic section
     *
     * @since  1.2.0
     * @return string
     */
    protected function getStatistics()
    {
        $output = '';
        $top_inlinks = Statistic::getAggregatedCount( [
            "type" => "link_to",
        ] );
        $top_outlinks = Statistic::getAggregatedCount( [
            "type" => "link_from",
        ] );
        $top_anchors = Statistic::getAggregatedCount( [
            "type" => "anchor",
        ] );
        $output .= '<div class="inside">';
        
        if ( empty($top_anchors) ) {
            $output .= '<p>' . __( 'There are no statistics to display', 'internal-links' ) . '.</p>';
            $output .= '</div>';
            return $output;
        }
        
        $output .= '<div class="row">';
        $output .= '<div class="col-4 no-top-padding">';
        $output .= '<h3>' . __( 'Top 10 incoming links', 'internal-links' ) . '</h3>';
        $output .= $this->getLinkList( $top_inlinks, 'link_to' );
        $output .= '</div>';
        $output .= '<div class="col-4 no-top-padding">';
        $output .= '<h3>' . __( 'Top 10 outgoing links', 'internal-links' ) . '</h3>';
        $output .= $this->getLinkList( $top_outlinks, 'link_from' );
        $output .= '</div>';
        $output .= '<div class="col-4 no-top-padding"> ';
        $output .= '<h3>' . __( 'Top 10 anchor texts', 'internal-links' ) . '</h3>';
        $output .= $this->getKeywordList( $top_anchors, 'anchor' );
        $output .= '</div>';
        $output .= '<div class="row"></div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
    
    /**
     * Generates all index related meta data
     *
     * @since  1.2.0
     * @return string
     */
    private function getIndexMeta()
    {
        $output = '';
        $linkindex_info = Environment::get( 'linkindex' );
        
        if ( $linkindex_info['last_update']['entries'] == "" ) {
            $help_url = Help::getLinkUrl(
                'editor/',
                null,
                'editor onboarding',
                'dashboard'
            );
            $output .= '<p>' . __( 'Index has no entries yet', 'internal-links' ) . '.</p>';
            $output .= '<p class="divide"><span class="dashicons dashicons-arrow-right-alt"></span> <strong>' . __( 'Start to set some keywords to your posts', 'internal-links' ) . ' - <a href="' . $help_url . '" target="_blank" rel="noopener">' . __( 'learn how it works', 'internal-links' ) . '</a></strong></p>';
            return $output;
        }
        
        $hours = (int) get_option( 'gmt_offset' );
        $minutes = ($hours - floor( $hours )) * 60;
        $date = $linkindex_info['last_update']['date']->setTimezone( new \DateTimeZone( sprintf( '%+03d:%02d', $hours, $minutes ) ) );
        $output .= '<p><strong>' . __( 'Amount of links in the index', 'internal-links' ) . '</strong>: ' . $linkindex_info['last_update']['entries'] . '</p>';
        $output .= '<p><strong>' . __( 'Amount of configured keywords', 'internal-links' ) . '</strong>: ' . Statistic::getConfiguredKeywordsCount() . '</p>';
        $output .= '<p><strong>' . __( 'Last built', 'internal-links' ) . '</strong>: ' . $date->format( get_option( 'date_format' ) ) . ' ' . __( 'at', 'internal-links' ) . ' ' . $date->format( get_option( 'time_format' ) ) . '</p>';
        $output .= '<p><strong>' . __( 'Duration for construction', 'internal-links' ) . '</strong>: ' . $linkindex_info['last_update']['duration'] . ' ' . __( 'seconds', 'internal-links' );
        return $output;
    }
    
    /**
     * Generates a list of keywords
     *
     * @since  1.2.0
     * @param  array  $data         Bag of objects
     * @param  string $keyword_node The name of the keyword property in single object
     * @return string
     */
    private function getKeywordList( array $data, $keyword_node )
    {
        $render_header = [ __( 'Keyword', 'internal-links' ), __( 'Count', 'internal-links' ) ];
        $render_data = [];
        if ( !isset( $data[0] ) || !property_exists( $data[0], $keyword_node ) ) {
            return '';
        }
        foreach ( $data as $row ) {
            $keyword = $row->{$keyword_node};
            $render_data[] = [ $keyword, $row->elements ];
        }
        return $this->getList( $render_header, $render_data );
    }
    
    /**
     * Generates a list of post ids as post links
     *
     * @since  1.2.0
     * @param  array $data          Bag of objects
     * @param  int   $asset_id_node The name of the post id property in single object
     * @return string
     */
    private function getLinkList( array $data, $asset_id_node )
    {
        $render_header = [ __( 'Page', 'internal-links' ), __( 'Count', 'internal-links' ), __( 'Action', 'internal-links' ) ];
        $render_data = [];
        if ( !isset( $data[0] ) || !property_exists( $data[0], $asset_id_node ) ) {
            return '';
        }
        foreach ( $data as $row ) {
            $asset_id = (int) $row->{$asset_id_node};
            if ( $asset_id < 1 || $row->type != 'post' ) {
                continue;
            }
            $asset_data = IndexAsset::getMeta( $asset_id, 'post' );
            $edit_link = sprintf( '<a href="%s" title="' . __( 'Edit', 'internal-links' ) . '" class="tip">%s</a>', $asset_data->url_edit, '<span class="dashicons dashicons-edit"></span>' );
            $post_link = sprintf( '<a href="%s" title="' . __( 'Open', 'internal-links' ) . '" class="tip" target="_blank" rel="noopener">%s</a>', $asset_data->url, '<span class="dashicons dashicons-external"></span>' );
            $render_data[] = [ $asset_data->title, $row->elements, $post_link . $edit_link ];
        }
        return $this->getList( $render_header, $render_data );
    }
    
    /**
     * Generic method for generating a list
     *
     * @since  1.2.0
     * @param  array $header
     * @param  array $data
     * @return string
     */
    private function getList( array $header, array $data )
    {
        $output = '';
        $output .= '<table class="wp-list-table widefat striped ilj-statistic-table">';
        $output .= '<thead>';
        $output .= '<tr>';
        foreach ( $header as $title ) {
            $output .= '<th scope="col">' . $title . '</th>';
        }
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach ( $data as $row ) {
            $output .= '<tr>';
            foreach ( $row as $col ) {
                $output .= '<td>' . $col . '</td>';
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }
    
    /**
     * Returns the version including the subscription type
     *
     * @since  1.1.0
     * @return string
     */
    protected function getVersion()
    {
        return ILJ_VERSION . ' <span class="badge basic">Basic</span>';
    }

}