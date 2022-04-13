"use strict";

/* POST to a thread */
document.getElementById('post_f').setAttribute('action', "javascript:void(0);");
document.getElementById('post_b').setAttribute('onClick', "postAJAX();");

//get thread id and pages id
let qstring = window.location.search;
let threadId = '';
let pageId = '';
let replyId = '';

var queryDict = {};
location.search.substring(1).split("&").forEach(function(item) {queryDict[item.split("=")[0]] = item.split("=")[1]});
threadId = queryDict["thread"];
pageId = queryDict["pages"];

/*
    LIKE a post
*/
function likeEvent(){
    let upvotes = document.getElementsByClassName('upvote');
    for(let i = 0;i < upvotes.length;i++){
        upvotes[i].removeAttribute('href');
        upvotes[i].style.cursor = 'pointer';

        upvotes[i].addEventListener('click', function(){
            $.ajax({
                type: "GET",
                data: {postId: upvotes[i].getAttribute('data-postid'),
                        threadId: threadId},
                url: "php/like_post_ajax.php",
                success: function(echo){
                    if(echo.charAt(0) != '0'){ 
                        console.log("like_post_ajax.php failure: " + echo);
                    }
                    else if(echo.substring(1) == "-1"){
                        document.getElementsByClassName('likes')[i].innerText = parseInt(document.getElementsByClassName('likes')[i].innerText) - 1;
                    } else {
                        document.getElementsByClassName('likes')[i].innerText = parseInt(document.getElementsByClassName('likes')[i].innerText) + 1;
                    }
                }
            });
        });
    }
}
likeEvent();

/*
    REPLY to a post
*/
function replyEvent(){
    let replys = document.getElementsByClassName('reply_post');

    let post_content = document.getElementById('post_content');

    for(let i = 0;i < replys.length;i++){
        replys[i].removeAttribute('href');
        replys[i].style.cursor = 'pointer';

        replys[i].addEventListener('click', function(){

            document.getElementById('reply').innerText = "[Reply to #" + replys[i].getAttribute('data-postnum') + "]";
            post_content.style.display = "block"
            post_content.scrollIntoView();
            replyId = replys[i].getAttribute('data-replyid');
        });

    }
}
replyEvent();

function postAJAX(){
    let ptitle = document.getElementById('post_title');
    let pmsg = document.getElementById('post_text');

    $.ajax({
        type: "POST",
        data: {post_title: ptitle.value,
            post_text: pmsg.value,
            replyEventId: replyId},
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
    $.ajax({
        type: "GET",
        url: "thread.php",
        data: {thread: threadId,
                pages: pageId},
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
    if(edit_thread) {
        edit_thread.removeAttribute('href');
        edit_thread.setAttribute('style', 'cursor:pointer;');
        edit_thread.addEventListener('click', editThread);
    }
}
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
        edit_posts[i].index = edit_posts[i].getAttribute('data-num');
        edit_posts[i].postid = edit_posts[i].getAttribute('data-postid');
    }
}
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