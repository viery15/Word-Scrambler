@extends('Layouts.index')

@section('title')
Word Scramble Game
@endsection

@section('content')
<div class="row" style="margin-top: 10%">
    <div class="col-md-6 mx-auto my-auto">
        <div class="card">
            <div class="card-header">
                <h5>Login to Admin Page</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label>Username</label>
                        <input name="username" v-model="username" type="text" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" v-model="password" type="text" class="form-control" placeholder="Password">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button v-on:click="login()" style="float:right" class="btn btn-sm btn-success"><span class="fa fa-check"></span> Login</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var app = new Vue({
        el: '#app',
        data: {
            api:{
                login: "/login"
            },
            username: "",
            password: ""
        },

        mounted(){

        },

        methods: {
            async login(){
                var user = {
                    username: this.username,
                    password: this.password
                }

                const result = await $.ajax({
                    url: this.api.login,
                    type: 'POST',
                    data: user,
                    dataType: "JSON"
                });

                if(result.status == "S"){
                    window.location.href = '/history';
                }
            }
        },
    });
</script>

@endsection
