"use strict";

var queryDict = {};
location.search.substring(1).split("&").forEach(function(item) {queryDict[item.split("=")[0]] = item.split("=")[1]});
let toid = queryDict["id"];

let fpmsg = document.getElementById('pmsg');
let convo = document.getElementById('convo');
convo.scrollTo(0, convo.scrollHeight);

document.getElementById('fpm').setAttribute('action', "javascript:void(0);");

document.getElementById('sendpm').addEventListener('click', function(e){
    e.preventDefault();
    if(fpmsg.value.length > 0){
        //document.getElementById('heart').firstElementChild.innerHTML += '<span>'  + 'me' + '<span class="f3">' + '(now):' + '</span>' + '<span>' + fpmsg.value + '</span>' + '</span><br>';
        $.ajax({
            type: "POST",
            data: {pmsg: fpmsg.value,
                recipient: toid},
            url: "php/pmed_ajax.php",
            success: function(echo){
                if (echo != '0') console.log('pmed_ajax.php failure: ' + echo);
            }
        });
        refreshMsg();
        
        convo = document.getElementById('convo');
        convo.scrollTo(0, convo.scrollHeight);

        fpmsg.value = '';
    }
});

function refreshMsg(){
    $.ajax({type: "POST",
     data:{to: toid,},
      url: "php/convo_ajax.php",
      success: function(echo){
        document.getElementById('heart').firstElementChild.innerHTML = echo;
      }
    });
};

window.setInterval(refreshMsg, 2000);