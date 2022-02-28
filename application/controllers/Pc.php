<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/mc_table.php';

class Pc extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
    }

    public function createLink()
    {
        $post = fileGetContent();
        if(getParam()['action'] == 'wa') {
            $url = LIVE_URL . "index.php?c=pc&m=pr&param=" . simpleEncrypt($post->waTaskId);
            $link = SHARE_URL . str_replace('=', '_', simpleEncrypt($url));
            $message = "https://wa.me?text=Lembur Tanggal $post->waOvtDate %0A%0A Task ID: $post->waTaskId%0A Waktu Mulai: $post->waStartDate%0A Waktu Selesai: $post->waEndDate%0A Kebutuhan Personil: $post->waTotalPersonel%0A $link";
            response(['message' => $message]);
        } else {
            $url = LIVE_URL . "index.php?c=pc&m=pr&param=" . simpleEncrypt($post->waTaskId);
            response(['url' => $url]);
        }
    }

    public function pr()
    {
        if(isset(getParam()['param'])) {
            $taskId = simpleEncrypt(getParam()['param'], 'd');
            if($taskId) {
                $overtime = $this->Overtime->getOvertime(['equal_task_id' => $taskId])->row();
                $ovtDetail = $this->Overtime->getOvertimeDetail(['equal_task_id' => $taskId])->result();
                $this->load->view('html/overtime/print/print_overtime', ['ovt' => $overtime, 'ovtDetail' => $ovtDetail]);
            } else {
                $this->load->view('html/invalid_response', ['message' => 'Token tidak valid']);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Token tidak valid']);
        }
    }
}
