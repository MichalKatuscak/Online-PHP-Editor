<?php

class Presenter {
    
    private $db;
    private $ftp;
    
    private $model;
    private $view;
    
    public function setModelView() 
    {
        $this->model = new Model($this->db);
        $this->view = new View($this->ftp,@$_GET['action']);
    }
    
    public function connectDB ($server, $username, $password, $database) 
    {
        $this->db = '';//new DB($server, $username, $password, $database);
        /*$this->db->query('SELECT username,password FROM users;');
        $fetch = $this->db->fetch_array(); 
        if ($fetch) {
            foreach ($fetch as $row) {
                echo '<p>'.$row['username'].': '.$row['password'];
            }
        }*/
    }
    
    public function connectFTP ($server, $username, $password, $folder) 
    {
        if (isset($_GET['folder'])) $folder = $_GET['folder'];
	if (isset($_GET['server']) && isset($_GET['login']) && isset($_GET['password'])) {
		$_SESSION['auth-ftp'] = 1;
		$_SESSION['auth-ftp-server'] = $_GET['server'];
		$_SESSION['auth-ftp-username'] = $_GET['login'];
		$_SESSION['auth-ftp-password'] = $_GET['password'];
	}
	if (isset($_GET['logout'])) {
		$_SESSION['auth-ftp'] = 0;
		$_SESSION['auth-ftp-server'] = 0;
		$_SESSION['auth-ftp-username'] = 0;
		$_SESSION['auth-ftp-password'] = 0;
	}

	if ($_SESSION['auth-ftp'] == 1) {
        	$this->ftp= new FTP($_SESSION['auth-ftp-server'], $_SESSION['auth-ftp-username'], $_SESSION['auth-ftp-password'], $folder);
	}
    }
    
    public function render () 
    {
        if (!isset($_GET['ajax'])) {
            $this->view->setFtpBlock($this->ftp);
        }
        return $this->view->render();
    }
    
}
