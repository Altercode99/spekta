<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccessController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AccessModel', 'Access');
        $this->Access->myConstruct('main');

        $this->auth->isAuth();
    }

    public function getDepartments()
    {
        $deptList = [];
        $depts = $this->Hr->getWhere('departments', ['location' => empLoc()], 'id, name')->result();
        foreach ($depts as $dept) {
            $subs = $this->Hr->getWhere('sub_departments', ['department_id' => $dept->id])->result();
            $items = [];
            foreach ($subs as $sub) {
                $items[] = [
                    'id' => 'sub-' . $sub->id,
                    'text' => $sub->name,
                    'icons' => [
                        'file' => 'icon_key',
                    ],
                ];
            }
            $deptList[] = [
                'id' => 'dept-' . $dept->id,
                'text' => $dept->name,
                'icons' => [
                    'file' => 'icon_department',
                    'folder_opened' => 'icon_department',
                    'folder_closed' => 'icon_department',
                ],
                'items' => $items,
                // 'open' => count($items) > 0 ? 1 : 0
            ];
        }
        response(['status' => 'success', 'items' => $deptList]);
    }

    public function getRanks()
    {
        $rankList = [];
        $ranks = $this->Hr->getWhere('ranks', ['location' => empLoc(), 'id >' => 2], 'id,grade,name')->result();
        foreach ($ranks as $rank) {
            $rankList[] = [
                'id' => 'rank-' . $rank->id,
                'text' => $rank->grade ? "$rank->name ($rank->grade)" : $rank->name,
                'icons' => [
                    'file' => 'icon_rank',
                ],
            ];
        }
        response(['status' => 'success', 'items' => $rankList]);
    }

    public function getMenus()
    {
        $params = getParam();
        $subId = $params['subId'];
        $rankId = $params['rankId'];

        $menus = $this->Main->getAll('menus')->result();
        $userMenus = $this->Main->getWhere('users_menu', ['sub_id' => $subId, 'rank_id' => $rankId, 'status' => 'ACTIVE'], 'menu_id')->result();
        $newUserMenus = [];
        if ($userMenus) {
            foreach ($userMenus as $user) {
                $newUserMenus[$user->menu_id] = true;
            }
        }

        $items = [];
        foreach ($menus as $menu) {
            $isChecked = array_key_exists($menu->id, $newUserMenus) ? true : false;
            $items[] = [
                'id' => 'menu-' . $menu->id,
                'text' => $menu->name,
                'checked' => $isChecked,
                'icons' => [
                    'file' => 'icon_' . $menu->icon,
                ],
            ];
        }
        response(['status' => 'success', 'items' => $items]);
    }

    public function getAccordions()
    {
        $params = getParam();
        $subId = $params['subId'];
        $rankId = $params['rankId'];
        $menuId = $params['menuId'];
        $userAccs = [];
        $parent = [];
        $child = [];

        $accordions = $this->Main->getWhere('accordions', ['menu_id' => $params['menuId']])->result();
        $usersMenu = $this->Main->getOne('users_menu', ['sub_id' => $subId, 'rank_id' => $rankId, 'menu_id' => $menuId], 'id');

        if ($usersMenu) {
            $userAccordions = $this->Main->getWhere('users_accordion', ['user_menu_id' => $usersMenu->id])->result();
            foreach ($userAccordions as $acc) {
                if ($acc->status == 'ACTIVE') {
                    $userAccs[$acc->acc_id] = true;
                }
            }
        }

        if (count($accordions) > 0) {
            foreach ($accordions as $acc) {
                $isChecked = array_key_exists($acc->id, $userAccs) ? true : false;
                $accList[] = [
                    'id' => $acc->code,
                    'text' => $acc->name,
                    'checked' => $isChecked,
                    'icons' => [
                        'file' => 'icon_list',
                    ],
                ];
            }
            response(['status' => 'success', 'items' => $accList]);
        } else {
            response(['status' => 'success', 'items' => []]);
        }
    }

    public function getTrees()
    {
        $params = getParam();
        $subId = $params['subId'];
        $rankId = $params['rankId'];
        $menuId = $params['menuId'];
        $accCode = $params['accCode'];
        $userTrees = [];

        $usersMenu = $this->Main->getOne('users_menu', ['sub_id' => $subId, 'rank_id' => $rankId, 'menu_id' => $menuId], 'id');
       
        if (!$usersMenu) {
            response(['status' => 'error', 'message' => 'Silahkan aktifkan menu untuk accordions tersebut terlebih dahulu']);
        }

        $accId = $this->Main->getOne('accordions', ['code' => $accCode], 'id')->id;
        $trees = $this->Main->getWhere('acc_trees', ['acc_id' => $accId])->result();
        $accTrees = $this->Main->getOne('users_accordion', ['user_menu_id' => $usersMenu->id, 'acc_id' => $accId], 'trees');

        if ($accTrees) {
            $accTrees = explode(',', $accTrees->trees);
            foreach ($accTrees as $tree) {
                $userTrees[$tree] = true;
            }
        }

        foreach ($trees as $tree) {
            if ($tree->parent == 0) {
                $parent[$tree->id] = [
                    'code' => $tree->code,
                    'name' => $tree->name,
                ];
            } else {
                $child[$tree->parent][] = [
                    'code' => $tree->code,
                    'name' => $tree->name,
                ];
            }
        }

        if (isset($parent) && count($parent) > 0) {
            foreach ($parent as $parentId => $parentValue) {
                $items = [];
                foreach ($child[$parentId] as $childId => $childValue) {
                    $isChildCheck = array_key_exists($childValue['code'], $userTrees) ? true : false;
                    $items[] = [
                        'id' => $childValue['code'],
                        'text' => $childValue['name'],
                        'checked' => $isChildCheck,
                        'icons' => [
                            'file' => 'menu_icon',
                        ],
                    ];
                }
                $isParentCheck = array_key_exists($parentValue['code'], $userTrees) ? true : false;
                $treesList[] = [
                    'id' => $parentValue['code'],
                    'text' => $parentValue['name'],
                    'checked' => $isParentCheck,
                    'open' => 1,
                    'icons' => [
                        'folder_opened' => 'arrow_down',
                        'folder_closed' => 'arrow_right',
                    ],
                    'items' => $items,
                ];
            }
            response(['status' => 'success', 'items' => $treesList]);
        } else {
            response(['status' => 'success', 'items' => []]);
        }
    }

    public function updateMenus()
    {
        $post = fileGetContent();
        $subId = $post->subId;
        $rankId = $post->rankId;
        $menuId = $post->menuId;
        $status = $post->status;

        $usersMenu = $this->Main->getOne('users_menu', ['sub_id' => $subId, 'rank_id' => $rankId, 'menu_id' => $menuId]);
        if (!$usersMenu) {
            $data = [
                'sub_id' => $subId,
                'rank_id' => $rankId,
                'menu_id' => $menuId,
                'status' => $status,
                'created_by' => empId(),
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->Main->create('users_menu', $data);
        } else {
            $data = [
                'status' => $status,
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->Main->updateById('users_menu', $data, $usersMenu->id);
        }

        response(['status' => 'success', 'message' => 'Update akses menu berhasil']);
    }

    public function updateAccordions()
    {
        $post = fileGetContent();
        $subId = $post->subId;
        $rankId = $post->rankId;
        $menuId = $post->menuId;
        $accCode = $post->accCode;
        $status = $post->status;

        $accId = $this->Main->getOne('accordions', ['code' => $accCode], 'id')->id;
        $usersMenu = $this->Main->getOne('users_menu', ['sub_id' => $subId, 'rank_id' => $rankId, 'menu_id' => $menuId], 'id');

        if ($usersMenu) {
            $checkAcc = $this->Main->getOne('users_accordion', ['user_menu_id' => $usersMenu->id, 'acc_id' => $accId], 'id');
            if (!$checkAcc) {
                $data = [
                    'user_menu_id' => $usersMenu->id,
                    'acc_id' => $accId,
                    'status' => $status,
                    'trees' => '',
                    'created_by' => empId(),
                    'updated_by' => empId(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->Main->create('users_accordion', $data);
            } else {
                $data = [
                    'status' => $status,
                    'updated_by' => empId(),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->Main->updateById('users_accordion', $data, $checkAcc->id);
            }
            response(['status' => 'success', 'message' => 'Update akses accordions berhasil']);
        } else {
            response(['status' => 'error', 'message' => 'Menu untuk accordions tersebut belum aktif!']);
        }
    }

    public function updateTrees()
    {
        $post = fileGetContent();
        $subId = $post->subId;
        $rankId = $post->rankId;
        $menuId = $post->menuId;
        $accCode = $post->accCode;
        $trees = $post->trees;

        $accId = $this->Main->getOne('accordions', ['code' => $accCode], 'id')->id;
        $userMenuId = $this->Main->getOne('users_menu', ['sub_id' => $subId, 'rank_id' => $rankId, 'menu_id' => $menuId], 'id')->id;

        $userTrees = "";
        foreach ($trees as $tree) {
            $userTrees = $userTrees === '' ? $tree : $userTrees . ',' . $tree;
        }

        $checkAcc = $this->Main->getOne('users_accordion', ['user_menu_id' => $userMenuId, 'acc_id' => $accId]);
        if (!$checkAcc) {
            $data = [
                'user_menu_id' => $userMenuId,
                'acc_id' => $accId,
                'trees' => $userTrees,
                'status' => 'ACTIVE',
                'created_by' => empId(),
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->Main->create('users_accordion', $data);
        } else {
            $data = [
                'trees' => $userTrees,
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->Main->update('users_accordion', $data, ['user_menu_id' => $userMenuId, 'acc_id' => $accId]);
        }

        response(['status' => 'success', 'message' => 'Update akses trees berhasil']);
    }
}
