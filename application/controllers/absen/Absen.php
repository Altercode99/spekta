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
                    ->setResizeToWidth(75);
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
}
