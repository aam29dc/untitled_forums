"use strict";
let user = document.getElementById('username');
let pwd = document.getElementById('pwd');
let submit = document.getElementById('login');

if(submit != null){
    submit.disabled = true;

    document.getElementById('f_login').addEventListener('input', function(){
        if(user.value.length > 0 && pwd.value.length > 0){
            submit.removeAttribute('disabled');
        }
        else submit.disabled = true;
    });
}