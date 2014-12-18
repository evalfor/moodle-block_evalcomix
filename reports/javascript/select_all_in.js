
function select_all_in(){
	var inputs=document.getElementsByTagName("input");
	var flag = 1;
				
	for(var i=0;i<inputs.length;++i){
		if(inputs[i].type=="checkbox"){
			if(inputs[i].checked!="checked" && inputs[i].checked!=true){
				inputs[i].checked="checked";
				flag = 0;			
			}
		}
	}
	if(flag == 1){
		for(var i=0;i<inputs.length;++i){
			if(inputs[i].type=="checkbox"){
				inputs[i].checked="";
			}
		}
	}
}
			