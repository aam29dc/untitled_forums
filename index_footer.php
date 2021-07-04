<?php if(!isset($x)) require_once('php/lib.php');?></div>
<div class="aside">
    <nav>
        <ul>
            <li class="vnavh"><h1>Navigation:</h1></li>
            <?php include_once(abs_php_include($x).'php/navigation.php');?>
        </ul>
    </nav><br>
    <?php include_once(abs_php_include($x).'php/controlpanel.php');?>
</div>
<button class="accordion">&copy; aam29dc</button>
<div class="panel">
    <p>created by aam29dc@gmail.com</p>
</div>
</div>
</body>
<script src="<?php abs_include($x);?>js/accordion.js"></script>
<script src="<?php abs_include($x);?>js/general.js"></script>
<!--<script src="js/gl/webgl-utils.js"></script>
<script src="js/gl/webgl.js"></script>-->
</html>