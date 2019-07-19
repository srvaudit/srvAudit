<?php

namespace App\Http\Controllers;

use App\Command;
use App\Session;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Events\CommandReceived;
use Illuminate\Support\Facades\Log;

class CommandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $command = new Command($request->all());
        $session = Session::select('id')->where('sid', $request['sid'])->first();
        $command->session_id = $session->id;
        $command->start = Carbon::createFromTimestamp($request->input('start'))->toDateTimeString('Y-m-d H:i:s');
        $command->end = Carbon::createFromTimestamp($request->input('end'))->toDateTimeString('Y-m-d H:i:s');
        $command->save();
        event(new CommandReceived($command));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Command  $command
     * @return \Illuminate\Http\Response
     */
    public function show(Command $sid)
    {
        return Command::where('sid', '=', "$sid")->orderBy('created_at', 'asc')->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Command  $command
     * @return \Illuminate\Http\Response
     */
    public function edit(Command $command)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Command  $command
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Command $command)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Command  $command
     * @return \Illuminate\Http\Response
     */
    public function destroy(Command $command)
    {
        //
    }
}
