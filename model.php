<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': 
		if(!file_exists('../../data/model.json')) file_put_contents('../../data/model.json', '{}')
		?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/model/model.css" />
		<div class="blocForm">
			<h2><?php echo T_("Model");?></h2>
			<p><?php echo T_("This powerful plugin allows you to create layout templates for CKEditor.");?></p>
			<p><?php echo T_("It works with the drop-down button") .'<img src="uno/plugins/model/unomodel/icons/unomodel.png" style="border:1px solid #aaa;padding:3px;margin:0 6px -5px;border-radius:2px;" />' . T_("added to the text editor when the plugin is enabled."); ?></p>
			<p><?php echo T_("Two models exist by default : Two columns").
				'<img src="uno/plugins/model/unomodel/icons/twocol.png" style="border:1px solid #aaa;padding:3px;margin:0 6px -5px;border-radius:2px;" />'.
				T_("and Three columns").
				'<img src="uno/plugins/model/unomodel/icons/threecol.png" style="border:1px solid #aaa;padding:3px;margin:0 6px -5px;border-radius:2px;" />'.
				T_("The column width is adjustable.");?>
			</p>
			<div id="modelConf">
				<h3><?php echo T_("Size for the two models");?></h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo T_("Size column");?>&nbsp;1</label></td>
						<td>
							<select name="tw1" id="tw1" onChange="f_draw_model('twocol');">
							<?php for($v=1;$v<12;++$v) echo '<option value="'.$v.'">'.$v.' / 12</option>'; ?>
							</select>
						</td>
						<td>
							<img src="uno/plugins/model/unomodel/icons/twocol.png" style="border:1px solid #aaa;padding:3px;margin:0 6px -5px;border-radius:2px;" />
						</td>
						<td style="text-align:center;">
							<div id='twa' class="modelCol1 modelBlock">1</div>
							<div id='twb' class="modelCol2 modelBlock">2</div>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php echo T_("Size column");?>&nbsp;1</label><br />
							<label><?php echo T_("Size column");?>&nbsp;2</label>
						</td>
						<td>
							<select name="th1" id="th1" onChange="f_draw_model('threecol');">
							<?php for($v=1;$v<11;++$v) echo '<option value="'.$v.'">'.$v.' / 12</option>'; ?>
							</select><br />
							<select name="th2" id="th2" onChange="f_draw_model('threecol');">
							<?php for($v=1;$v<11;++$v) echo '<option value="'.$v.'">'.$v.' / 12</option>'; ?>
							</select>
						<td>
							<img src="uno/plugins/model/unomodel/icons/threecol.png" style="border:1px solid #aaa;padding:3px;margin:0 6px -5px;border-radius:2px;" />
						</td>
						<td style="width:400px;text-align:center;">
							<div id='tha' class="modelCol1 modelBlock">1</div>
							<div id='thb' class="modelCol2 modelBlock">2</div>
							<div id='thc' class="modelCol3 modelBlock">3</div>
						</td>
					</tr>
				</table>
				<div class="bouton fr" onClick="f_save_model();" title="<?php echo T_("Save");?>"><?php echo T_("Save");?></div>
				<div class="clear"></div>
				<h3><?php echo T_("Create / Edit a model");?></h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo T_("Icon");?></label></td>
						<td>
							<select name="cri" id="cri" onChange="document.getElementById('cricon').src='uno/plugins/model/unomodel/icons/'+this.options[this.selectedIndex].value+'.png';">
							<?php for($v=0;$v<10;++$v) echo '<option value="'.$v.'">'.$v.'</option>'; ?>
							<?php for($v=97;$v<123;++$v) echo '<option value="'.chr($v).'">'.chr($v).'</option>'; ?>
							</select>
							<img src="uno/plugins/model/unomodel/icons/0.png" id="cricon" style="border:1px solid #aaa;padding:3px;margin:0 7px -7px 20px;border-radius:2px;" />
						</td>
						<td>
							<em><?php echo T_("Select an icon for this model.");?></em>
						</td>
					</tr>
					<tr>
						<td><label><?php echo T_("Name");?></label></td>
						<td>
							<input type="text" id="crn" name="crn"  style="width:150px;" value="" />
						</td>
						<td>
							<em><?php echo T_("Select a name for this model. Only alphanumeric characters");?></em>
						</td>
					</tr>
					<tr>
						<td><label><?php echo T_("Block Size");?></label></td>
						<td>
							<select name="cr1" id="cr1">
							<?php for($v=1;$v<12;++$v) echo '<option value="'.$v.'">'.T_("width").'&nbsp;:&nbsp;'.$v.' / 12</option>'; ?>
							<option value="12"><?php echo T_("full width"); ?></option>
							</select>
							<br />
							<select name="cr2" id="cr2">
								<option value="0"><?php echo T_("after the last block");?></option>
								<option value="1" disabled><?php echo T_("in the current block");?></option>
							</select>
						</td>
						<td rowspan="3" style="width:400px;text-align:center;">
							<div id='crView' style="width:100%;"></div>
						</td>
					</tr>
					<tr>
						<td><label><?php echo T_("Block Content");?></label></td>
						<td>
							<select name="cr3" id="cr3" onChange="f_custom_model(this);">
								<option value="all"><?php echo T_("No restriction"); ?></option>
								<option value="h2">H2 <?php echo T_("Title"); ?></option>
								<option value="h3">H3 <?php echo T_("Title"); ?></option>
								<option value="h2c">H2 <?php echo T_("Title + Content"); ?></option>
								<option value="h3c">H3 <?php echo T_("Title + Content"); ?></option>
								<option value="img"><?php echo T_("Image"); ?></option>
								<option value="li"><?php echo T_("List"); ?></option>
								<option value="css"><?php echo T_("HTML and CSS"); ?></option>
							</select>
						</td>
					</tr>
					<tr id="crhtm" style="display:none;">
						<td style="vertical-align:top;"><label>HTML</label></td>
						<td><input type="text" id="crh" name="crh"  style="width:150px;" value="<p>Text</p>" /></td>
					</tr>
					<tr id="crcss" style="display:none;">
						<td style="vertical-align:top;"><label>CSS</label></td>
						<td>
							<input type="text" id="crs" name="crs"  style="width:150px;" value="" /><br />
							<em>ex : color:#222;font-size:1.5em;</em>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="bouton fl" onClick="f_add_model();" title="<?php echo T_("Add");?>"><?php echo T_("Add");?></div>
							<div class="bouton fl" onClick="f_del_model();" title="<?php echo T_("Remove");?>"><?php echo T_("Remove");?></div>
						</td>
						<td></td>
					</tr>
				</table>
				<div class="bouton fr" onClick="f_saveCR_model();" title="<?php echo T_("Save");?>"><?php echo T_("Save");?></div>
				<div class="clear"></div>
				<h3><?php echo T_("Existing model");?></h3>
				<div id="modelExist"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'save':
		$alw = array(
			'all'=>'alw',
			'h2'=>'a h2[title,class,id]{text-align,float,margin,padding,border}(*);',
			'h3'=>'a h3[title,class,id]{text-align,float,margin,padding,border}(*);',
			'h2c'=>'alw',
			'h3c'=>'alw',
			'img'=>'img[src,width,height,alt,title,class,id]{text-align,float,margin,padding,width,height,border}(*);',
			'li'=>'ul li span strong em u[title,class,id]{text-align,margin,padding}(*);',
			'css'=>'alw'
			);
		$con = array(
			'all'=>'<p>'.T_("Text").'</p>',
			'h2'=>'<h2>H2</h2>',
			'h3'=>'<h3>H3</h3>',
			'h2c'=>'<h2>H2</h2><p>'.T_("Text").'</p>',
			'h3c'=>'<h3>H3</h3><p>'.T_("Text").'</p>',
			'img'=>'<img src="uno/plugins/model/unomodel/icons/model.jpg" />',
			'li'=>'<ul><li>'.T_("List").'</li></ul>',
			'css'=>'<p>'.T_("Text").'</p>'
			);
		// 1. model.json
		$q = @file_get_contents('../../data/model.json');
		if($q) $a = json_decode($q,true);
		else $a = Array();
		if(isset($_POST['tw1']))
			{
			$a['tw1'] = ((isset($_POST['tw1']) && $_POST['tw1'])?$_POST['tw1']:'6');
			$a['th1'] = ((isset($_POST['th1']) && $_POST['th1'])?$_POST['th1']:'4');
			$a['th2'] = ((isset($_POST['th2']) && $_POST['th2'])?$_POST['th2']:'4');
			}
		else if(isset($_POST['cr']))
			{
			$a['list'][$_POST['nam']]['c'] = $_POST['cr'];
			$a['list'][$_POST['nam']]['i'] = $_POST['ico'];
			}
		$out = json_encode($a);
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
		if(isset($a['list'])) foreach($a['list'] as $kl=>$vl)
			{
			$t = ''; $e = '';
			foreach($vl['c'] as $k=>$v)
				{
				$v1 = explode('|',$v);
				$v2 = 0;
				if(isset($vl['c'][$k+1]))
					{
					$v3 = explode('|',$vl['c'][$k+1]);
					if(!$v1[2] && $v3[2]) $v2 = 1;
					}
				if($v2) // ouverture bloc parent
					{
					if(substr($v1[1],0,1)=='*')
						{
						$v0 = explode('**',substr($v1[1],1));
						$t .= '<div class="w3-col m'.$v1[0].' grid'.$v1[0].'"'.((isset($v0[1]) && $v0[1])?' style="'.$v0[1].'"':'').'>';
						}
					else $t .= '<div class="w3-col m'.$v1[0].' grid'.$v1[0].'">';
					}
				else
					{
					if(substr($v1[1],0,1)=='*')
						{
						$v0 = explode('**',substr($v1[1],1));
						$t .= '<div class="w3-col m'.$v1[0].' grid'.$v1[0].' col'.($k+1).'"'.((isset($v0[1]) && $v0[1])?' style="'.$v0[1].'"':'').'>'.$v0[0].'</div>';
						$e .= 'col'.($k+1).':{selector:\'.col'.($k+1).'\',allowedContent:'.($alw['css']=='alw'?'alw':'\''.$alw['css'].'\'').'},';
						}
					else
						{
						$t .= '<div class="w3-col m'.$v1[0].' grid'.$v1[0].' col'.($k+1).'">'.$con[$v1[1]].'</div>';
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
		for($v=0;$v<count($dyn);++$v)
			{
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
		$q = file_get_contents('unomodel/plugin_src.js');
		$q = str_replace('//INCLUDE//',$o,$q);
		if (file_put_contents('../../data/model.json', $out) && file_put_contents('unomodel/plugin.js', $q)) echo T_('Backup performed');
		else echo '!'.T_('Impossible backup');
		break;
		// ********************************************************************************************
		case 'delCR':
		$q = @file_get_contents('../../data/model.json');
		if($q) $a = json_decode($q,true);
		else $a = Array();
		if(isset($a['list'][$_POST['nam']])) unset($a['list'][$_POST['nam']]);
		$out = json_encode($a);
		if(file_put_contents('../../data/model.json', $out)) echo T_('Model removed');
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
