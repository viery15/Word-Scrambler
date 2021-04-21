<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameDetail;
use App\Models\Word;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{

    public function index(){
        return view('game.index');
    }

    public function play(){
        return view('game.play');
    }

    public function result($id){
        return view('game.result', compact('id'));
    }

    public function getResult($id){
        $game = Game::find($id);
        $game_detail = DB::select("
            select a.seq, a.word_scrambled, a.user_answer, a.user_score, a.max_score, b.word
            FROM word_scrambler.game_detail a
            JOIN word_scrambler.words b ON a.word_id = b.id
            WHERE game_id = " . $game['id'] . "
            ORDER BY a.seq ASC;
        ");

        $res['status'] = "S";
        $res['result']['game'] = $game;
        $res['result']['game_detail'] = $game_detail;
        return response()->json($res);
    }

    public function setProfile(Request $request){
        try{
            Session::put('name', $request->name);
            Session::put('email', $request->email);

            $res['status'] = "S";
            $res['message'] = "Profile saved";

        } catch(Exception $e){
            $res['status'] = "E";
            $res['message'] = $e->getMessage();
        }

        return response()->json($res);
    }

    public function scoring(Request $request){
        $game_detail = $request->game_detail;

        $total_user_score = 0;
        $total_max_score = 0;

        try{

            for ($i=0; $i < count($game_detail); $i++) {
                $word = Word::where('id', '=', $game_detail[$i]['word_id'])->first();
                $game_detail[$i]['max_score'] = strlen($word['word']);

                $user_answer = Word::where('word', '=', $game_detail[$i]['user_answer'])->first();
                if($user_answer !== null){
                    $total_user_score += strlen($game_detail[$i]['user_answer']);
                    $game_detail[$i]['user_score'] = strlen($game_detail[$i]['user_answer']);

                }
                else{
                    $game_detail[$i]['user_score'] = 0;
                }

                $total_max_score += $game_detail[$i]['max_score'];
            }

            $percentage_score = round(($total_user_score / $total_max_score) * 100);

            $game = $request->game;
            $game['user_score'] = $total_user_score;
            $game['max_score'] = $total_max_score;;
            $game['percentage_score'] = $percentage_score;

            $game_res = Game::create($game);
            $game_detail_res = [];

            for ($i=0; $i < count($game_detail); $i++) {
                $game_detail[$i]['game_id'] = $game_res['id'];
                $game_detail[$i]['seq'] = $i + 1;
                $detail_res = GameDetail::create($game_detail[$i]);
                array_push($game_detail_res, $detail_res);
            }

            $res['status'] = "S";
            $res['result']['game'] = $game_res;
            $res['result']['game_detail'] = $game_detail_res;

        } catch(Exception $e){
            throw $e;
        }

        return response()->json($res);
    }

}
