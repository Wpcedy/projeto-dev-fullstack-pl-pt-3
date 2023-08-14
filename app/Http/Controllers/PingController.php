<?php

namespace App\Http\Controllers;

class PingController extends Controller
{
    public function index()
    {
        $data = ['Backend is working'];

        return response()->json($data, 200);
    }
}