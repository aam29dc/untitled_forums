"use strict";
let s_pwd = document.getElementById('s_pwd'); //signup pwd to differ from login pwd
let c_pwd = document.getElementById('confirm_pwd');
let s_user = document.getElementById('s_username'); //signup user to differ from login user
let mail = document.getElementById('email');
let signup = document.getElementById('signup');
signup.disabled = true;

function confirmPass(){
    let span = document.getElementById('span_pwd');

    if(s_pwd.value !== c_pwd.value){
        span.style.color = 'red';
        span.innerText = "Passwords not equal.";
    }
    else {
        span.style.color = 'rgb(0,255,0)';
        span.innerText = "Passwords equal.";
    }
}
s_pwd.addEventListener('input', confirmPass);
c_pwd.addEventListener('input', confirmPass);

document.getElementById('f_signup').addEventListener('input', function(){
    if(s_pwd.value.length > 0 && c_pwd.value.length > 0 && s_user.value.length > 0 && mail.value.length > 0){
        signup.removeAttribute('disabled');
    }
    else signup.disabled = true;
});

s_user.addEventListener('blur', function(){
    const username = s_user.value;
    let span = document.getElementById('user_free');

    if(username.length >= 3){
        span.innerText = "checking...";

        $.ajax({
            type: "POST",
            data: {username: username},
            url: "php/user_free.php",
            success: function(echo){
                if(echo === '0'){
                    span.style.color = 'rgb(0,255,0)';
                    span.innerText = "Available!";
                }
                else {
                    span.style.color = 'red';
                    span.innerText = "Unavailable.";
                }
            }
        });
    } else span.innerText = "Not enough characters.";
});

mail.addEventListener('blur', function(){
    const email = mail.value;
    let span = document.getElementById('email_free');

    if(email === '') span.innerText = '';
    else {
        $.ajax({
            type: "POST",
            data: {email: email},
            url: "php/email_free.php",
            success: function(echo){
                if(echo === '1'){
                    span.style.color = 'red';
                    span.innerText = "Email already in use";
                } else span.innerText = '';
            }
        });
    }
});
// show/hide passwords
let s_tpass = document.getElementById('s_tpass');
let tcpass = document.getElementById('tcpass');

s_tpass.style.display = 'initial';
s_tpass.style.cursor = 'pointer';
tcpass.style.display = 'initial';
tcpass.style.cursor = 'pointer';

s_tpass.addEventListener('click', function(){
    swapsrc(`s_tpass`,`/site/img/show.png`,`/site/img/hide.png`);
    togglepass(`s_pwd`);
});

tcpass.addEventListener('click', function(){
    swapsrc(`tcpass`,`/site/img/show.png`,`/site/img/hide.png`);
    togglepass(`confirm_pwd`);
});