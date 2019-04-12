<?php

namespace App\Http\Controllers;

use Auth;
use View;
use User;
use Storage;
use OneSignal;
use App\Audit;
use App\Command;
use App\Session;
use Carbon\Carbon;
use App\SessionLine;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Events\SessionReceived;
use Illuminate\Support\Facades\Log;
use App\Events\CommandListRequested;
use Symfony\Component\Process\Process;
use Laravel\Spark\Contracts\Repositories\NotificationRepository;


class SessionController extends Controller
{
    protected $commands;

    public function __construct(NotificationRepository $notifications, Command $commands)
    {
        $this->notifications = $notifications;
        $this->middleware('auth');
        $this->commands = $commands;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $latestsessions = Session::select('sessions.sid as sid', 'sessions.hostname as hostname', 'sessions.username as username', 'sessions.created_at as created_at', 'sessions.updated_at as updated_at')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->orderBy('sessions.created_at','desc')->take(6)->get();
        if(count($latestsessions) > 0) {
            foreach($latestsessions as $session) {
                $prettystarttimes[] = $session->created_at->diffForHumans();
            }
        } else {
            return view('start');
        }
        return view('home', [
            'servers'           =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('hostname')->orderBy('hostname','asc')->groupBy('hostname')->get(),
            'lservers'          =>  $this->getServerSessionCounts(),
            'usersessioncounts' =>  $this->getUserSessionCounts(),
            'users'             =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('username')->orderBy('username','asc')->groupBy('username')->get(),
            'latestsessions'    =>  $latestsessions,
            'prettystarttimes'  =>  $prettystarttimes,
            'numlines'          =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('sid','hostname')->count(),
            'totalsessions'     =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->count(),
            'totalcommands'     =>  Command::join('sessions', 'sessions.id', '=', 'commands.session_id')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->count(),
            'daysessions'       =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->subDay(), Carbon::now()])->count(),
            'daycommands'       =>  Command::join('sessions', 'sessions.id', '=', 'commands.session_id')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('commands.created_at', [Carbon::now()->subDay(), Carbon::now()])->count(),
            'yesterdaysessions' =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->subDay(2), Carbon::now()->subDay()])->count(),
            'yesterdaycommands' =>  Command::join('sessions', 'sessions.id', '=', 'commands.session_id')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('commands.created_at', [Carbon::now()->subDay(2), Carbon::now()->subDay()])->count(),
            'weeksessions'      =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->subWeek(), Carbon::now()])->count(),
            'weekcommands'      =>  Command::join('sessions', 'sessions.id', '=', 'commands.session_id')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('commands.created_at', [Carbon::now()->subWeek(), Carbon::now()])->count(),
            'lastweeksessions'  =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->subWeek(2), Carbon::now()->subWeek()])->count(),
            'lastweekcommands'  =>  Command::join('sessions', 'sessions.id', '=', 'commands.session_id')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('commands.created_at', [Carbon::now()->subWeek(2), Carbon::now()->subWeek()])->count(),
            'monthsessions'     =>  Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()])->count(),
            'monthcommands'     =>  Command::join('sessions', 'sessions.id', '=', 'commands.session_id')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('commands.created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()])->count(),
        ]);
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
        $session = new Session($request->all());
        $session->user_id = $request->user()->id;
        $session->team_id = $request->user()->current_team_id;
        $session->created_at = Carbon::now();
        $session->save();
        // send to redis -> socket.io -> browser vue instance
        event(new SessionReceived($session));
        foreach($request->user()->currentTeam->users as $user) {
            $this->notifications->create($user, [
                'icon' => 'fa-chevron-right',
                'body' => 'Session '.$session->sid.' started by '.$session->username.' on host '.$session->hostname,
                'action_text' => 'View Session',
                'action_url' => '/sessions/'.$session->sid,
            ]);
            if($user->onesignal_id) {
                Log::debug("onesignal_id: $user->onesignal_id");
                OneSignal::sendNotificationToUser($session->username." started a session on ".$session->hostname, $user->onesignal_id, $url = "https://dev.srvaudit.com/sessions/".$session->sid, $data = null, $buttons = null, $schedule = null);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $session = Session::where('sid','=',"$id")->first();
        $session->notes = $request->notes;
        $session->updated_at = Carbon::now();
        $session->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        //
    }

    /**
     * Get servers sorted by number of logins
     *
     * @return array
     */
    public function getUserSessionCounts()
    {
        $user = Auth::user();
        $users = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->get();
        $topusers = array();
        foreach ($users as $auser)
        {
            $topusers[] = $auser->username;
        }
        $users = array_count_values($topusers);
        arsort($users);
        // show only top 6 servers by session count
        $users = array_slice($users,0,6);
        return $users;
    }

    /**
     * Get servers sorted by number of logins
     *
     * @return array
     */
    public function getServerSessionCounts()
    {
        $user = Auth::user();
        $servers = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->get();
        $topservers = array();
        foreach ($servers as $server)
        {
            $topservers[] = $server->hostname;
        }
        $servers = array_count_values($topservers);
        arsort($servers);
        // show only top 6 servers by session count
        $servers = array_slice($servers,0,6);
        return $servers;
    }

    /**
     * Get list of sessions with specific server $id
     *
     * @param mixed $id
     */
    public function getServers($id)
    {
        $user = Auth::user();
        $servers = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('hostname')->orderBy('hostname','asc')->groupBy('hostname')->get();
        $users = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('username')->orderBy('username','asc')->groupBy('username')->get();
        $sessions = Session::select('sessions.id as id', 'sessions.sid as sid', 'sessions.hostname as hostname', 'sessions.username as username', 'sessions.created_at as created_at', 'sessions.updated_at as updated_at')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->where('hostname','=',"$id")->distinct()->orderBy('sessions.created_at','desc')->simplePaginate(25);
	$numcommands = array();
	$numlines = array();
        $prettystarttimes = array();
        $sessiondurations = array();
        foreach($sessions as $session) {
            $numcommands[] = $session->commands()->count();
            $prettystarttimes[] = $session->created_at->diffForHumans();
            $starttimes[] = $session->created_at;
            $endtimes[] = $session->updated_at;
            $sessiondurations[] = $session->updated_at->diffForHumans($session->created_at, true);
        }
        return view('sessionList', [
            'servers'           => $servers,
            'prettystarttimes'  => $prettystarttimes,
            'starttimes'        => $starttimes,
            'endtimes'          => $endtimes,
            'sessiondurations'  => $sessiondurations,
            'user'              => $users,
            'thisserver'        => $id,
            'numcommands'       => $numcommands,
            'sessions'          => $sessions,
            'numlines'          => $numlines
        ]);
    }

    /**
     * Get list of sessions with specific username
     *
     * @param mixed $username
     */
    public function getUser($username)
    {
        $user = Auth::user();
        $servers = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('hostname')->orderBy('hostname','asc')->groupBy('hostname')->get();
        $users = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('username')->orderBy('username','asc')->groupBy('username')->get();
        $sessions = Session::select('sessions.id as id', 'sessions.sid as sid', 'sessions.hostname as hostname', 'sessions.username as username', 'sessions.created_at as created_at', 'sessions.updated_at as updated_at')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->where('username','=',"$username")->distinct()->orderBy('sessions.created_at','desc')->simplePaginate(25);
        $numcommands = array();
        $numlines = array();
        $prettystarttimes = array();
        $sessiondurations = array();
        foreach($sessions as $session) {
            $numcommands[] = $session->commands()->count();
            $prettystarttimes[] = $session->created_at->diffForHumans();
            $starttimes[] = $session->created_at;
            $endtimes[] = $session->updated_at;
            $sessiondurations[] = $session->updated_at->diffForHumans($session->created_at, true);
        }
        return view('sessionList', [
            'servers'       => $servers,
            'prettystarttimes'  =>  $prettystarttimes,
            'starttimes'        => $starttimes,
            'endtimes'          => $endtimes,
            'sessiondurations'  =>  $sessiondurations,
            'user'          => $users,
            'numcommands'   => $numcommands,
            'thisserver'    => $username,
            'sessions'         => $sessions,
            'numlines'      => $numlines
        ]);
    }

    /**
     * Get list of sessions
     */
    public function listSessions()
    {
        $user = Auth::user();
        $servers = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('hostname')->orderBy('hostname','asc')->groupBy('hostname')->get();
        $users = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->select('username')->orderBy('username','asc')->groupBy('username')->get();
        $sessions = Session::select('sessions.id as id', 'sessions.sid as sid', 'sessions.hostname as hostname', 'sessions.username as username', 'sessions.created_at as created_at', 'sessions.updated_at as updated_at')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->orderBy('sessions.created_at','desc')->simplePaginate(25);
	$numcommands = array();
	$numlines = array();
        if(count($sessions) > 0) {
            foreach($sessions as $session) {
                $numcommands[] = $session->commands()->count();
                $prettystarttimes[] = $session->created_at->diffForHumans();
                $starttimes[] = $session->created_at;
                $endtimes[] = $session->updated_at;
                $sessiondurations[] = $session->updated_at->diffForHumans($session->created_at, true);
            }
        } else {
            return view('start');
        }

        return view('sessionList', [
            'servers'           => $servers,
            'prettystarttimes'  =>  $prettystarttimes,
            'starttimes'        =>  $starttimes,
            'endtimes'          =>  $endtimes,
            'sessiondurations'  =>  $sessiondurations,
            'thisserver'        => '',
            'user'              => $users,
            'sessions'          => $sessions,
            'numcommands'       => $numcommands,
            'numlines'          => $numlines
        ]);
    }

    /**
     * Displays session details
     *
     * @return mixed
     */
    public function getSession($id, Request $request)
    {
        $user = Auth::user();
        $sessionuser = Session::select('username')->where('sid','=',"$id")->get();
        $sessions = Session::select('sessions.id as id', 'sessions.hostname as hostname', 'sessions.ticket_url as ticket_url', 'sessions.created_at as created_at', 'sessions.updated_at as updated_at', 'sessions.notes as notes')->join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->where('sid','=',"$id")->orderBy('sessions.created_at','desc')->simplePaginate(25);
        foreach($sessions as $session) {
            $started = $session->created_at->diffForHumans();
            $starttime = $session->created_at;
            $endtime = $session->updated_at;
            $duration = $session->updated_at->diffForHumans($session->created_at, true);
            $ticket_url = $session->ticket_url;
            $notes = $session->notes;
        }
		if ($request->ajax() || $request->isJson()) {
            //Get data from your Model or whatever
            return response()->json([
                'commands' => Command::where('sid','=',"$id")->get()
            ]);
		} else {
			return view('session', [
				'sid'           => $id,
				'user'          => $sessionuser,
				'sessions'      => $sessions,
				'duration'      => $duration,
				'ticket_url'    => $ticket_url,
				'notes'         => $notes,
				'command_count' => Command::where('sid','=',"$id")->count(),
				'starttime'     => $starttime,
				'endtime'       => $endtime,
				'started'       => $started
			]);
		}
        event(new CommandListRequested($this->commands->show($id)));
    }

    /**
     * getAuditSummary
     *
     * @param mixed $lines
     * @param mixed $id
     *
     * @return array $result Audit Summary
     */
    public function getAuditSummary($lines, $id)
    {
        $auditfile = '';
        foreach ($lines as $line)
        {
            $auditfile = $auditfile . $line->message . "\n";
        }
        Storage::disk('local')->put($id, $auditfile);
        exec("aureport -if /var/www/storage/app/$id", $result);

        return $result;
    }

    /**
     * getAudit
     *
     * @param mixed $id
     *
     * @return array $result Audit Lines
     */
    public function getAuditLines($id)
    {
        $audit_lines = Audit::select('message')->where('sid', '=', "$id")->get();
        return $audit_lines;
    }

    /**
     * getSessionSummary
     *
     * @param mixed $lines
     * @param mixed $id
     *
     * @return array $result Session Summary
     */
    public function getSessionSummary($lines, $id)
    {
        $sessionfile = '';
        foreach ($lines as $line)
        {
            $sessionfile = $sessionfile . $line->line;
        }
        Storage::disk('local')->put($id, $sessionfile);
        exec("cat /var/www/storage/app/$id", $result);

        return $result;
    }

    /**
     * getSessionChart
     *
     * @return array $result Session Summary
     */
    public function getSessionChart()
    {
        $user = Auth::user();
        $numMonths=6;
        $monthData['label'] = 'Sessions per month';
        $monthLabels[] = Carbon::now()->format('F');
        for($i=0; $i < $numMonths; $i++) {
            if($i > 0) {
                $monthData['data'][] = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->startOfMonth()->subMonth($i)->startOfMonth(), Carbon::now()->startOfMonth()->subMonth($i)->endOfMonth()])->count();
                $monthLabels[] = Carbon::now()->startOfMonth()->subMonth($i)->format('F');
            } else {
                $monthData['data'][] = Session::join('users', 'sessions.user_id', '=', 'users.id')->where('users.current_team_id', '=', $user->current_team_id)->whereBetween('sessions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count();
            }
        }
        return response()->json([
            'labels' => $monthLabels,
            'datasets'  => [$monthData]
        ]);
    }

    /**
     * getUserChart
     *
     * @return array $result Session Summary
     */
    public function getUserChart()
    {
        $user = Auth::user();
        $numMonths=6;
        $reportUsers = Session::select('username')->where('team_id','=',$user->current_team_id)->distinct()->get();
        for($i=0; $i < $numMonths; $i++) {
            if($i > 0) {
                $monthLabels[] = Carbon::now()->startOfMonth()->subMonth($i)->format('F');
            } else {
                $monthLabels[] = Carbon::now()->startOfMonth()->format('F');
            }
        }
        $k=0;
        foreach($reportUsers as $auser) {
            for($i=0; $i < $numMonths; $i++) {
                ${'userStats'.$k}['label'] = $auser->username;
                if($i > 0) {
                    ${'userStats'.$k}['data'][] = Session::select()->whereBetween('sessions.created_at', [Carbon::now()->startOfMonth()->subMonth($i)->startOfMonth(), Carbon::now()->startOfMonth()->subMonth($i)->endOfMonth()])->where('username','=',"$auser->username")->where('team_id', '=', $user->current_team_id)->count();
                } else {
                    ${'userStats'.$k}['data'][] = Session::select()->whereBetween('sessions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->where('username','=',"$auser->username")->where('team_id', '=', $user->current_team_id)->count();
                }
            }
            $dataset[] = ${'userStats'.$k};
            $k++;
        }
        return response()->json([
            'labels' => $monthLabels,
            'datasets'  => $dataset
        ]);
    }
}
