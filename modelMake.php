<?php
if (!isset($_SESSION['cmsuno'])) exit();
?>
<?php
$Ustyle .= ".grid1,.grid2,.grid3,.grid4,.grid5,.grid6,.grid7,.grid8,.grid9,.grid10,.grid11,.grid12{padding:12px;position:relative;float:left;min-height:1px;box-sizing:border-box;-moz-box-sizing:border-box;}\r\n";
$Ustyle .= ".grid12{width:100%;}.grid11{width:91.66666667%;}.grid10{width:83.33333333%;}.grid9{width:75%;}.grid8{width:66.66666667%;}.grid7{width:58.33333333%;}.grid6{width:50%;}.grid5{width:41.66666667%;}.grid4{width:33.33333333%;}.grid3{width:25%;}.grid2{width:16.66666667%;}.grid1{width:8.33333333%;}\r\n";
$Ustyle .= ".twoCol,.threeCol{margin:0 -12px;}\r\n";
$UstyleSm .= ".grid11,.grid10,.grid9,.grid8,.grid7,.grid6{width:100%;}.grid5{width:83.33333333%;}.grid4{width:66.66666667%;}.grid3{width:50%;}.grid2{width:33.33333333%;}.grid1{width:16.66666667%;}\r\n";
if(strpos($Ucontent,'class="row ')!==false) $Ustyle .= '.row:before,.row:after{content:" ";display:table;}.row:after{clear:both;}'."\r\n";
?>
