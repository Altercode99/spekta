<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashOvtController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
        $this->load->model('DashboardModel', 'Dashboard');
        $this->Dashboard->myConstruct('hr');
        $this->auth->isAuth();
    }

    public function getMonthlySummary()
    {
        $post = fileGetContent();
        $categories = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    public function getSubOvtGrid()
    {
        $overtimes = $this->Dashboard->getSubOvtGrid(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $ovt) {
            $xml .= "<row id='$ovt->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->sub_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($ovt->ovt_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC(toNumber($ovt->ovt_value)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }

        gridXmlHeader($xml);
    }

    public function getSummaryPersonil()
    {
        $post = fileGetContent();
        $year = $post->year;
        $month = $post->month;
        $mBefore = getMonthBefore($year, $month);
        $dayInMonthBefore = cal_days_in_month(CAL_GREGORIAN, $mBefore['month'], $mBefore['year']);
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $monthLength = $dayInMonthBefore > $dayInMonth ? $dayInMonthBefore : $dayInMonth;

        $overtimesBefore = $this->Dashboard->getSummaryPersonil(['month_overtime_date' => $mBefore['month'], 'year_overtime_date' => $mBefore['year'], 'equal_status' => 'CLOSED'])->result();
        $overtimes = $this->Dashboard->getSummaryPersonil(getParam())->result();
        $data1 = [];
        $series1 = [];
        foreach ($overtimes as $ovt) {
            $d = getDay($ovt->overtime_date);
            if (!array_key_exists($d, $data1)) {
                $data1[$d] = $ovt->ovt_value;
            } else {
                $data1[$d] += $ovt->ovt_value;
            }
        }

        $data2 = [];
        $series2 = [];
        foreach ($overtimesBefore as $ovt) {
            $d = getDay($ovt->overtime_date);
            if (!array_key_exists($d, $data2)) {
                $data2[$d] = $ovt->ovt_value;
            } else {
                $data2[$d] += $ovt->ovt_value;
            }
        }

        $categories = [];
        for ($i = 1; $i <= $monthLength; $i++) {
            if (array_key_exists($i, $data1)) {
                $series1[] = floatval($data1[$i]);
            } else {
                $series1[] = 0;
            }
            $categories[] = $i;
        }

        for ($i = 1; $i <= $monthLength; $i++) {
            if (array_key_exists($i, $data2)) {
                $series2[] = floatval($data2[$i]);
            } else {
                $series2[] = 0;
            }
        }

        $series = [
            [
                'name' => mToMonth($mBefore['month']) . ' ' . $mBefore['year'],
                'data' => $series2,
            ], [
                'name' => mToMonth($month) . ' ' . $year,
                'data' => $series1,
            ],
        ];
        response(['status' => 'success', 'categories' => $categories, 'series' => $series]);
    }

    public function getTop5Sub()
    {
        $overtimes = $this->Dashboard->getSubOvtGrid(getParam())->result();
        $xml = "";
        $no = 1;
        $data = [];
        $total = 0;
        foreach ($overtimes as $ovt) {
            $total += $ovt->ovt_value;
        }

        foreach ($overtimes as $ovt) {
            if ($no <= 5) {
                if ($no == 1) {
                    $data[] = [
                        'name' => $ovt->sub_name,
                        'y' => $ovt->ovt_value > 0 ? ($ovt->ovt_value / $total) * 100 : 0,
                        'sliced' => true,
                        'selected' => true,
                    ];
                } else {
                    $data[] = [
                        'name' => $ovt->sub_name,
                        'y' => $ovt->ovt_value > 0 ? ($ovt->ovt_value / $total) * 100 : 0,
                    ];
                }
            }
            $no++;
        }

        response(['status' => 'success', 'series' => $data]);
    }

    public function getOvtCompare()
    {
        $post = fileGetContent();
        $year1 = $post->yearOne;
        $year2 = $post->yearTwo;

        $categories = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $overtimes_1 = $this->Dashboard->getYearlyOvt($year1, ['equal_status' => 'CLOSED'])->result();
        $overtimes_2 = $this->Dashboard->getYearlyOvt($year2, ['equal_status' => 'CLOSED'])->result();

        $data_1 = [];
        $data_2 = [];

        $year_1_data = [];
        $year_2_data = [];

        $series_1 = [];
        $series_2 = [];

        foreach ($overtimes_1 as $ovt_1) {
            $m = getMonth($ovt_1->overtime_date);
            if (!array_key_exists($m, $data_1)) {
                $data_1[$m] = $ovt_1->ovt_value;
            } else {
                $data_1[$m] += $ovt_1->ovt_value;
            }
        }

        foreach ($overtimes_2 as $ovt_2) {
            $m = getMonth($ovt_2->overtime_date);
            if (!array_key_exists($m, $data_2)) {
                $data_2[$m] = $ovt_2->ovt_value;
            } else {
                $data_2[$m] += $ovt_2->ovt_value;
            }
        }

        for ($i = 1; $i <= 12; $i++) {
            if (array_key_exists($i, $data_1)) {
                $series_1[] = floatval($data_1[$i]);
            } else {
                $series_1[] = 0;
            }

            if (array_key_exists($i, $data_2)) {
                $series_2[] = floatval($data_2[$i]);
            } else {
                $series_2[] = 0;
            }
        }

        $series = [
            [
                'name' => $year1,
                'data' => $series_1,
                'pointPlacement' => 'on',
            ],
            [
                'name' => $year2,
                'data' => $series_2,
                'pointPlacement' => 'on',
            ],
        ];

        response([
            'status' => 'success',
            'categories' => $categories,
            'series' => $series,
            'title' => $year1 . " vs " . $year2,
        ]);

    }

    public function getSumOvtGrid()
    {
        $overtimes = $this->Overtime->getReportOvertime(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }
            $meal = $overtime->meal > 0 ? "✓ ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_sub_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->ovt_sub_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->ovt_division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->premi_overtime)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->meal)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getSumOvtSubGrid()
    {
        $overtimes = $this->Overtime->getReportOvertimeSub(getParam())->result();
        $ovt = [];
        foreach ($overtimes as $overtime) {
            $ovt[$overtime->sub_name] = [
                'effective_hour' => $overtime->effective_hour,
                'break_hour' => $overtime->break_hour,
                'real_hour' => $overtime->real_hour,
                'overtime_hour' => $overtime->overtime_hour,
                'overtime_value' => $overtime->overtime_value,
                'meal' => $overtime->meal,
            ];
        }

        $subs = $this->HrModel->subWithDept();
        $xml = "";
        $no = 1;
        foreach ($subs as $sub) {
            if (array_key_exists($sub->name, $ovt)) {
                $effectiveHour = $ovt[$sub->name]['effective_hour'];
                $breakHour = $ovt[$sub->name]['break_hour'];
                $realHour = $ovt[$sub->name]['real_hour'];
                $overtimeHour = $ovt[$sub->name]['overtime_hour'];
                $overtimeHalue = toNumber($ovt[$sub->name]['overtime_value']);
                $meal = toNumber($ovt[$sub->name]['meal']);
            } else {
                $effectiveHour = 0;
                $breakHour = 0;
                $realHour = 0;
                $overtimeHour = 0;
                $overtimeHalue = 0;
                $meal = 0;
            }

            $xml .= "<row id='$sub->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            if ($sub->name != '-') {
                $xml .= "<cell>" . cleanSC($sub->name) . "</cell>";
            } else {
                $xml .= "<cell>Direct To Sub Unit</cell>";
            }

            if ($sub->dept_name != '-') {
                $xml .= "<cell>" . cleanSC($sub->dept_name) . "</cell>";
            } else {
                $xml .= "<cell>Direct To Unit</cell>";
            }
            $xml .= "<cell>" . cleanSC($effectiveHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($breakHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($realHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtimeHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtimeHalue) . "</cell>";
            $xml .= "<cell>" . cleanSC($meal) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getSumDivGrid()
    {
        $overtimes = $this->Overtime->getReportOvertimeDiv(getParam())->result();
        $ovt = [];
        foreach ($overtimes as $overtime) {
            $ovt[$overtime->div_name] = [
                'effective_hour' => $overtime->effective_hour,
                'break_hour' => $overtime->break_hour,
                'real_hour' => $overtime->real_hour,
                'overtime_hour' => $overtime->overtime_hour,
                'overtime_value' => $overtime->overtime_value,
                'meal' => $overtime->meal,
            ];
        }

        $divs = $this->HrModel->divWithSub();
        $xml = "";
        $no = 1;
        foreach ($divs as $div) {
            if (array_key_exists($div->name, $ovt)) {
                $effectiveHour = $ovt[$div->name]['effective_hour'];
                $breakHour = $ovt[$div->name]['break_hour'];
                $realHour = $ovt[$div->name]['real_hour'];
                $overtimeHour = $ovt[$div->name]['overtime_hour'];
                $overtimeHalue = toNumber($ovt[$div->name]['overtime_value']);
                $meal = toNumber($ovt[$div->name]['meal']);
            } else {
                $effectiveHour = 0;
                $breakHour = 0;
                $realHour = 0;
                $overtimeHour = 0;
                $overtimeHalue = 0;
                $meal = 0;
            }

            $xml .= "<row id='$div->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            if ($div->name != '-') {
                $xml .= "<cell>" . cleanSC($div->name) . "</cell>";
            } else {
                $xml .= "<cell>Direct To Bagian</cell>";
            }

            if ($div->sub_name != '-') {
                $xml .= "<cell>" . cleanSC($div->sub_name) . "</cell>";
            } else {
                $xml .= "<cell>Direct To Sub Unit</cell>";
            }

            $xml .= "<cell>" . cleanSC($effectiveHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($breakHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($realHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtimeHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtimeHalue) . "</cell>";
            $xml .= "<cell>" . cleanSC($meal) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getSumOvtEmpGrid()
    {
        $overtimes = $this->Overtime->getReportOvertimeEmp(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->emp_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->div_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->sub_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->dept_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->overtime_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            $xml .= "<cell>" . cleanSC(toNumber($overtime->meal)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getSummaryMachine()
    {
        $params = getParam();
        $filter_1 = $params;
        $filter_2 = $params;
        $filter_1['groupby_machine_1'] = true;
        $filter_1['notequal_machine_1'] = 0;
        $filter_2['groupby_machine_2'] = true;
        $filter_2['notequal_machine_2'] = 0;
        $overtimes_1 = $this->Dashboard->getSummaryMachine($filter_1, 'machine_1')->result_array();
        $overtimes_2 = $this->Dashboard->getSummaryMachine($filter_2, 'machine_2')->result_array();
        $data = [];
        $grid = [];
        $total_1 = 0;
        $total_2 = 0;
        foreach ($overtimes_1 as $key => $ovt1) {
            $total_1 += $ovt1['ovt_hour'];
        }
        foreach ($overtimes_2 as $key => $ovt2) {
            $total_2 += $ovt2['ovt_hour'];
        }

        $no = 1;
        foreach ($overtimes_1 as $key => $ovt) {
            if ($no <= 10) {
                $data[$ovt['machine_1']] = [
                    'name' => $ovt['machine_one'],
                    'y' => $ovt['ovt_hour'] > 0 ? ($ovt['ovt_hour'] / $total_1) * 100 : 0,
                ];
            }
            $grid[$ovt['machine_1']] = [
                'name' => $ovt['machine_one'],
                'sub_department' => $ovt['sub_department'],
                'division' => $ovt['division'],
                'total_hour' => $ovt['ovt_hour']
            ];
            $no++;
        }

        $no = 1;
        foreach ($overtimes_2 as $key => $ovt) {
            if ($no <= 10) {
                if (isset($data[$ovt['machine_1']]['y'])) {
                    $percent = $data[$ovt['machine_1']]['y'] + ($ovt['ovt_hour'] > 0 ? ($ovt['ovt_hour'] / ($total_1 + $total_2)) * 100 : 0);
                } else {
                    $percent = $ovt['ovt_hour'] > 0 ? ($ovt['ovt_hour'] / ($total_1 + $total_2)) * 100 : 0;
                }
              
                $data[$ovt['machine_1']] = [
                    'name' => $ovt['machine_one'],
                    'y' => $percent,
                ];
            }

            if (isset($grid[$ovt['machine_1']]['ovt_hour'])) {
                $hour = $data[$ovt['machine_1']]['ovt_hour'] + $ovt['ovt_hour'];
            } else {
                $hour = $ovt['ovt_hour'];
            }

            $grid[$ovt['machine_1']] = [
                'name' => $ovt['machine_one'],
                'sub_department' => $ovt['sub_department'],
                'division' => $ovt['division'],
                'total_hour' => $hour
            ];
            $no++;
        }

        $keys = array_column($data, 'y');
        array_multisort($keys, SORT_DESC, $data);
        $no = 1;
        foreach ($data as $key => $value) {
            if ($no == 1) {
                $data[$key]['sliced'] = true;
                $data[$key]['selected'] = true;
                break;
            }
        }
       
        $xml = "";
        $no = 1;
        $gridData = [];
        foreach ($grid as $key => $value) {
            $gridData['rows'][] = [
                'id' => $key,
                'data' => [
                    $no,
                    $value['name'],
                    $value['sub_department'],
                    $value['division'],
                    $value['total_hour']
                ]
            ];
            $no++;
        }
        response(['status' => 'success', 'series' => $data, 'grid' => $gridData]);
    }
}
