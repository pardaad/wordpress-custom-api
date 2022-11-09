<?php
// you can save this file in wp-content/mu-plugin folder or copy and pate following codes into your theme's functions.php

// Register new route hooks on wordpress rest api init action
add_action('rest_api_init', 'pardaadPostsRoute');

// our function to create new route oprations
// pardaad/v1 is our route namespace
// posts is our route endpoint
// our new rest api url will be http(s)://siteurl.com/pardaad/v1/posts
// getPardaadPosts is our route callback function
function pardaadPostsRoute() {
  register_rest_route('pardaad/v1', 'posts', array(
    'methods' => 'GET',
    'callback' => 'getPardaadPosts'
  ));
}

// $data is the data that passed to the rest api url
// term is the parameter that passd to our api
function getPardaadPosts($data) {

    // create new WP Query
    // you can add each post types that you need to be in search results
    // term parameter gets data searched in our new api address
    $pardaadQuery = new WP_Query(array(
        'post_type' => array('post', 'page'),
        'post_status' => 'publish',
        's' => sanitize_text_field($data['term'])
    ));
    
    // create an empty array for each post types
    $results = array(
    'posts' => array(),
    'pages' => array()
    );
    
    while($pardaadQuery->have_posts()) {
        $pardaadQuery->the_post();

        // fill in the posts array
        if (get_post_type() == 'post') {
          array_push($results['posts'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'authorName' => get_the_author()
          ));
        }

        // fill in the pages array
        if (get_post_type() == 'page') {
          array_push($results['pages'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'authorName' => get_the_author()
          ));
        }

    }
    
    // return requested results
    return $results;

}
