<?php
// generate random string
function getRandomString($n)
{
    $n = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

// create posts for every two digit aplhabet combination
function create_posts(){
    foreach(range('A','Z') as $x){
        foreach(range('A','Z') as $y){
            for ($z = 1; $z <= 10; $z++) {
                $post_args = array(
                    'post_title' => $x.$y.getRandomString(5),
                    'post_type' => 'perpetualregister',
                    'post_status' => 'publish',
                );
                $post_id = wp_insert_post($post_args);
                // Update meta data
                var_dump( update_field( 'field_prs_id' , getRandomString(10), $post_id ) );
                update_field( 'field_prs_lifestats' , '('.getRandomString(4).' , '.getRandomString(4).')', $post_id );
                update_field( 'field_prs_sort' , getRandomString(5), $post_id );
            }
        }
    }
}
// sort perpetaul register posts by letters
function sort_perpetual_register(){
    $posts = get_posts( array(
        'post_type' => 'perpetualregister',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ) );
    $return = [];
    if ( $posts ) {
        foreach( $posts as $post ){
            $title = $post->post_title;
            $title_upp = strtoupper( $post->post_title );
            $single_str = substr( $title_upp , 0 , 1 );
            $double_str = substr( $title_upp , 0 , 2 );
            $return[ $single_str ][ $double_str ][] = array(
                'title' => $title,
                'lifestat' => get_field( 'field_prs_lifestats' ,$post->ID ),
            );
        }
    }
    return $return;
}

// Sql operation to delete all perpetualregister posts with meta data 
function delete_data(){
    global $wpdb;

    $sql_query = 'DELETE a,b
    FROM `'.$wpdb->prefix.'posts` a
    LEFT JOIN `'.$wpdb->prefix.'postmeta` b
        ON (a.ID = b.post_id)
    WHERE a.post_type = "perpetualregister"';
    $result = $wpdb->query( $sql_query );
    return true;
}