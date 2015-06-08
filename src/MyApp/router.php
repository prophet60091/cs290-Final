<?php
namespace MyApp;
session_start();//start a session if none
require_once "database.php";
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 6/1/2015
 * Time: 11:04 AM
 */
//todo move everything except the router to unservable directories
$remote = "/~jackrobe/cs290-Final/";
$url = parse_url($_SERVER['REQUEST_URI']);

class Routing{

    function __construct($rout)
    {

        date_default_timezone_set('UTC');
        $this->title = '';
        $this->fire($rout);
        $this->baseurl = "/~jackrobe/cs290-Final/";
        $this->headerText = '';
        $this->alertText = '';

    }
    public function fire($method)
    {
        if (method_exists($this, $method))
        {
            return $this->$method();
        }
        else
        {
            $method = 'Def';
            return @$this->$method();

        }
    }

    public function Def(){

        $this->alertText = '';
        $this->title = "Login Page";
        require "html-header.php";
        if(!isset($_SESSION['validated']) ) {
            require "login.php";
        }else{

            //send em to the lobby then.
            $this->fire('Lobby');


        }
        require "footer.php";

    }

    //route lobby
    function Lobby(){

        if(isset($_SESSION['validated']) && $_SESSION['validated'] === true && isset($_SESSION['userid']) ) {
            $this->title = "Lobby";
            require "html-header.php";
            echo "you're in the lobby now!";
            require "footer.php";
        }else{

            $this->alertText = "No Access - You are not logged in.";
            Routing::fire('Login');


        }

    }

    // route login
    //
 function Login(){

         $this->alertText = '';
         $this->title = "Login Page";
        require "html-header.php";
      if(!isset($_SESSION['validated']) ) {
         require "login.php";
     }else{

          //TODO change when user is loggedin



      }
        require "footer.php";

    }

    function Logout(){


            $_SESSION['validated'] = 0;
            $this->alertText = 'Logged Out';
            $this->title= 'Logged Out';
            $this->headerText= 'Thanks for stopping by!';
            require "html-header.php";
            require 'logout.php';
            require "footer.php";


    }

    function Register(){

        $this->title = "Registration";
        require "html-header.php";
        require "register.php";
        require "footer.php";

    }


    //Route Room
    function Room(){

        if(isset($_SESSION['validated']) && $_SESSION['validated'] === true && isset($_SESSION['userid']) ) {
            $this->title = "Room";
            require "html-header.php";
            require 'room.php';
            require "footer.php";
        }else{

            session_destroy();
            $this->alertText = "No Access - You are not logged in.";
            Routing::fire('Login');


        }

    }

}

// Set functions to be used
$functions[$remote.'Register'] = 'Register';
$functions[$remote . 'Login']    = 'Login';
$functions[$remote . 'Logout']    = 'Logout';
//TODO add logout;
//TODO add musicedit?
$functions[$remote .'']    = 'Def';
$functions[$remote .'Lobby']    = 'Lobby';
$functions[$remote . 'Room']    = 'Room';

$start =  New Routing($functions[$url['path']]);

?>