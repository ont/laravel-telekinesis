<?php

/**
 * Your package routes would go here
 */

Route::post(
    Config::get('telekinesis.route_name'),
    'Ont\Telekinesis\MainController@index'
);
