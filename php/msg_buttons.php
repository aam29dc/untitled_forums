<?php
function drawMsgButtons($msgBox){
    echo '<button type="button" onclick="input_tag(`'.$msgBox.'`,`a`);" class="b1">link</button><button type="button" onclick="input_tag(`'.$msgBox.'`,`b`);" class="b1">bold</button><button type="button" onclick="input_tag(`'.$msgBox.'`,`i`);" class="b1">italic</button><button type="button" onclick="input_tag(`'.$msgBox.'`,`s`);" class="b1">strike</button><button type="button" onclick="input_tag(`'.$msgBox.'`,`u`);" class="b1">underline</button><button type="button" onclick="input_tag(`'.$msgBox.'`,`sub`);" class="b1">sub</button><button type="button" onclick="input_tag(`'.$msgBox.'`,`sup`);" class="b1">sup</button>';
}
?>