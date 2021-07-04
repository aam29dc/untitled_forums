/*
    GLOBALS
*/
"use strict";

let MYAPP = {};

MYAPP.html = document.querySelector('html');
MYAPP.theme = getCookie("theme");

if(MYAPP.theme == ""){
     setCookie("theme", "light", 30);
     MYAPP.theme = "light";
}

MYAPP.html.dataset.theme = "theme-" + MYAPP.theme;    //'theme-light';

/*  
    FUNCTIONS
*/
 
 function setCookie(cname, cvalue, exdays){
     let d = new Date();
     d.setTime(d.getTime() + exdays*24*60*60*1000);
     let expires = "expires=" + d.toUTCString();
     document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
 }

 function getCookie(cname){
     let name = cname + "=";
     let decodedCookie = decodeURIComponent(document.cookie);
     let arr = decodedCookie.split(';');
     
     for(let i = 0;i < arr.length; i++){
         let ele = arr[i];

         while(ele.charAt(0) == ' '){   // deletes white spaces
             ele = ele.substring(1);
         }
         if(ele.indexOf(name) == 0){
             return ele.substring(name.length, ele.length);
         }
     }
     return "";
}

function swaptheme(){
    let theme = getCookie("theme");

    //not set
    if(theme == ""){
         setCookie("theme", "light", 30);
         theme = "light";
    }

    //swap
    if(theme == "light"){
        MYAPP.html.dataset.theme = "theme-dark";
        setCookie("theme", "dark", 30);
    }
    else{
        MYAPP.html.dataset.theme = "theme-light";
        setCookie("theme", "light", 30);
    }
}

function swapsrc(id, first, second){
    let check = document.getElementById(id);

    if(check.src == 'http://' + location.host + '/site/' + first){
        check.src = 'http://' + location.host + '/site/' + second;
    }
    else{
        check.src = 'http://' + location.host + '/site/' + first;
    }
}

function hideelement(id){
    let ele = document.getElementById(id);
    ele.style.display = "none";
}

function input_tag(id, tag){
    let subtag = '';
    if(tag == 'a') subtag = ' href=""';

    document.getElementById(id).value += "<" + tag + subtag + "></" + tag + ">";
  }