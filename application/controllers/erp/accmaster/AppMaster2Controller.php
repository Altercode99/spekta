<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppMaster2Controller extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AppMasterModel', 'AppMaster');
        $this->AppMaster->myConstruct('main');

        $this->auth->isAuth();
    }

    /* ========================= OVERTIME FUNCTIONS  =========================*/
    public function getEmailGrid()
    {
        $params = getParam();
        $emails = $this->Main->getWhere('email', ['MONTH(created_at)' => $params['month'], 'YEAR(created_at)' => $params['year']], '*', null, ['created_at' => 'DESC'])->result();
        $xml = "";
        $no = 1;
        foreach ($emails as $email) {
            $color = "bgColor='#ccc'";
            if ($email->status == 1) {
                $status = 'Terkirim';
                $color = "bgColor='#8bc38f'";
            } else {
                $status = 'Belum Terkirim';
            }

            if ($email->send_date !== '0000-00-00 00:00:00') {
                $send = toIndoDateTime($email->send_date);
            } else {
                $send = '-';
            }

            $xml .= "<row id='$email->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($email->alert_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($email->subject) . "</cell>";
            $xml .= "<cell>" . cleanSC($email->subject_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($email->email_to) . "</cell>";
            $xml .= "<cell>" . cleanSC($email->email_cc) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($email->created_at)) . "</cell>";
            $xml .= "<cell>" . cleanSC($status) . "</cell>";
            $xml .= "<cell>" . cleanSC($send) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function emailMessage()
    {
        $post = fileGetContent();
        $email = $this->Main->getDataById('email', $post->id);
        response(['status' => 'success', 'template' => $email->message]);
    }

    public function emailSend()
    {
        $post = fileGetContent();
        $email = $this->Main->getDataById('email', $post->id);
        $send = $this->sendmail->sendEmail($email->subject, $email->message, $email->email_to, $email->email_cc, $email->subject_name);
        if ($send) {
            $data = [
                'status' => 1,
                'send_date' => date('Y-m-d H:i:s'),
            ];
            $this->Main->updateById('email', $data, $email->id);
            response(['status' => 'success', 'message' => 'Kirim email berhasil']);
        } else {
            response(['status' => 'error', 'message' => 'Gagal mengirim email!']);
        }
    }

    public function emailDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus <br>";
            $this->Main->delete('email', ['id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function enableEmail()
    {
        $post = fileGetContent();
        $this->Main->updateById('email_send', ['status' => $post->status], 1);
        $status = strtoupper($post->status);
        response(['status' => 'success', 'message' => "Pengiriman email $status"]);
    }

    public function setEmailStatus()
    {
        $status = $this->Main->getDataById('email_send', 1)->status;
        response(['status' => $status]);
    }

    /* ========================= OVERTIME FUNCTIONS  =========================*/
    public function reqOvertimeGrid()
    {
        $overtimes = $this->AppMaster->getMasterOvertime(getParam());
        $xml = "";
        $no = 1;
        foreach ($overtimes as $ovt) {
            $xml .= "<row id='$ovt->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->category) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->department) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->sub_department) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->division) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($ovt->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function reqOvertimeForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $location = $this->Hr->getDataById('overtime_requirement', $params['id'], 'id,name,division_id,sub_department_id,department_id,pic_emails,category');
            fetchFormData($location);
        } else {
            $post = prettyText(getPost(), ['name']);
            $this->updateReqOvertime($post);
        }
    }

    public function updateReqOvertime($post)
    {
        $overtime = $this->Hr->getDataById('overtime_requirement', $post['id']);
        isDelete(["Data kebutuhan lembur $post[name]" => $overtime]);

        if ($overtime->name !== $post['name'] || $overtime->department_id !== $post['department_id'] ||
            $overtime->sub_department_id !== $post['sub_department_id'] || $overtime->division_id !== $post['division_id']) {
            $checkOvertime = $this->Hr->getOne('overtime_requirement', [
                'name' => $post['name'],
                'department_id' => $post['department_id'],
                'sub_department_id' => $post['sub_department_id'],
                'division_id' => $post['division_id'],
            ]);
            isExist(["Data kebutuhan lembur $post[name]" => $checkOvertime]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('overtime_requirement', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    /* ========================= PIC FUNCTIONS  =========================*/
    public function picGrid()
    {
        $overtimes = $this->AppMaster->getMasterPIC(getParam());
        $xml = "";
        $no = 1;
        foreach ($overtimes as $ovt) {
            $codeName = null;
            if ($ovt->code == 'overtime') {
                $codeName = 'Pembuatan Lembur';
            } else if ($ovt->code == 'vehicles') {
                $codeName = 'Reservasi Kendaraan Dinas';
            } else if ($ovt->code == 'meeting_rooms') {
                $codeName = 'Reservasi Ruang Meeting';
            }

            $xml .= "<row id='$ovt->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($codeName) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->department) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->sub_department) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($ovt->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function picForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $location = $this->Main->getDataById('pics', $params['id'], 'id,code,name,sub_department_id,department_id,pic_emails');
            fetchFormData($location);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createPic($post);
            } else {
                $this->updatePic($post);
            }
        }
    }

    public function createPic($post)
    {
        $pic = $this->Main->getOne('pics', [
            'code' => $post['code'],
            'department_id' => $post['department_id'],
            'sub_department_id' => $post['sub_department_id'],
        ]);
        isExist(["Data pic $post[name]" => $pic]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->Main->create('pics', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updatePic($post)
    {
        $pic = $this->Main->getDataById('pics', $post['id']);
        isDelete(["Data pic $post[name]" => $pic]);

        if ($pic->code !== $post['code'] || $pic->department_id !== $post['department_id'] || $pic->sub_department_id !== $post['sub_department_id']) {
            $checkPic = $this->Main->getOne('pics', [
                'code' => $post['code'],
                'department_id' => $post['department_id'],
                'sub_department_id' => $post['sub_department_id'],
            ]);
            isExist(["Data pic $post[name]" => $checkPic]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Main->updateById('pics', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    /* ========================= NATIONAL FREEDAY FUNCTIONS  =========================*/
    public function freeGrid()
    {
        $frees = $this->AppMaster->getNasionalFree(getParam());
        $xml = "";
        $no = 1;
        foreach ($frees as $free) {
            $xml .= "<row id='$free->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDate($free->date)) . "</cell>";
            $xml .= "<cell>" . cleanSC($free->description) . "</cell>";
            $xml .= "<cell>" . cleanSC($free->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($free->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($free->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }

        gridXmlHeader($xml);
    }

    public function freeForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $free = $this->Hr->getDataById('national_days', $params['id']);
            fetchFormData($free);
        } else {
            $post = prettyText(getPost(), ['description']);
            if (!isset($post['id'])) {
                $this->createFree($post);
            } else {
                $this->updateFree($post);
            }
        }
    }

    public function createFree($post)
    {
        $free = $this->Hr->getWhere('national_days', ['date' => $post['date']])->row();
        isExist(['Hari libur ' . toIndoDate($post['date']) => $free]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('national_days', $post);
        xmlResponse('inserted', toIndoDate($post['date']));
    }

    public function updateFree($post)
    {
        $free = $this->Hr->getDataById('national_days', $post['id']);
        isDelete(['Hari libur ' . toIndoDate($post['date']) => $free]);

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('national_days', $post, $post['id']);
        xmlResponse('updated', toIndoDate($post['date']));
    }

    public function freeDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus <br>";
            $this->Hr->delete('national_days', ['id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= VIDEO CATEGORI FUNCTIONS  =========================*/
    public function getVideoCatGrid()
    {
        $categories = $this->AppMaster->getVideoCat(getParam());
        $xml = "";
        $no = 1;
        foreach ($categories as $cat) {
            $xml .= "<row id='$cat->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($cat->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($cat->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($cat->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($cat->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }

        gridXmlHeader($xml);
    }

    public function videoCatDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isHasSub = $this->Main->getOne('video_subcategories', ['cat_id' => $data->id]);
            if(!$isHasSub) {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->Main->delete('video_categories', ['id' => $data->id]);
            } else {
                $mSuccess .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function catForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $category = $this->Main->getDataById('video_categories', $params['id'], 'id,name');
            fetchFormData($category);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createCategory($post);
            } else {
                $this->updateCategory($post);
            }
        }
    }

    public function createCategory($post)
    {
        $category = $this->Main->getOne('video_categories', ['name' => $post['name']]);
        isExist(["Kategori video $post[name]" => $category]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->Main->create('video_categories', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateCategory($post)
    {
        $category = $this->Main->getDataById('video_categories', $post['id']);
        isDelete(["Kategori video $post[name]" => $category]);

        if ($category->name !== $post['name']) {
            $checkCategory = $this->Main->getOne('video_categories', ['name' => $post['name']]);
            isExist(["Kategori video $post[name]" => $checkCategory]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Main->updateById('video_categories', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }


}
