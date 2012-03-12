<?php

/**
 * Online PHP Editor
 *
 * PHP version 5.3
 * 
 * @author     Michal Katuščák <michal@katuscak.cz>
 * @copyright  2011 Michal Katuščák
 * @version    0.1
 * @link       http://onlinephpeditor.katuscak.cz
 */

session_start();
header ('Content-type:text/html;charset=utf-8');

include_once ('./libs/debuger.class.php');
include_once ('./libs/db.class.php');
include_once ('./libs/ftp.class.php');
include_once ('./config.php');

include_once ('./kernel/model.class.php');
if (isset($_GET['ajax'])) {
    include_once ('./kernel/view-ajax.class.php');
} else {
    include_once ('./kernel/view.class.php');
}
include_once ('./kernel/presenter.class.php');

$GLOBALS['debuger'] = new Debuger;

$presenter = new Presenter;
$presenter->connectDB($db['server'],$db['username'],$db['password'],$db['database']);
$presenter->connectFTP($ftp['server'],$ftp['username'],$ftp['password'],$ftp['folder']);
$presenter->setModelView();

echo $presenter->render();

//echo $GLOBALS['debuger']->render();