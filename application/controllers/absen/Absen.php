<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Absen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("AbsenModel");
        $this->AbsenModel->myConstruct('hr');
    }

    public function qrGate()
    {
        $params = getParam();
        if(isset($params['gate'])) {
            $gate = $this->Hr->getOne('gates', ['gate' => $params['gate']]);
            if($gate) {
                $this->load->view('html/absen/gate', ['gate' => $gate]);
            } else {
                $this->load->view('html/invalid_response', ['message' => 'Gate tidak ditemukan!']);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Gate tidak valid!']);
        }
    }

    public function genQrCode()
    {
        $post = fileGetContent();
        $token = time().'-'.$post->gate;
        $gate = $this->Hr->getOne('gates', ['gate' => $post->gate]);
        
        if($gate) {
            $update = $this->Hr->update('gates', ['token' => $token, 'before_token' =>  $gate->token], ['gate' => $post->gate]);
            if($update) {
                $writer = new PngWriter();
                $qrCode = QrCode::create($token)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                    ->setSize(300)
                    ->setMargin(10)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255));
                $logo = Logo::create('./public/img/spekta.png')
                    ->setResizeToWidth(0);
                $label = Label::create($gate->gate_name)
                    ->setTextColor(new Color(255, 0, 0));
                $result = $writer->write($qrCode, $logo, $label);
                $result->saveToFile("./assets/qr_absen/$post->gate.png");
                $dataUri = $result->getDataUri();
                $imgUrl = base_url("assets/qr_absen/$post->gate.png");
                $newQR = "<img src='$dataUri' alt='$gate->token'>";

                response(['status' => 'success', 'newQR' => $newQR]);
            } else {
                response(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh']);
            }
        } else {
            response(['status' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh']);
        }
    }

    public function getShiftGrid()
    {
        $shiftRegs = $this->AbsenModel->getShiftGridReguler(getParam())->result();
        $shifts = $this->AbsenModel->getShiftGrid(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($shiftRegs as $shift) {
            $workTime = $shift->work_start.' - '.$shift->work_end;
            $xml .= "<row id='$shift->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC('ALL') ."</cell>";
            $xml .= "<cell>". cleanSC('ALL') ."</cell>";
            $xml .= "<cell>". cleanSC('ALL') ."</cell>";
            $xml .= "<cell>". cleanSC($shift->name) ."</cell>";
            $xml .= "<cell>". cleanSC($workTime) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($shift->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        foreach ($shifts as $shift) {
            $workTime = $shift->work_start.' - '.$shift->work_end;
            $xml .= "<row id='$shift->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->department) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->division) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->name) ."</cell>";
            $xml .= "<cell>". cleanSC($workTime) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($shift->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($shift->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function shiftForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $cat = $this->Hr->getDataById('work_time', $params['id'], 'id,name');
            fetchFormData($cat);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createWorkTime($post);
            } else {
                $this->updateWorkTime($post);
            }
        }
    }

    public function createWorkTime($post)
    {
        if($post['is_reguler'] != '1') {
            $time = $this->Hr->getWhere('work_time', [
                'name' => $post['name'], 
                'division_id' => $post['division_id'],
                'location' => empLoc()
            ])->row();
        } else {
            $time = $this->Hr->getWhere('work_time', [
                'name' => $post['name'], 
                'location' => empLoc()
            ])->row();
        }
       
        isExist(["Kategori $post[name]" => $time]);
        unset($post['is_reguler']);
        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->Hr->create('work_time', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function shiftDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus <br>";
            $this->Hr->delete('work_time', ['id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

}
