<?php

class View {
    
    // {{{ properties
    
    private $tpl_var = Array();
    
    // }}}
    
    // {{{ __construct()   
    
    public function __construct ($ftp) 
    {
        $this->ftp = $ftp;
        $this->template = 'ope09';
        $this->tpl_var['code'] = file_get_contents('./libs/db.class.php');
        $this->tpl_var['header'] = '<script language="javascript" src="./libs/ajax.js"></script>';
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
        $html = file_get_contents('./templates/'.$this->template.'/index.html');
        foreach ($this->tpl_var as $key=>$tpl_var) {
            $html = str_replace('{tpl var $'.$key.'}', $tpl_var, $html);
        }
        return $html;
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
		$this->tpl_var['ftp-folder'] = '';
		$this->tpl_var['ftp-folders'] = '';
		$this->tpl_var['ftp-files'] = '';
	}
    }    
    // }}}
    
}
