<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}

class Erp_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BasicModel', 'Main');
        $this->Main->myConstruct('main');
        $this->load->model('BasicModel', 'Hr');
        $this->Hr->myConstruct('hr');
        $this->load->model('BasicModel', 'Qhse');
        $this->Qhse->myConstruct('qhse');
        $this->load->model('BasicModel', 'General');
        $this->General->myConstruct('general');
        $this->load->model('BasicModel', 'Mtn');
        $this->Mtn->myConstruct('mtn');
        $this->load->model('BasicModel', 'Chat');
        $this->Chat->myConstruct('chat');

        $this->kf_hr = $this->auth->kf_hr;
        $this->kf_main = $this->auth->kf_main;
        $this->kf_qhse = $this->auth->kf_qhse;
    }

    public function getTempFile($action)
    {
        $params = getParam();
        $action = $params['action'];

        $file = $this->Main->getOne('temp_files', ['emp_id' => $this->auth->empId, 'action' => $action]);
        if ($file) {
            $data = [
                'filename' => $file->filename,
                'doc_name' => $file->doc_name,
                'type' => $file->type,
                'size' => setFileSize($file->size),
            ];
            response(['status' => 'exist', 'file' => $data]);
        } else {
            response(['status' => 'empty']);
        }
    }

    public function uploadTempFile($action, $save = true)
    {
        $this->doUpload($action, $save, 'files');
    }

    public function uploadTempImage($fodler, $action, $save = true)
    {
        $this->doUpload($action, $save, $fodler);
    }

    public function doUpload($action, $save, $folder)
    {
        if (@$_REQUEST['mode'] == 'html5' || @$_REQUEST['mode'] == 'flash') {

            $name = $_FILES['file']['name'];
            $extension = pathinfo($name, PATHINFO_EXTENSION);

            $filename = time() . '_' . $this->auth->empId . '_' . rand(100, 999) . '.' . $extension;
            move_uploaded_file($_FILES['file']['tmp_name'], './assets/' . $folder . '/' . $filename);
            if ($save && $save !== 'false') {
                $data = [
                    'emp_id' => $this->auth->empId,
                    'action' => $action,
                    'doc_name' => $name,
                    'filename' => $filename,
                    'type' => $extension,
                    'size' => $_FILES['file']['size'],
                ];

                $this->Main->create('temp_files', $data);
            }

            header("Content-Type: text/json");
            print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "', extra: {info: 'just a way to send some extra data', param: 'some value here'}}");

        }
    }
}
