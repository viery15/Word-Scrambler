<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameDetail;
use App\Models\Word;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{

    public function index(){
        return view('Game.index');
    }

    public function play(){
        return view('Game.play');
    }

    public function result($id){
        return view('Game.result', compact('id'));
    }

    public function getResult($id){
        $game = Game::find($id);
        $game_detail = GameDetail::where('game_id', '=', $game['id'])->get();

        $res['status'] = "S";
        $res['result']['game'] = $game;
        $res['result']['game_detail'] = $game_detail;
        return response()->json($res);
    }

    public function history(){
        return view('Admin.history');
    }

    public function getHistory(){
        $game = Game::orderBy('user_score', 'DESC')->get();

        $res['status'] = "S";
        $res['result']['game'] = $game;
        return response()->json($res);
    }

    public function setProfile(Request $request){
        Session::put('name', $request->name);
        Session::put('email', $request->email);

        $res['status'] = "S";
        $res['message'] = "Profile saved";

        return response()->json($res);
    }

    public function submit(Request $request){
        $game = $request->game;
        $game_detail = $request->game_detail;
        $game['user_score'] = 0;
        $game_detail_res = [];

        if($game_detail != ""){
            foreach ($game_detail as $detail) {
                $game['user_score'] += $detail['user_score'];
            }
        }

        $game_res = Game::create($game);

        if($game_detail != ""){
            for ($i=0; $i < count($game_detail); $i++) {
                $game_detail[$i]['game_id'] = $game_res['id'];
                $game_detail[$i]['seq'] = $i + 1;
                $detail_res = GameDetail::create($game_detail[$i]);
                array_push($game_detail_res, $detail_res);
            }
        }

        $res['status'] = "S";
        $res['result']['game'] = $game_res;
        $res['result']['game_detail'] = $game_detail_res;

        return response()->json($res);

    }

    public function scoring(Request $request){

        $word = Word::where('id', '=', $request->word_id)->first();
        $user_answer = Word::where('word', '=', $request->user_answer)->first();

        if($user_answer !== null){
            $score = strlen($request->user_answer);
        }
        else {
            $score = 0;
        }

        $max_score = strlen($word['word']);

        $res['status'] = "S";
        $res['result']['user_score'] = $score;
        $res['result']['max_score'] = $max_score;

        return response()->json($res);

    }


}
