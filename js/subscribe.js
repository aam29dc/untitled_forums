let email = document.getElementById('email');
let submit = document.getElementById('subscribe');

submit.disabled = true;

document.getElementById('f_sub').addEventListener('input', function(){
    if(email.value.length > 0){
        submit.removeAttribute('disabled');
    }
    else submit.disabled = true;
});