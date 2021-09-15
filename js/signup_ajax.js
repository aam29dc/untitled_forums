"use strict";
let pwd = document.getElementById('pwd');
let c_pwd = document.getElementById('confirm_pwd');
let user = document.getElementById('username');
let mail = document.getElementById('email');
let signup = document.getElementById('signup');
signup.disabled = true;

function confirmPass(){
    let span = document.getElementById('span_pwd');

    if(pwd.value != c_pwd.value){
        span.style.color = 'red';
        span.innerText = "Passwords not equal.";
    }
    else {
        span.style.color = 'rgb(0,255,0)';
        span.innerText = "Passwords equal.";
    }
}
pwd.addEventListener('input', confirmPass);
c_pwd.addEventListener('input', confirmPass);

document.getElementById('f_signup').addEventListener('input', function(){
    if(pwd.value.length > 0 && c_pwd.value.length > 0 && user.value.length > 0 && mail.value.length > 0){
        signup.removeAttribute('disabled');
    }
    else signup.disabled = true;
});

$(document).ready(function(){
    user.addEventListener('blur', function(){
        let username = user.value;
        let span = document.getElementById('user_free');

        if(username === ''){}
        else if(username.length >= 3){
            span.innerText = "checking...";

            $.ajax({
                type: "POST",
                data: {username: username},
                url: "php/user_free.php",
                success: function(data){
                    if(data === '0'){
                        span.style.color = 'rgb(0,255,0)';
                        span.innerText = "Available!";
                    }
                    else {
                        span.style.color = 'red';
                        span.innerText = "Unavailable.";
                    }
                }
            });
        }
        else span.innerText = "Not enough characters.";
    });

    mail.addEventListener('blur', function(){
        let email = mail.value;
        let span = document.getElementById('email_free');

        if(email === '') span.innerText = '';
        else {
            $.ajax({
                type: "POST",
                data: {email: email},
                url: "php/email_free.php",
                success: function(data){
                    if(data === '1'){
                        span.style.color = 'red';
                        span.innerText = "Email already in use";
                    }
                    else span.innerText = '';
                }
            });
        }
    });
});