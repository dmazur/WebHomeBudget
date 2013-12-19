<?php
require_once '../config.php';

class MySessionHandler {
    public function __construct() {
        session_start();
        session_set_cookie_params(0);
        
        if (!isset($_SESSION['init']))
        {
                session_regenerate_id();
                $_SESSION['init'] = true;
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        }
 
 
        if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
        {
                session_regenerate_id();
                $_SESSION['init'] = true;
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];   
        }
    }
    
    public function destroy() {
        session_destroy();
    }
    
    public function getParam($name) {
        return $_SESSION[$name];
    }
    
    public function setParam($name, $value) {
        if ($name === 'init' || $name === 'ip') {
            // TODO: handle that error
        }
        else {
            $_SESSION[$name] = $value;
        }
    }
    
    public function isParam($name) {
        return isset($_SESSION[$name]);
    }
}

?>