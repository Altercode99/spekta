<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;

class AppController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
        $this->load->model('OtherModel', 'Other');
        $this->Other->myConstruct('main');
    }

    public function index()
    {
        $this->load->view('app');
    }

    public function loadViews()
    {
        $type = $this->input->get('type');
        $typePath = $type == 'layout' ? '/layouts/admin' : '/admin';
        $params = getParam();
        if (isset($params['file'])) {
            $file = $params['file'];
            if ($file != "app.php") {
                $this->load->view($typePath . '/' . $file);
            }
        }
    }

    public function listViews()
    {
        $type = $this->input->get('type');
        $typePath = $type == 'layout' ? '/views/layouts/admin' : '/views/admin';
        $path = APPPATH . $typePath;
        $dh = opendir($path);
        while (false !== ($filename = readdir($dh))) {
            $files[] = $filename;
        }
        $allviews = preg_grep('/\.php$/i', $files);
        $listview = [];
        foreach ($allviews as $view) {
            $listview[] = $view;
        }
        sort($listview);
        echo (json_encode($listview));
    }

    public function loadPrivilageViews()
    {
        $params = getParam();
        if (isset($params['file'])) {
            $file = $params['file'];
            if ($file != "app.php") {
                $this->load->view($file);
            }
        }
    }

    public function listPrivilageViews()
    {
        $deptId = $this->auth->deptId;
        $subId = $this->auth->subId;
        $rankId = $this->auth->rankId;
        $pltDeptId = $this->auth->pltDeptId;
        $pltSubId = $this->auth->pltSubId;
        $pltRankId = $this->auth->pltRankId;

        $listview = [
            'menu' => [],
            'accordions' => [],
            'trees' => [],
            'files' => [],
        ];
        $loadedFolder = [];

        if ($this->auth->role === 'admin') {
            $menuList = $this->Main->getAll('menus')->result();
        } else {
            $users_accordion = 'users_accordion';
            $usersMenu = $this->Main->getWhere('users_menu', ['status' => 'ACTIVE'], 'id,menu_id', null, null, ['sub_id' => [$subId, $pltSubId], 'rank_id' => [$rankId, $pltRankId]])->result();
            if (!$usersMenu) {
                $usersMenu = $this->Main->getWhere('users_menu_dept', ['status' => 'ACTIVE'], 'id,menu_id', null, null, ['dept_id' => [$deptId, $pltDeptId], 'rank_id' => [$rankId, $pltRankId]])->result();
                $users_accordion = 'users_accordion_dept';
            }

            $menuIds = [];
            $userMenuIds = [];
            foreach ($usersMenu as $menu) {
                $menuIds[] = $menu->menu_id;
                $userMenuIds[] = $menu->id;
            }

            $menuIds = array_unique($menuIds);
            $userMenuIds = array_unique($userMenuIds);

            $accIds = [];
            $accTrees = [];
            if ($usersMenu) {
                $menuList = $this->Main->getWhereIn('menus', ['id' => $menuIds])->result();
                $userAccs = $this->Main->getWhereIn($users_accordion, ['user_menu_id' => $userMenuIds])->result();
                foreach ($userAccs as $userAcc) {
                    if ($userAcc->status === 'ACTIVE') {
                        $accIds[] = $userAcc->acc_id;
                        $trees = explode(',', $userAcc->trees);
                        foreach ($trees as $key => $value) {
                            if ($value) {
                                $listview['trees'][] = $value;
                            }
                        }
                    }
                }
            }

            if (count($accIds) > 0) {
                $accIds = array_unique($accIds);
                $accordions = $this->Main->getWhereIn('accordions', ['id' => $accIds])->result();
                foreach ($accordions as $acc) {
                    $listview['accordions'][] = $acc->code;
                }
            }
        }

        $treeFiles = [];
        if ($this->auth->role !== "admin") {
            if (count($listview['trees']) > 0) {
                $trees = $this->Main->getWhereIn('acc_trees', ['code' => $listview['trees']])->result();
                foreach ($trees as $tree) {
                    if ($tree->file != '') {
                        $expTree = explode(',', $tree->file);
                        foreach ($expTree as $file) {
                            $treeFiles[$file] = $file;
                        }
                    }
                }
            }
        }

        if (isset($menuList)) {
            foreach ($menuList as $menu) {
                $listview['menu'][] = $menu->name;
                if ($menu->main_folder) {
                    $folders = explode(',', $menu->main_folder);
                    foreach ($folders as $key => $folder) {
                        if (!array_key_exists($folder, $loadedFolder)) {
                            $loadedFolder[$folder] = true;
                            $views = $this->loadPrivView($folder);
                            foreach ($views as $key => $view) {
                                if ($this->auth->role !== "admin") {
                                    if ($view['filename'] == 'document.php') {
                                        $listview['files'][] = 'admin/' . $view['folder'];
                                    } else {
                                        if (array_key_exists($view['filename'], $treeFiles)) {
                                            $listview['files'][] = 'admin/' . $view['folder'];
                                        }
                                    }
                                } else {
                                    $listview['files'][] = 'admin/' . $view['folder'];
                                }
                            }
                        }
                    }
                }
                if ($menu->subfolder_1) {
                    $folders = explode(',', $menu->subfolder_1);
                    foreach ($folders as $key => $folder) {
                        if (!array_key_exists($folder, $loadedFolder)) {
                            $loadedFolder[$folder] = true;
                            $views = $this->loadPrivView(str_replace(':', '/', $folder));
                            foreach ($views as $key => $view) {
                                if ($this->auth->role !== "admin") {
                                    if (array_key_exists($view['filename'], $treeFiles)) {
                                        $listview['files'][] = 'admin/' . $view['folder'];
                                    }
                                } else {
                                    $listview['files'][] = 'admin/' . $view['folder'];
                                }
                            }
                        }
                    }
                }
                if ($menu->subfolder_2) {
                    $folders = explode(',', $menu->subfolder_2);
                    foreach ($folders as $key => $folder) {
                        $views = $this->loadPrivView(str_replace(':', '/', $folder));
                        if (!array_key_exists($folder, $loadedFolder)) {
                            $loadedFolder[$folder] = true;
                            foreach ($views as $key => $view) {
                                if ($this->auth->role !== "admin") {
                                    if (array_key_exists($view['filename'], $treeFiles)) {
                                        $listview['files'][] = 'admin/' . $view['folder'];
                                    }
                                } else {
                                    $listview['files'][] = 'admin/' . $view['folder'];
                                }
                            }
                        }
                    }
                }
            }
        }

        echo json_encode([
            'menu' => $listview['menu'],
            'accordions' => $listview['accordions'],
            'trees' => $listview['trees'],
            'files' => $listview['files'],
        ]);
    }

    public function loadPrivView($folder)
    {
        $typePath = '/views/admin/' . $folder;
        $path = APPPATH . $typePath;
        $dh = opendir($path);
        while (false !== ($filename = readdir($dh))) {
            $files[] = $filename;
        }
        $allviews = preg_grep('/\.php$/i', $files);
        $listview = [];
        foreach ($allviews as $view) {
            $listview[] = [
                'filename' => $view,
                'folder' => $folder . '/' . $view,
            ];
        }

        return $listview;
    }

    public function getLocation()
    {
        $params = getParam();
        $datas = $this->Main->getAll('locations')->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => $data->name,
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
    }
    public function checkSession()
    {
        if (!$this->auth->isLogin()) {
            response(['status' => 'expired']);
        } else {
            response(['status' => 'valid']);
        }
    }

    public function getCities()
    {
        $combo = new ComboConnector($this->db, "PHPCI");
        $params = getParam();
        if (strlen($params['destination']) > 3) {
            $combo->render_sql("SELECT * FROM $this->kf_main.cities WHERE city_name LIKE '%$params[destination]%' ORDER BY city_name ASC LIMIT 25", 'city_name', 'city_name');
        }
    }

    public function getHolidays()
    {
        $frees = $this->Hr->getAll('national_days')->result();
        $freeDays = '';
        foreach ($frees as $free) {
            if ($freeDays == '') {
                $freeDays = $free->date;
            } else {
                $freeDays = $freeDays . ',' . $free->date;
            }
        }
        response(['status' => 'success', 'holidays' => $freeDays]);
    }

    public function getHolidaysView()
    {
        $post = fileGetContent();
        $date = date('Y-m-d', strtotime($post->date));
        $date = explode('-', $date);
        $frees = $this->Hr->getWhere('national_days', ['YEAR(date)' => $date[0], 'MONTH(date)' => $date[1]])->result();
        $freeDays = [];
        foreach ($frees as $free) {
            $freeDays[] = [
                'id' => $free->id,
                'date' => toIndoDate($free->date),
                'description' => maxStringLength($free->description, 40),
                'path' => './public/codebase/icons/clock.png',
            ];
        }

        response(['status' => 'success', 'data' => $freeDays]);
    }

    public function getProfile()
    {
        $post = fileGetContent();
        $emp = $this->HrModel->getEmpByUserId($this->auth->userId);
        $user = $this->Main->getDataById('users', $this->auth->userId);
        $spv = $this->Hr->getOne('employees', ['nip' => $emp->direct_spv]);
        if ($spv) {
            $spvName = $spv->name;
        } else {
            $spvName = '-';
        }
        $data = [
            'emp' => $emp,
            'user' => $user,
            'spv' => $spvName,
        ];
        $template = $this->load->view('html/profile', $data, true);
        response(['status' => 'success', 'profile' => $template]);
    }
}
