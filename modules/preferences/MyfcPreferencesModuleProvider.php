<?php

function myfc_provide_preferences()
{
    wp_enqueue_media();

    $request = $_REQUEST;
    $router = myfanclub\modules\preferences\MyfcPreferencesModuleRouter::getInstance();
    $router->route($request);
}

add_action('wp_ajax_myfc_get_image', 'myfc_get_image');
function myfc_get_image()
{
    if (isset($_GET['id'])) {
        $image = wp_get_attachment_image(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), 'medium', false, array('id' => 'myfc-preview-image'));
        $data = array(
            'image' => $image,
        );
        wp_send_json_success($data);
    } else {
        wp_send_json_error();
    }
}
