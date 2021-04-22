@extends('Layouts.index')

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
<div class="row" style="margin-top: 12%">
    <div class="col-md-12 mx-auto my-auto">

        <div class="card" id="card-difficulty">
            <div class="card-header">
                <span>Select Word Difficulty</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 d-flex justify-content-center">
                        <div v-on:click="selectDifficulty('short')" class="box d-flex justify-content-center" style="cursor: pointer; padding-top: 10%; padding-bottom: 3%; background-color: #ecb35d;
                        color: white;">
                            <span style="font-family: cursive;">Short ( 1-4 characters )</span>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <div v-on:click="selectDifficulty('medium')" class="box d-flex justify-content-center" style="cursor: pointer; padding-top: 10%; padding-bottom: 3%; background-color: cornflowerblue; color: white">
                            <span style="font-family: cursive;">Medium ( 5-8 characters )</span>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <div v-on:click="selectDifficulty('long')" class="box d-flex justify-content-center" style="cursor: pointer; padding-top: 10%; padding-bottom: 3%; background-color: crimson; color: white;">
                            <span style="font-family: cursive;">Long ( 9+ characters )</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>

        <div class="card" id="card-main" style="display: none">
            <div class="card-header" style="background-color: #7fb2ff;">
                <div class="row">
                    <div class="col">
                        <strong>Stage @{{ game.stage }}</strong>
                    </div>
                    <div class="col">
                       <strong>Lives :</strong> <span v-for="(heart, index) in heart" style="color: red; margin-right: 1%" class="fa fa-heart"></span>
                    </div>
                    <div class="col">
                        <strong>Hints :</strong> <span v-for="(hint, index) in hint" style="color: gold; margin-right: 1%" class="fa fa-key"></span>
                    </div>
                    <div class="col">
                        <strong> Score : @{{ score }} </strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row d-flex justify-content-center">
                            <div v-for="(char, index) in display.charOption" :key="index" class="col-md-4" style="margin-top: 2%">
                                <button v-if="char.isPicked == false" v-on:click="pickChar(index)" style="width: 100px; height: 70px" class="btn btn-sm btn-info text-uppercase">@{{ char.char }}</button>
                                <button v-if="char.isPicked == true && char.isHint == false" style="width: 100px; height: 70px; background-color: #1757b8; color:white; cursor:not-allowed" class="btn btn-sm text-uppercase">@{{ char.char }}</button>
                                <button v-if="char.isPicked == true && char.isHint == true" style="width: 100px; height: 70px; background-color: hsl(130, 78%, 41%); color:white; cursor:not-allowed" class="btn btn-sm text-uppercase">@{{ char.char }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 my-auto">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                Your Guess :
                            </div>
                        </div>
                        <div class="row" style="margin-top: 2%">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div v-for="(char, index) in pickedChar" :key="index" style="margin-left: 2%; font-family: sans-serif; border-bottom: 1px solid black; min-width: 30px; min-height: 50px">
                                    <div class="text-uppercase">
                                        <h1 v-if="char != ''">@{{char}}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5%">
                            <div class="col-md-12 mx-auto d-flex justify-content-center">
                                <button v-on:click="reset()" class="btn btn-sm btn-danger"><span class="fa fa-sync"></span> Reset</button>
                                <button v-if="hint > 0" style="margin-left: 2%" v-on:click="setHint()" class="btn btn-sm btn-info"><span class="fa fa-key"></span> Hint</button>
                                <button style="margin-left: 2%" v-if="pickedChar.length == display.charOption.length" v-on:click="check()" style="float:right" class="btn btn-sm btn-success">Submit <span class="fa fa-arrow-right"></span></button>
                                <button style="margin-left: 2%" v-else disabled style="float:right; cursor: not-allowed" class="btn btn-sm btn-success">Submit <span class="fa fa-arrow-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('Game.playJs')
@endsection
