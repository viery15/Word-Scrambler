@extends('layouts.index')

@section('title')
    Game Result
@endsection

@section('content')
<div class="card" style="margin-top: 3%">
    <div class="card-header">
        <span>Game Result</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <table>
                    <tr>
                        <td>Name</td>
                        <td> : </td>
                        <td class="text-nowrap" style="padding-left: 3%">@{{ game.name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td> : </td>
                        <td class="text-nowrap" style="padding-left: 3%">@{{ game.email }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td> : </td>
                        <td style="padding-left: 3%">@{{ game.date }}</td>
                    </tr>
                    <tr>
                        <td>Difficulty Words</td>
                        <td> : </td>
                        <td style="padding-left: 3%">@{{ game.type }}</td>
                    </tr>
                    <tr>
                        <td>Stage</td>
                        <td> : </td>
                        <td style="padding-left: 3%">@{{ game.stage }}</td>
                    </tr>
                    <tr>
                        <td>Score</td>
                        <td> : </td>
                        <td style="padding-left: 3%"><strong>@{{ game.user_score }}</strong></td>
                    </tr>
                </table>
                <br>
                <a href="/" class="btn btn-sm btn-success"><span class="fa fa-sync"></span> Play Again</a>
            </div>
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Scrambled Word</th>
                                <th>User Answer</th>
                                <th>User Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detail, index) in game_detail" :key="index">
                                <td>@{{ detail.seq }}</td>
                                <td>@{{ detail.word_scrambled }}</td>
                                <td>@{{ detail.user_answer }}</td>
                                <td><strong>@{{ detail.user_score }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="card-footer">
    </div>
</div>
@endsection

@section('js')
<script>
    var app = new Vue({
        el: '#app',
        data: {
            api:{
                result: '/api/result/'
            },
            game: "",
            game_detail: "",
            id: ""
        },

        mounted(){
            this.id = "{{ $id }}";

            this.getResult();
        },

        methods: {
            async getResult(){
                const res = await $.ajax({
                    url: this.api.result + this.id,
                    type: "GET"
                });

                this.game = res.result.game;
                this.game.date = new Date(this.game.created_at).toString("dd MMMM yyyy HH:mm")

                this.game_detail = res.result.game_detail;
            }
        },
    });
</script>

@endsection
