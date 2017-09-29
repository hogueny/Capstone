<?php

require_once("../conf.php");
$pc = new C_PhpChartX(array($arr1),'basic_chart');
    $pc->set_title(array('text'=>'basic chart'));
    $pc->set_animate(true);
    $pc->add_plugins(array('highlighter', 'cursor'));
    $pc->draw();
?>