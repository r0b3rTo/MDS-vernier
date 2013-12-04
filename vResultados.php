<?php
    session_start();
    $Legend = "Resultados de la Evaluación";
    include "lib/cResultados.php";
    include "vHeader.php";
    require_once "lib/phpChart_Lite/conf.php";
    extract($_GET);
    extract($_POST);
    $all = true;
    date_default_timezone_set('America/Caracas');
?>   

  <div class="well" align="center">  
  
    <?php
  
    $l1 = array(18, 36, 14, 11);
    $l2 = array(array(2, 14), array(7, 2), array(8,5));
    $l3 = array(4, 7, 9, 2, 11, 5, 9, 13, 8, 7);

    $pc = new C_PhpChartX(array($l1,$l2,$l3),'chart1');

    $pc->jqplot_show_plugins(false);
    $pc->set_legend(array('show'=>true));
    $pc->set_animate(true);
    $pc->add_series(array('showLabel'=>true));
    $pc->add_series(array('showLabel'=>true));
    $pc->add_series(array('showLabel'=>true));
    
    $pc->draw(600,300);   
    
    $pc->get_display(false);
   ?>
  
  </div>
 


<?
include "vFooter.php";
?>