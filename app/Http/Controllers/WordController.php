<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Exception;
use Illuminate\Support\Facades\File;

class WordController extends Controller
{
    public function getScrambleWords($type){
        try{
            $words = $this->getRandomWords($type, 10);
            $scramble_words = array();

            foreach ($words as $word) {
                $word_result['word_id'] = $word->id;
                $word_result['word_scrambled'] = str_shuffle($word->word);
                array_push($scramble_words, $word_result);
            }

            $res['status'] = "S";
            $res['result'] = $scramble_words;

        } catch(Exception $e){
            $res['status'] = "E";
            $res['message'] = $e->getMessage();
        }

        return response()->json($res);

    }

    public function getWords(){
        try {
            $words = Word::get();

            $res['status'] = "S";
            $res['result'] = $words;

            return response()->json($words);

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRandomWords($type, $limit){
        try{
            $words = Word::inRandomOrder()->where('type', '=', $type)->limit($limit)->get();

            return $words;

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

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
