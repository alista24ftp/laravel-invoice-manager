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
