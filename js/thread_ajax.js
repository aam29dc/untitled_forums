"use strict";

/* POST REPLY */
document.getElementById('post_f').setAttribute('action', "javascript:void(0);");
document.getElementById('post_b').setAttribute('onClick', "postAJAX();");

//get thread id and pages id
let qstring = window.location.search;
let threadId = '';
let pageId = '';
let qi = 0;

//get threadId
for(qi = 0; qi < qstring.length; qi++){
    if(qstring[qi] >= '0' && qstring[qi] <= '9'){
        threadId += qstring[qi];
    }
    else if(qstring[qi] == '&') break;
}

//get pageId
for(; qi < qstring.length; qi++){
    if(qstring[qi] >= '0' && qstring[qi] <= '9'){
        pageId += qstring[qi];
    }
}

function postAJAX(){
    let ptitle = document.getElementById('post_title');
    let pmsg = document.getElementById('post_text');

    $.ajax({
        type: "POST",
        data: {post_title: ptitle.value,
            post_text: pmsg.value},
        url: "php/posted_ajax.php",
        success: function(echo){
            if(echo != '0') console.log("posted_ajax.php failure: " + echo);
        }
    });

    ptitle.value = '';
    pmsg.value = '';

    refreshThread();
}

function refreshThread(){
    let post_coll = document.getElementById('post_coll');
    let post_content = document.getElementById('post_content');

    $.ajax({
        type: "GET",
        url: "thread.php",
        success: function(echo){
            document.getElementById('heart').innerHTML = echo;
            // re-set attributes and eventlisteners to new echo'd content
            document.getElementById('post_f').setAttribute('action', "javascript:void(0);");
            document.getElementById('post_b').setAttribute('onClick', "postAJAX();");
            // (coll and content from general.js) re-Add event listeners
            for(let i = 0;i < coll.length; i++){
                coll[i].style.display = 'initial';
                coll[i].classList.remove('active');
                content[i].style.display = 'none';
                coll[i].addEventListener("click", toggleContent);
            }

            threadEvent();
            postsEvent();

            // syn effect doesnt work after refresh
        }
    });
}
/* EDIT THREAD */
let edit_thread = document.getElementById('edit_thread');

function threadEvent(){
    edit_thread = document.getElementById('edit_thread');
    edit_thread.removeAttribute('href');
    edit_thread.setAttribute('style', 'cursor:pointer;');
    edit_thread.addEventListener('click', editThread);
};
threadEvent();

function removeThreadElements(){
    document.getElementById('edit_title').remove();
    document.getElementById('edit_message').remove();
    document.getElementById('submit_thread').remove();
    document.getElementById('deleted').remove();
    document.getElementById('thread_title').style.display = 'initial';
    document.getElementById('thread_msg').style.display = 'initial';
}

function uneditThread(){
    removeThreadElements();

    this.removeEventListener('click', uneditThread);
    this.addEventListener('click', editThread);
}

function editThread(){
    let thread_title = document.getElementById('thread_title');
    let thread_msg = document.getElementById('thread_msg');

    let title = document.createElement('input');
    setAttributes(title, {"type": "text", "id": "edit_title", "name": "edit_title", "size": "64", "style": "width:98%;",
    "class": "textfield", "value": thread_title.innerHTML});

    let msg = document.createElement('textarea');

    setAttributes(msg, {"type": "text", "id": "edit_message", "name": "edit_message", "style": "width:98%;height:200px;overflow-y:scroll;",
    "class": "textfield"});

    msg.value = thread_msg.innerHTML;

    thread_title.style.display = 'none';
    thread_msg.style.display = 'none';

    let submit = document.createElement('button');
    submit.innerText = "Submit";
    setAttributes(submit, {"id": "submit_thread"});

    submit.addEventListener('click', function(){
        $.ajax({type: "POST",
                data: {ori_title: thread_title.innerHTML,
                    ori_message: thread_msg.innerHTML,
                    edit_title: title.value,
                    edit_message: msg.value,
                    threadid: threadId},
                url: "php/edited_thread_ajax.php",
                success: function(echo){
                    if(echo != '0') console.log("edited_thread_ajax.php error: " + echo);
                    else {
                        thread_title.innerHTML = title.value;
                        thread_msg.innerHTML = msg.value;
                    }
                }
        });
        removeThreadElements();

        edit_thread.removeEventListener('click', uneditThread);
        edit_thread.addEventListener('click', editThread);
    });

    let deleted = document.createElement('button');
    deleted.innerText = "Delete";
    setAttributes(deleted, {"id": "deleted", "style": "float:right;"});

    deleted.addEventListener('click', function(){
        $.ajax({type: "POST",
                data: {deleted: "Delete",
                        threadid: threadId},
                url: "php/deleted_thread_ajax.php",
                success: function(echo){
                    if(echo != '0'){
                        console.log("edit_thread_ajax.php error: " + echo);
                    }
                    else document.getElementById('heart').innerHTML = 'Thread deleted. Redirecting to home page.';
                    waitdirect();   //from waitdirect.js, included in index_footer.php for thread.php
                }
            });
    });

    thread_msg.before(title);
    thread_msg.before(msg);
    thread_msg.before(submit);
    thread_msg.before(deleted);

    this.removeEventListener('click', editThread);
    this.addEventListener('click', uneditThread);
}

/* EDIT POST(S) */

let edit_posts = document.getElementsByClassName('edit_post');

function postsEvent(){
    edit_posts = document.getElementsByClassName('edit_post');

    for(let i = 0; i < edit_posts.length; i++){
        edit_posts[i].removeAttribute('href');
        edit_posts[i].setAttribute('style', 'cursor:pointer;');
        edit_posts[i].addEventListener('click', editPost);
        edit_posts[i].index = i;
        edit_posts[i].postid = edit_posts[i].getAttribute('data-postid');
    }
};
postsEvent();

function editPost(){
    let postid = this.postid;
    let post = this.index;
    let post_title = document.getElementsByClassName('post_title')[post];
    let post_msg = document.getElementsByClassName('post_msg')[post];

    let title = document.createElement('input');
    let msg = document.createElement('textarea');

    setAttributes(title, {"type": "text", "class": "edit_post_title textfield", "name": "edit_post_title", "size": "64", "style": "width:98%;",
    "value": post_title.innerHTML});

    setAttributes(msg, {"type": "text", "class": "edit_post_message textfield", "name": "edit_post_message", "style": "width:98%;height:200px;overflow-y:scroll;"});

    msg.value = post_msg.innerHTML;

    post_title.style.display = 'none';
    post_msg.style.display = 'none';

    let submit = document.createElement('button');
    submit.innerText = "Submit";
    setAttributes(submit, {"class": "submit_post"});

    submit.addEventListener('click', function(){
        $.ajax({
            type: "POST",
            data: {postid: postid,
                edit_title: title.value,
                edit_message: msg.value,
                ori_title: post_title.innerHTML,
                ori_message: post_msg.innerHTML},
            url: "php/edited_ajax.php",
            success: function(echo){
                if(echo != '0'){
                    console.log("edited_ajax.php error: " + echo);
                }
                else {
                    post_title.innerHTML = title.value;
                    post_msg.innerHTML = msg.value;
                }
            }
        });

        removePostElements();
        post_title.style.display = 'initial';
        post_msg.style.display = 'initial';
        edit_posts[post].removeEventListener('click', uneditPost);
        edit_posts[post].addEventListener('click', editPost);
    });

    let deleted = document.createElement('button');
    deleted.innerText = "Delete";
    setAttributes(deleted, {"class": "deleted_post", "style": "float:right;"});

    deleted.addEventListener('click', function(){
        $.ajax({
            type: "POST",
            data: {postid: postid,
                delete: "Delete"},
            url: "php/edited_ajax.php",
            success: function(echo){
                if(echo != '0'){
                    console.log("deleted_post_ajax.php error: " + echo);
                }
            }
        });

        removePostElements();
        edit_posts[post].remove();
        post_title.innerHTML = "";
        post_msg.style.display = 'initial';
        post_msg.innerHTML = '<span style="color:red;">Post deleted by author.</span>';
    });

    post_msg.before(title);
    post_msg.before(msg);
    post_msg.before(submit);
    post_msg.before(deleted);

    function removePostElements(){
        title.remove();
        msg.remove();
        submit.remove();
        deleted.remove();
    }

    function uneditPost(){
        removePostElements();
        post_title.style.display = 'initial';
        post_msg.style.display = 'initial';
        this.addEventListener('click', editPost);
    }

    this.removeEventListener('click', editPost);
    this.addEventListener('click', uneditPost);
}