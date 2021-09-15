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
window.requestAnimFrame = function(){
    return (
        window.requestAnimationFrame       || 
        window.webkitRequestAnimationFrame || 
        window.mozRequestAnimationFrame    || 
        window.oRequestAnimationFrame      || 
        window.msRequestAnimationFrame     || 
        function(callback){
            window.setTimeout(callback, 1000 / 60);
        }
    );
}();

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

function togglepass(id){
    let pass = document.getElementById(id);

    if(pass.type === "password") pass.type = "text";
    else pass.type = "password";
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

/*
    SCROLL LINE EFFECT
*/
function getVerticalScrollPercentage(elm){
    let p = elm.parentNode;
    return (elm.scrollTop || p.scrollTop) / (p.scrollHeight - p.clientHeight);
}

let line = document.getElementById('line');
line.style.top = 0;

document.addEventListener('scroll', function(){ 
    let pos = (0.997)*getVerticalScrollPercentage(document.body);   //because if line is at 100% it is hidden,
    //and browsers hide line at different percents
    let [a,b,c] = [0,0,0];

    if(pos < 1/6){      //red -> yellow
        a = 255;
        b = (pos - 0)*6*255;
        c = 0;
    }
    else if(pos >= 1/6 && pos < 2/6){   //yellow -> green
        a = 255 - (pos - 1/6)*6*255;
        b = 255;
        c = 0;
    }
    else if(pos >= 2/6 && pos < 3/6){   //green -> sky
        a = 0;
        b = 255;
        c = (pos - 2/6)*6*255;
    }
    else if(pos >= 3/6 && pos < 4/6){   //sky -> blue
        a = 0;
        b = 255 - (pos - 3/6)*6*255;
        c = 255;
    }
    else if(pos >= 4/6 && pos < 5/6){   //blue -> pink
        a = (pos - 4/6)*6*255;
        b = 0;
        c = 255;
    }
    else if(pos >= 5/6 && pos <= 6/6){  //pink -> red
        a = 255;
        b = 0;
        c = 255 - (pos - 5/6)*6*255;
    }

    line.style.backgroundColor = 'rgb(' + a + ',' + b + ',' + c + ')';
    line.style.top = '' + pos*100 + '%';
});

/*
    CURSOR COLOR EFFECT
*/
let canvas = document.createElement('canvas');
let cxt = canvas.getContext('2d');

canvas.height = 17;
canvas.width = 12;

var [r, g, b] = [255, 0, 0];
var [i, j, k] = [0, 0, 0];

document.addEventListener('mousemove', function(){
    //get cursor bg color
    if(r == 255 && g == 0 && b == 0){     //red
        i = 0;
        j = 1;
        k = 0;
    }
    else if(r == 255 && g == 255 && b == 0){    //yellow
        i = -1;
        j = 0;
        k = 0;
    }
    else if(r == 0 && g == 255 && b == 0){    //green
        i = 0;
        j = 0;
        k = 1;
    }
    else if(r == 0 && g == 255 && b == 255){    //cyan
        i = 0;
        j = -1;
        k = 0;
    }
    else if(r == 0 && g == 0 && b == 255){    //blue
        i = 1;
        j = 0;
        k = 0;
    }
    else if(r == 255 && g == 0 && b == 255){    //pink
        i = 0;
        j = 0;
        k = -1;
    }

    r += i;
    g += j;
    b += k;

    cxt.fillStyle = 'rgb(' + r + ',' + g + ',' + b + ')';

    //draw cursor
    cxt.lineWidth = 1;
    cxt.strokeStyle = 'black';
    cxt.lineTo(0,16);
    cxt.lineTo(1,16);
    cxt.lineTo(5,12);
    cxt.lineTo(11,12);
    cxt.lineTo(11,11);
    cxt.lineTo(0,0);
    cxt.stroke();
    cxt.fill()

    document.body.style.cursor = 'url(' + canvas.toDataURL() + '), auto';
});

/*
    SYN FONT COLOR EFFECT
*/
var [ar, ag, ab] = [255, 0, 0];
var [ai, aj, ak] = [0, 0, 0];

let syn = document.querySelectorAll('a:not(.nsyn)');

function drawsyn(){
    for(let x = 0; x < syn.length; x++){
        syn[x].style.backgroundImage = `linear-gradient(90deg, rgb(${ar}, ${ag}, ${ab}), rgb(${ab}, ${ar}, ${ag}), rgb(${ag}, ${ab}, ${ar}))`;
    }
    
    if(ar == 255 && ag == 0 && ab == 0){     //red
        ai = 0;
        aj = 1;
        ak = 0;
    }
    else if(ar == 255 && ag == 255 && ab == 0){    //yellow
        ai = -1;
        aj = 0;
        ak = 0;
    }
    else if(ar == 0 && ag == 255 && ab == 0){    //green
        ai = 0;
        aj = 0;
        ak = 1;
    }
    else if(ar == 0 && ag == 255 && ab == 255){    //cyan
        ai = 0;
        aj = -1;
        ak = 0;
    }
    else if(ar == 0 && ag == 0 && ab == 255){    //blue
        ai = 1;
        aj = 0;
        ak = 0;
    }
    else if(ar == 255 && ag == 0 && ab == 255){    //pink
        ai = 0;
        aj = 0;
        ak = -1;
    }

    ar += ai;
    ag += aj;
    ab += ak;

    window.requestAnimFrame(drawsyn);
}
window.requestAnimFrame(drawsyn);

/*
    CUBE
*/
document.addEventListener('mousemove', function(e){
    document.getElementById('cubeicle').style.transform = "rotateX(" + e.clientX/window.innerWidth*360 + "deg) rotateY("
    + e.clientY/window.innerHeight*360 + "deg)";
});

/* SEARCH */
let sb = document.getElementById('searchb');
sb.disabled = true;

document.getElementById('searcht').addEventListener('input', function(){
    sb.removeAttribute('disabled');
});