<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;

class ProjectController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ChatModel');
        $this->ChatModel->myConstruct('chat');
        $this->auth->isAuth();
    }

    public function loadData()
    {
        $post = fileGetContent();
        if($post->divId != '-' && $post->divId != 0) {
            $tasks = $this->Main->getWhere('projects_task', ['sub_department_id' => $post->subId, 'division_id' => $post->divId, 'MONTH(start_date)' => $post->month, 'YEAR(start_date)' => $post->year], '*', null, ['start_date' => 'ASC'])->result();
            $links = $this->Main->getWhere('projects_link', ['sub_department_id' => $post->subId, 'division_id' => $post->divId])->result();
        } else {
            $tasks = $this->Main->getWhere('projects_task', ['sub_department_id' => $post->subId, 'MONTH(start_date)' => $post->month, 'YEAR(start_date)' => $post->year], '*', null, ['start_date' => 'ASC'])->result();
            $links = $this->Main->getWhere('projects_link', ['sub_department_id' => $post->subId, 'MONTH(start_date)'])->result();
        }

        $divisions = $this->Hr->getWhere('divisions', ['sub_department_id' => $post->subId])->result();
        $tasksList = $this->Main->getGroupBy('projects_task', '*', ['task_id'], ['MONTH(start_date)' => $post->month, 'YEAR(start_date)' => $post->year])->result();
        $data = [];
        $dataLink = [];
        foreach ($tasks as $task) {
            if($post->taskId != '-') {
                if($task->task_id == $post->taskId) {
                    $dt = [
                        'id' => $task->id, 
                        'text' => $task->text, 
                        'start_date' => revDate($task->start_date), 
                        'duration' => $task->duration, 
                        'progress' => $task->progress, 
                        'open' => 1
                    ];
        
                    if($task->parent > 0) {
                        $dt['parent'] = $task->parent;
                    }
        
                    $data[] = $dt;
                }
            } else {
                $dt = [
                    'id' => $task->id, 
                    'text' => $task->text, 
                    'start_date' => revDate($task->start_date), 
                    'duration' => $task->duration, 
                    'progress' => $task->progress, 
                    'open' => 1
                ];
    
                if($task->parent > 0) {
                    $dt['parent'] = $task->parent;
                }
    
                $data[] = $dt;
            }
        }

        foreach ($links as $link) {
            $dataLink[] = [
                'id' => $link->id, 
                'source' => $link->source, 
                'target' => $link->target,
                'type' => $link->type,
            ];
        }

        $html = "";
        $html .= " Sub Bagian: <select id='gantt_division_id' style='height: 21px'>";
        if($divisions) {
            $html .= "<option value='-'>ALL</option>";
            foreach ($divisions as $division) {
                $selected = $post->divId != '-' && $post->divId != 0 && $post->divId == $division->id ? 'selected' : '';
                $html .= "<option $selected value='$division->id'>$division->name</option>";
            }
        } else {
            $html .= "<option value=''>-</option>";
        }
        $html .= "</select>";

        $html2 = "";
        $html2 .= " Tugas: <select id='gantt_task_id' style='height: 21px'>";
        if($divisions) {
            $html2 .= "<option value='-'>ALL</option>";
            foreach ($tasksList as $tls) {
                $selected = $post->taskId != '-' && $post->taskId == $tls->task_id ? 'selected' : '';
                $html2 .= "<option $selected value='$tls->task_id'>$tls->text</option>";
            }
        } else {
            $html2 .= "<option value=''>-</option>";
        }
        $html2 .= "</select>";

        response(['status' => 'success', 'tasks' => ['data' => $data, 'links' => $dataLink], 'division' => $html, 'tasksList' => $html2]);
    }

    public function lightboxAction()
    {
        $params = getParam();
        $post = fileGetContent();
        $action = $post->action;
        $mode = $params['gantt_mode'];
        $id = $post->id;
        $data = $post->data;

        if($action == 'inserted') {
            if($mode == 'tasks') {
                $task = $this->Main->getDataById('projects_task', $id);
                if(!$task) {
                    $this->createTask($id, $data);
                } else {
                    $this->updateTask($id, $data);
                }
            } else if($mode == 'links') {
                $link = $this->Main->getDataById('projects_link', $id);
                if(!$link) {
                    $this->createLink($id, $data);
                } else {
                    $this->updateLink($id, $data);
                }
            }
        } else if($action == 'updated') {
            if($mode == 'tasks') {
                $this->updateTask($id, $data);
            } else if($mode == 'links'){
                $this->updateLink($id, $data);
            }
        } else if($action == 'deleted') {
            if($mode == 'tasks') {
                $this->Main->deleteById('projects_task', $id);
            } else if($mode == 'links') {
                $this->Main->deleteById('projects_link', $id);
            }
        }
    }

    public function createTask($id, $data)
    {
        $dataInsert = [
            'id' => $id,
            'location' => empLoc(),
            'text' => ucwords(strtolower($data->text)),
            'sub_department_id' => empSub(),
            'division_id' => empDiv(),
            'start_date' => date('Y-m-d H:i:s', strtotime($data->start_date)),
            'end_date' => date('Y-m-d H:i:s', strtotime($data->end_date)),
            'duration' => $data->duration,
            'progress' => $data->progress,
            'parent' => $data->parent,            
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if($data->parent != 0) {
            $task_id = $this->Main->getOne('projects_task', ['id' => $data->parent])->task_id;
            $dataInsert['task_id'] = $task_id;
        } else {
            $dataInsert['task_id'] = $id;
        }
        $this->Main->create('projects_task', $dataInsert);
        echo "Task Insert : ".$id;
    }

    public function updateTask($id, $data)
    {
        $dataUpdate = [
            'text' => ucwords(strtolower($data->text)),
            'start_date' => date('Y-m-d H:i:s', strtotime($data->start_date)),
            'end_date' => date('Y-m-d H:i:s', strtotime($data->end_date)),
            'duration' => $data->duration,
            'progress' => $data->progress,
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Main->updateById('projects_task', $dataUpdate, $id);
        echo "Task Updated : ".$id;
    }

    public function createLink($id, $data)
    {
        $dataInsert = [
            'id' => $id,
            'sub_department_id' => empSub(),
            'division_id' => empDiv(),
            'source' => $data->source,
            'target' => $data->target,
            'type' => $data->type,
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Main->create('projects_link', $dataInsert);
        echo "Link Insert : ".$id;
    }

    public function updateLink($id, $data)
    {
        $dataUpdate = [
            'source' => $data->source,
            'target' => $data->target,
            'type' => $data->type,
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Main->updateById('projects_link', $dataUpdate, $id);
        echo "Link Updated : ".$id;
    }

    public function getSpektaChatUser()
    {
        $users = $this->ChatModel->getSpektaChatUser();
        $xml = "";
        $no = 1;
        foreach ($users as $key => $value) {
            $xml .= "<row id='$value[id]'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>". cleanSC($value['name']) ."</cell>";
            $xml .= "<cell>". cleanSC($value['email']) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getEmpEmail()
    {
        $emps = $this->Hr->getAll('employees')->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $xml .= "<row id='$emp->email'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->email) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function shareSpekta()
    {
        $post = fileGetContent();
        $ids = $post->ids;
        $task = $post->tasks;
        $subId = $task->subId;
        $subName = $task->subName;
        $divId = $task->divId;
        $taskId = $task->taskId;
        $month = $task->month;
        $year = $task->year;
        $taskUrl = LIVE_URL . 'index.php?c=PublicController&m=ganttChart&token=' . simpleEncrypt("$taskId:$subId:$divId:$month:$year");

        if($taskId != '-') {
            $project = $this->Main->getDataById('projects_task', $taskId);
            $startDate = toIndoDateTime($project->start_date);
            $taskName = $project->name;
            if($divId != '-') {
                $divName = $this->Hr->getDataById("divisions", $divId)->name;
                $message = "Berikut ini adalah <b>Jadwal Pekerjaan</b> Bagian $subName ($divName) ".mToMonth($month)." $year yang dikirim oleh <b>".empName()."</b> dengan Nama Pekerjaan: <b>$taskName</b>|left";
                
            } else {
                $message = "Berikut ini adalah <b>Jadwal Pekerjaan</b> Bagian $subName ".mToMonth($month)." $year yang dikirim oleh <b>".empName()."</b> dengan Nama Pekerjaan: <b>$taskName</b>|left";;
            }

            $data = [
                'title' => 'Jadwal Pekerjaan',
                'p' => [
                    "Dear Bapak/Ibu|left",
                    $message,
                    "Yang mulai dilaksanakan pada:|left",
                    "$startDate|center",
                    "Untuk info lebih lanjut, silahkan klik <a target='_blank' href='$taskUrl'><i>di sini.</i></a>|left",
                ],
            ];
        } else {
            if($divId != '-') {
                $divName = $this->Hr->getDataById("divisions", $divId)->name;
                $message = "Berikut ini adalah <b>Jadwal Pekerjaan</b> Bagian $subName ($divName) ".mToMonth($month)." $year yang dikirim oleh <b>".empName()."</b>|left";
            } else {
                $message = "Berikut ini adalah <b>Jadwal Pekerjaan</b> Bagian $subName ".mToMonth($month)." $year yang dikirim oleh <b>".empName()."</b>|left";;
            }

            $data = [
                'title' => 'Jadwal Pekerjaan',
                'p' => [
                    "Dear Bapak/Ibu|left",
                    $message,
                    "Untuk info lebih lanjut, silahkan klik <a target='_blank' href='$taskUrl'><i>di sini.</i></a>|left",
                ],
            ];
        }

        $dataMessage = [];
        foreach ($ids as $id) {
            $gid = "1-$id";
            $dataMessage[] = sendChatMsg($data, $gid);
        }

        $insert = $this->Chat->createMultiple('gr_msgs', $dataMessage);
        if($insert) {
            response(['status' => 'success', 'message' => 'Berhasil mengirim jadwal pekerjaan via S.P.E.K.T.A Chat']);
        } else {
            response(['status' => 'success', 'message' => 'Gagal mengirim jadwal pekerjaan']);
        }
    }

    public function shareEmail()
    {
        $post = fileGetContent();
        $ids = $post->ids;
        $task = $post->tasks;
        $subId = $task->subId;
        $subName = $task->subName;
        $divId = $task->divId;
        $taskId = $task->taskId;
        $month = $task->month;
        $year = $task->year;
        $taskUrl = LIVE_URL . 'index.php?c=PublicController&m=ganttChart&token=' . simpleEncrypt("$taskId:$subId:$divId:$month:$year");

        if($taskId != '-') {
            $project = $this->Main->getDataById('projects_task', $taskId);
            $startDate = toIndoDateTime($project->start_date);
            $taskName = $project->name;
            if($divId != '-') {
                $divName = $this->Hr->getDataById("divisions", $divId)->name;
                $subject = "Jadwal Pekerjaan Bagian $subName ($divName) ".mToMonth($month)." $year yang dikirim oleh ".empName();
                $subjectName = "Spekta Share: Jadwal Pekerjaan Bagian $subName ($divName) ".mToMonth($month)." $year yang dikirim oleh ".empName();
            } else {
                $subject = "Jadwal Pekerjaan Bagian $subName ".mToMonth($month)." $year yang dikirim oleh ".empName();
                $subjectName = "Spekta Share: Jadwal Pekerjaan Bagian $subName ".mToMonth($month)." $year yang dikirim oleh ".empName();
            }
        } else {
            if($divId != '-') {
                $divName = $this->Hr->getDataById("divisions", $divId)->name;
                $subject = "Jadwal Pekerjaan Bagian $subName ($divName) ".mToMonth($month)." $year yang dikirim oleh ".empName();
                $subjectName = "Jadwal Pekerjaan Bagian $subName ($divName) ".mToMonth($month)." $year yang dikirim oleh ".empName();
            } else {
                $subject = "Jadwal Pekerjaan Bagian $subName ".mToMonth($month)." $year yang dikirim oleh ".empName();
                $subjectName = "Spekta Share: Jadwal Pekerjaan Bagian $subName ".mToMonth($month)." $year yang dikirim oleh ".empName();
            }
        }

        $email = '';
        foreach ($ids as $id) {
            if($email == '') {
                $email = $id;
            } else {
                $email = $email.','.$id;
            }
        }

        $message = $this->load->view('html/public/gantt/gantt_chart_email_share', [
            'locName' => locName(),
            'shareBy' => empName(),
            'subDepartment' => $subName,
            'divName' => isset($divName) ? $divName : null,
            'month' =>  mToMonth($month),
            'year' => $year,
            'taskName' => isset($taskName) ? $taskName : null,
            'taskUrl' => $taskUrl
        ], true);

        $dataEmail = [
            'alert_name' => 'TASK_TIMELINE_SHARE',
            'subject' => $subject,
            'subject_name' => $subjectName,
            'email_to' => $email,
            'message' => $message
        ];

        $insert = $this->Main->create('email', $dataEmail);
        if($insert) {
            response(['status' => 'success', 'message' => 'Berhasil mengirim jadwal pekerjaan via Email']);
        } else {
            response(['status' => 'success', 'message' => 'Gagal mengirim jadwal pekerjaan']);
        }
    }
}