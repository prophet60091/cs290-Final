/**
 * Created by Robert on 5/31/2015.
 */
function transfer(url, usr, msg, req) {
    this.src = url;
    this.usr = usr;
    this.message = msg;
    this.requestType = req;

}


var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");

    //give user an ID
};

conn.onmessage = function(e) {
    console.log(e.data);

    var reply = JSON.parse(e.data);

    // determine what type it is
    //handle empty case
    switch(reply['requestType']){

        case "message":

            var chat = document.createElement("div");
            var cwind = document.getElementById("messages");
            cwind.appendChild(chat);
            cwind.lastElementChild.innerHTML = reply["message"];
            break;
        case "videoUpdate":
            var frame = document.getElementById('videoFrame');
            var vid = document.createElement("iframe");
            vid.setAttribute("src",  encodeURI(reply["src"]));

            frame.appendChild(vid);

            break;
        default:
            console.log("nothing gaind; nothing earned")
    }

};

var button = document.getElementById("msgSend");

button.onclick = function() {
    var val = document.getElementById("msgText").value;

    console.log( ' button sending this '+ val);
    sendMsg(val);
};



function sendMsg(val){
    // build a JSON string that has user info, etc
    // adding to it's requestType will change  what it does serverside
    var msgArray = [];
    msgArray["requestType"] = "message";
    msgArray["message"] = val;
    msgObj = new transfer("", "", val, "message");

    conn.send(JSON.stringify(msgObj));
    console.log( 'sending this '+ val);
    console.log( JSON.stringify(msgObj));
}