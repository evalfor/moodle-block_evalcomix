
        var req;
        var validated = -1;
        var timer = null;
      
        function trim(str){ return str.replace(/^\s+|\s+$/, ''); }
        
        function resetValidated(){ validated = -1; }
        
        function validate(){
                var errstr = "";
                var num = -1;
                var fields = new Array(3);
                fields[0] = "id_s__evalcomix_serverurl";
                
                if(trim(document.getElementById("id_s__evalcomix_serverurl").value).length == 0){
                        errstr += "* server_url is empty";
                        num = 0;
                }else if (trim(document.getElementById("id_s__evalcomix_serverurl").value).search(/http:\/\//i) == -1){
                        errstr += "* server_url not valid";
                        num = 0;
                }
              
                
                if(errstr.length > 0){
                        alert(errstr);
                        document.getElementById(fields[num]).focus();
                        validated = 0;
                }else{
                        verify();
                }
        }
        
        function createQuery(){
                var pairs = new Array();
                pairs.push("u="+encodeURIComponent(document.getElementById("id_s__evalcomix_serverurl").value));
                return pairs.join("&");
        }        

        function verify(){
                timer = setTimeout('timeCount()', 5000);
                var url = '../blocks/evalcomix/verify.php';
                var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
                var query = createQuery();
            if (window.XMLHttpRequest) { // Non-IE browsers
                req = new XMLHttpRequest();
                req.onreadystatechange = getResult;
                try {
                    req.open("POST", url, true);
                                req.setRequestHeader("Content-Type", contentType);
                                req.send(query);
                } catch (e) {
                                clearTimeout(timer);
                    alert(e);
                }
            } else if (window.ActiveXObject) { // IE
                req = new ActiveXObject("Microsoft.XMLHTTP");
                if (req) {
                    req.onreadystatechange = getResult;
                    req.open("POST", url, true);
                                req.setRequestHeader("Content-Type", contentType);
                    req.send(query);
                }else{
                                clearTimeout(timer);
                        }
            }
        }
        function getResult(){
                if(req.readyState == 4){ //completed
                        if (req.responseText != '1'){
                                alert(req.responseText);
                        }
                        clearTimeout(timer);
                }
        }
        
        function timeCount(){
                var originalTxt = document.getElementById("validatebtn").value;
                document.getElementById("validatebtn").value = originalTxt + "...";
        }

