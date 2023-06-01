<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $post = new Post($db);

    // Blog post query
    $result = $post->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any post
    if ($num > 0) {
        // Post array
        $posts_arr = array();
        $posts_arr['data'] =array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);      // allows var such as $id and $title to be used in the next part

            $post_item = array(
                'id' => $id,
                'title' => $title,
                'body' => html_entity_decode($body),
                'author' => $author,
                'category_id' => $category_id,
                'category_name' => $category_name
            );

            // Push to "data"
            array_push($posts_arr['data'], $post_item);
        }

        // Turn to JSON & output
        echo json_encode($posts_arr);
    }
    else {
        // No post
        echo json_encode(
            array('message' => 'No Post Found')
        );
    }

?>