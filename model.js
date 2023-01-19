//
// CMSUno
// Plugin Model
//
var modelL=[];
function f_load_model(){
	var o='<table class="list">',n=0,a='';
	document.getElementById("cr2").options[1].disabled=true;
	document.getElementById("newModel").style.display='none';
	fetch("uno/data/model.json?r="+Math.random())
	.then(r=>r.json())
	.then(function(data){
		if(data.tw1)document.getElementById("tw1").value=data.tw1;
		else document.getElementById("tw1").options[5].selected=true;
		if(data.th1)document.getElementById("th1").value=data.th1;
		else document.getElementById("th1").options[3].selected=true;
		if(data.th2)document.getElementById("th2").value=data.th2;
		else document.getElementById("th2").options[3].selected=true;
		f_draw_model('twocol');f_draw_model('threecol');
		if(data.list){
			for(k in data.list)if(data.list.hasOwnProperty(k))(function(k){
				let v=data.list[k],w,t;
				modelL[n]=[];modelL[n]['c']=v.c;modelL[n]['i']=v.i;modelL[n]['r']=v.r;modelL[n]['n']=k;
				o+='<tr><td><input type="radio" name="modelRadio" value="'+k+'" onClick="f_drawCR_model('+n+')">&nbsp;</td>';
				o+='<td>'+k+'<br><img src="uno/plugins/model/unomodel/icons/'+v.i+'.png" id="cricon" style="border:1px solid #aaa;padding:3px;margin:0 7px -7px 20px;border-radius:2px;" /></td>';
				o+='<td><div style="color:#333">';
				o+='<i class="modelResp'+((typeof v.r!=='undefined')?v.r:0)+'"></i></div>';
				for(w=0;w<v.c.length;++w){
					a=v.c[w].split('|');
					a[1]=a[1].replace(/!i!/g,'|');
					t=(w+1)+'';
					o+='<span class="modelColt'+t.substr(-1,1)+'">'+(w!=0?'-':'')+'&nbsp;'+a[0]+'/12&nbsp;('+a[1].replace(/</g,'&lt;')+')'+(a[2]==1?'(in)':'')+'</span>&nbsp;';
				}
				o+='</td>';
				o+='<td onClick="f_delCR_model(\''+k+'\')">X</td></tr>';
				++n;
			})(k);
			document.getElementById("modelExist").innerHTML=o+'</table>';
			document.getElementById("cr2").options[1].disabled=false;
		}
		else document.getElementById("crView").innerHTML="";
	});
}
function f_save_model(){
	let tw1=document.getElementById("tw1").options[document.getElementById("tw1").selectedIndex].value;
	let th1=document.getElementById("th1").options[document.getElementById("th1").selectedIndex].value;
	let th2=Math.min(document.getElementById("th2").options[document.getElementById("th2").selectedIndex].value,(11-th1));
	let x=new FormData();
	x.set('action','save');
	x.set('unox',Unox);
	x.set('tw1',tw1);
	x.set('th1',th1);
	x.set('th2',th2);
	fetch('uno/plugins/model/model.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(r=>f_alert(r));
}
function f_draw_model(f){
	var a,b;
	if(f=='twocol'){
		a=document.getElementById("tw1").options[document.getElementById("tw1").selectedIndex].value;
		if(a){
			document.getElementById("twa").style.width=f_gr_model(a)+'%';
			document.getElementById("twb").style.width=f_gr_model(12-a)+'%';
		}
	}
	else if(f=='threecol'){
		a=document.getElementById("th1").options[document.getElementById("th1").selectedIndex].value;
		b=Math.min(document.getElementById("th2").options[document.getElementById("th2").selectedIndex].value,(11-a));
		if(a&&b){
			document.getElementById("tha").style.width=f_gr_model(a)+'%';
			document.getElementById("thb").style.width=f_gr_model(b)+'%';
			document.getElementById("thc").style.width=f_gr_model(12-a-b)+'%';
		}
	}
}
function f_drawCR_model(f){
	var a,b,v,w,s=document.getElementById("crView").getElementsByTagName('DIV'),u=-1;
	document.getElementById("crView").innerHTML="";
	if(f==-1){
		let q=document.querySelectorAll("#modelExist input[type='radio']"),i;
		for(i=0;i<q.length;++i)q[i].checked=false;
		document.getElementById("crn").value='';
		document.getElementById("cri").value=0;
		document.getElementById("crr").value=0;
		document.getElementById("cricon").src='uno/plugins/model/unomodel/icons/0.png';
		return;
	}
	document.getElementById("newModel").style.display='table-row';
	for(w=0;w<modelL[f]['c'].length;++w){
		b=modelL[f]['c'][w].split('|');
		a=document.createElement('div');
		a.style.width=f_gr_model(b[0])+'%';
		a.className='modelCol'+(w+1)+' modelBlock';
		a.title=modelL[f]['c'][w];
		a.innerHTML=(w+1);
		if(b[2]==0)document.getElementById("crView").appendChild(a);
		else{
			for(v=0;v<s.length;++v){if(s[v].parentNode.id=='crView')u=v;}
			if(u!=-1)s[u].appendChild(a);
			for(v=0;v<s[u].childNodes.length;++v)if(s[u].childNodes[v].nodeType===3)s[u].removeChild(s[u].childNodes[v]);
		}
	}
	document.getElementById("cricon").src='uno/plugins/model/unomodel/icons/'+((typeof modelL[f]['i']!=='undefined')?modelL[f]['i']:0)+'.png';
	document.getElementById("cri").value=((typeof modelL[f]['i']!=='undefined')?modelL[f]['i']:0);
	document.getElementById("crr").value=((typeof modelL[f]['r']!=='undefined')?modelL[f]['r']:0);
	document.getElementById("crn").value=((typeof modelL[f]['n']!=='undefined')?modelL[f]['n']:'');
}
function f_add_model(){
	var cr1,cr2,cr3,a,s=document.getElementById("crView").getElementsByTagName('DIV'),t=s.length+1+'',u=-1;
	cr1=document.getElementById("cr1").options[document.getElementById("cr1").selectedIndex].value;
	cr2=document.getElementById("cr2").options[document.getElementById("cr2").selectedIndex].value;
	cr3=document.getElementById("cr3").options[document.getElementById("cr3").selectedIndex].value;
	if(cr3=='css')cr3='*'+document.getElementById("crh").value.replace(/\|/g,'!i!')+'**'+document.getElementById("crs").value.replace(/(<([^>]+)>)/ig,'').replace(/\|/g,'!i!');
	a=document.createElement('div');
	a.style.width=f_gr_model(cr1)+'%';
	a.className='modelCol'+t.substr(t.length-1,1)+' modelBlock';
	a.title=cr1+'|'+cr3+'|'+cr2;
	a.innerHTML=t;
	if(cr2==0)document.getElementById("crView").appendChild(a);
	else{
		for(v=0;v<s.length;++v)if(s[v].parentNode.id=='crView')u=v;
		if(u!=-1)s[u].appendChild(a);
		for(v=0;v<s[u].childNodes.length;++v)if(s[u].childNodes[v].nodeType===3)s[u].removeChild(s[u].childNodes[v]);
	}
	document.getElementById("cr2").options[1].disabled=false;
}
function f_del_model(){
	var s=document.getElementById("crView").getElementsByTagName('DIV');
	if(s.length){
		s[s.length-1].parentNode.removeChild(s[s.length-1]);
		if(s.length&&s[s.length-1].parentNode==document.getElementById("crView"))s[s.length-1].innerHTML=s.length;
	}
	if(!s.length)document.getElementById("cr2").options[1].disabled=true;
}
function f_delCR_model(f){
	let x=new FormData();
	x.set('action','delCR');
	x.set('unox',Unox);
	x.set('nam',f);
	fetch('uno/plugins/model/model.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_model();
	});
}
function f_saveCR_model(){
	let cr=[],s=document.getElementById("crView").getElementsByTagName('DIV'),v,ico,res,nam;
	ico=document.getElementById("cri").options[document.getElementById("cri").selectedIndex].value;
	res=document.getElementById("crr").options[document.getElementById("crr").selectedIndex].value;
	nam=document.getElementById("crn").value;
	for(v=0;v<s.length;++v){
		cr[v]=s[v].title; // grid
	}
	if(nam.length&&s.length){
		let x=new FormData();
		x.set('action','save');
		x.set('unox',Unox);
		x.set('cr',JSON.stringify(cr));
		x.set('ico',ico);
		x.set('res',res);
		x.set('nam',nam);
		fetch('uno/plugins/model/model.php',{method:'post',body:x})
		.then(r=>r.text())
		.then(function(r){
			f_alert(r);
			f_load_model();
		});
	}
}
function f_custom_model(f){
	if(f.options[f.selectedIndex].value=='css'){
		document.getElementById('crhtm').style.display=='table-row';
		document.getElementById('crcss').style.display=='table-row';
	}
	else{
		document.getElementById('crhtm').style.display=='none';
		document.getElementById('crcss').style.display=='none';
		document.getElementById('crh').value='<p>Text</p>';
		document.getElementById('crs').value='';
	}
}
function f_gr_model(f){var gr=[];gr[0]=0;gr[1]=8.33333333;gr[2]=16.66666667;gr[3]=25;gr[4]=33.33333333;gr[5]=41.66666667;gr[6]=50;gr[7]=58.33333333;gr[8]=66.66666667;gr[9]=75;gr[10]=83.33333333;gr[11]=91.66666667;gr[12]=100;return gr[f];}
//
f_load_model();
