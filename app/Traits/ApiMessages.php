<?php

namespace App\Traits;

trait ApiMessages
{
    private function successResponse($message,$data,$code)
    {
        return response()->json(['message'=>$message,'data'=>$data,'code'=>$code],$code);
    }

    protected function errorResponse($message,$data,$code)
    {
        return response()->json(['message'=>$message,'error'=>$data,'code'=>$code],$code);
    }


}