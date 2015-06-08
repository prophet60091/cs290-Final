<?php
session_start();//start a session if none
require "database.php";
use MyApp\database;
if(isset($_POST['username'])){

    // validate it
    $validate = new database();
    if( $validate->check_userName($_POST['username'])) {

        $response = array();
        $response['error'] = $validate->feedback;
        $response['status'] = 1;
        echo json_encode($response);

    }else{

       $response = array();
        $response['error'] = $validate->feedback;
        $response['status'] = 0;
       echo json_encode($response);

    }

}else{

    $response = array();
    $response['error'] = "No post data sent to the server";
    $response['status'] = 0;
    echo json_encode($response);

}

?>