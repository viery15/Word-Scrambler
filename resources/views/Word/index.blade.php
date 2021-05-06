@extends('Admin.layout')

@section('title')
    Words
@endsection

@section('content')
    <h3>Words</h3>

    <button v-on:click="openModalWord()" class="btn btn-sm btn-info" style="margin-bottom: 3%"><span class="fa fa-plus"></span> Insert Word</button>

    <table class="table table-bordered" id="table-words">
        <thead>
            <tr>
                <th>No</th>
                <th>Word</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(word, index) in dataWords" :key="index">
                <td class="text-center">@{{ index + 1 }}</td>
                <td>@{{ word.word }}</td>
                <td>@{{ word.type }}</td>
                <td class="text-center">
                    <button v-on:click="editWord(word)" class="btn btn-sm btn-warning"><span class="fa fa-pen"></span> Edit</button>
                    <button v-on:click="deleteWord(word.id)" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span> Delete</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="modal-word" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Word</label>
                        <input v-model="word.word" type="text" class="form-control" placeholder="Word">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control" v-model="word.type">
                            <option value="short">Short</option>
                            <option value="medium">Medium</option>
                            <option value="long">Long</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-on:click="saveWord()" type="button" class="btn btn-primary"><span class="fa fa-check"></span> Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                words: '/api/words'
            },
            dataWords: "",
            word:{
                word: "",
                type: ""
            }
        },

        mounted(){
            this.getWords();
        },

        methods: {
            async getWords(){
                const res = await $.ajax({
                    url: this.api.words,
                    type: "GET"
                });

                this.dataWords = res;

                setTimeout(function () {
                    $("#table-words").DataTable();
                }, 100);
            },

            openModalWord(){
                $("#modal-word").modal("show");
            },

            async saveWord(){

                if(this.word.id != undefined){
                    this.updateWord();
                }
                else {
                    var data = {
                        word: this.word
                    }

                    const res = await $.ajax({
                        url: this.api.words,
                        type: "POST",
                        dataType: "JSON",
                        data: data
                    });

                    if(res.status == "S"){
                        alert(res.msg);
                        location.reload();
                    }
                }
            },

            async deleteWord(id){
                const res = await $.ajax({
                    url: this.api.words + '/' + id ,
                    type: "DELETE"
                });

                if(res.status == "S"){
                    alert(res.msg);
                    location.reload();
                }
            },

            editWord(word){
                this.word = word;

                $("#modal-word").modal("show");
            },

            async updateWord(){
                var data = {
                    word: this.word
                }

                const res = await $.ajax({
                    url: this.api.words + '/' + this.word.id ,
                    type: "PUT",
                    dataType: "JSON",
                    data: data
                });

                if(res.status == "S"){
                    alert(res.msg);
                    location.reload();
                }
            }
        },
    });
</script>
@endsection
