<?php

namespace App\Http\Controllers;

use App\Command;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request) {
        $data = Command::select(\DB::raw("min(cmd), sid"))->groupBy('sid')->where("cmd", "LIKE", "%".$request->input('query')."%")->get();

        return response()->json($data);
    }
}
