<!--
TODO Make more than one room allow room creation
TODO simplify websockets for this specific use
-->
<div class="row">
    <div class="small-3 introTitle smaller columns">Pusher | <span>Share youTube Videos with Friends</span></div>
    <div class="small-3 columns button" id="vidButton" onclick="toggleDisplay(this.id)">Video</div>
    <div class="small-3 columns button" id="libButton" onclick="toggleDisplay(this.id)">My Library</div>
    <div class="small-3 columns button" id="serButton" onclick="toggleDisplay(this.id)">Search</div>
    <div class="small-3 columns button" id="logoutButton" onclick="logout();">Logout</div>

</div>
<? echo $_SESSION["userid"]; ?>
<div class="row">
    <div class="large-3 small-12 columns" >
        <div class="msgFrame small-12 columns" id="msgFrame">
            <div id="messages"></div>
        </div>
        <div class="small-12 chatter left" >
            <form onsubmit="sendPrep(); return false;" autocomplete="off">
                <input type="text" id="msgText"  maxlength="254" >

            </form>
        </div>
</div>

<div class="row">
    <div class="large-8 small-12 columns" >
            <?php require "videoSection.php" ?>
    </div>
</div>

<script>

        var windoH = window.innerHeight || document.documentElement.clientHeight;
        var windoW = window.innerWidth || document.documentElement.clientWidth;
       document.getElementById('msgFrame').style.height = windoH * 0.65 +'px';
       document.getElementById('msgFrame').style.maxHeight = windoH * 0.65 +'px';
        document.getElementById('iframe').style.height= windoH * 0.65 + 'px';
        document.getElementById('iframe').style.height= windoH * 0.65 + 'px';
        document.getElementById('iframe').style.width= '100%';
        window.onload = function(){toggleDisplay('libButton')};

    function Transfer(url, usr, msg, req) {
        this.src = url;
        this.usr = usr;
        this.message = msg;
        this.requestType = req;

    }


    var conn = new WebSocket('ws://162.222.180.144:8080');
        conn.onopen = function(e) {
            if(conn) {
                console.log("Connection established!");
            }else{
                var messageF = document.getElementById('msgFrame');
                document.getElementById('msgTxt').innerHTML='Sorry the chat server is down at the moment';
                messageF.innerHTML = 'Sorry the chat server is down at the moment'
            }

    };

    conn.onmessage = function(e) {
        console.log(e.data);

        var reply = JSON.parse(e.data);

        // determine what type it is
            //handle empty case
            switch(reply['requestType']){

                case "message":

                    var chat = document.createElement("div");
                    chat.className = localStorage.getItem('userName');
                    var cwind = document.getElementById("messages");
                    cwind.appendChild(chat);
                    cwind.lastElementChild.innerHTML = reply['usr'] + ': '  + reply["message"];
                    updateScroll();

                break;

                case "videoUpdate":
                    var vid = document.getElementById("iframe");
                    vid.src = encodeURI(reply["src"] ) + '?rel=0&autoplay=1';
                    toggleDisplay('vidButton');




                break;
                default:
                    console.log("nothing gaind; nothing earned")
            }

    };

    // SEND CHAT MESSAGE
    function sendPrep(){
        var user = localStorage.getItem('userName');
        var chat = document.createElement("div");
        var cwind = document.getElementById("messages");
        var msg = document.getElementById("msgText").value;
        cwind.appendChild(chat);
        cwind.lastElementChild.innerHTML = user + ': ' + msg;

        console.log( ' button sending this '+ msg);
        sendMsg(msg);
//
    };
    //
    function sendMsg(val){

        document.getElementById("msgText").value = '';
        var user = localStorage.getItem('userName');
        msgObj = new Transfer("", user, val, "message");
        updateScroll();
        conn.send(JSON.stringify(msgObj));

    }

    //http://stackoverflow.com/questions/18614301/keep-overflow-div-scrolled-to-bottom-unless-user-scrolls-up
   function updateScroll(){
       var element = document.getElementById("msgFrame");
       element.scrollTop = element.scrollHeight;
   }



</script>
