<?php
defined('BASEPATH') or exit('No direct script access allowed');

//application/libraries/CreatorJWT.php
require APPPATH . '/libraries/JWT.php';

class CreatorJWT
{

    /*************This function generate token private key**************/

    private $key = "1234567890qwertyuiopmnbvcxzasdfghjkl";
    public function GenerateToken($data)
    {
        $jwt = JWT::encode($data, $this->key);
        return $jwt;
    }

    /*************This function DecodeToken token **************/

    public function DecodeToken($token)
    {
        $decoded = JWT::decode($token, $this->key, array('HS256'));
        $decodedData = (array) $decoded;
        return $decodedData;
    }

    public function checkToken($token)
    {
        if(array_key_exists('authorization', $token)) {
            $jwtData = $this->DecodeToken(str_replace('Bearer ', '', $token['authorization']));
            if(count($jwtData) > 0) return true;
        } else if(array_key_exists('Authorization', $token)) {
            $jwtData = $this->DecodeToken(str_replace('Bearer ', '', $token['Authorization']));
            if(count($jwtData) > 0) return true;
        } else {
            response([
                'status' => false,
                'error' => 'Token bermasalah',
            ], 400);
        }
    }
}
