<?php
session_start();//start a session if none
require_once "database.php";
use MyApp\database;
if(isset($_POST['username']) && isset($_POST['password'])){

    // validate it
    $validate = new database();
    if( $validate->login($_POST['username'], $_POST['password'])) {

        $_SESSION['validated']= true;
        $_SESSION['on']= 2;
        $_SESSION['userid']= $validate->userid;
        $_SESSION['userMusic'] = $validate->userMusic;
        $_SESSION['feedback'] = $validate->feedback;
        $response = array();
        $response['error'] = $validate->feedback;
        $response['status'] = 1;
        $response['userName'] = $validate->userName;
        $response['userMusic'] = $validate->userMusic;
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