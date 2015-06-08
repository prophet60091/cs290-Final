<!-- This begins the video section of the Room.php -->

<!-- video frame -->
<div class="row">
    <div class="small-12 flex-video" id="videoFrame">
        <iframe id="iframe"  src="" allowfullscreen></iframe>
    </div>

<!-- library -->
    <div class="small-12" id="videoLibrary">
        <h2 id="subhead"></h2> <div id="status" ></div>
            <form class="addVideo" id="addVideo" onsubmit="makeRequest(); return false;">
                <div class="row collapse">
                    <div class="small-9 columns"><input type="text" id="term"></div>
                    <div class="small-3 columns"><button class ="postfix radius button" type="submit" value="submit" >Search</button></div>
                </div>
            </form>

        <div class="userLib">

            <ul id="libList" class="small-block-grid-3"></ul>
        </div>

    </div>
</div>
<script>

    //TODO this funciton should allow displaying of portions of the list at a time scroll to update list
    //TODO use scrollling to load see codepn http://codepen.io/prophet60091/pen/YXVqQZ
    //TODO display nothing if there are no videos!!
    //TODO make a function to sample videos before sending
    //TODO disallow play while something is playing

// Function toggels the display of the main content depending on what is clicked
// @param element (the id of the button pressed)
function toggleDisplay(element){

    switch (element){

        case 'vidButton'  :
            document.getElementById('videoFrame').style.display = '';

            document.getElementById('videoLibrary').style.display = 'none';
            document.getElementById('addVideo').style.display = 'none';
            document.getElementById('videoFrame').style.active = '';
            break;
        case 'libButton'  :

            document.getElementById('videoFrame').style.display = 'none';
            document.getElementById('videoLibrary').style.display  = '';
            document.getElementById('addVideo').style.display  = 'none';
            document.getElementById('subhead').innerHTML  = 'Your: Library:';
            dispUsrLibrary();

           break;
        case 'serButton'  :
            document.getElementById('videoFrame').style.display = 'none';
            document.getElementById('videoLibrary').style.display = '';
            document.getElementById('addVideo').style.display = '';
            document.getElementById('subhead').innerHTML  = 'Search for Videos to Add:';
            if(lastSearch) displayLib(lastSearch);
            break;
        default :
            console.log('no soup for you!' + element);
    }
}

//made to hold the result of a query to youtube, and also
//what we keep in the users userMusic
// it will be the obj to update the library etc.
function VideoObj(id, a, t, i, l, ln, f){
    this.id = id;
    this.author = a;
    this.title = t;
    this.img = i;
    this.link = l;
    this.lgth = ln;
    this.format = f;

}

    //DISPLAY
    // displays a library
    // @param an array of VideoObj's
    // @param dType what type of display is it, search or library
    function displayLib(vidObj, dType){

        Object.keys(vidObj).forEach(function (key) {

            //rows
            var row = document.createElement("li");
            row.id = vidObj[key].id;
            //links

            //everything comes in on the title. split it up
            //generic albeit
            var str = vidObj[key].title.split('-');

            //youtube video link
            var vurl = vidObj[key].link;

            //container
            var itemCon = document.createElement("div");
            row.appendChild(itemCon);
            itemCon.className = "row video-row";

            var imgCon = document.createElement("div");
            //row.appendChild(itemCon);
            imgCon.className = "class small-4 columns";

            var img = document.createElement("img");
            img.src = vidObj[key].img;

            var text = document.createElement("div");
            text.className = "video-texts small-6 columns";

            var artist_display = document.createElement("span");
            artist_display.innerHTML = '<strong>Artist: </strong> ';
            artist_display.innerHTML += str[0];
            artist_display.id= 'artist';

            var duration_display = document.createElement("span");
            duration_display.innerHTML =  '<strong>Length: </strong> ';
            duration_display.innerHTML += vidObj[key].lgth;
            duration_display.id= 'rLength';

            var title_display = document.createElement("span");
            title_display.innerHTML =  '<strong>Title:</strong> ';
            title_display.innerHTML +=   vidObj[key].title;
            title_display.id= 'title';

            text.appendChild(artist_display);
            text.appendChild(title_display);
            text.appendChild(duration_display);

            //Add some buttons display dependant (eichter search or library)
            var buttons = videoFun(vidObj[key], dType);
            buttons.className= 'video-functions small-2 columns';
            itemCon.appendChild(imgCon);
            imgCon.appendChild(img);
            itemCon.appendChild(text);
            itemCon.appendChild(buttons);

            var list = document.getElementById('libList');
            list.appendChild(row);

        });

    }

//Displays the user's Library from local storage
//calls displayLib on the users local storage  userMusic
//
function dispUsrLibrary() {
    //reset the area
    document.getElementById('libList').innerHTML = '';
    var lo = JSON.parse(localStorage.getItem('userMusic'));
    if(lo) {
        displayLib(lo, 'library');
    }else{

    }
}
// plays a video
//sends it to everyone
//@param link (a link -for now only YT video links will do anything)
function playVideo(e){
    var user = localStorage.getItem('userName');
    sendMsg('Now Playing :' + e.title);
    //TODO open thas back up when its all together
    msgObj = new Transfer(e.link, user, 'NOW HEAR THIS', "videoUpdate");

    var vid = document.getElementById("iframe");
    toggleDisplay('vidButton');

    vid.src = encodeURI(e.link ) + '?rel=0&autoplay=1';

    conn.send(JSON.stringify(msgObj));
}

//adds a video to the user library
// used as a helper function inside OR makeRequest
//@param e (a id of a single VideoObj)
//@param array (an array of VideoObj)
function addVideo(e){

    var  newMusic = [];
    var usrM = localStorage.getItem('userMusic');

    //hide what was just added
    document.getElementById(e.id).style.display = 'none';

    if (usrM !== 'null' && usrM !== '' && usrM !== null) {
        newMusic = JSON.parse(usrM);
        newMusic.unshift(e);
        localStorage.setItem('userMusic', JSON.stringify(newMusic));
        updateServer();
    } else {
        newMusic.unshift(e);
        localStorage.setItem('userMusic', JSON.stringify(newMusic));
        updateServer();
    }

}

function removeVid(id) {
    var usrM = localStorage.getItem('userMusic');

    if (usrM !== 'null' && usrM !== '') {
        array = JSON.parse(usrM);

        var lookup = {};
        var i;
        for ( i = 0, len = array.length; i < len; i++) {
            lookup[array[i].id] = array[i];
        }

        if (lookup[id]) {
             //delete it
            array.splice(i-1, 1);
            //in case we get down to the last element
            if(array.length == 0){
                localStorage.setItem('userMusic', 'null');
            }else {

                localStorage.setItem('userMusic', JSON.stringify(array));
            }
            //hide what was removed
            document.getElementById(id).style.display = 'none';

            setTimeout(function() { updateServer() }, 3000);

        }else{
            document.getElementById("status").innerHTML = 'Nothing to Remove';
        }


    }else{
        document.getElementById("status").innerHTML = 'Library is empty';
    }
}



//Send the updaed userList locally to the server for storage
function updateServer(){

    var url = 'src/MyApp/StoreList.php';
    var req= window.XMLHttpRequest ?
        new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

    req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200) {

            var reqObj = req.responseText;
            if(reqObj){
                //update teh page information
                var ob1 = JSON.parse(reqObj);

                document.getElementById("status").innerHTML = ob1.feedback;
                setTimeout(function() {  document.getElementById("status").innerHTML = '' }, 4000);

            }

        }else{

            reqObj = '';

        }

    }


    req.open('POST', url, true);
    req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    req.send("userMusic="+ localStorage.getItem('userMusic'));

}

//helper function to display various links depending on the type of list being displayed
function videoFun(vidObj, dispType){
    var display = document.createElement('div'); //what we're going to append in the display

    switch(dispType){

        case 'search':
            var addLink = document.createElement('a');
                addLink.onclick = function() {addVideo(vidObj)} ;
            var addImg = document.createElement('img');
                addImg.src = 'src/MyApp/css/img/add.png';

            addLink.appendChild(addImg);
            display.appendChild(addLink);
            //TODO if its already in the Library hide it
           // var lookup = {};
            //for (var i = 0, len = localStorage.getItem('userMusic').length; i < len; i++) {
            //    lookup[localStorage.getItem('userMusic')[i].id] = localStorage.getItem('userMusic')[i];
           // }

            //if(lookup[vidObj.id] !== ''){
            //    addLink.onclick = displayNotice('That is already in your library!');
            //}


        break;
        case 'library':
            //add
            var addLink = document.createElement('a');
            addLink.onclick = function() {playVideo(vidObj)} ;
            var addImg = document.createElement('img');
            addImg.src = 'src/MyApp/css/img/play.png';
            //delete
            var delLink = document.createElement('a');
            delLink.onclick = function() {removeVid(vidObj.id)} ;
            var delImg = document.createElement('img');
            delImg.src = 'src/MyApp/css/img/delete.png';


            addLink.appendChild(addImg);
            delLink.appendChild(delImg);
            display.appendChild(addLink);
            display.appendChild(delLink);

        break;
        default:

    }

    return display;
}


function displayNotice(text){
        alert(text);

    }



///GOOGLE STUFF
//ADAPTED FROM https://google-api-javascript-client.googlecode.com/hg/samples/
// Enter the API key from the Google Develoepr Console - to handle any unauthenticated
// requests in the code.
// The provided key works for this sample only when run from
// https://google-api-javascript-client.googlecode.com/hg/samples/requestSample.html
// To use in your own application, replace this API key with your own.
var apiKey = 'AIzaSyBVeBgSFczKH60vss83aULpguw5nSLHXMs';
 var lastSearch;   // an array we want to hold on to for displaying results
function makeRequest() {
    gapi.client.setApiKey(apiKey);
    function writeResponse(resp) {
        var responseText;
        var musicArray = [];
        if (resp.error && resp.error.errors[0].debugInfo == 'QuotaState: BLOCKED') {
            responseText = 'Invalid API key provided. Please replace the "apiKey" value with your own.';
        } else {

            responseText = resp;

            //set it up for displaying
            //reset the area
            document.getElementById('libList').innerHTML = '';
            toggleDisplay('videoFrame');
            //get the good part of the response
            var lo = responseText.items;
            var baseURL = 'http://youtube.com/embed/';
            Object.keys(lo).forEach(function (key) {

                var id = lo[key].id; // the id has url id etc.
                var snpt = lo[key].snippet;  // the snippet portion of what google returnes
                //make a new object to display
                vo = new VideoObj(id.videoId, '', snpt.title, snpt.thumbnails.default.url, baseURL + id.videoId, '', id.kind);
                musicArray.push(vo);

            }) ;

            //call displayLib to show it
            displayLib(musicArray, 'search');
            lastSearch = musicArray;

        }

    }
    var term = document.getElementById('term').value;
    var restRequest = gapi.client.request({
        'path': '/youtube/v3/search',
        'params' : {'part' : 'snippet', 'q' : term,
            'videoEmbeddable' : 'true', 'videoType' : 'any', 'type': 'video', 'maxResults': '50', 'key' : apiKey
        }
    });
    restRequest.execute(writeResponse);
}
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>