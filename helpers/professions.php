<?php

function get_professions($professions_type, $lang)
{
    $professions_data = pods(
        $professions_type,
        [
            'limit' => -1,
            'orderby' => 't.name ASC',
            'select' => implode(', ', [
                't.id',
                $lang === 'cs' ? 't.namecz AS name' : 't.name AS name',
                't.palladio',
            ]),
        ]
    );

    $professions = [];

    while ($professions_data->fetch()) {
        $professions[] = [
            'id' => $professions_data->display('id'),
            'name' => $professions_data->display('name'),
            'palladio' => $professions_data->field('palladio') == 0 ? false : true,
        ];
    }

    return $professions;
}


add_action('wp_ajax_insert_profession', function () {
    if (!is_in_editor_role()) {
        wp_send_json_error('Not allowed', 403);
    }

    $data = decode_php_input();

    $action = $data->action;
    $id = $data->id;
    $type = $data->type;

    $data = [
        'name' => test_input($data->nameen),
        'namecz' => test_input($data->namecz),
        'palladio' => (bool) $data->palladio ? 1 : 0,
    ];

    $new_data = [
        'pod' => $type,
        'data' => $data
    ];

    if ($action == 'edit') {
        $new_data['id'] = $id;
    }

    $result_id = pods_api()->save_pod_item($new_data);

    wp_send_json_success(array_merge($data, ['id' => $result_id]));
});


add_action('wp_ajax_professions_table_data', function () {
    $professions = pods(
        test_input($_GET['type']),
        [
            'orderby' => 't.name ASC',
            'limit' => -1,
            'select' => implode(', ', [
                't.id',
                't.name AS name',
                't.namecz',
                't.palladio',
            ]),

        ]
    );

    $professions_filtered = [];

    while ($professions->fetch()) {
        $professions_filtered[] = [
            'id' => $professions->display('id'),
            'name' => $professions->display('name'),
            'namecz' => $professions->display('namecz'),
            'palladio' => $professions->field('palladio') == 0 ? false : true,
        ];
    }

    header('Content-Type: application/json');
    wp_die(json_encode($professions_filtered, JSON_UNESCAPED_UNICODE));
});


add_action('wp_ajax_professions_select_data', function () {
    $professions = get_professions($_GET['type'], $_GET['lang']);

    $short = [];
    $detailed = [];

    foreach ($professions as $pr) {
        if ($pr['palladio'] === true) {
            $short[] = [ 'value' => $pr['name'], 'id' => $pr['id'], ];
        } else {
            $detailed[] = [ 'value' => $pr['name'], 'id' => $pr['id'], ];
        }
    }

    header('Content-Type: application/json');

    echo json_encode(
        [
            'professions_short' => $short,
            'professions_detailed' => $detailed,
        ],
        JSON_UNESCAPED_UNICODE
    );

    wp_die();
});
