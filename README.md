# untitled_forums
Requires jQuery for AJAX (to update page without redirect/refresh). This site is also fully functional with JS disabled.

[ **PHP(PDO) & SQL(mysql):** ]

--signup/login to create a thread or post in thread.

--guest, member, moderator, admin priviledges.

--control panel for mods to ban, and rank up users.

--block another user to stop seeing their threads, posts, and pms.

--edit username, pwd, profile msg, delete profile.

--have a conversations/pm (private message) another user.

[ HTML & CSS: ]

--saved variable css template, click the logo in the upper left corner to swap between dark and light themes

--Mobile phone layout is set with is_mobile() function (lib.php) in layouts and form elements.

[ JavaScript/AJAX: ]

--for swapping between light and dark theme

--for clicking on button to put input tags (a,b,i,s,...) in submit forms

--for AJAX: edit a thread/post without a page redirect. Submit a post without a page redirect. Submit a pm in a private conversation without a page redirect. Login/signout without a page redirect.

--synesthesia font, scroll line color change as user scrolls down page, mouse pointer color change as user moves mouse

![userx_v3_1](https://user-images.githubusercontent.com/73267302/135941971-f125d8b6-1a74-4a7b-aeec-84caa96e1d01.png)

![userx_v3_2](https://user-images.githubusercontent.com/73267302/135941955-0c1eae36-6cd2-4f14-8d90-e13291cd4a8e.png)

[MOBILE Phone layout]

![userx_v3_mobile](https://user-images.githubusercontent.com/73267302/135941978-f1689e28-daeb-4c22-a8a9-2ad7f867dce0.png)

_______________________________________________________________________________________________
(11/01/21) __[3nd RELEASE]__
_______________________________________________________________________________________________
Updates with JavaScript, and AJAX: Now you can post/edit/delete a reply or thread, without redirecting you to another page. Conversations or PM's also don't require a page redirect, making it feel like a modern message app. The login button now is a pop out without redirecting you to the login page. This all makes the site feel like a modern Single Page Application. See TODO.txt for other changes/fixes (are indented and prefixed with a \*). Changes/fixes to come are not indented.
_______________________________________________________________________________________________
(10/14/21) __[2nd RELEASE]__
_______________________________________________________________________________________________
This site has been updated using JavaScript for various transitions and animations that I've also posted on my GitHub. It includes synesthesia-font, a scroll line effect that changes colors, a cube that rotates and the mouse pointer that changes color as the users moves the mouse. The signup page has been updated using AJAX (uses jQuery for web compatibility) to notify the user that a username has been taken (without refreshing the page), and passwords don't match, etc. There have been various other changes and improvements to the site. Mostly changes on the front-end of the site. More updates to come.
_______________________________________________________________________________________________
__[1st RELEASE]__
_______________________________________________________________________________________________

forum software with dynamic web pages
dynamic web pages (layout files: index_header, index_footer).
