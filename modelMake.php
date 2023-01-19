<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(empty($Ua['w3'])) {
	$Ustyle .= ".grid1,.grid2,.grid3,.grid4,.grid5,.grid6,.grid7,.grid8,.grid9,.grid10,.grid11,.grid12{padding:12px;position:relative;float:left;min-height:1px;box-sizing:border-box;-moz-box-sizing:border-box;}\r\n";
	$Ustyle .= ".grid12{width:100%;}.grid11{width:91.66666667%;}.grid10{width:83.33333333%;}.grid9{width:75%;}.grid8{width:66.66666667%;}.grid7{width:58.33333333%;}.grid6{width:50%;}.grid5{width:41.66666667%;}.grid4{width:33.33333333%;}.grid3{width:25%;}.grid2{width:16.66666667%;}.grid1{width:8.33333333%;}\r\n";
	$Ustyle .= ".twoCol,.threeCol{margin:0 -12px;}\r\n";
	$UstyleSm .= ".grid11,.grid10,.grid9,.grid8,.grid7,.grid6{width:100%;}.grid5{width:83.33333333%;}.grid4{width:66.66666667%;}.grid3{width:50%;}.grid2{width:33.33333333%;}.grid1{width:16.66666667%;}\r\n";
	if(strpos($Ucontent,'class="row ')!==false) $Ustyle .= '.row:before,.row:after{content:" ";display:table;}.row:after{clear:both;}'."\r\n";
}
if(file_exists('data/model.json') && file_exists('plugins/model/unomodel/plugin.js') && filemtime('plugins/model/unomodel/plugin.js')<filemtime('plugins/model/modelMake.php')) modelUpdate();
//
function modelUpdate() {
	// IDEM SAVE Part in model.php
	// Update ckeditor button content after plugin update
	// 1. model.json
	$q = file_get_contents('data/model.json');
	if($q) $a = json_decode($q,true);
	else return;
	// 2. dynamic.js
	$dyn = array();
	$dyn[0] = array(
		'n'=>'twocol',
		't'=>'<div class="w3-row row twoCol"><div class="w3-col m'.(isset($a['tw1'])?$a['tw1']:6).' grid'.(isset($a['tw1'])?$a['tw1']:6).' col1"><p>Content</p></div><div class="w3-col m'.(isset($a['tw1'])?intval(12-$a['tw1']):6).' grid'.(isset($a['tw1'])?intval(12-$a['tw1']):6).' col2"><p>Content</p></div></div>',
		'e'=>'{col1:{selector:\'.col1\'},col2:{selector:\'.col2\'}}',
		'a'=>'alw',
		'u'=>'twoCol',
		'l'=>T_('Two columns')
		);
	$dyn[1] = array(
		'n'=>'threecol',
		't'=>'<div class="w3-row row threeCol"><div class="w3-col m'.(isset($a['th1'])?$a['th1']:4).' grid'.(isset($a['th1'])?$a['th1']:4).' col1"><p>Text</p></div><div class="w3-col m'.(isset($a['th2'])?$a['th2']:4).' grid'.(isset($a['th2'])?$a['th2']:4).' col2"><p>Text</p></div><div class="w3-col m'.(isset($a['th1'])?intval(12-$a['th1']-$a['th2']):4).' grid'.(isset($a['th1'])?intval(12-$a['th1']-$a['th2']):4).' col3"><p>Text</p></div></div>',
		'e'=>'{col1:{selector:\'.col1\'},col2:{selector:\'.col2\'},col3:{selector:\'.col3\'}}',
		'a'=>'alw',
		'u'=>'threeCol',
		'l'=>T_('Three columns')
		);
	$c = 0;
	if(isset($a['list'])) foreach($a['list'] as $kl=>$vl) {
		$t = ''; $e = '';
		$re = (!empty($vl['r'])?(intval($vl['r'])===1?'l':'s'):'m'); // w3.css - 0: (m => 600px) - 1: (l => 993px) - 2: (s => not responsive)
		foreach($vl['c'] as $k=>$v) {
			$v1 = explode('|',$v);
			$v2 = 0;
			if(isset($vl['c'][$k+1])) {
				$v3 = explode('|',$vl['c'][$k+1]);
				if(!$v1[2] && $v3[2]) $v2 = 1;
			}
			if($v2) { // ouverture bloc parent
				if(substr($v1[1],0,1)=='*') {
					$v0 = explode('**',substr($v1[1],1));
					$t .= '<div class="w3-col '.$re.$v1[0].' grid'.$v1[0].'"'.((isset($v0[1]) && $v0[1])?' style="'.$v0[1].'"':'').'>';
				}
				else $t .= '<div class="w3-col '.$re.$v1[0].' grid'.$v1[0].'">';
			}
			else {
				if(substr($v1[1],0,1)=='*') {
					$v0 = explode('**',substr($v1[1],1));
					$t .= '<div class="w3-col '.$re.$v1[0].' grid'.$v1[0].' col'.($k+1).'"'.((isset($v0[1]) && $v0[1])?' style="'.$v0[1].'"':'').'>'.$v0[0].'</div>';
					$e .= 'col'.($k+1).':{selector:\'.col'.($k+1).'\',allowedContent:'.($alw['css']=='alw'?'alw':'\''.$alw['css'].'\'').'},';
				}
				else {
					$t .= '<div class="w3-col '.$re.$v1[0].' grid'.$v1[0].' col'.($k+1).'">'.$con[$v1[1]].'</div>';
					$e .= 'col'.($k+1).':{selector:\'.col'.($k+1).'\',allowedContent:'.($alw[$v1[1]]=='alw'?'alw':'\''.$alw[$v1[1]].'\'').'},';
				}
				if(!isset($vl['c'][$k+1]) || ($v1[2] && !$v3[2])) $t .= '</div>'; // fermeture bloc parent
			}
		}
		$u = preg_replace("/[^a-zA-Z0-9]+/", "", $kl);
		$t = str_replace("!i!", "|", $t);
		$t = str_replace("'", "\\'", $t);
		$dyn[2+$c] = array(
			'n'=>$vl['i'],
			't'=>'<div class="w3-row row unomodel '.$u.'">'.$t.'</div>',
			'e'=>'{'.substr($e,0,-1).'}',
			'a'=>'alw',
			'u'=>$u,
			'l'=>$kl
			);
		++$c;
	}
	$o = "dyn=[];ico='';";
	$o .= "var alw='p a div span h2 h3 h4 h5 h6 section article iframe object embed strong b i em cite pre blockquote small sub sup code ul ol li dl dt dd table thead tbody th tr td img caption mediawrapper br[*]{*}(*)';\r\n";
	for($v=0;$v<count($dyn);++$v) {
		$o .= "dyn[".$v."]={";
		$o .= "n:'".$dyn[$v]['n']."',";
		$o .= "t:'".$dyn[$v]['t']."',";
		$o .= "e:".$dyn[$v]['e'].",";
		$o .= "a:".$dyn[$v]['a'].","; // var
		$o .= "u:'".$dyn[$v]['u']."',";
		$o .= "l:'".$dyn[$v]['l']."'";
		$o .= "};\r\n";
	}
	$o .= "for(v=0;v<dyn.length;++v){ico+=dyn[v].n+',';};ico=ico.substr(0,ico.length-1);";
	// 3. Save
	$q = file_get_contents('plugins/model/unomodel/plugin_src.js');
	$q = str_replace('//INCLUDE//', $o, $q);
	file_put_contents('plugins/model/unomodel/plugin.js', $q);
}
?>
