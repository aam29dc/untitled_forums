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

// show/hide pass
let tpass = document.getElementById('tpass');

tpass.style.display = 'initial';
tpass.style.cursor = 'pointer';

tpass.addEventListener('click', function(){
    swapsrc(`tpass`,`img/show.png`,`img/hide.png`);
    togglepass(`pwd`);
});