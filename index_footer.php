<?php if(!isset($x)) require_once('php/lib.php');?></div>
<div id="aside">
    <nav>
        <ul>
            <li class="vnavh"><h1>Navigation:</h1></li>
            <?php include_once(abs_php_include($x).'php/navigation.php');?>
        </ul>
    </nav><br>
    <?php include_once(abs_php_include($x).'php/controlpanel.php');?>
    <section id="cubeicle">
        <div class="face front"></div>
        <div class="face right"></div>
        <div class="face back"></div>
        <div class="face left"></div>
        <div class="face top"></div>
        <div class="face bottom"></div>
    </section>
</div>
<button class="accordion">&copy; aam29dc</button>
<div class="panel">
    <p>created by aam29dc@gmail.com</p>
</div>
</div>
<script src="<?php abs_include($x);?>js/accordion.js"></script>
<script src="<?php abs_include($x);?>js/general.js"></script>
<?php /* CONDITIONAL INCLUDE */
    if($_SERVER['QUERY_STRING'] == 'page=signup'){
        echo '<script src="js/jquery-3.6.0.min.js"></script>'."\n";
        echo '<script src="js/signup_ajax.js"></script>'."\n";
    }
    else if($_SERVER['QUERY_STRING'] == 'page=login'){
        echo '<script src="js/login.js"></script>';
    }
    else if($_SERVER['QUERY_STRING'] == 'page=sub'){
        echo '<script src="js/subscribe.js"></script>';
    }
?>
<!--<script src="js/gl/webgl-utils.js"></script>
<script src="js/gl/webgl.js"></script>-->
</body>
</html>