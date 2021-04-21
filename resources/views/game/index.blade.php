@extends('layouts.index')

@section('title')
Word Scramble Game
@endsection

@section('content')
<div class="row" style="margin-top: 10%">
    <div class="col-md-6 mx-auto my-auto">
        <div class="card">
            <div class="card-header">
                <h5>Welcome to Word Scramble Game</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" v-model="name" type="text" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" v-model="email" type="text" class="form-control" placeholder="Email">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button v-on:click="start()" style="float:right" class="btn btn-sm btn-success"><span class="fa fa-check"></span> Start</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('Game.indexJs')
@endsection
