<?php

use Zizaco\Entrust\EntrustFacade;
use Zizaco\Entrust\Entrust;
//use \App\Modules\Accounts\Models\Institution;
use \DB;
//use mikehaertl\wkhtmlto\Pdf;
//use Maatwebsite\Excel\Facades\Excel;
/**
 * breadcrumb()
 * The purpose of this method is to make breadcrumb interactive.
 * This method is being used in main layout.
 *
 * Note: Every developer has to add relevant breadcrumb data in the format defined in method
 * 
 */
function breadcrumb($displayName = '', $urlSlug = '') {
    // Default breadcrumb, refering to home.
    $breadcrumbLinks = array('displayName' => 'Home', 'route' => 'home');
    // Note: Every developer has to add relevant breadcrumb data in the format defined below. 
    // Standard array structure to fill in array for all possible breadcrumb info.

    $current_params = Route::current();
    if(is_null($current_params)){
        echo view('breadcrumb', compact('breadcrumbLinks'));
        return;
    }
    $current_params = $current_params->parameters();
    //     dd($current_params);
    $links = array(
        'user' => array(
            'displayName' => 'Users',
            'route' => 'user',
            'postfix' => array('home' => 'Home', 'institution_summary' => 'Administration')
        ),
        'institution_summary' => array(
            'displayName' => 'Institution',
            'route' => 'institution_summary',
            'postfix' => array('home' => 'Home', 'institution_summary' => 'Administration')
        ),
        'createInstitution' => array(
            'displayName' => !empty($edit_institute)? $instituteName: 'Institution',
            'route' => 'createInstitution',
            'postfix' => array('home' => 'Home', 'admin_panel' => 'Administration', 'institution_summary' => 'Institute')
        ),
        'roles' => array(
            'displayName' => 'Roles',
            'route' => 'roles',
            'postfix' => array('home' => 'Home', 'institution_summary' => 'Administration')
        ),
        'messaging_dashboard' => array(
            'displayName' => ucfirst($displayName),
            'route' => 'roles',
            'postfix' => array('home' => 'Home', 'messaging_dashboard' => array('label' => 'Messages', 'params' => array('type' => 'inbox')))
        ),
        'newreport-detail' => array(
            'displayName' => $displayName,
            'route' => 'newreport-detail',
            'postfix' => array('home' => 'Home', 'newreports-index' => 'Reports')
        ), 
    );
    $currentRoute = Route::currentRouteName();
    if (array_key_exists($currentRoute, $links)) {
        $breadcrumbLinks = $links[$currentRoute];
    }
    echo view('breadcrumb', compact('breadcrumbLinks'));
}