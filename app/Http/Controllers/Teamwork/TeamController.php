<?php

namespace App\Http\Controllers\Teamwork;
use Auth;
use App\Models\User;
use App\Models\Team;
use JWTAuth;
use Illuminate\Support\Facades\Auth as Auth2;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;
use Validator;

class TeamController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        $team    = new Team();
        // // team attach alias
        //     $user->attachTeam($team, $pivotData); // First parameter can be a Team object, array, or id
        // $team = Team::create([
        //     "name" => "Internal team"
        // ]);
            // or eloquent's original technique
            // $request->user()->teams()->attach($team->id);
        
        return response()->json([
            'success'=>true,
            // 'user'=>$user
            // 'user'=>$user->teams
                'user'=>$request->user()
            ], 422);
       
        // view('teamwork.index')
        //     ->with('teams', auth()->user()->teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teamwork.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string',
        // ]);
        $rules = ['name'=>'required|string'];
         $validator = Validator::make($request->all(), $rules);
        // echo $validator;

        // create the user account
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
        $teamModel = config('teamwork.team_model');

        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        $team = $teamModel::create([
            'name' => $request->name,
            'owner_id' => $request->user()->getKey(),
        ]);
        // $request->user()->attachTeam($team); or this
        $user->attachTeam($team);
        // $user->teams()->attach($team->id);
        return response()->json([
            'success'=>true,
        'user_teams'=>$user->teams
    ], 422);
    }

    /**
     * Switch to the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function switchTeam($id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);
        try {
            auth()->user()->switchTeam($team);
        } catch (UserNotInTeamException $e) {
            abort(403);
        }

        return redirect(route('teams.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        return view('teamwork.edit')->withTeam($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        $team->name = $request->name;
        $team->save();

        return redirect(route('teams.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->delete();

        $userModel = config('teamwork.user_model');
        $userModel::where('current_team_id', $id)
                    ->update(['current_team_id' => null]);

        return redirect(route('teams.index'));
    }
}