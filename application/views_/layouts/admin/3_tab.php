<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

if ($this->auth->isLogin()) {
    header('Content-Type: application/javascript');
   
    if(array_key_exists(1, $this->auth->appMenu) || array_key_exists(2, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/AccessTabs.php';
    }

    if(array_key_exists(2, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/HrTabs.php';
    }

    if(array_key_exists(3, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/DocTabs.php';
    }

    if(array_key_exists(4, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/OtherTabs.php';
    }

    if(array_key_exists(5, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/GaTabs.php';
    }

    if(array_key_exists(8, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/TnPTabs.php';
    }

    if(array_key_exists(9, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/DashboardTabs.php';
    }

    if(array_key_exists(10, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/tabs/ProjectTabs.php';
    }
    
}