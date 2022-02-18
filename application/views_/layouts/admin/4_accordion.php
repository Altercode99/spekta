<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    function checkTrees() {
        if(sidebar.isItemHidden("trees")) {
            sidebar.hideItem("info");
            sidebar.hideItem("user");
            sidebar.showItem("trees");
        }
    }

JS;

if ($this->auth->isLogin()) {
    header('Content-Type: application/javascript');
    echo $script;
    
    if(array_key_exists(1, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/AccessAccordions.php';
    }

    if(array_key_exists(2, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/HrAccordions.php';
    }

    if(array_key_exists(3, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/DocAccordions.php';
    }

    if(array_key_exists(4, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/OtherAccordions.php';
    }

    if(array_key_exists(5, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/GaAccordions.php';
    }

    if(array_key_exists(6, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/ProductionAccordions.php';
    }

    if(array_key_exists(7, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/WarehouseAccordions.php';
    }

    if(array_key_exists(8, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/TnPAccordions.php';
    }

    if(array_key_exists(9, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/DashboardAccordions.php';
    }

    if(array_key_exists(10, $this->auth->appMenu)) {
        require_once APPPATH . 'views/layouts/admin/accordions/ProjectAccordions.php';
    }

}