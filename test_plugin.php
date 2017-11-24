<?php
/*
Plugin Name: Test Plugin
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: Краткое описание плагина.
Version: Номер версии плагина, например: 1.0
Author: Имя автора плагина
Author URI: http://страница_автора_плагина
*/

add_action('add_meta_boxes', 'metatest_init');
add_action('save_post', 'metatest_save');

function metatest_init()
{
    add_meta_box('metatest', 'User information',
        'metatest_showup', 'post', 'side', 'high');
}

function metatest_showup($post)
{

// получение существующих метаданных
    $data = get_user_meta($post->post_author);
    $main_data = get_userdata($post->post_author);

    //var_dump($data);
    $arr = array(
        'ID' => $post->post_author,
        'user_email' => $main_data->user_email,
        'lang' => $data['lang'][0],
        'first_name' => $data['first_name'][0],
        'last_name' => $data['last_name'][0],
        'address' => $data['address'][0],
        'phone' => $data['phone'][0],
    );

// скрытое поле с одноразовым кодом
    wp_nonce_field("metatest_action", "metatest_nonce");

// поле с метаданными

    foreach ($arr as $k => $v) {
        echo '<p>' . $k . ': <input type="text" name="' . $k . '" value="'
            . esc_attr($v) . '"/></p>';
    }

}

function metatest_save($postID)
{
    //var_dump($_POST);
    $post = get_post($postID);
    $author_id = $post->post_author;
// пришло ли поле наших данных?
    if (!isset($_POST))
        return "No data";

// проверка достоверности запроса
   if ( is_admin() && wp_verify_nonce($_POST['metatest_nonce'], "metatest_action")==1 || wp_verify_nonce($_POST['metatest_nonce'], "metatest_action") == 2){
       update_user_meta($author_id, 'lang', $_POST['lang']);
       update_user_meta($author_id, 'first_name', $_POST['first_name']);
       update_user_meta($author_id, 'last_name', $_POST['last_name']);
       update_user_meta($author_id, 'address', $_POST['address']);
       update_user_meta($author_id, 'phone', $_POST['phone']);
       wp_update_user(array(
           'ID' => $author_id,
           'user_email' => $_POST['user_email']
       ));
   }





// запись

}

?>