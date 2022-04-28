<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckinsUserController extends Controller
{
    public function index(Request $request){
        $checkins = $request->user()->checkins()->orderBy('date', 'desc')->get();
        return $checkins;
    }
}
