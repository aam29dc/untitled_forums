"use strict";
let td = document.getElementsByClassName('link');
let span = document.getElementsByTagName('span');

for(let i = 0; i < td.length; i++){     //td and spans should be equal
    td[i].style.cursor = 'pointer';
    span[i].style.display = 'initial';
}