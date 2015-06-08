<?php
namespace MyApp;
use mysqli;
class database
{
//TODO separate out the login from regi functions and the db connection.
    function __construct()
    {

        date_default_timezone_set('UTC');
        $this->usr = 'jackrobe-db';
        $this->db = 'jackrobe-db';
        $this->host = 'oniddb.cws.oregonstate.edu';
        $this->password = 'jSYd8HEWn4rUmsff';
        $this->success = '';
        $this->feedback = '';
        $this->userid = '';
        $this->userMusic = '';
        $this->userName = '';
        $this->ms = new mysqli($this->host, $this->usr, $this->password, $this->db);

        if ($this->ms->connect_error) {
            $this->feedback = $this->ms->error;
            return false;
        }
        return true;
    }

    function __destruct()
    {
        //print "Destroying " . $this->usr . "\n";

    }

    function check_userName(&$usr)
    {
        $this->feedback = "";
        //bind
        if ($st = $this->ms->prepare("SELECT uname FROM user WHERE uname=? ")) {
            $st->bind_param("s", $usr);
        }else {
            $this->feedback .= "No Biding";
        }
        //execute
        if (!$st->execute()) {
            $st->close();
            $this->feedback .= "No execution";
            //fetch
        } else {
            // check if it's there
            if (!$st->fetch()) {
                $this->feedback = "User Name does not exist - try again";
                $st->close();

            } else {
                return true;
            }
        }
        return false;
    }


    //````````````````````````````````````````````````````````````````
    //
    //
    function login(&$usr, &$pass)
    {

        //md5 the password
        $hashedPass = sha1($pass);
        //check u name
        if ($this->check_userName($usr)) {

            // Now check for both pass and user
            //bind
            if ($st = $this->ms->prepare("SELECT * FROM user WHERE uname=? AND pass=? ")) {
                $st->bind_param("ss", $usr, $hashedPass);
            } else {
                $this->feedback = "Failed Binding";

            }

            //execute
            $st->execute();
            $result = $st->get_result();
            if ($result->num_rows == 1) {

                //get some info
                while ($row = $result->fetch_assoc()) {
                    $this->userid = $row['id'];
                    $this->userMusic = $row['list'];
                    $this->userName = $row['uname'];
                }

                $this->feedback = "Login Successful! for user " . $this->userid;
                $st->close();
                return true;

            } else {

                $this->feedback = "Password is incorrect - try again";

            }
            $st->close();
        }

        return false;
    }





    //````````````````````````````````````````````````````````````````
    // retunred teh user id from the user name
    //  @param userName
    function get_uid(&$usr)
    {

        //check u name
        if ($this->check_userName($usr)) {

            // Now check for both pass and user
            //bind
            if ($st = $this->ms->prepare("SELECT id FROM user WHERE uname=?")) {
                $st->bind_param("s", $usr);
            } else {
                $this->feedback = "Failed Binding";
                return false;
            }

            //execute
            $st->execute();
            $result = $st->get_result();
            if ($result->num_rows == 1) {

                //get some info
                while ($row = $result->fetch_assoc()) {
                    $this->userid = $row['id'];
                }
                $this->feedback = "successful-check userName";
                $st->close();
                return true;

            } else {

                $this->feedback = "Could not execute";
                $st->close();
                return false;
            }

        }

        return false;
    }

    //````````````````````````````````````````````````````````````````
    //REGISTRATION
    //@ param username and a password - strings
    //
    function registration(&$usr, &$pass)
    {

        //md5 the password
        $hashedPass = sha1($pass);

        if ($this->check_userName($usr)) {
            $this->feedback = "User Already exists - try another name";
            return false;
        }
        // Now add both pass and user
        //bind
        if ($st = $this->ms->prepare("INSERT INTO user VALUES (NULL, ?, ?, NULL )")){
            $st->bind_param("ss", $usr, $hashedPass);
        }else{
            $this->feedback = 'Failed binding';

        }
        //execute
        if ($st->execute()) {

            $this->userid = $this->get_uid($usr);
            $this->feedback = "Successfully Registered";
            $st->close();
            return true;

        } else {
            $st->close();
            $this->feedback = $this->ms->error;
            $this->feedback = "Registeration Failed";
            return false;
        }

    }

    //````````````````````````````````````````````````````````````````
    //SAVE USERS LIST
    //@ param list - strings
    //
    function saveList($list)
    {

        // Now add both pass and user
        //bind
        if ($st = $this->ms->prepare("UPDATE user SET list =? WHERE id=?")){
            $st->bind_param("si", $list, $_SESSION['userid'] );

        }else {
            $this->feedback = "Failed at binding params";
            return false;
        }

        //execute
        if ($st->execute()) {
            if($st->affected_rows == 1){

                $this->userMusic = $list;
                $this->feedback = "Successfully Updated List";
                $st->close();
                return true;
            }else{

                $this->feedback = "Something went wrong no rows updated for user- " . $_SESSION['userid'];
                $st->close();
                return false;
            }

        } else {
            $st->close();
            $this->feedback = "Something went wrong in execution when updating your list";
            return false;
        }

    }

}

   ?>