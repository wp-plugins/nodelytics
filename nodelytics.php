<?php
/*
Plugin Name: Nodelytics
Plugin URI: http://www.strx.it/2011/04/nodelytics-node-js-real-time-stats/
Description: Add Nodelytics code to enable real time statistics for your site
Version: 1.1.0
Author: Strx
Author URI: http://www.strx.it
License: GPL2
*/

function nodelytics_defaults(){
    return array(
  		//'findids'=>0
    );
}

function nodelytics_get_options(){
    $opts=nodelytics_defaults();
    foreach($opts as $o=>$v){
        $opts[$o]=get_option('nodelytics-'.$o, $v);
    }
    return $opts;
}

function nodelytics_start(){
  $opts=nodelytics_get_options();
	if (!is_user_logged_in()){
		//$opts['outline']=0;
	}
  //echo '<img src="http://nodelytics.strx.it/gif" style="position:fixed; right:0; bottom:0; border:0; margin:0; padding:0; opacity:0;">';
  echo '<script type="text/javascript" src="http://nodelytics.strx.it/script"></script>';
}

function nodelytics_settings_menu(){
    add_options_page(__('Nodelytics','strx'), __('Nodelytics','strx'), 'manage_options', 'nodelytics_settings', 'nodelytics_settings');
}

function nodelytics_settings(){
    if (!current_user_can('manage_options')){ wp_die( __('You do not have sufficient permissions to access this page.') ); }

    //Previous Saved Values or Default Ones
    $opts=nodelytics_get_options();

    //Update options
    if( isset($_POST[ 'nodelytics-update' ]) ) {
        foreach($opts as $o=>$v){
            $opts[$o]=$_POST['nodelytics-'.$o];
            update_option( 'nodelytics-'.$o, $opts[$o] );
        }
    }

    extract($opts);

	  $rv ='<div class="wrap">';
    $rv.=' <h2>Nodelytics real time stats</h2>';
    $rv.=' You can also access you Nodelytics at ';
    
    $home=get_bloginfo('home');
    $urlInfo=parse_url($home);
    $host=$urlInfo['host'];
    
    $nlurl="http://nodelytics.strx.it/stat/$host";

	  $rv.='<a target="_blank" href="'.$nlurl.'">'.$nlurl.'</a>';
	  $rv.='<iframe style="width: 100%; height: 560px; border:1px solid #aaa;" src="'.$nlurl.'" alt="Nodelytics for '.$host.'" title="Nodelytics for '.$host.'"></iframe>';
    $rv.='</div>';

    echo $rv;
}


//Registering scripts and plugin hooks
if ( !is_admin() ) {
    add_action('wp_footer','nodelytics_start');
}else{
    add_action('admin_menu', 'nodelytics_settings_menu');
}


