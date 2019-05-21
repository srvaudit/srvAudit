<?php

namespace App\Http\Controllers;

use App\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request) {
        $data = Command::select(\DB::raw("min(cmd) as cmd, sid"))->orderBy('id', 'desc')->groupBy('sid')->where("cmd", "LIKE", "%".$request->input('query')."%")->get();

        foreach($data as $d) {
            $d->cmd = Str::limit($d->cmd, 10, '...');
        }
        return response()->json($data);
    }
}
