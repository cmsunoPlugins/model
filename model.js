//
// CMSUno
// Plugin Model
//
var modelL=[];
function f_load_model(){
	var o='<table class="list">',n=0,a='';
	document.getElementById("cr2").options[1].disabled=true;
	fetch("uno/data/model.json?r="+Math.random())
	.then(r=>r.json())
	.then(function(data){
		if(data.tw1){
			t=document.getElementById("tw1");
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==data.tw1){to[v].selected=true;v=to.length;}}
		}else document.getElementById("tw1").options[5].selected=true;
		if(data.th1){
			t=document.getElementById("th1");
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==data.th1){to[v].selected=true;v=to.length;}}
		}else document.getElementById("th1").options[3].selected=true;
		if(data.th2){
			t=document.getElementById("th2");
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==data.th2){to[v].selected=true;v=to.length;}}
		}else document.getElementById("th2").options[3].selected=true;
		f_draw_model('twocol');f_draw_model('threecol');
		if(data.list){
			for(k in data.list)if(data.list.hasOwnProperty(k))(function(k){
				let v=data.list[k];
				modelL[n]=[];modelL[n]['c']=v.c;modelL[n]['i']=v.i;modelL[n]['n']=k;
				o+='<tr><td><input type="radio" name="modelRadio" value="'+k+'" '+(n==0?'checked':'')+' onClick="f_drawCR_model('+n+')">&nbsp;</td>';
				o+='<td>'+k+'<img src="uno/plugins/model/unomodel/icons/'+v.i+'.png" id="cricon" style="border:1px solid #aaa;padding:3px;margin:0 7px -7px 20px;border-radius:2px;" /></td>';
				o+='<td>';
				for(w=0;w<v.c.length;++w){
					a=v.c[w].split('|');
					a[1]=a[1].replace(/!i!/g,'|');
					o+=(w!=0?'-':'')+'&nbsp;'+a[0]+'/12&nbsp;('+a[1].replace(/</g,'&lt;')+')'+(a[2]==1?'(in)':'')+'&nbsp;';
				}
				o+='</td>';
				o+='<td onClick="f_delCR_model(\''+k+'\')">X</td></tr>';
				++n;
			})(k);
			f_drawCR_model(0);
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
	document.getElementById("cricon").src='uno/plugins/model/unomodel/icons/'+modelL[f]['i']+'.png';
	t=document.getElementById("cri");
	to=t.options;
	for(w=0;w<to.length;++w){if(to[w].value==modelL[f]['i']){to[w].selected=true;w=to.length;}}
	document.getElementById("crn").value=modelL[f]['n'];
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
	let cr=[],s=document.getElementById("crView").getElementsByTagName('DIV'),v,ico;
	ico=document.getElementById("cri").options[document.getElementById("cri").selectedIndex].value;
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
