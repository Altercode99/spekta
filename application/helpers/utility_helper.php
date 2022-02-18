<?php

function json($data)
{
    echo json_encode($data);
    die();
}

function response($data, $statusCode = 200)
{
    header('Content-Type:application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    die();
}

function fileGetContent()
{
    $json = file_get_contents("php://input");
    $obj = json_decode($json);
    return $obj;
}

function asset($text)
{
    return base_url('public/' . $text);
}

function dd($var)
{
    echo "<pre>";
    var_dump($var);
    die();
    echo "</pre>";
}

function getTime($date)
{
    return date('H:i', strtotime($date));
}

function toNumber($number)
{
    return number_format($number, 2, ',', '.');
}

function toDateTime($date)
{
    return date_format($date, "d/m/Y H:i:s");
}

function mToMonth($m)
{
    $m = intval($m);
    $month = [
        "1" => "Januari",
        "2" => "Februari",
        "3" => "Maret",
        "4" => "April",
        "5" => "Mei",
        "6" => "Juni",
        "7" => "Juli",
        "8" => "Agustus",
        "9" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember",
    ];

    return $month[$m];
}

function toIndoDate($date)
{
    if ($date == '0000-00-00') {
        return '-';
    }

    $date = explode("-", $date);
    $month = mToMonth($date[1]);
    return $date[2] . " " . $month . " " . $date[0];
}

function toIndoDate2($date)
{
    if ($date == '0000-00-00') {
        return '-';
    }

    $date = explode("-", $date);
    return $date[2] . "-" . $date[1] . "-" . $date[0];
}

function toIndoDateDay($date)
{
    if ($date == '' || $date == '0000-00-00') {
        return '-';
    }

    $dayName = dayname(date("D", strtotime($date)));
    $date = explode("-", $date);
    $month = mToMonth($date[1]);
    return $dayName . ", " . $date[2] . " " . $month . " " . $date[0];
}

function toIndoDateTime($date)
{
    $datetime = explode(" ", $date);
    $date = toIndoDateDay($datetime[0]);

    if ($date == '' || $date == '0000-00-00') {
        return '-';
    }

    $time = $datetime[1];
    return $date . " " . $time;
}

function toIndoDateTime2($date)
{
    $datetime = explode(" ", $date);
    $date = toIndoDate($datetime[0]);

    if ($date == '' || $date == '0000-00-00') {
        return '-';
    }

    $time = $datetime[1];
    return $date . " " . $time;
}

function toIndoDateTime3($date)
{
    $datetime = explode(" ", $date);
    $date = toIndoDate2($datetime[0]);

    if ($date == '' || $date == '0000-00-00') {
        return '-';
    }

    $time = $datetime[1];
    return $date . " " . $time;
}

function toIndoSlash($date)
{
    $newDate = explode('-', $date);
    return $newDate[2] . '/' . $newDate[1] . '/' . $newDate[0];
}

function getDay($date)
{
    return intval(explode('-', $date)[2]);
}

function getMonth($date)
{
    return intval(explode('-', $date)[1]);
}

function getMonthBefore($y, $m)
{
    if ($m > 1) {
        $mb = $m - 1;
        $yb = $y;
    } else {
        $mb = 12;
        $yb = $y - 1;
    }

    return [
        'year' => $yb,
        'month' => $mb,
    ];
}

function substrDate($date)
{
    $day = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 4);
    return $year . '-' . $month . '-' . $day;
}

function dayName($number)
{
    $days = [
        "Mon" => "Senin",
        "Tue" => "Selasa",
        "Wed" => "Rabu",
        "Thu" => "Kamis",
        "Fri" => "Jumat",
        "Sat" => "Sabtu",
        "Sun" => "Minggu",
    ];
    return $days[$number];
}

function getPost($except = null, $byPass = null)
{
    $ci = &get_instance();
    $posts = $ci->input->post();
    $post = array();
    $post['ids'] = $posts['ids'];

    if ($byPass) {
        $newByPass = [];
        foreach ($byPass as $key => $value) {
            $newByPass[$value] = true;
        }
    }

    foreach ($posts as $key => $value) {
        $fix = str_replace($post['ids'] . '_', '', $key);
        if ($byPass && array_key_exists($fix, $newByPass)) {
            $post[$fix] = $value;
        } else {
            $clean = preg_replace("/[^a-zA-Z0-9(@)_#.%,<>:&\/ \n-]+/", "", $value);
            $post[$fix] = $clean;
        }
    }

    if ($except) {
        foreach ($except as $key => $value) {
            if (array_key_exists($value, $post)) {
                unset($post[$value]);
            }
        }
    }

    unset($post['file_uploader_r_0']);
    unset($post['file_uploader_s_0']);
    unset($post['file_uploader_count']);
    unset($post['ids']);
    unset($post['!nativeeditor_status']);
    unset($post[0]);

    return $post;
}

function getGridPost()
{
    $ci = &get_instance();
    $posts = $ci->input->post();
    $post = array();
    foreach ($posts as $key => $value) {
        $clean = preg_replace("/[^a-zA-Z0-9(@)_#.%,<>:&\/ \n-]+/", "", $value);
        $post[$key] = $clean;
    }

    $ids = explode(",", $post['ids']);
    unset($post['ids']);
    foreach ($ids as $id) {
        unset($post[$id . '_gr_id']);
        unset($post[$id . '_!nativeeditor_status']);
        unset($post[0]);
    }

    $posts = [];
    $lengthData = count($post) / count($ids);
    $data = 0;
    foreach ($ids as $id) {
        foreach ($post as $key => $value) {
            if ($data < $lengthData) {
                $posts[$id]['c' . $data] = $value;
                unset($post[$id . '_c' . $data]);
                $data++;
            }
        }
        $data = 0;
    }
    return $posts;
}

function getParam()
{
    $ci = &get_instance();
    $gets = $ci->input->get();
    $get = array();
    foreach ($gets as $key => $value) {
        if ($key !== 'd' || $key !== 'c' || $key !== 'm' || $key !== 'file') {
            $clean = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            $get[$key] = $clean;
        }
    }
    return $get;
}

function fetchFormData($formdata)
{
    foreach ($formdata as $key => $value) {
        $field[] = $key;
        $data[$key] = $value !== '0000-00-00' ? $value : "";
    }
    response(['field' => $field, 'data' => $data]);
}

function cryptoRandSecure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) {
        return $min;
    }
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter;
    } while ($rnd > $range);
    return $min + $rnd;
}

function genUnique($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet);

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[cryptoRandSecure(0, $max - 1)];
    }

    return $token;
}

function cleanSC($string)
{
    if ($string) {
        return "<![CDATA[$string]]>";
    } else {
        return $string;
    }
}

function xmlResponse($type, $message, $id = null)
{
    if (!$id) {
        $id = genUnique(10);
    }
    $message = str_replace('&amp', '&amp;amp', $message);
    $message = str_replace('&', '&amp;amp', $message);
    $message = str_replace('>', '&amp;gt;', $message);
    $message = str_replace('<', '&amp;lt;', $message);
    header("Content-type: text/xml");
    echo ('<?xml version="1.0" encoding="ISO-8859-1"?>');
    echo "<data><action type='$type' sid='$id' tid='$id' message='$message' /></data>";
    die();
}

function gridXmlHeader($xml)
{
    echo header("Content-type: text/xml");
    echo ("<?xml version='1.0' encoding='ISO-8859-1'?>\n");
    echo "<rows>";
    echo $xml;
    echo "</rows>";
}

function isExist($array)
{
    foreach ($array as $key => $value) {
        if ($value) {
            xmlResponse('error', $key . ' sudah ada!', $key);
        }
    }
}

function isDelete($array)
{
    foreach ($array as $key => $value) {
        if (!$value) {
            xmlResponse('error', $key . ' sudah dihapus!', $key);
        }
    }
}

function isEmpty($array)
{
    foreach ($array as $key => $value) {
        if (!$value) {
            xmlResponse('error', $key . ' masih kosong!', $key);
        }
    }
}

function prettyText($post, $ucwords = [], $capitalize = [])
{
    if ($capitalize && count($capitalize) > 0) {
        foreach ($capitalize as $key => $value) {
            if (array_key_exists($value, $post)) {
                $text = explode(' ', $post[$value]);
                $newText = [];
                foreach ($text as $key => $tValue) {
                    $containNumber = preg_match('/\d/', $tValue);
                    if ($containNumber) {
                        $newText[] = $tValue;
                    } else {
                        if (ctype_upper($tValue)) {
                            $newText[] = $tValue;
                        } else {
                            $newText[] = strtoupper($tValue);
                        }
                    }
                }
                $post[$value] = implode(' ', $newText);
            }
        }
    }

    if ($ucwords && count($ucwords) > 0) {
        foreach ($ucwords as $key => $value) {
            if (array_key_exists($value, $post)) {
                $text = explode(' ', $post[$value]);
                $newText = [];
                foreach ($text as $key => $tValue) {
                    $containNumber = preg_match('/\d/', $tValue);
                    if ($containNumber) {
                        $newText[] = $tValue;
                    } else {
                        if (ctype_upper($tValue)) {
                            $newText[] = $tValue;
                        } else {
                            $newText[] = ucwords(strtolower($tValue));
                        }
                    }
                }
                $post[$value] = implode(' ', $newText);
            }
        }
    }

    return $post;
}

function fillStrip($string)
{
    if (!$string || $string == '') {
        return '-';
    } else {
        return $string;
    }
}

function toUcWords($posts)
{
    $newPosts = [];
    foreach ($posts as $key => $value) {
        $newPosts[$key] = ucwords(strtolower($value));
    }
    return $newPosts;
}

function setFileSize($size)
{
    $kb = $size / 1000;
    $fixSize = $kb;
    if ($kb > 1000) {
        $mb = $kb / 1000;
        $fixSize = number_format($mb, 2, '.', ',') . ' MB';
    } else {
        $fixSize = number_format($fixSize, 2, '.', ',') . ' KB';
    }

    return $fixSize;
}

function access($value, $rules = [])
{
    $isGranted = false;
    foreach ($rules as $key => $rule) {
        if ($value === $rule) {
            $isGranted = true;
        }
    }

    return $isGranted;
}

function empId()
{
    $ci = &get_instance();
    return $ci->auth->empId ? $ci->auth->empId : null;
}

function empName()
{
    $ci = &get_instance();
    return $ci->auth->empName ? $ci->auth->empName : null;
}

function empNip()
{
    $ci = &get_instance();
    return $ci->auth->empNip ? $ci->auth->empNip : null;
}

function empRole()
{
    $ci = &get_instance();
    return $ci->auth->role ? $ci->auth->role : null;
}

function empLoc()
{
    $ci = &get_instance();
    return $ci->auth->empLoc ? $ci->auth->empLoc : null;
}

function empRank()
{
    $ci = &get_instance();
    return $ci->auth->rankId ? $ci->auth->rankId : null;
}

function locName()
{
    $ci = &get_instance();
    return $ci->auth->locName ? $ci->auth->locName : null;
}

function empDept()
{
    $ci = &get_instance();
    return $ci->auth->deptId ? $ci->auth->deptId : null;
}

function empSub()
{
    $ci = &get_instance();
    return $ci->auth->subId ? $ci->auth->subId : null;
}

function empDiv()
{
    $ci = &get_instance();
    return $ci->auth->divId ? $ci->auth->divId : null;
}

function checkDateExist($current, $start, $end)
{
    $now = new DateTime($current);
    $startdate = new DateTime($start);
    $enddate = new DateTime($end);

    if ($startdate < $now && $now < $enddate) {
        return true;
    } else {
        return false;
    }
}

function maxStringLength($string, $max)
{
    $stringLength = strlen($string);
    $result = substr($string, 0, $max);
    if ($stringLength > $max) {
        return $result . "...";
    } else {
        return $string;
    }
}

function revDate($date)
{
    $date = explode('-', $date);
    return "$date[2]-$date[1]-$date[0]";
}

function advanceSearch($get)
{
    $where = '';
    $groupBy = '';
    if (isset($get)) {
        foreach ($get as $key => $value) {
            if ($key != 'search' && $key != 'd' && $key != 'c' && $key != 'm' && count(explode('dhxr', $key)) === 1) {
                $expKey = explode('_', $key);
                if ($expKey[0] == 'equal') {
                    $column = str_replace('equal_', '', $key);
                    $where .= " AND a.$column = '$value'";
                } else if ($expKey[0] == 'notequal') {
                    $column = str_replace('notequal_', '', $key);
                    $where .= " AND a.$column != '$value'";
                } else if ($expKey[0] == 'betweendate') {
                    $date = explode(",", $value);
                    $column = str_replace('betweendate_', '', $key);
                    $where .= " AND DATE(a.$column) BETWEEN '$date[0]' AND '$date[1]'";
                } else if ($expKey[0] == 'notin') {
                    $date = explode(",", $value);
                    $column = str_replace('notin_', '', $key);
                    $expValue = explode(",", $value);
                    $in = "";
                    foreach ($expValue as $expKey => $expVal) {
                        if ($in === "") {
                            $in = "'" . $expVal . "'";
                        } else {
                            $in = $in . ",'" . $expVal . "'";
                        }
                    }
                    $where .= " AND a.$column NOT IN($in)";
                } else if ($expKey[0] == 'in') {
                    $date = explode(",", $value);
                    $column = str_replace('in_', '', $key);
                    $expValue = explode(",", $value);
                    $in = "";
                    foreach ($expValue as $expKey => $expVal) {
                        if ($in === "") {
                            $in = "'" . $expVal . "'";
                        } else {
                            $in = $in . ",'" . $expVal . "'";
                        }
                    }
                    $where .= " AND a.$column IN($in)";
                } else if ($expKey[0] == 'month') {
                    $column = str_replace('month_', '', $key);
                    $where .= " AND MONTH(a.$column) = '$value'";
                } else if ($expKey[0] == 'year') {
                    $column = str_replace('year_', '', $key);
                    $where .= " AND YEAR(a.$column) = '$value'";
                } else if ($expKey[0] == 'gt') {
                    $column = str_replace('gt_', '', $key);
                    $where .= " AND a.$column > '$value'";
                } else if ($expKey[0] == 'lt') {
                    $column = str_replace('lt_', '', $key);
                    $where .= " AND a.$column < '$value'";
                } else if ($expKey[0] == 'groupby') {
                    $column = str_replace('groupby_', '', $key);
                    if ($value) {
                        if ($groupBy == '') {
                            $groupBy = "a.$column";
                        } else {
                            $groupBy = $groupBy . ",a.$column";
                        }
                    }
                } else if ($expKey[0] == 'wherejoin') {
                    $column = str_replace('wherejoin_', '', $key);
                    $where .= " AND a.$column = $value";
                } else if ($expKey[0] == 'join') {
                    $alias = $expKey[1];
                    if ($expKey[2] == 'notequal') {
                        $column = str_replace('join_' . $alias . '_notequal_', '', $key);
                        $where .= " AND $alias.$column != '$value'";
                    }
                }
            }
        }
    }

    if ($groupBy != '') {
        $groupBy = " GROUP BY ($groupBy)";
        $where .= $groupBy;
    }

    return $where;
}

function sortArrayDesc($array, $column)
{
    $keys = array_column($array, $column);
    return array_multisort($keys, SORT_DESC, $array);
}

function toRomawi($month)
{
    if ($month == '01') {
        return "I";
    } else if ($month == '02') {
        return "II";
    } else if ($month == '03') {
        return "III";
    } else if ($month == '04') {
        return "IV";
    } else if ($month == '05') {
        return "V";
    } else if ($month == '06') {
        return "VI";
    } else if ($month == '07') {
        return "VII";
    } else if ($month == '08') {
        return "VII";
    } else if ($month == '09') {
        return "IX";
    } else if ($month == '10') {
        return "X";
    } else if ($month == '11') {
        return "XI";
    } else if ($month == '12') {
        return "XII";
    }
}

function genOvtDate($date, $time)
{
    $time = explode(":", $time);
    return date('Y-m-d H:i:s', strtotime("$date $time[0]:$time[1]:00"));
}

function countHour($start, $end, $type)
{
    $datetimeObj1 = new DateTime($start);
    $datetimeObj2 = new DateTime($end);
    $interval = $datetimeObj1->diff($datetimeObj2);

    if ($type == 'd') {
        return $interval->format("%a");
    }

    if ($type == 'h') {
        $hour = $interval->format('%h');
        $minute = $interval->format('%i');
        return floatval($hour) + (floatval($minute) / 60);
    }
}

function countTotalHourWorkday($hour, $minute)
{
    if ($hour > 0 || $minute > 0) {
        if ($hour == 0) {
            $fistHour = ($minute / 60) * 1.5;
            return $fistHour;
        } else {
            $fistHour = 1 * 1.5;
        }

        $hour -= 1;

        if ($hour > 0) {
            if ($minute > 0) {
                $nextHour = $hour * 2;
                $nextMinute = number_format(($minute / 60), 2) * 2;
                $next = number_format(floatval($nextHour + $fistHour + $nextMinute), 2);
                return $next;
            } else {
                $nextHour = $hour * 2;
                $next = number_format(floatval($nextHour + $fistHour), 2);
                return $next;
            }
        } else {
            if ($minute > 0) {
                $nextMinute = number_format(($minute / 60), 2) * 2;
                $next = number_format(floatval($fistHour + $nextMinute), 2);
                return $next;
            } else {
                $next = number_format(floatval($fistHour), 2);
                return $next;
            }
        }
    } else {
        return 0;
    }
}

function countTotalHourWeekend($hour, $minute)
{
    $minute = $minute > 0 ? number_format(($minute / 60), 2) : 0;
    if ($hour > 0 || $minute > 0) {
        if ($hour == 0) {
            $fistHour = $minute * 2;
            return number_format($fistHour, 2);
        } else if ($hour < 7) {
            $fistHour = $hour * 2;
            $nextMinute = number_format(($minute * 2), 2);
            return number_format(($fistHour + $nextMinute), 2);
        } else if ($hour == 7) {
            $fistHour = $hour * 2;
            if ($minute > 0) {
                $nextHour = $minute * 3;
                return number_format(floatval($fistHour + $minute), 2);
            } else {
                return number_format($fistHour, 2);
            }
        } else if ($hour > 7 && $hour <= 8) {
            $fistHour = 7 * 2;
            $hour -= 7;
            $nextHour = $hour * 3;
            if ($minute > 0) {
                $newHourMinute = $minute * 3;
                return number_format(floatval($fistHour + $nextHour + $newHourMinute), 2);
            } else {
                return number_format(floatval($fistHour + $nextHour), 2);
            }
        } else if ($hour >= 9) {
            $fistHour = 7 * 2;
            $hour -= 7;
            $secondHour = 1 * 3;
            $hour -= 1;
            $nextHour = $hour * 4;
            if ($minute > 0) {
                $newHourMinute = $minute * 4;
                return number_format(floatval($fistHour + $nextHour + $secondHour + $newHourMinute), 2);
            } else {
                return number_format(floatval($fistHour + $secondHour + $nextHour), 2);
            }
        }
    } else {
        return 0;
    }
}

function countTotalHourNationDay($hour, $minute)
{
    $minute = $minute > 0 ? number_format(($minute / 60), 3) : 0;
    if ($hour > 0 || $minute > 0) {
        if ($hour == 0) {
            $fistHour = $minute * 3;
            return number_format($fistHour, 2);
        } else if ($hour < 7) {
            $fistHour = $hour * 3;
            $nextMinute = number_format(($minute * 3), 2);
            return number_format(($fistHour + $nextMinute), 2);
        } else if ($hour == 7) {
            $fistHour = $hour * 3;
            if ($minute > 0) {
                $nextHour = $minute * 4;
                return number_format(floatval($fistHour + $minute), 2);
            } else {
                return number_format($fistHour, 2);
            }
        } else if ($hour > 7 && $hour <= 8) {
            $fistHour = 7 * 3;
            $hour -= 7;
            $nextHour = $hour * 4;
            if ($minute > 0) {
                $newHourMinute = $minute * 4;
                return number_format(floatval($fistHour + $nextHour + $newHourMinute), 2);
            } else {
                return number_format(floatval($fistHour + $nextHour), 2);
            }
        } else if ($hour >= 9) {
            $fistHour = 7 * 3;
            $hour -= 7;
            $secondHour = 1 * 4;
            $hour -= 1;
            $nextHour = $hour * 4;
            if ($minute > 0) {
                $newHourMinute = $minute * 4;
                return number_format(floatval($fistHour + $nextHour + $secondHour + $newHourMinute), 2);
            } else {
                return number_format(floatval($fistHour + $secondHour + $nextHour), 2);
            }
        }
    } else {
        return 0;
    }
}

function checkWeekend($date)
{
    $ci = &get_instance();
    $month = date('m', strtotime($date));
    $day = date('D', strtotime($date));
    if ($day === 'Sat' || $day === 'Sun') {
        return true;
    } else {
        return false;
    }
}

function checkWeekend2($date)
{
    $ci = &get_instance();
    $month = date('m', strtotime($date));
    $day = date('D', strtotime($date));
    if ($day === 'Sat' || $day === 'Sun') {
        return [
            'status' => true,
            'day' => $day,
        ];
    } else {
        return [
            'status' => false,
            'day' => $day,
        ];
    }
}

function checkNationalDay($date)
{
    $ci = &get_instance();
    $national = $ci->Hr->getOne('national_days', ['date' => $date]);
    if ($national) {
        return true;
    } else {
        return false;
    }
}

function totalHour($empId, $date, $start, $end, $startTime, $endTime)
{
    $ci = &get_instance();
    $startTime = $startTime;
    $endTime = $endTime;

    $expStart = explode(':', $startTime);
    $expEnd = explode(':', $endTime);

    $startHour = $expStart[0];
    $startMinute = $expStart[1];

    $endHour = $expEnd[0];
    $endMinute = $expEnd[1];

    $hour = 0;
    $div = 0;
    $rest1 = 12;
    $rest2 = 18;
    $rest3 = 24;
    $normalRest4 = 4;
    $rest4 = 28;
    $normalRest5 = 5;
    $rest5 = 29;
    $rest6 = 0;

    $mealTime1 = 12.5;
    $mealTime2 = 18.5;
    $mealTime3 = 24.5;

    $meal1 = 0;
    $meal2 = 0;
    $meal3 = 0;

    if ($endHour < $startHour && intval($endHour) < 8) {
        $endFixing = intval($endHour) + 24;
    } else {
        $endFixing = intval($endHour);
    }

    $divStartMinute = intval($startMinute) / 60;
    $divEndMinute = intval($endMinute) / 60;

    $totalMeal = 0;

    for ($i = intval($startHour) + 1; $i <= $endFixing; $i++) {
        if ($i == $rest1 || $i == $rest2 || $i == $rest3 || $i == $rest4 || $i == $rest5 || ($i - 1) == $rest6) {
            if ($i != $rest5) {
                $div++;
            }
        }

        if (($i + $divEndMinute) > $mealTime1 && $hour >= 3 && $startHour < 12) {
            if ($meal1 == 0) {
                $totalMeal++;
                $meal1 = 1;
            }
        }
        if (($i + $divEndMinute) > $mealTime2 && $hour >= 3 && $startHour < 18) {
            if ($meal2 == 0) {
                $totalMeal++;
                $meal2 = 1;
            }
        }
        if (($i + $divEndMinute) > $mealTime3 && $hour >= 3 && $startHour < 24) {
            if ($meal3 == 0) {
                $totalMeal++;
                $meal3 = 1;
            }
        }

        $hour++;
    }

    if ($endHour == $rest1 || $endHour == $rest2 || $endHour == $rest3 || $endHour == $normalRest4 || $endHour == $normalRest5 || $endHour == $rest6) {
        if ($endHour == $normalRest4) {
            $div -= 1;
        } else if ($endHour == $normalRest5) {
            if ($divEndMinute == 0) {
                $div -= 0.5;
            }
        } else {
            if (intval($endMinute) == 0) {
                $div -= 1;
            } else {
                $div -= $divEndMinute;
            }
        }
    }

    if ($div < 0) {
        $totalHour = ($hour - $divStartMinute + $divEndMinute);
    } else {
        $totalHour = ($hour - $divStartMinute + $divEndMinute) - $div;
        if ($endHour < $startHour && ($hour - $divStartMinute + $divEndMinute) > 7.5) {
            $totalHour += 1;
        }
    }

    $expHour = explode(".", $totalHour);
    $realHour = $expHour[0];
    $realMinute = isset($expHour[1]) && $expHour[1] > 0 ? 30 : 0;

    if (checkNationalDay($date)) {
        $overtimeHour = countTotalHourNationDay($realHour, $realMinute);
        $statusDay = 'Libur Nasional';
    } else if (checkWeekend($date)) {
        $overtimeHour = countTotalHourWeekend($realHour, $realMinute);
        $statusDay = 'Hari Libur';
    } else {
        $overtimeHour = countTotalHourWorkday($realHour, $realMinute);
        $statusDay = 'Hari Kerja';
    }

    $premi = $ci->Hr->getOne('employee_sallary', ['emp_id' => $empId])->premi_overtime;
    $emp = $ci->Hr->getDataById('employees', $empId);
    $totalPremi = $emp->rank_id <= 6 || $emp->overtime == 1 ? $totalHour * $premi : $overtimeHour * $premi;
    return [
        'status_day' => $statusDay,
        'effective_hour' => ($hour - $divStartMinute + $divEndMinute),
        'break_hour' => $div < 0 ? 0 : $div,
        'real_hour' => $totalHour,
        'overtime_hour' => $emp->rank_id <= 6 || $emp->overtime == 1 ? $totalHour : $overtimeHour,
        'premi_overtime' => $premi,
        'overtime_value' => $totalPremi,
        'total_meal' => $totalHour >= 3 ? $totalMeal : 0,
    ];
}

function addDayToDate($date, $day)
{
    $date = new DateTime($date);
    $date->modify("+$day day");
    return $date->format('Y-m-d H:i:s');
}

function backDayToDate($date, $day)
{
    $date = new DateTime($date);
    $date->modify("-$day day");
    return $date->format('Y-m-d H:i:s');
}

function checkStatusDay($date)
{
    if (checkNationalDay($date)) {
        $statusDay = 'Libur Nasional';
    } else if (checkWeekend($date)) {
        $statusDay = 'Hari Libur';
    } else {
        $statusDay = 'Hari Kerja';
    }
    return $statusDay;
}

function simpleEncrypt($string, $action = 'e')
{
    $secret_key = 'Z4k1r1sh4q53pt14n';
    $secret_iv = 'Z4k1r1sh4q53pt14nSyaf1q';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

function isUnique($value, $params)
{
    $ci = &get_instance();
    $params = explode(".", $params);

    $table = $params[0];
    $field = $params[1];

    $ci->db->where($field, $value);

    if (count($params) === 3) {
        $id = $params[2];
        $ci->db->where_not_in('id', $id);
    }

    $data = $ci->db->limit(1)->get($table)->result();
    return $data ? false : true;
}

function formValidate($data, $validator)
{
    foreach ($data as $key => $value) {
        if (array_key_exists($key, $validator)) {
            $toValidate = explode(",", $validator[$key]);
            foreach ($toValidate as $key => $toValue) {
                if ($toValue == 'isEmail') {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        xmlResponse('error', 'Email tidak valid');
                    }
                }
            }
        }
    }
}

function sendChatMsg($data, $gid)
{
    $ci = &get_instance();
    $style = [
        'container' => 'display:flex;flex-direction:column;justify-content:space-between;align-items:center;width:100%;height:auto',
        'title' => 'font-family:sans-serif;text-align:center;width:100%;color:#ccc;padding-top:10px',
        'hr' => 'border; 1px solid #ccc',
        'body' => 'width:100%;color:#ccc',
        'field' => 'text-align:left;padding:10px',
    ];
    $message = $ci->load->view('html/chat/send_message', ['style' => $style, 'data' => $data], true);
    $dataMessage = [
        'gid' => $gid,
        'uid' => 1,
        'msg' => str_replace('\n', '', $message),
        'type' => 'msg',
        'rtxt' => 0,
        'rid' => 0,
        'rmid' => 0,
        'lnurl' => '',
        'lntitle' => null,
        'lndesc' => null,
        'lnimg' => '',
        'rtype' => 'msg',
        'cat' => 'user',
    ];
    return $dataMessage;
}
