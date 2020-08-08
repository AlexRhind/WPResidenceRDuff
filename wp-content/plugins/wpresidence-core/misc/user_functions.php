<?php


function wpestate_check_user_permission_on_dashboard($current_page=''){
    $current_user                   =   wp_get_current_user();
    $userID                         =   $current_user->ID;
    $user_login                     =   $current_user->user_login;
    
    if( current_user_can('administrator') ){
        return true;
    }
    
    $user_role = intval (get_user_meta( $current_user->ID, 'user_estate_role', true) );

    
    if($user_role!==0 && $user_role>1){
        return true;
    }
    
    $permissions = wpresidence_get_option('wp_estate_user_page_permission', '');
    if (empty($permissions) || $permissions=='' ){
        return true;
    }
    
    if($current_page==''){
        $current_page = basename( get_page_template() );
        $current_page = str_replace('.php', '', $current_page);
    }
    
   if( in_array($current_page, $permissions) ){
       return true;
   }else{
       return false;
   }
      
}


