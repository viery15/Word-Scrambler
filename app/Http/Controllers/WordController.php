<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WordController extends Controller
{

    public function index(){
        return view('Word.index');
    }

    public function create(Request $request){

        try{
            if(Word::create($request->word)){
                $res['status'] = "S";
                $res['msg'] = "Saved!";
            }
        } catch(Exception $e){
            $res['status'] = "E";
            $res['msg'] = $e->getMessage();
        }

        return $res;

    }

    public function delete($id){

        try{
            if(Word::destroy($id)){
                $res['status'] = "S";
                $res['msg'] = "Deleted!";
            }
        } catch(Exception $e){
            $res['status'] = "E";
            $res['msg'] = $e->getMessage();
        }

        return $res;

    }

    public function update(Request $request, $id){

        try{
            $word = Word::where('id', '=', $id)->first();
            if ($word !== null) {
                $word->fill($request->word)->save();

                $res['status'] = "S";
                $res['msg'] = "Updated!";
            }
        } catch(Exception $e){
            $res['status'] = "E";
            $res['msg'] = $e->getMessage();
        }

        return $res;

    }

    public function getScrambleWords($type){
        $word = $this->getRandomWords($type, 1);
        $scramble_words = array();

        $word_result['word_id'] = $word[0]->id;
        $word_result['word'] = $word[0]->word;
        do{
            $shuffle = str_shuffle($word[0]->word);
            $word_result['word_scrambled'] = $shuffle;
        } while($word[0]->word == $shuffle);
        array_push($scramble_words, $word_result);

        $res['status'] = "S";
        $res['result'] = $scramble_words;

        return response()->json($res);

    }

    public function getWords(){
        $words = Word::get();

        $res['status'] = "S";
        $res['result'] = $words;

        return response()->json($words);
    }

    public function getRandomWords($type, $limit){
        $words = Word::inRandomOrder()->where('type', '=', $type)->limit($limit)->get();

        return $words;

    }

    public function export(){
        $file = File::get(storage_path('words/common.txt'));

        $save = [];
        foreach (explode("\n", $file) as $line){
            $word = Word::where('word', '=', $line)->first();

            if($word === null){
                if($line != ""){
                    $input['word'] = $line;
                    if(strlen($line) >= 1 && strlen($line) <= 4){
                        $input['type'] = "short";
                    }

                    else if(strlen($line) >= 5 && strlen($line) <= 8){
                        $input['type'] = "medium";
                    }

                    else if(strlen($line) >= 9){
                        $input['type'] = "long";
                    }

                    Word::create($input);
                    array_push($save, $input);
                }
            }
        }

        return response()->json($save);

    }
}
