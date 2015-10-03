//
// CMSUno
// Plugin Model
//
var modelPath=UconfigFile[UconfigNum]+'/../unomodel/'; // used to load dynamic.js
UconfigNum++;

CKEDITOR.plugins.addExternal('unomodel',modelPath);
CKEDITOR.editorConfig = function(config){
	config.extraPlugins += ',unomodel';
	config.toolbarGroups.push('unomodel');
	if(UconfigFile.length>UconfigNum)config.customConfig=UconfigFile[UconfigNum];
};
