@extends('layouts.index')

@section('title')
Word Scramble Game
@endsection

@section('style')
    <style>
        .box {
            border: 1px solid black;
            min-height: 100px;
            border-radius: 10px;
            width: 100%;
        }
    </style>
@endsection

@section('content')
<div class="row" style="margin-top: 3%">
    <div class="col-md-12 mx-auto my-auto">

        <div class="card" id="card-difficulty">
            <div class="card-header">
                <span>Select Word Difficulty</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 d-flex justify-content-center">
                        <div v-on:click="selectDifficulty('short')" class="box d-flex justify-content-center" style="cursor: pointer; padding-top: 5%; padding-bottom: 3%; background-color: #ecb35d;
                        color: white;">
                            <span>Short ( 1-4 characters )</span>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <div v-on:click="selectDifficulty('medium')" class="box d-flex justify-content-center" style="cursor: pointer; padding-top: 5%; padding-bottom: 3%; background-color: cornflowerblue; color: white">
                            <span>Medium ( 5-8 characters )</span>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <div v-on:click="selectDifficulty('long')" class="box d-flex justify-content-center" style="cursor: pointer; padding-top: 5%; padding-bottom: 3%; background-color: crimson; color: white;">
                            <span>Long ( 9+ characters )</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>

        <div class="card" id="card-main" style="display: none">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <strong>Stage @{{ game.stage }}</strong>
                    </div>
                    <div class="col">
                        Lives : <span v-for="(heart, index) in heart" style="color: red; margin-right: 1%" class="fa fa-heart"></span>
                    </div>
                    <div class="col">
                        Score : @{{ score }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row d-flex justify-content-center">
                            <div v-for="(char, index) in display.charOption" :key="index" class="col-md-4" style="margin-top: 2%">
                                <button v-if="char.isPicked == false" v-on:click="pickChar(index)" style="width: 100px; height: 70px" class="btn btn-sm btn-info text-uppercase">@{{ char.char }}</button>
                                <button v-else style="width: 100px; height: 70px; background-color: #1757b8; color:white; cursor:not-allowed" class="btn btn-sm text-uppercase">@{{ char.char }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 my-auto">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                You Guess:
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <h1 class="text-uppercase text-bold" style="font-family: sans-serif;">
                                    @{{ display.userAnswer }}
                                </h1>
                            </div>
                        </div>
                        <div v-if="display.userAnswer != ''" class="row" style="margin-top: 1%">
                            <div class="col-md-12 mx-auto d-flex justify-content-center">
                                <button v-on:click="reset()" class="btn btn-sm btn-danger"><span class="fa fa-sync"></span> Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button v-if="pickedChar.length == display.charOption.length" v-on:click="check()" style="float:right" class="btn btn-sm btn-success">Submit <span class="fa fa-arrow-right"></span></button>
                <button v-else disabled style="float:right; cursor: not-allowed" class="btn btn-sm btn-success">Submit <span class="fa fa-arrow-right"></span></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('Game.playJs')
@endsection
