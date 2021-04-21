<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Exception;
use Illuminate\Support\Facades\File;

class WordController extends Controller
{
    public function getScrambleWords($type){
        $words = $this->getRandomWords($type, 1);
        $scramble_words = array();

        foreach ($words as $word) {
            $word_result['word_id'] = $word->id;
            do{
                $shuffle = str_shuffle($word->word);
                $word_result['word_scrambled'] = $shuffle;
            } while($word->word == $shuffle);
            array_push($scramble_words, $word_result);
        }

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
        // $input['type'] = "medium";

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
