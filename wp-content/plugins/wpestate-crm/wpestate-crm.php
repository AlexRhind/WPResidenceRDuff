<?php
/*
 *  Plugin Name: WpEstate CRM
 *  Plugin URI: https://wpestate.org
 *  Description: WpEstate Customer Relationship Management
 *  Version:     1.0
 *  Author:      wpestate
 *  Author URI:  https://wpestate.org
 *  License:     GPL2
 *  Text Domain: wpestate-crm
 *  Domain Path: /languages
 * 
*/


define('WPESTATE_CRM_URL',  plugins_url() );
define('WPESTATE_CRM_DIR_URL',  plugin_dir_url(__FILE__) );
define('WPESTATE_CRM_PATH',  plugin_dir_path(__FILE__) );
define('WPESTATE_CRM_BASE',  plugin_basename(__FILE__) );


add_action( 'wp_enqueue_scripts',       'wpestate_crm_enqueue_styles' );
add_action( 'admin_enqueue_scripts',    'wpestate_crm_enqueue_styles_admin'); 
add_action( 'plugins_loaded',           'wpestate_crm_check_plugin_functionality_loaded' ); 
register_activation_hook( __FILE__, 'wpestate_crm_functionality' );
register_deactivation_hook( __FILE__, 'wpestate_crm_deactivate' );

function wpestate_crm_enqueue_styles(){
    
}

function wpestate_crm_enqueue_styles_admin(){
    wp_enqueue_script('wpestate_crm_script',WPESTATE_CRM_DIR_URL.'js/crm_script.js', array('jquery'), '1.0', true); 
    wp_localize_script('wpestate_crm_script', 'wpestate_crm_script_vars', 
        array( 'ajaxurl'            => esc_url(admin_url('admin-ajax.php')),
                'admin_url'         =>  get_admin_url(),
                
        )
    );
    
    
    wp_enqueue_style('wpestate_crm_style',  WPESTATE_CRM_DIR_URL.'css/crm_style.css', array(), '1.0', 'all'); 
}

function wpestate_crm_check_plugin_functionality_loaded(){
    
}

function wpestate_crm_functionality(){
    
}

require_once(WPESTATE_CRM_PATH . 'post-types/leads.php');
require_once(WPESTATE_CRM_PATH . 'post-types/contacts.php');
require_once(WPESTATE_CRM_PATH . 'libs/metaboxes.php');

add_action( 'admin_menu', 'wpestate_crm_top_level_menu' );
function wpestate_crm_top_level_menu(){
        global $submenu;
        add_menu_page('WpEstate CRM','WpEstate CRM','edit_posts', 'wpestate-crm','',   WPESTATE_CRM_URL . '/wpestate-crm/images/crm.png','10');
        add_submenu_page('wpestate-crm', esc_html__('Leads','wpestate-crm'),   esc_html__('Leads','wpestate-crm'),'edit_posts','edit.php?post_type=wpestate_crm_lead','');
        add_submenu_page('wpestate-crm', esc_html__('Contacts','wpestate-crm'), esc_html__('Contacts','wpestate-crm'), 'edit_posts', 'edit.php?post_type=wpestate_crm_contact','');
        add_submenu_page('wpestate-crm', esc_html__('Add New Contact','wpestate-crm'), esc_html__('Add New Contact','wpestate-crm'), 'edit_posts', 'post-new.php?post_type=wpestate_crm_contact','');
        add_submenu_page('wpestate-crm', esc_html__('Contact Status','wpestate-crm'), esc_html__('Edit Contact Statuses','wpestate-crm'), 'edit_posts', 'edit-tags.php?taxonomy=wpestate-crm-contact-status&post_type=wpestate_crm_contact','');
      
        add_submenu_page('wpestate-crm', esc_html__('Add New Lead','wpestate-crm'), esc_html__('Add New Lead','wpestate-crm'), 'edit_posts', 'post-new.php?post_type=wpestate_crm_lead','');
        add_submenu_page('wpestate-crm', esc_html__('Lead Status','wpestate-crm'), esc_html__('Edit Lead Statuses','wpestate-crm'), 'edit_posts', 'edit-tags.php?taxonomy=wpestate-crm-lead-status&post_type=wpestate_crm_lead','');
      
        unset( $submenu['wpestate-crm'][0]);
        
}



global $contact_post_array;
$contact_post_array=array(
        'status'  => array( 
                                'type'      => 'taxonomy',
                                'source'    =>  'wpestate-crm-contact-status',
                                'label'     => esc_html__('Status','wpestate-crm'),
                                'defaults'  =>  '',
                            ) ,
        'source'  => array( 
                                'type'      => 'taxonomy',
                                'source'    =>  'wpestate-crm-contact-source',
                                'label'     => esc_html__('Source','wpestate-crm'),
                                'defaults'  =>  '',
                            ) ,
    
        'crm_first_name'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('Full Name','wpestate-crm'),
                                'defaults'  =>  '',
                            ) ,

        'crm_email'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('Email','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
        'crm_address'  => array( 
                                'type'      => 'textarea',
                                'label'     => esc_html__('Address','wpestate-crm'),
                                'defaults'  =>  '',
                            ),                
        'crm_city'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('City','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
        'crm_county'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('County','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
        'crm_state'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('State','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
        'crm_mobile'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('Mobile','wpestate-crm'),
                                'defaults'  =>  '',
                            ),                        
        'crm_phone'  => array( 
                                'type'      => 'input',
                                'label'     => esc_html__('Phone','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
                            
        'crm_private'=> array( 
                                'type'      => 'textarea',
                                'label'     => esc_html__('Notes','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
    
);




$leads_post_array=array(
        'crm_handler'  => array( 
                                'type'      => 'post_type',
                                'source'    =>  array('estate_agent','estate_agency','estate_developer'),
                                'label'     => esc_html__('Agent in Charge','wpestate-crm'),
                                'defaults'  =>  '',
                            ), 
                            
    
        'crm_private'=> array( 
                'type'      => 'textarea',
                'label'     => esc_html__('Notes','wpestate-crm'),
                'defaults'  =>  '',
            ), 
    
        'crm_lead_permalink'=> array( 
                'type'      => 'input',
                'label'     => esc_html__('Sent From','wpestate-crm'),
                'defaults'  =>  '',
            ), 
);


//crm_first_name

function wpestate_crm_return_tax($postID,$tax){
    $return='';   
    $return.= '<div class="crm_contact_status">';
          
            $return.=  esc_html__('Status','wpestate-crm').': ';
            $terms  =   get_the_terms($postID,$tax);
            $status =   '';
            if(is_array($terms)){
                foreach($terms as $term){
                    $status.=$term->name.',';
                }
                $status= rtrim($status,',');
            }
            if($status==''){
                $status=' - ';
            }
            $return.= $status;
        $return.='</div>';
        return $return;
}