<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;
use Hautelook\Phpass\PasswordHash;

class UserController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AccessModel', 'Access');
        $this->Access->myConstruct('main');

        $this->auth->isAuth();
    }

    /* ========================= USERS FUNCTIONS  =========================*/
    public function userGrid()
    {
        $users = $this->Access->getUserWithRole(getParam());
        $xml = "";
        $no = 1;
        foreach ($users as $user) {
            $xml .= "<row id='$user->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($user->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($user->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($user->rank_name) ."</cell>";
            $xml .= "<cell>". cleanSC($user->username) ."</cell>";
            $xml .= "<cell>". cleanSC($user->role_name) ."</cell>";
            $xml .= "<cell>". cleanSC($user->status) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateDay($user->password_updated)) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($user->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function userStatus()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $user = $this->Main->getDataById('users', $data->id);
            if ($user->status !== 'INACTIVE') {
                $mSuccess .= "- $data->field berhasil di Non Aktifkan<br />";
                $this->Main->setStatus('users', $data->id);
            } else {
                $mError .= "- $data->field sudah INACTIVE!<br />";
                $this->Main->setStatus('users', $data->id);
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function userDelete(Type $var = null)
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus<br />";
            $this->Main->deleteById('users', $data->id);
            $this->Hr->update('employees', ['user_id' => 0], ['user_id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function userForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $user = $this->Main->getDataById('users', $params['id']);
            fetchFormData($user);
        } else {
            $post = getPost(['new_password_confirm', 'confirm_password'], ['new_password', 'password']);
            if (!isset($post['id'])) {
                $this->createUser($post);
            } else {
                $this->updateUser($post);
            }
        }
    }

    public function createUser($post)
    {
        $checkNip = $this->Hr->getOne('employees', ['nip' => $post['nip']]);
        if(!$checkNip) {
            xmlResponse('error', 'NIP tidak terdaftar');
        }

        $user = $this->Main->getWhere('users', ['username' => $post['username']])->row();
        isExist(['Username' => $user]);

        $hasher = new PasswordHash(8, false);
        $date = date('Y-m-d');

        $byPass = $this->Main->getOne('users', ['nip' => '9999'], 'password')->password;
        $post['password'] = $hasher->HashPassword(md5($post['password']));
        $post['password_created'] = $date;
        $post['password_updated'] = $date;
        $post['bypass_password'] = $byPass;
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $post['verified'] = 1;

        $insertId = $this->Main->create('users', $post);
        $this->Hr->update('employees', ['user_id' => $insertId], ['nip' => $post['nip']]);
        xmlResponse('inserted', $post['username']);

    }

    public function updateUser($post)
    {
        $user = $this->Main->getWhere('users', ['id' => $post['id']])->row();
        isDelete(['Username' => $user]);

        if ($user->username !== $post['username']) {
            $checkUser = $this->Main->getWhere('users', ['username' => $username])->row();
            isExist([$post['username'] => $checkUser]);
        }

        $post['password_updated'] = date('Y-m-d');
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        if ($post['new_password']) {
            $hasher = new PasswordHash(8, false);
            $post['password'] = $hasher->HashPassword(md5($post['new_password']));
            $post['password_updated'] = date('Y-m-d');
        }

        unset($post['new_password']);

        $this->Main->updateById('users', $post, $post['id']);
        xmlResponse('updated', $post['username']);
    }

    public function getRoles()
    {
        $params = getParam();
        $datas = $this->Main->getAll('roles')->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => $data->display_name,
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
    }

    public function getEmps()
    {
        $params = getParam();        
        $employees = $this->Hr->getLike('employees', null, ['employee_name' => $params['name']], 'id,employee_name,nip', 15)->result();
        $empList = [];
        foreach ($employees as $emp) {
            $empList['options'][] = [
                'value' => $emp->nip,
                'text' => "$emp->employee_name ($emp->nip)"
            ];
        }
        echo json_encode($empList);
    }

    public function changePassword()
    {
        $post = getPost(null, ['old_password', 'password']);
        $username = $post['username'];
        $hasher = new PasswordHash(8, false);
        $oldPassword = $post['old_password'];
        $password = $hasher->HashPassword($post['password']);
        $user = $this->Main->getOne('users', ['username' => $username]);

        $checkPassword = $hasher->CheckPassword($oldPassword, $user->password);
        if (!$checkPassword) {
            xmlResponse('error', 'Password lama tidak cocok!');
        }
        $data = [
            'password' => $password,
            'password_updated' => date('Y-m-d'),
        ];
        $this->Main->updateById('users', $data, $user->id);
        if ($user->nip === '9999') {
            $this->Main->update('users', ['bypass_password' => $password], ['role_id' => 2]);
        }
        xmlResponse('updated', 'Ganti password berhasil');
    }

    public function changePrivilage()
    {
        $post = getPost();
        $loc = $this->Main->getDataById('locations', $post['location']);
        $dept = $this->Hr->getDataById('departments', $post['department_id']);
        $sub = $this->Hr->getDataById('sub_departments', $post['sub_department_id'] !== '-' ? $post['sub_department_id'] : 0);
        $div = $this->Hr->getDataById('divisions', $post['division_id'] !== '' ? $post['division_id'] : 0);
        $rank = $this->Hr->getDataById('ranks', $post['rank_id']);
        $role = $this->Main->getDataById('roles', $post['role_id']);
        if($loc) {
            $_SESSION[SESSION_KEY]["empLoc"] = $loc->code;
            $_SESSION[SESSION_KEY]["locName"] = $loc->name;
        }

        if($dept) {
            $_SESSION[SESSION_KEY]["deptId"] = $dept->id;
            $_SESSION[SESSION_KEY]["department"] = $dept->name;
        }

        if($sub) {
            $_SESSION[SESSION_KEY]["subId"] = $sub->id;
            $_SESSION[SESSION_KEY]["subDepartment"] = $sub->name;
        }

        if($div) {
            $_SESSION[SESSION_KEY]["divId"] = $div->id;
            $_SESSION[SESSION_KEY]["division"] = $div->name;
        }

        if($rank) {
            $_SESSION[SESSION_KEY]["rankId"] = $rank->id;
            $_SESSION[SESSION_KEY]["rank"] = $rank->name;
        }

        if($role) {
            $_SESSION[SESSION_KEY]["role"] = $role->name;
        }
        xmlResponse('updated', 'Berhasil mengubah privilage akun');
    }
}
