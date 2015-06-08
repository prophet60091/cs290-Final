<?php
session_start();//start a session if none
require "database.php";
use MyApp\database;
if(isset($_POST['username']) && isset($_POST['password'])){

    // validate it
    $validReg = new database();
    if( $validReg->registration($_POST['username'], $_POST['password'])) {

        $_SESSION['validated']= true;
        $_SESSION['on']= 2;
        $_SESSION['userid']= $validReg->userid;
        $_SESSION['userMusic'] = $validReg->userMusic;
        $_SESSION['feedback'] = $validReg->feedback;
        $response['error'] = $validReg->feedback;
        $response['status'] = 1;
        $response['userName'] = $validReg->userName;
        $response['userMusic'] = $validReg->userMusic;
        echo json_encode($response);

    }else{

       $response = array();
        $response['error'] = $validReg->feedback;
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