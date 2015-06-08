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
        <form  id="login" onsubmit="checkit(); return false;" autocomplete="off">
            <label for="username">Username: </label><input type="text" name="username" id="username" class="" >
            <label for="password">Password: </label><input type="password" name="password" id="password" class="" >
            <input type="submit"  value="submit" name=submit" id="submit"  > <a href="Register" class="right">Register</a>
        </form>
    </div>

<script type="application/javascript">

    //little java script to  fire on submission. Highlights the field when they didn't enter a username
    if(localStorage.getItem('username') == 'MISSING' ){
        document.getElementById('username').value = localStorage.getItem('username');
        document.getElementById('username').className = "missedElement";

    }
    if(localStorage.getItem('attempt') === 'true'){

        document.getElementById('username').value = localStorage.getItem('username');
        document.getElementById('username').className = "missedElement";
        document.getElementById('loginError').innerHTML = "Yeahhh..., if you could go ahead an enter some data, that'd be greaaaat.";
    }

    function checkit(){

        if(document.getElementById('username').value !== ''){

            //document.getElementById('login').action = 'Logging';
            //localStorage.removeItem('username');

            var uname = document.getElementById('username').value;
            var pass = document.getElementById('password').value;
            connect( uname, pass);

        }else{
            console.log("else");
            localStorage.setItem('username', 'MISSING');
            localStorage.setItem('attempt', 'true');

        }
    }


    function connect( u, p) {

       var url = 'src/MyApp/ValidateLogin.php';
        var req= window.XMLHttpRequest ?
            new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

        req.onreadystatechange = function() {
            if (req.readyState == 4 && req.status == 200) {

                var reqObj = req.responseText;
                if(reqObj){ updateFields(reqObj);
                }

            }else{

               reqObj = '';

            }

        }


        req.open('POST', url, true);
        req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        req.send("username="+ u + "&password=" + p);


    }

    function updateFields(r){

        var ob1 = JSON.parse(r);

        document.getElementById("loginError").innerHTML = ob1.error;
        localStorage.setItem('userMusic', ob1.userMusic );
        localStorage.setItem('userName', ob1.userName );
        if(ob1.status == 1){

            location.href = "Room"
        }

    }

    function Status(error, status) {
        this.error = error;
        this.status = status;
    }

</script>