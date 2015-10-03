//
// CMSUno
// Plugin Model
//
var ico='',dyn=[],it={};
jQuery.getScript(modelPath+'dynamic.js');

CKEDITOR.plugins.add('unomodel',{
	requires:'widget',
	init:function(editor){
		for(var v=0;v<dyn.length;++v)(function(v){
			editor.widgets.add(dyn[v].n,{
				template:dyn[v].t,
				editables:dyn[v].e,
				allowedContent:dyn[v].a,
				requiredContent:'div(unomodel)',
				upcast:function(element){return element.name=='div'&&element.hasClass(dyn[v].u);}
			});
		})(v);
		editor.addMenuGroup('unomodel');
		for(v=0;v<dyn.length;++v){
			it[dyn[v].n]={
				label: dyn[v].l,
				group:'unomodel',
				command:dyn[v].n,
				icon:this.path+'icons/'+dyn[v].n+'.png'
			};
		}
		editor.addMenuItems(it);
		editor.ui.add('unomodel',CKEDITOR.UI_MENUBUTTON,{
			label: 'Model',
			modes:{wysiwyg:1},
			icon:this.path+'icons/unomodel.png' ,
			onMenu:function(){
				var a={},v;
				for(v in it)a[v]=CKEDITOR.TRISTATE_OFF;
				return a;
			}
		});
		if(typeof editor.config.contentsCss=='object')editor.config.contentsCss.push(CKEDITOR.getUrl(this.path+'unomodel.css'));
		else editor.config.contentsCss=[editor.config.contentsCss,CKEDITOR.getUrl(this.path+'unomodel.css')];
	}
});