<?php

namespace App\API;


class ApiMessage{
    public static function errorMessage($message, $code){
        return [
            'data' => [
                'message' => $message,
                'code' => $code
            ]
        ];
    }

    public static function successMessage($message, $code){
        return [
            'data' => [
                'message' => $message,
                'code' => $code
            ]
        ];
    }
}