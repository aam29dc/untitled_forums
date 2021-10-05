"use strict";
let email = document.getElementById('email');
let subscribe = document.getElementById('subscribe');

subscribe.disabled = true;

document.getElementById('f_sub').addEventListener('input', function(){
    if(email.value.length > 0){
        subscribe.removeAttribute('disabled');
    }
    else subscribe.disabled = true;
});