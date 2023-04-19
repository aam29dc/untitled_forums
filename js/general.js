"use strict";
/*
    GLOBALS
*/

//const MYAPP = {};   
/*  used and first defined in index_header.php because
    firefox flickers from light <-> dark theme when on dark theme   */

MYAPP.html = document.querySelector('html');
MYAPP.theme = getCookie("theme");

if(MYAPP.theme === ""){
     setCookie("theme", "light", 30);
     MYAPP.theme = "light";
}

MYAPP.html.dataset.theme = "theme-" + MYAPP.theme;    //'theme-light';

function setCookie(cname, cvalue, exdays){
     let d = new Date();
     d.setTime(d.getTime() + exdays*24*60*60*1000);
     let expires = "expires=" + d.toUTCString();
     document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
 }

function getCookie(cname){
     const name = cname + "=";
     let decodedCookie = decodeURIComponent(document.cookie);
     let arr = decodedCookie.split(';');
     
     for(let e of arr){
         let ele = e;

         while(ele.charAt(0) === ' '){   // deletes white spaces
             ele = ele.substring(1);
         }
         if(ele.indexOf(name) === 0){
             return ele.substring(name.length, ele.length);
         }
     }
     return "";
} /* end of define from js <script> in index_header.php */

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

function swaptheme(){
    let theme = getCookie("theme");

    //not set
    if(theme === ""){
         setCookie("theme", "light", 30);
         theme = "light";
    }

    //swap
    if(theme === "light"){
        MYAPP.html.dataset.theme = "theme-dark";
        setCookie("theme", "dark", 30);
    }
    else {
        MYAPP.html.dataset.theme = "theme-light";
        setCookie("theme", "light", 30);
    }
}

function swapsrc(id, first, second){
    let check = document.getElementById(id);

    if(check.src === location.origin + first){
        check.src = location.origin + second;
    }
    else check.src = location.origin + first;
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
    if(tag === 'a') subtag = ' href=""';

    document.getElementById(id).value += "<" + tag + subtag + "></" + tag + ">";
}

function setAttributes(el, attrs){
    for(let key in attrs){
        el.setAttribute(key, attrs[key]);
    }
}

/*
    Checkerbox (light <-> dark theme button)
*/
let checkerbox = document.getElementById('checkerbox');
checkerbox.style.cursor = 'pointer';
checkerbox.addEventListener('click', function(){
    swapsrc('checkerbox','/img/logo16.png','/img/logo16inv.png');
    swaptheme();
});

/*
    SCROLL LINE EFFECT
*/
function getVerticalScrollPercentage(elm){
    const p = elm.parentNode;
    return (elm.scrollTop || p.scrollTop) / (p.scrollHeight - p.clientHeight);
}

let line = document.getElementById('line');
line.style.top = 0;

document.addEventListener('scroll', function(){ 
    const pos = (0.997)*getVerticalScrollPercentage(document.body);   //because if line is at 100% it is hidden,
    //and browsers hide line at different percents
    let [za,zb,zc] = [0,0,0];

    if(pos < 1/6){      //red -> yellow
        za = 255;
        zb = (pos - 0)*6*255;
        zc = 0;
    }
    else if(pos >= 1/6 && pos < 2/6){   //yellow -> green
        za = 255 - (pos - 1/6)*6*255;
        zb = 255;
        zc = 0;
    }
    else if(pos >= 2/6 && pos < 3/6){   //green -> sky
        za = 0;
        zb = 255;
        zc = (pos - 2/6)*6*255;
    }
    else if(pos >= 3/6 && pos < 4/6){   //sky -> blue
        za = 0;
        zb = 255 - (pos - 3/6)*6*255;
        zc = 255;
    }
    else if(pos >= 4/6 && pos < 5/6){   //blue -> pink
        za = (pos - 4/6)*6*255;
        zb = 0;
        zc = 255;
    }
    else if(pos >= 5/6 && pos <= 1){  //pink -> red
        za = 255;
        zb = 0;
        zc = 255 - (pos - 5/6)*6*255;
    }

    line.style.backgroundColor = 'rgb(' + za + ',' + zb + ',' + zc + ')';
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
var [ci, cj, ck] = [0, 0, 0];

document.addEventListener('mousemove', function(){
    //get cursor bg color
    if(r === 255 && g === 0 && b === 0){     //red
        ci = 0;
        cj = 1;
        ck = 0;
    }
    else if(r === 255 && g === 255 && b === 0){    //yellow
        ci = -1;
        cj = 0;
        ck = 0;
    }
    else if(r === 0 && g === 255 && b === 0){    //green
        ci = 0;
        cj = 0;
        ck = 1;
    }
    else if(r === 0 && g === 255 && b === 255){    //cyan
        ci = 0;
        cj = -1;
        ck = 0;
    }
    else if(r === 0 && g === 0 && b === 255){    //blue
        ci = 1;
        cj = 0;
        ck = 0;
    }
    else if(r === 255 && g === 0 && b === 255){    //pink
        ci = 0;
        cj = 0;
        ck = -1;
    }

    r += ci;
    g += cj;
    b += ck;

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

let syns = document.querySelectorAll('a:not(.nsyn)');

for(let syn of syns){
    syn.classList.add('syn');
}

function drawsyn(){
    for(let syn of syns){
        syn.style.backgroundImage = `linear-gradient(90deg, rgb(${ar}, ${ag}, ${ab}), rgb(${ab}, ${ar}, ${ag}), rgb(${ag}, ${ab}, ${ar}))`;
    }
    
    if(ar === 255 && ag === 0 && ab === 0){     //red
        ai = 0;
        aj = 1;
        ak = 0;
    }
    else if(ar === 255 && ag === 255 && ab === 0){    //yellow
        ai = -1;
        aj = 0;
        ak = 0;
    }
    else if(ar === 0 && ag === 255 && ab === 0){    //green
        ai = 0;
        aj = 0;
        ak = 1;
    }
    else if(ar === 0 && ag === 255 && ab === 255){    //cyan
        ai = 0;
        aj = -1;
        ak = 0;
    }
    else if(ar === 0 && ag === 0 && ab === 255){    //blue
        ai = 1;
        aj = 0;
        ak = 0;
    }
    else if(ar === 255 && ag === 0 && ab === 255){    //pink
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

/*
    SEARCH 
*/
let sb = document.getElementById('searchb');
sb.disabled = true;

document.getElementById('searcht').addEventListener('input', function(){
    sb.removeAttribute('disabled');
});

/*
    ACCORDION 
*/
let accs = document.getElementsByClassName("accordion");

for (let acc of accs) {
  acc.addEventListener("click", function() {
    this.classList.toggle("active");
    let panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}

/*
    COLLAPSIBLES
*/
let coll = document.getElementsByClassName("collapsible");
let content = document.getElementsByClassName('content');

function toggleContent() {
    this.classList.toggle("active");
    content = this.nextElementSibling;
    if (content.style.display === "block") {
        content.style.display = "none";
    } else content.style.display = "block";
}

for (let i = 0; i < coll.length; i++) {
    coll[i].style.display = 'initial';
    coll[i].style.cursor = 'pointer';
    content[i].style.display = 'none';
    coll[i].addEventListener("click", toggleContent);
}

/*
    LOGIN.PHP
*/
let user = document.getElementById('username');
let pwd = document.getElementById('pwd');
let submit = document.getElementById('login');

if(submit !== null){
    submit.disabled = true;

    document.getElementById('f_login').addEventListener('input', function(){
        if(user.value.length > 0 && pwd.value.length > 0){
            submit.removeAttribute('disabled');
        }
        else submit.disabled = true;
    });
}

// show/hide pass
let tpass = document.getElementById('tpass');

if(tpass !== null) {
    tpass.style.display = 'initial';
    tpass.style.cursor = 'pointer';

    tpass.addEventListener('click', function(){
        swapsrc(`tpass`,`/img/show.png`,`/img/hide.png`);
        togglepass(`pwd`);
    });
}

/*
    LOGIN DIV POPOUT
*/
let user_login = document.getElementById('user_login');
if(user_login !== null){
    user_login.removeAttribute('href');
    user_login.style.cursor = 'pointer';

    let login_popout = document.getElementById('login_popout');
    if(login_popout !== null){
        login_popout.style.display = 'none';

        user_login.addEventListener('click', function(){
            if(login_popout.style.display === 'none'){
                login_popout.style.display = 'initial';
                //click outside of login should close the login popup
                /*document.body.addEventListener('mousedown', function(e){
                    if(e.)
                    login_popout.style.display = 'none';
                })*/
                document.body.addEventListener('keydown', function(e){
                    if(e.key === 'Escape') login_popout.style.display = 'none';
                })
            } else login_popout.style.display = 'none';
        });

        document.getElementById('f_login').setAttribute('action', "javascript:void(0);");

        let login = document.getElementById('login');
        login.addEventListener('click', function(){
            $.ajax({
                type: "POST",
                data: {username: document.getElementById('username').value,
                    pwd: document.getElementById('pwd').value},
                url: "php/logged_ajax.php",
                success: function(echo){
                    if(echo === '0'){
                        login_popout.style.display = 'none';
                        if(window.location.pathname.includes('logout')){
                            window.location = "index.php";
                        }
                        else window.location = window.location;
                    }
                    else {
                        window.location = "index.php?page=login";
                        console.log("logged.php error: " + echo);
                    }
                }
            });
        });
    }
}

/*
    LOGOUT
*/
let logout = document.getElementById('logout');

if(logout !== null){
    logout.removeAttribute('href');
    logout.style.cursor = 'pointer';

    logout.addEventListener('click', function(){
        $.ajax({
            type: "GET",
            url: "logout_ajax.php",
            success: function(echo){
                if(echo !== '0'){
                    console.log("logout_ajax.php error: " + echo);
                } else window.location = window.location;
            }
        });
    });
}