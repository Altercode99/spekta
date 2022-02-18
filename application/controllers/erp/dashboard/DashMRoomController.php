<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashMRoomController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DashboardModel', 'Dashboard');
        $this->Dashboard->myConstruct('hr');
        $this->load->model('GaModel');
        $this->GaModel->myConstruct('general');
        $this->auth->isAuth();
    }

    public function getTotalByColumn()
    {
        $params = getParam();
        $revs = $this->GaModel->getMeetingRevGroupGrid($params)->result_array();
        $data = [];
        $total = 0;
        foreach ($revs as $key => $value) {
            $total += $value[$params['column']];
        }
        $keys = array_column($revs, $params['column']);
        array_multisort($keys, SORT_DESC, $revs);
        $no = 1;
        $color = [];
        foreach ($revs as $key => $value) {
            if($no == 1) {
                $color[] = $value['color'];
                $data[] = [
                    'name' => $value['room_name'] . ' ( '. $value[$params['column']] .' )',
                    'y' => $value[$params['column']] > 0 ? ($value[$params['column']] / $total) * 100 : 0,
                    'sliced' => true,
                    'selected' => true
                ];
            } else {
                $color[] = $value['color'];
                $data[] = [
                    'name' => $value['room_name'] . ' ( '. $value[$params['column']] .' )',
                    'y' => $value[$params['column']] > 0 ? ($value[$params['column']] / $total) * 100 : 0
                ];
            }
            $no++;
        }
        response(['status' => 'success', 'series' => $data, 'color' => $color]);
    }

    public function getTotalBySnack()
    {
        $params = getParam();
        $revs = $this->GaModel->getMeetingRevGroupGrid($params)->result_array();
        $series = []; 
        $categories = [];
        foreach ($revs as $key => $value) {
            $categories[] = $value['room_name'];
            $series[] = [$value['room_name'], floatval($value['total_snack'])];
        }

        $data[] = [
            'name' => "Total Biaya Snack",
            'data' => $series
        ];

        response(['status' => 'success', 'categories' => $categories, 'series' => $data]);
    }
}