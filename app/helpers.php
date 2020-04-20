<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function active_nav_item($nav, $custom_actions = [])
{
    $actions = ['index', 'show', 'create', 'edit'];
    $actions = array_merge($actions, $custom_actions);

    foreach($actions as $action){
        if(if_route($nav . '.' . $action)){
            return true;
        }
    }
    return false;
}

function sanitize($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function canadian_provinces()
{
    return [
        'BC' => 'British Columbia',
        'AB' => 'Alberta',
        'SK' => 'Saskatchewan',
        'MB' => 'Manitoba',
        'ON' => 'Ontario',
        'QC' => 'Quebec',
        'NB' => 'New Brunswick',
        'NS' => 'Nova Scotia',
        'PE' => 'Prince Edward Island',
        'NL' => 'Newfoundland and Labrador',
        'YT' => 'Yukon',
        'NT' => 'Northwest Territories',
        'NU' => 'Nunavut',
    ];
}
