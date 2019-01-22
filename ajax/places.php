<?php

function list_bl_places_simple()
{
    $place_pods = pods(
        'bl_place',
        [
            'orderby'=> 't.name ASC',
            'limit' => -1
        ]
    );
    $places = [];
    $index = 0;

    while ($place_pods->fetch()) {
        $places[$index]['id'] = $place_pods->display('id');
        $places[$index]['name'] = $place_pods->display('name');
        $places[$index]['country'] = $place_pods->display('country');
        $index++;
    }

    echo json_encode(
        $places,
        JSON_UNESCAPED_UNICODE
    );
    wp_die();
}
add_action('wp_ajax_list_bl_places_simple', 'list_bl_places_simple');



function list_bl_place_single()
{
    $results = [];

    if (!array_key_exists('pods_id', $_GET)) {
        echo '404';
        wp_die();
    }

    $pod = pods('bl_place', $_GET['pods_id']);

    if (!$pod->exists()) {
        echo '404';
        wp_die();
    }

    $results['id'] = $pod->display('id');
    $results['name'] = $pod->field('name');
    $results['country'] = $pod->field('country');

    echo json_encode(
        $results,
        JSON_UNESCAPED_UNICODE
    );

    wp_die();
}

add_action('wp_ajax_list_bl_place_single', 'list_bl_place_single');


function delete_bl_place()
{
    if (!array_key_exists('pods_id', $_GET)) {
        echo '404';
        wp_die();
    }

    $user = wp_get_current_user();
    $role = (array) $user->roles;

    if (!in_array('blekastad_editor', $role) && !in_array('administrator', $role)) {
        echo '403';
        wp_die();
    }

    $pod = pods('bl_place', $_GET['pods_id']);
    $result = $pod->delete();
    echo $result;

    wp_die();
}

add_action('wp_ajax_delete_bl_place', 'delete_bl_place');
