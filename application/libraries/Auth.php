<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth
{
    protected $ci;

    public $userId;
    public $username;
    public $roleId;
    public $role;
    public $empNip;
    public $empId;
    public $empName;
    public $deptId;
    public $department;
    public $subId;
    public $subDepartment;
    public $rankId;
    public $rank;
    public $divId;
    public $division;
    public $empLoc;
    public $locName;
    public $picOvertime;
    public $pltDepartment;
    public $pltDeptId;
    public $pltSubDepartment;
    public $pltSubId;
    public $pltDivision;
    public $pltDivId;
    public $pltRankId;

    public $kf_main;
    public $kf_hr;
    public $kf_qhse;
    public $kf_general;
    public $kf_mtn;
    public $kf_chat;

    public $appMenu = [];

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->Model('BasicModel', 'Main');
        $this->ci->Main->myConstruct('main');
        $this->ci->load->Model('BasicModel', 'Hr');
        $this->ci->Hr->myConstruct('hr');

        $this->me();
        $this->dbActive();
        $this->getAppMenu();
    }

    public function isLogin()
    {
        if (!$this->ci->session->userdata(SESSION_KEY)) {
            return false;
        } else {
            return true;
        }
    }

    public function isAuth()
    {
        if (!$this->ci->session->userdata(SESSION_KEY)) {
            die();
        }
    }

    public function me()
    {
        if ($this->ci->session->userdata(SESSION_KEY)) {
            $this->userId = $_SESSION[SESSION_KEY]["userId"];
            $this->username = $_SESSION[SESSION_KEY]["username"];
            $this->roleId = $_SESSION[SESSION_KEY]["roleId"];
            $this->role = $_SESSION[SESSION_KEY]["role"];
            $this->empNip = $_SESSION[SESSION_KEY]["empNip"];
            $this->empId = $_SESSION[SESSION_KEY]["empId"];
            $this->empName = $_SESSION[SESSION_KEY]["empName"];
            $this->deptId = $_SESSION[SESSION_KEY]["deptId"];
            $this->department = $_SESSION[SESSION_KEY]["department"];
            $this->subId = $_SESSION[SESSION_KEY]["subId"];
            $this->subDepartment = $_SESSION[SESSION_KEY]["subDepartment"];
            $this->rankId = $_SESSION[SESSION_KEY]["rankId"];
            $this->rank = $_SESSION[SESSION_KEY]["rank"];
            $this->divId = $_SESSION[SESSION_KEY]["divId"];
            $this->division = $_SESSION[SESSION_KEY]["division"];
            $this->empLoc = $_SESSION[SESSION_KEY]["empLoc"];
            $this->locName = $_SESSION[SESSION_KEY]["locName"];
            $this->picOvertime = $_SESSION[SESSION_KEY]["picOvertime"];
            $this->pltDepartment = $_SESSION[SESSION_KEY]["pltDepartment"];
            $this->pltDeptId = $_SESSION[SESSION_KEY]["pltDeptId"];
            $this->pltSubDepartment = $_SESSION[SESSION_KEY]["pltSubDepartment"];
            $this->pltSubId = $_SESSION[SESSION_KEY]["pltSubId"];
            $this->pltDivision = $_SESSION[SESSION_KEY]["pltDivision"];
            $this->pltDivId = $_SESSION[SESSION_KEY]["pltDivId"];
            $this->pltRankId = $_SESSION[SESSION_KEY]["pltRankId"];
        }
    }

    public function getAppMenu()
    {
        if ($this->role === 'admin') {
            $menus = $this->ci->Main->getAll('menus')->result();
            foreach ($menus as $menu) {
                $this->appMenu[$menu->id] = true;
            }
        } else {
            
            $menus = $this->ci->Main->getWhere('users_menu', null, 'menu_id', null, null, ['sub_id' => [$this->subId, $this->pltSubId], 'rank_id' => [$this->rankId, $this->pltRankId]])->result();
            if (!$menus) {
                $menus = $this->ci->Main->getWhere('users_menu_dept', null, 'menu_id', null, null, ['dept_id' => [$this->deptId, $this->pltDeptId], 'rank_id' => [$this->rankId, $this->pltRankId]])->result();
            }
            if ($menus) {
                foreach ($menus as $menu) {
                    $this->appMenu[$menu->menu_id] = true;
                }
            }
        }
    }

    public function dbActive()
    {
        $this->kf_main = ENVIRONMENT !== 'production' ? MAIN_DB_DEV : MAIN_DB;
        $this->kf_hr = ENVIRONMENT !== 'production' ? HR_DB_DEV : HR_DB;
        $this->kf_qhse = ENVIRONMENT !== 'production' ? QHSE_DB_DEV : QHSE_DB;
        $this->kf_general = ENVIRONMENT !== 'production' ? GENERAL_DB_DEV : GENERAL_DB;
        $this->kf_mtn = ENVIRONMENT !== 'production' ? MTN_DB_DEV : MTN_DB;
        $this->kf_chat = ENVIRONMENT !== 'production' ? CHAT_DB_DEV : CHAT_DB;
    }
}
