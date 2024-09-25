<?php

namespace common\components\services;

use yii\helpers\Json;

class SmsGate
{
    const API_BASE_URL = 'https://smspilot.ru/api.php?send=test&to=79087964781&apikey=XYZ&format=json';
    const API_KEY = 'API_KEY';

    public static function send($phone, $text)
    {
        $query = http_build_query([
            'apikey' => self::API_KEY,
            'send' => $text,
            'to' => $phone,
            'format' => 'json'
        ]);
        $result = file_get_contents(self::API_BASE_URL . $query);
        $json = Json::decode($result);

        if (isset($json['send'])) {
            return true;
        }

        return false;
    }
}