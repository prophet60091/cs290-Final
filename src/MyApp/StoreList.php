<?php
session_start();//start a session if none
require_once "database.php";
use MyApp\database;

if(isset($_POST['userMusic']) && $_POST['userMusic']){
    $response['feedback'] = '';
    $store = new database();
    if( $store->saveList($_POST['userMusic'])){

        $response['feedback'] = $store->feedback;
        $response['userMusic'] = $store->userMusic;
        $_SESSION['userMusic'] = $store->userMusic;
        echo json_encode($response);

    }else{
        $response['feedback'] .= $store->feedback;
        $response['feedback'] .= "Could not update your list. Any changes you make will not be permanent, reason - DB";
        echo json_encode($response);
    }

}else{

    $response['feedback'] .= "Could not update your list. Any changes you make will not be permanent, reason post var";
    echo json_encode($response);
}


?>