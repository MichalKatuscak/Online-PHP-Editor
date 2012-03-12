<?php

/**
 * Class for communication with FTP server
 *
 * @author Michal
 */
final class FTP {
    
    private $ftp;
    
    private $serverName;
    private $username;
private $folder;
    
    public function __construct ($server, $username, $password, $folder)
    {
        // Set up a connection 
        $conn = @ftp_connect($server) OR $GLOBALS['debuger']->add('Can\'t connect to FTP server <em>'.$server.'</em>',get_class($this).'::'.'__construct',1); 

        // Login 
        if (ftp_login($conn, $username, $password)) 
        {
            // Change the dir 
            @ftp_chdir($conn, './'.$folder);
$this->folder = './'.$folder;
            
            // Return the resource 
            $this->ftp = $conn;
            
            // Set serverName and username
            $this->serverName = $server;
            $this->username = $username;
            
            // Pasive connection
            ftp_pasv($this->ftp, true);
        } else {return false;} 
        
    }
    
    public function getSourceCode($file)
    {
        $remote_file = $file;
        $local_file = './temp/'.str_replace('/','--',$this->getFtpPwd()).'---'.$file;
        
        $handle = fopen($local_file, 'w');
        ftp_fget($this->ftp, $handle, $remote_file, FTP_ASCII, 0);
        fclose($handle);
        
        $handle = fopen($local_file, "r");
        $contents = fread($handle, filesize($local_file));
        fclose($handle);
        return $contents;
    }
    
    public function deleteFile($file,$folder)
    {
        if (ftp_delete($this->ftp,$folder.'/'.$file)) {
            return 'ok';
        }
        return $folder.'/'.$file;
    }
    
    public function getFilesFolders ($folder) 
    {
        $list = ftp_nlist($this->ftp,$folder.'/');
        $files = Array();
        $dirs = Array();
        $empty = Array();
        $editable = Array();
            //Is '..'?
        $t = ftp_pwd($this->ftp);
        if (@ftp_chdir($this->ftp,'..')===true) {
            ftp_chdir($this->ftp,$t);
            $dirs[] = '..';
        }
        foreach($list as $key=>$file) {
            if ($this->is_dir($file)) {
                if ($file != '..' && $file != '.') {
                    $dirs[] = $file;
                }
                if ($this->is_dir_empty($file)) {
                    $empty[$file] = true;
                }
            } else {
                $files[] = $file;
            }
        }
        return Array('folders'=>$dirs, 'files'=>$files, 'empty'=>$empty);
    }
    
    public function saveFile ($file,$folder,$value) 
    {
        if (file_exists('./temp/'.str_replace('/','--',$folder).'---'.$file)) {
            $local_file = './temp/'.str_replace('/','--',$folder).'---'.$file;
        }
        elseif (file_exists('./temp/'.$folder)) {
            $local_file = './temp/'.$folder;
        } 
        
        $handle = fopen($local_file, 'w');
        fwrite($handle, $value);
        fclose($handle);
        
        @$this->deleteFile($file,$folder);
        if (ftp_put($this->ftp, $file, $local_file, FTP_ASCII)) {
            return 'ok';
        }
        return $folder.'/'.$file;
    }
    
    public function getFtpPwd () 
    {
        return ftp_pwd($this->ftp);
    }
    
    private function is_dir_empty ($folder) 
    {
        $list = ftp_nlist($this->ftp,$folder);
        if (isset($list[0])) {
            return false;
        } else {
            return true;
        }
    }
    
    private function is_dir($dir) 
    {
        if (@ftp_chdir($this->ftp, '/'.$this->folder.'/'.$dir)) {
            ftp_chdir($this->ftp, '..');
            return true;
        } else {
            return false;
        }
    }
    
    public function getServerName ()
    {
        return $this->serverName;
    }
    
    public function getUsername ()
    {
        return $this->username;
    }

    
}

?>
