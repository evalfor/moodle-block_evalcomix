						function getHTTPObject(){
							if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
							else if (window.XMLHttpRequest) return new XMLHttpRequest();
							else {
								alert("Your browser does not support AJAX.");
								return null;
							}
						}
	
						function setOutput(){
							if(httpObject.readyState == 4){
								document.body.innerHTML = httpObject.responseText;
							}
						}
	
						// Implement business logic
	
						function doWork(tag, url, valores){
							httpObject = getHTTPObject();
							if (httpObject != null) {
								httpObject.open("POST", url, true);
								httpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
								httpObject.send(valores);
								httpObject.onreadystatechange = function(){
									if(httpObject.readyState == 4){
										if(tag == 'html')
											document.write(httpObject.responseText);
										else{
											document.getElementById(tag).innerHTML = '';
											document.getElementById(tag).innerHTML = httpObject.responseText;
										}
									}
								}
							}
						}	
						var httpObject = null;
					
						function sendPost(tag, vars, form1){	
							var cadena = replace('+', '<<_mas_>>', vars);
							//replace('+', '<mas>', 'titulooo + mas');
							var tam = document.getElementById(form1).elements.length;
							for (i=1; i < tam; i++) {			
								if(document.getElementById(form1).elements[i].type == 'checkbox')
									cadena += "&" + document.getElementById(form1).elements[i].id + "=" + document.getElementById(form1).elements[i].checked;
								else if(document.getElementById(form1).elements[i].type != 'radio' && document.getElementById(form1).elements[i].id != '' && document.getElementById(form1).elements[i].id != 'addDim' && document.getElementById(form1).elements[i].id != 'addSubDim' && document.getElementById(form1).elements[i].id != 'addAtr' && document.getElementById(form1).elements[i].id != 'numvalores')
									cadena += "&" + document.getElementById(form1).elements[i].id + "=" + document.getElementById(form1).elements[i].value;
							}//alert("MENSAJE DE ESTADO   " + cadena);
							cadena = replace('+', '<<_mas_>>', cadena);
							doWork(tag, "servidor.php",cadena);
						}
						
						function replace(a, b, string){
							var result = '';
							for(var i=0;i<string.length;i++){
								var temp=string.substr(i,1);
								result += temp.replace(a,b); 								
							}
							return result;
						}
