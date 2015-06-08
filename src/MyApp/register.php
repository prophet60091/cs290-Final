<?php
/**
 * User: Robert
 * Date: 4/27/2015
 * Time: 5:17 PM
 * * Assignment 4 login.php

 */
 ?>
    <div class="form" >
        <div id="loginError"></div>
        <form  id="Register"  >
            <label for="username">Username: </label><input type="text" name="username" id="username" maxlength="30" class="no" onkeyup="checkUser();">
            <label for="password">Password: </label><input type="password" name="password" id="password"  maxlength="30" class="no" onkeyup="checkPass();">
            <label for="password2">Password: </label><input type="password" name="password2" id="password2" maxlength="30"  class="no" onkeyup="checkPass2();">
            <input type="button"  value="Submit" name=Submit" id="submit" onclick="sendIt();">
        </form>
    </div>

<script type="application/javascript">

    function checkUser(){

        if (document.getElementById('username').value == ''){
            document.getElementById('username').className = "alert";
            document.getElementById('loginError').innerHTML = "Missing User Name!";

        } else if (document.getElementById('username').value.length < 2 || document.getElementById('username').value.length > 30){
            document.getElementById('username').className = "alert";
            document.getElementById('loginError').innerHTML = "User name is too short or too long!";

        } else{
            document.getElementById('username').className = "good";
            document.getElementById('loginError').innerHTML = "Ok";
            var u = document.getElementById('username').value;
            userCheck(u)
        }


    }

    function checkPass(){

        if (document.getElementById('password').value == ''){
            document.getElementById('password').className = "alert";
            document.getElementById('loginError').innerHTML = "Enter a Password";

        } else if (document.getElementById('password').value.length < 5 || document.getElementById('username').value.length > 30){
            document.getElementById('password').className = "alert";
            document.getElementById('loginError').innerHTML = "Password is too short or too long!";

        } else{
            document.getElementById('password').className = "good";
            document.getElementById('loginError').innerHTML = "Ok";
        }

    }

    function checkPass2(){

        if (document.getElementById('password2').value == ''){
            document.getElementById('password2').className = "alert";
            document.getElementById('loginError').innerHTML = "Enter a Password";

        } else if (document.getElementById('password2').value.length < 5 || document.getElementById('username').value.length > 30) {
            document.getElementById('password2').className = "alert";
            document.getElementById('loginError').innerHTML = "Password is too short or too long!";

        }else if(document.getElementById('password2').value !== document.getElementById('password').value){

                document.getElementById('loginError').innerHTML = "Passwords do not match!";
                document.getElementById('password2').className = "alert";

        } else{
            document.getElementById('password2').className = "good";
            document.getElementById('loginError').innerHTML = "Ok";
            return true;
        }

        return false;
    }
    function sendIt(){

        // Hurray the user entered everything we wanted

        var uname = document.getElementById('username').value;
        var pass = document.getElementById('password').value;
        console.log('sending...');
        connect(uname, pass);

    }

    //sends stuff to the server for checking
    function connect(u, p) {

       var url = 'src/MyApp/ValidateReg.php';
        var req= window.XMLHttpRequest ?
            new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

        req.onreadystatechange = function() {
            if (req.readyState == 4 && req.status == 200) {

                var reqObj = req.responseText;
                console.log(reqObj);
                if(reqObj){

                    updateFields(reqObj);

                }
            }else{
                reqObj = '';

            }
        }

        req.open('POST', url, true);
        req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        req.send("username="+ u + "&password=" + p);

    }

function userCheck(u) {

    var url = 'src/MyApp/CheckUser.php';
    var req = window.XMLHttpRequest ?
        new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {

            var reqObj = req.responseText;

            if (reqObj) {
                var ob2 = JSON.parse(reqObj);
                if (ob2.status) {
                    document.getElementById('username').className = "alert";
                    document.getElementById('loginError').innerHTML = "Name already in use!";
                }else {
                    document.getElementById('username').className = "good";
                }
            }
        } else {

            reqObj = '';

        }

    }

    req.open('POST', url, true);
    req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    req.send("username="+ u);

}
    function updateFields(r){

        var ob1 = JSON.parse(r);
        console.log(ob1);
        document.getElementById("loginError").innerHTML = ob1.error;
        localStorage.setItem('userMusic', ob1.userMusic );
        localStorage.setItem('userName', ob1.userName );

        //TODO AskFor next action as opposed to just sending them to he lobby, but it works...?
        if(ob1.status == 1){

         location.href = "Room"

        }



    }


</script>