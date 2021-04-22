@extends('Admin.layout')

@section('title')
    Game History
@endsection

@section('content')
    <h3>Game History</h3>

    <table class="table table-bordered" id="table-history">
        <thead>
            <tr>
                <th>No</th>
                <th>Email</th>
                <th>Name</th>
                <th>Word Difficulty</th>
                <th>Score</th>
                <th>Stage</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(game, index) in game" :key="index">
                <td class="text-center">@{{ index + 1 }}</td>
                <td>@{{ game.email }}</td>
                <td>@{{ game.name }}</td>
                <td>@{{ game.type }}</td>
                <td>@{{ game.user_score }}</td>
                <td>@{{ game.stage }}</td>
            </tr>
        </tbody>
    </table>
@endsection

@section('js')
<script>
    var app = new Vue({
        el: '#app',
        data: {
            api:{
                history: '/api/history/'
            },
            game: "",
        },

        mounted(){
            this.getHistory();
        },

        methods: {
            async getHistory(){
                const res = await $.ajax({
                    url: this.api.history,
                    type: "GET"
                });

                this.game = res.result.game;

                setTimeout(function () {
                    $("#table-history").DataTable();
                }, 100);
            }
        },
    });
</script>
@endsection
