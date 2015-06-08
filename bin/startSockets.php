<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 5/14/2015
 * Time: 12:51 PM
 */
use Ratchet\Server\IoServer;
    require dirname(__DIR__) . '/vendor/autoload.php';

   // $loop   = React\EventLoop\Factory::create();
    $vid = new MyApp\Chat;


    // Set up our WebSocket server for clients wanting real-time updates
    //$webSock = new React\Socket\Server($loop);
    //$webSock->listen(8080, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
    $webServer = IoServer::factory(
        new Ratchet\Http\HttpServer(
            new Ratchet\WebSocket\WsServer(

                    $vid

            )
        ),
        8080
    );

//$server = IoServer::factory(
//    new HttpServer(
//        new WsServer(
//            new Chat()
//        )
//    ),
//    8080
//);

$webServer->run();





?>