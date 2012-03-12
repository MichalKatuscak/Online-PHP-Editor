<?php

class View {
    
    // {{{ properties
    
    private $tpl_var = Array();
    
    // }}}
    
    // {{{ __construct()   
    
    public function __construct ($ftp,$action) 
    {
        $this->ftp = $ftp;
        $this->template = 'ope09';
        $this->template_block = (isset($_GET['template_block']))? $_GET['template_block'] : 'block_default';
        $this->template_block = str_replace(array('/','..'),'-',$this->template_block);
        $this->template_block = (file_exists('./templates/'.$this->template.'/'.$this->template_block.'.html'))?$this->template_block:'block_default';
        switch ($action) {
            case 'new_ftp_block':
                $this->setFtpBlock();
                break;
            case 'new_ftp_block_login':
                $this->setFtpBlock();
                break;
            case 'source_code_from_ftp':
                $this->codeFromFtp();
                break;
            case 'source_code_from_localhost':
                $this->codeFromLocalhost();
                break;
            case 'file_upload':
                $this->uploadFile();
                break;
            case 'delete_file_from_ftp':
                $this->deleteFileFromFtp();
                break;
            case 'download_file':
                $this->downloadFile($_GET['file'],$_GET['folder'],$_GET['from']);
                break;
            case 'save_file_to_localhost':
                $this->saveFileToLocalhost($_POST['file'],$_POST['folder'],$_POST['value']);
                break;
            case 'save_file_to_ftp':
                $this->saveFileToFtp($_POST['file'],$_POST['folder'],$_POST['value']);
                break;
        }
    }    
    
    // }}}
    
    // {{{ render()    
    /**
     * Render page
     * 
     * @return string Page in HTML
     */    
    public function render () 
    {
        $html = file_get_contents('./templates/'.$this->template.'/'.$this->template_block.'.html');
        foreach ($this->tpl_var as $key=>$tpl_var) {
            $html = str_replace('{tpl var $'.$key.'}', $tpl_var, $html);
        }
        return $html;
    }    
    // }}}
    
    // {{{ uploadFile()    
    /**
     * Upload file to temp (not to FTP)
     */    
    public function uploadFile () 
    {
        $filename = $_FILES['source']['name'];
        $fullname = '---localhost---'.time().'--'.basename($filename);
        $target_path = './temp/---localhost---'.time().'--'.basename($filename);
        if (@move_uploaded_file($_FILES['source']['tmp_name'], $target_path)) {
            $this->tpl_var['content'] = '<script language="javascript">window.top.window.close_upload("'.$fullname.'","'.$filename.'");</script> ';
        } else {
            $this->tpl_var['content'] = '<script language="javascript">window.top.window.only_close_upload("File was not upload.");</script> ';
        }
    }      
    // }}}
    
    // {{{ downloadFile($file,$folder,$from)    
    /**
     * Download file from FTP or Localhost
     */    
    public function downloadFile ($file,$folder,$from) 
    {
        if ($from == 'localhost') {
            if (file_exists('./temp/'.$folder)) {
                header("Content-Description: File Transfer");
                header("Content-Type: application/force-download;charset=utf-8");
                header("Content-Disposition: attachment; filename=\"".$file."\"");
                $this->tpl_var['content'] = file_get_contents('./temp/'.$folder);
            }            
        } else {

            if (file_exists('./temp/'.str_replace('/','--',$folder).'---'.$file)) {
                header("Content-Description: File Transfer");
                header("Content-Type: application/force-download;charset=utf-8");
                header("Content-Disposition: attachment; filename=\"".$file."\"");
                $this->tpl_var['content'] = file_get_contents('./temp/'.str_replace('/','--',$folder).'---'.$file);
            }  
}
    }      
    // }}}
    
    // {{{ saveFileToLocalhost($file,$folder,$value)    
    /**
     * Download file from FTP or Localhost
     */    
    public function saveFileToLocalhost ($file,$folder,$value) 
    {
        if (file_exists('./temp/'.$folder)) {
            $handle = fopen('./temp/'.$folder, 'w');
            if (fwrite($handle, $value)) { 
                $this->tpl_var['content'] = 'ok';                    
            }
            fclose($handle); 
        }  
    }      
    // }}}
    
    // {{{ saveFileToFtp($file,$folder,$value)    
    /**
     * SAve file to FTP
     */    
    public function saveFileToFtp ($file,$folder,$value) 
    {
        $this->tpl_var['content'] = $this->ftp->saveFile($file,$folder,$value);
    }      
    // }}}
    
    // {{{ codeFromLocalhost()    
    /**
     * Show source code upload from PC
     */    
    public function codeFromLocalhost () 
    {
        if (file_exists('./temp/'.$_GET['fullname'])) {
            $this->tpl_var['content'] = file_get_contents('./temp/'.$_GET['fullname']);
        } else {
            $handle = fopen('./temp/'.$_GET['fullname'], 'w');
            fwrite($handle, "<?php\r\n\t// Created with Online PHP Editor\r\n?>");
            fclose($handle);
            if (file_exists('./temp/'.$_GET['fullname'])) {
                $this->tpl_var['content'] = file_get_contents('./temp/'.$_GET['fullname']);
            }
        }
    }      
    // }}}
    
    // {{{ codeFromFtp()    
    /**
     * Get source code from ftp
     */    
    public function codeFromFtp () 
    {
        $file = $_GET['file'];
        $this->tpl_var['content'] = $this->ftp->getSourceCode($file);
    }      
    // }}}
    
    // {{{ deleteFileFromFtp()    
    /**
     * Delete file from FTP
     */    
    public function deleteFileFromFtp () 
    {
        $file = $_GET['file'];
        $folder = $_GET['folder'];
        $this->tpl_var['content'] = $this->ftp->deleteFile($file,$folder);
    }      
    // }}}
    
    // {{{ setFtpBlock()    
    /**
     * Set FTP block
     */    
    public function setFtpBlock () 
    {
	if ($_SESSION['auth-ftp'] == 1) {
		$ftp = $this->ftp;
		$this->tpl_var['ftp-server'] = $ftp->getServerName();
		$this->tpl_var['ftp-username'] = $ftp->getUsername();
		$this->tpl_var['ftp-folder'] = $ftp->getFtpPwd();
		$all = $ftp->getFilesFolders('.');
		$this->tpl_var['ftp-folders'] = '';
		$this->tpl_var['ftp-files'] = '';
		foreach ($all['folders'] as $key=>$folder) {
		    $this->tpl_var['ftp-folders'] .= '<li><img src="./templates/ope09/images/icons/folder'. (isset($all['empty'][$folder])?'-empty':'') .'.png"> <span><a href="#" onclick="ajax(\'new_ftp_block&folder='.$this->tpl_var['ftp-folder'].'/'.$folder.'\',\'block_ftp\')">'.$folder.'</a></span></li>';
		    /*$this->tpl_var['ftp-folders'] .= '<li><img src="./templates/ope09/images/icons/folder'. (isset($all['empty'][$folder])?'-empty':'') .'.png"> <span><a href="#" onclick="ajax(\'new_ftp_block&folder='.$this->tpl_var['ftp-folder'].'/'.$folder.'\',\'block_ftp\')">'.$folder.'</a></span><img src="./templates/ope09/images/icons/close.gif" title="Delete folder" class="delete" onclick="delete_folder_from_ftp(\''.$folder.'\',\''.$this->tpl_var['ftp-folder'].'\')"></li>';*/
		}
		foreach ($all['files'] as $file) {
		    $this->tpl_var['ftp-files'] .= '<li><img src="./templates/ope09/images/icons/file.png"> <span><a href="#" onclick="new_file_to_editor_from_ftp(\''.$this->tpl_var['ftp-folder'].'\',\''.$file.'\')">'.$file.'</a></span><img src="./templates/ope09/images/icons/close.gif" title="Delete file" class="delete" onclick="delete_file_from_ftp(\''.$file.'\',\''.$this->tpl_var['ftp-folder'].'\')"></li>';
		}
		$this->tpl_var['ftp-folder'] .= '<br><input type="submit" value="Logout" onclick="ajax(\'new_ftp_block_login&logout=logout\',\'block_ftp\')">';
	} else {
		$this->tpl_var['ftp-server'] = '<input id="ftp-server">';
		$this->tpl_var['ftp-username'] = '<input id="ftp-username"><br><input id="ftp-password" type="password"><br><input type="submit" value="Login" onclick="ajax(\'new_ftp_block_login&server=\'+document.getElementById(\'ftp-server\').value+\'&login=\'+document.getElementById(\'ftp-username\').value+\'&password=\'+document.getElementById(\'ftp-password\').value,\'block_ftp\')">';
if (!$this->ftp && isset($_GET['server'])) $this->tpl_var['ftp-username'] .= '<br>Nepodařilo se připojit k FTP';
		$this->tpl_var['ftp-folder'] = '';
		$this->tpl_var['ftp-folders'] = '';
		$this->tpl_var['ftp-files'] = '';
	}
    }      
    // }}}
    
}
