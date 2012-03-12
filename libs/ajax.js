var mouseX,mouseY,count_file=0,this_file=0,files=new Array(),panelBgColorHidden='#eee',panelBgColor='#e5e5e5',this_editor = new Array();
function ajax_open_window_upload_file () 
{
    document.getElementById('black_page').style.display = 'block';
    document.getElementById('ajax_notice_html').innerHTML = '<h1>Upload souboru <img onclick="ajax_close_window()" title="Close" src="./templates/ope09/images/icons/close_big.gif" class="ajax_notice_close"></h1>';
    document.getElementById('ajax_notice_html').innerHTML += '<form action="./index.php?ajax&action=file_upload" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="start_upload();"><center><p id="file_loader" style="display:none;"><img src="./images/loader.gif"></p><p id="upload_form"><label>File: <input name="source" type="file" size="30"></label><input type="submit" value="Upload"><iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></center></form>';
    document.getElementById('ajax_notice').style.display = 'block';
}
function ajax_open_window_new_file () 
{    
    setOpacity('black_page','0.1');
    setTimeout("setOpacity('black_page','0.2')",10);
    setTimeout("setOpacity('black_page','0.3')",20);
    setTimeout("setOpacity('black_page','0.4')",30);
    setTimeout("setOpacity('black_page','0.5')",40);
    setTimeout("setOpacity('black_page','0.6')",50);
    document.getElementById('black_page').style.display = 'block';
    document.getElementById('ajax_notice_html').innerHTML = '<h1>File name <img onclick="ajax_close_window()" title="Close" src="./templates/ope09/images/icons/close_big.gif" class="ajax_notice_close"></h1>';
    document.getElementById('ajax_notice_html').innerHTML += '<form action="#" onsubmit="create_new_file()"><p style="text-align:center;"><input size="30" id="new_file_name"> <input type="submit" value="Create"></form>';
    document.getElementById('ajax_notice').style.display = 'block';
}
function setOpacity(id,opacity)
{
    document.getElementById(id).style.opacity = opacity;
}
function create_new_file ()
{
    var file = document.getElementById('new_file_name').value;
    ajax_close_window ();
    new_file_to_editor_from_localhost('---localhost---'+file,file);
}
function download_this_file ()
{
    if (this_file > 0 && typeof files[this_file] != 'undefined') {
        if (files[this_file][2] == 1) {
            save_this_to_localhost();
        } else {
            folder = files[this_file][0];
            file = files[this_file][1];
            from = files[this_file][3];
            var zdroj = './index.php?ajax&template_block=default&action=download_file&from='+from+'&file='+file+'&folder='+folder;
            var new_window = window.open(zdroj, "_blank");
            window.focus();
        }
    }
}
function save_this_to_localhost () 
{
    var folder = files[this_file][0];
    var file = files[this_file][1];            
    var from = files[this_file][3];
    
    var value = this_editor[this_file].getValue();
    var id = 'default';
    var params = 'file='+file+'&folder='+encodeURIComponent(folder)+'&value='+encodeURIComponent(value);
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action=save_file_to_localhost';
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("POST",zdroj,true);
        objekt.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                var text = objekt.responseText;
                
                if (text != 'ok') {
                    alert('File can\'t be download.'+text);
                } else {
                    var zdroj = './index.php?ajax&template_block=default&action=download_file&from='+from+'&file='+file+'&folder='+folder;
                    var new_window = window.open(zdroj, "_blank");
                    window.focus();
                }
                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(params);
    }    
}
function save_this_file () 
{
    var folder = files[this_file][0];
    var file = files[this_file][1];            
    var from = files[this_file][3];
    
    var value = this_editor[this_file].getValue();
    var id = 'default';
    var params = 'file='+file+'&folder='+encodeURIComponent(folder)+'&value='+encodeURIComponent(value);
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action=save_file_to_ftp';
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("POST",zdroj,true);
        objekt.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                var text = objekt.responseText;
                
                if (text != 'ok') {
                    alert('File can\'t be save.'+text);
                } else {
                    ajax('new_ftp_block&folder='+folder,'block_ftp');
                    if (files[this_file][2] == 1) {
                        files[this_file][2] = 0;
                    }
                    document.getElementById('button_save').disabled = 'disabled';
                    var str = document.getElementById('panel_a_'+this_file).innerHTML;
                    document.getElementById('panel_a_'+this_file).innerHTML = str.slice(0,str.length-1);
                }
                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(params);
    }    
}
function delete_file_from_ftp (file,ftp_folder)
{
    var id = 'default';
    var url = 'delete_file_from_ftp&file='+file+'&folder='+ftp_folder;
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action='+url;
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("GET",zdroj,true);
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                var text = objekt.responseText;
                
                if (text != 'ok') {
                    alert('File was not deleted.'+text)
                }                 
                
                ajax('new_ftp_block&folder='+ftp_folder,'block_ftp');
                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(null);
    }
}
function start_upload()
{
    document.getElementById('file_loader').style.display = 'block';
    document.getElementById('upload_form').style.display = 'none';
}
function close_upload(fullname,file)
{
    ajax_close_window ();
    new_file_to_editor_from_localhost (fullname ,file)
}
function only_close_upload(error)
{
    ajax_close_window ();
    alert(error);
}
function ajax_close_window ()
{
    document.getElementById('ajax_notice').style.display = 'none';
    document.getElementById('black_page').style.display = 'none';
}
function show_this_file (id)
{
    if (typeof files[id] != 'indefined') {
        hide_file_from_editor(this_file);
        this_file = id;
        show_file_from_editor(this_file);
    }
}
function file_is_edit (id)
{
    if (files[id][2] == 0) {
        files[id][2] = 1;
        document.getElementById('button_save').disabled = false;
        document.getElementById('panel_a_'+id).innerHTML += '*';
        //myStr = myStr.slice(0,strLen-1);
    }
}
function new_file_to_editor_from_ftp (folder,file)
{
    for (var f in files) {
        if (files[f][1] == file && files[f][0] == folder) {
            show_this_file(f);
            return 0;
        }
    } 
    count_file++;
    if (this_file != 0) {
        hide_file_from_editor(this_file);
    }
    document.getElementById('ajax_loader').style.display = 'block';
    document.getElementById('panels').innerHTML += '<span class="active" id="panel_'+count_file+'" title="'+folder+'"><a onclick="show_this_file('+count_file+')" id="panel_a_'+count_file+'">'+file+'</a><img src="./templates/ope09/images/icons/close.gif" class="close_icon" onclick="close_file_from_editor('+count_file+')"></span>';   
    document.getElementById('editor').innerHTML += '<div id="file_'+count_file+'" onkeypress="file_is_edit('+count_file+')" style="display:none"><textarea id="code_'+count_file+'" name="code"></textarea></div>';

    url = 'source_code_from_ftp&file='+file+'&folder='+folder;
    id = 'code_'+count_file;
    
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action='+url;
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("GET",zdroj,true);
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                document.getElementById('ajax_loader').style.display = 'none';
                document.getElementById(id).innerHTML = objekt.responseText;
                
                // Folder, File, Save, (FTP || Localhost)
                files[count_file] = new Array(folder,file,0,'ftp');
                
                show_file_from_editor(count_file);
                this_file = count_file;
                var cms = document.getElementsByClassName('CodeMirror-scroll');
                for (var cm in cms) {
                    if (typeof cms[cm].style != 'undefined') {
                        cms[cm].style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                        cms[cm].style.height = (document.documentElement.clientHeight - 70)+'px';
                    }
                }
                document.getElementById('block_ftp').style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                document.getElementById('block_ftp').style.height = (document.documentElement.clientHeight - 70)+'px';
                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(null);
    }
}
function new_file_to_editor_from_localhost (folder,file)
{
    for (var f in files) {
        if (files[f][1] == file && files[f][0] == folder) {
            show_this_file(f);
            return 0;
        }
    } 
    count_file++;
    if (this_file != 0) {
        hide_file_from_editor(this_file);
    }
    document.getElementById('ajax_loader').style.display = 'block';
    document.getElementById('panels').innerHTML += '<span class="active" id="panel_'+count_file+'" title="'+folder+'"><a onclick="show_this_file('+count_file+')" id="panel_a_'+count_file+'">'+file+'</a><img src="./templates/ope09/images/icons/close.gif" class="close_icon" onclick="close_file_from_editor('+count_file+')"></span>';   
    document.getElementById('editor').innerHTML += '<div id="file_'+count_file+'" onkeypress="file_is_edit('+count_file+')" style="display:none"><textarea id="code_'+count_file+'" name="code"></textarea></div>';

    url = 'source_code_from_localhost&fullname='+folder;
    id = 'code_'+count_file;
    
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action='+url;
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("GET",zdroj,true);
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                document.getElementById('ajax_loader').style.display = 'none';
                document.getElementById(id).innerHTML = objekt.responseText;
                
                // Folder, File, Save
                files[count_file] = new Array(folder,file,0,'localhost');
                file_is_edit(count_file);
                
                show_file_from_editor(count_file);
                this_file = count_file;
                var cms = document.getElementsByClassName('CodeMirror-scroll');
                for (var cm in cms) {
                    if (typeof cms[cm].style != 'undefined') {
                        cms[cm].style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                        cms[cm].style.height = (document.documentElement.clientHeight - 70)+'px';
                    }
                }
                document.getElementById('block_ftp').style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                document.getElementById('block_ftp').style.height = (document.documentElement.clientHeight - 70)+'px';
                
                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(null);
    }
}
function new_file_to_editor (file)
{
    var folder = '---localhost---';
    for (var f in files) {
        if (files[f][1] == file && files[f][0] == folder) {
            show_this_file(f);
            return 0;
        }
    } 
    count_file++;
    if (this_file != 0) {
        hide_file_from_editor(this_file);
    }
    document.getElementById('ajax_loader').style.display = 'block';
    document.getElementById('panels').innerHTML += '<span class="active" id="panel_'+count_file+'" title="'+folder+'"><a onclick="show_this_file('+count_file+')" id="panel_a_'+count_file+'">'+file+'</a><img src="./templates/ope09/images/icons/close.gif" class="close_icon" onclick="close_file_from_editor('+count_file+')"></span>';   
    document.getElementById('editor').innerHTML += '<div id="file_'+count_file+'" onkeypress="file_is_edit('+count_file+')" style="display:none"><textarea id="code_'+count_file+'" name="code"></textarea></div>';

    url = 'create_file&file='+file+'&folder='+folder;
    id = 'code_'+count_file;
    
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action='+url;
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("GET",zdroj,true);
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                document.getElementById('ajax_loader').style.display = 'none';
                document.getElementById(id).innerHTML = objekt.responseText;
                
                // Folder, File, Save
                files[count_file] = new Array(folder,file,0,'localhost');   
                file_is_edit(count_file);
                
                show_file_from_editor(count_file);
                this_file = count_file;
                var cms = document.getElementsByClassName('CodeMirror-scroll');
                for (var cm in cms) {
                    if (typeof cms[cm].style != 'undefined') {
                        cms[cm].style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                        cms[cm].style.height = (document.documentElement.clientHeight - 70)+'px';
                    }
                }
                document.getElementById('block_ftp').style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(null);
    }
}
function hide_file_from_editor (id)
{
    this_editor[id].toTextArea();
    document.getElementById('code_'+id).innerHTML = this_editor[id].getValue();
    this_editor[id].setValue('');
    panel = 'panel_'+id;
    file = 'file_'+id;
    if (document.getElementById(file)) {
      document.getElementById(file).style.display = 'none';
    }
    if (document.getElementById(panel)) {
      document.getElementById(panel).style.backgroundColor = panelBgColorHidden;
    }
}
function show_file_from_editor (id)
{
    panel = 'panel_'+id;
    file = 'file_'+id;
    this_editor[id] = CodeMirror.fromTextArea(document.getElementById("code_"+id), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
    });

                var cms = document.getElementsByClassName('CodeMirror-scroll');
                for (var cm in cms) {
                    if (typeof cms[cm].style != 'undefined') {
                        cms[cm].style.maxHeight = (document.documentElement.clientHeight - 70)+'px';
                        cms[cm].style.height = (document.documentElement.clientHeight - 70)+'px';
                    }
                }
                
    if (document.getElementById(file)) {
      document.getElementById(file).style.display = 'block';
    }
    if (document.getElementById(panel)) {
      document.getElementById(panel).style.backgroundColor = panelBgColor;
    }
    this_editor[id].refresh();
    
    //Save button
    if (files[id][2] == 0) {
        document.getElementById('button_save').disabled = 'disabled';
    } else {
        document.getElementById('button_save').disabled = false;
    }
}
function close_file_from_editor (id)
{
    panel = 'panel_'+id;
    file = 'file_'+id;
    if (document.getElementById(file)) {
      document.getElementById(file).parentNode.removeChild(document.getElementById(file));
    }
    if (document.getElementById(panel)) {
      document.getElementById(panel).parentNode.removeChild(document.getElementById(panel));
    }
    delete files[id][0];
    delete files[id][1];
    delete files[id][2];
    delete files[id][3];
    delete files[id];
    
    if (typeof files != 'undefined') {
        if (this_file == id) {
            var last_id = 0;
            for (var f in files) {
                last_id = f;
            }
            if (last_id != 0) {
                this_file = last_id;
                show_file_from_editor(this_file);
            } else {
                this_file = 0;
            }
        }
    } else {
        this_file = 0;
    }
    if (this_file == 0) {        
        document.getElementById('button_save').disabled = 'disabled';
    }
}
function positionOfMouse(e) 
{ 
    var d,b; 
    if (!e) {var e=window.event;}	//IE mouse event 
    if (e.pageX || e.pageY)	 //other 
        {mouseX=e.pageX;mouseY=e.pageY;} 
    else if (e.clientX || e.clientY)	//IE 
    { 
        d=document;d=d.documentElement?d.documentElement:d.body; 
        mouseX=e.clientX+d.scrollLeft;mouseY=e.clientY+d.scrollTop; 
    } 
} 
function addAjaxLoader(id) {
    /*positionOfMouse();
    document.getElementsByTagName('body')[0].innerHTML += '<div id="ajax_loader" style="position:absolute;top:'+(mouseY+15)+'px;left:'+(mouseX+10)+'px;"><img src="./images/ajax-loader.gif" title="Loading.."></div>';
*/document.getElementsByTagName('body')[0].style.cursor = 'wait';
    }
function delAjaxLoader(id) {
    /*var id = 'ajax_loader';
    if (document.getElementById(id)) {
        document.getElementById(id).parentNode.removeChild(document.getElementById(id));
        ajax_qpanel_free(id);
    }*/document.getElementsByTagName('body')[0].style.cursor = 'auto';
}
function ajax (url, id)
{
    
    /* AJAX Loader START */
    addAjaxLoader(id);
    /* AJAX Loader END */
    
    var objekt = false; 
    var zdroj = './index.php?ajax&template_block='+id+'&action='+url;
    if(window.XMLHttpRequest){objekt=new XMLHttpRequest();}
    else if (window.ActiveXObject) {
     try {
     objekt = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (eror) {
     objekt = new ActiveXObject("Microsoft.XMLHTTP");}
    }
    if(objekt){
        objekt.open("GET",zdroj,true);
        objekt.onreadystatechange=function(){
            if(objekt.readyState==4 && objekt.status==200){
                
                document.getElementById(id).innerHTML = objekt.responseText;
                
                /* AJAX Loader START */
                delAjaxLoader(id);
                /* AJAX Loader END */
                    
            }
        }
        objekt.send(null);
    }
}
