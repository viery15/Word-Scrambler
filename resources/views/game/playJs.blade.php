<script>
    var app = new Vue({
        el: '#app',
        data: {
            api: {
                scrambleWord: "/word/scrambleWord",
                check: "/game/scoring",
                submit: "/game/submit"
            },
            dataScrambleWord: [],
            display:{
                word: {},
                currentIndex: "",
                charOption: [],
                userAnswer: ""
            },
            pickedChar: [],
            game_detail: [],
            game:{
                name: "",
                email: "",
                type: "",
                stage: 1
            },
            heart: 3,
            score: 0
        },

        mounted(){
            this.game.name = "{{ Session::get('name') }}";
            this.game.email = "{{ Session::get('email') }}";
        },

        methods: {

            pickChar(index){
                this.pickedChar.push(this.display.charOption[index].char);
                this.display.charOption[index].isPicked = true;

                this.display.userAnswer = this.pickedChar.join(" ");
                this.$forceUpdate();
            },

            reset(){
                this.pickedChar = [];
                this.display.charOption.forEach((char, index) => {
                    char.isPicked = false;
                });
                this.display.userAnswer = "";
            },

            async check(){
                var data = {
                    word_id: this.display.word.word_id,
                    user_answer: this.pickedChar.join("")
                }

                const result = await $.ajax({
                    url: this.api.check,
                    type: "POST",
                    data: data
                });

                if(result.result.user_score == 0){
                    this.heart--;
                    var msg = "";

                    if(this.heart == 0) {
                        this.gameOver();
                    }
                    else{
                        if(this.heart == 1){
                            msg = "One More Chance";
                        }
                        else if(this.heart > 1) {
                            msg = this.heart + " Hearts Left";
                        }

                        Swal.fire({
                            type: 'error',
                            title: 'You Got it Wrong',
                            text: msg
                        });
                    }
                }
                else {
                    this.game.stage++;
                    this.score += result.result.user_score;

                    this.game_detail.push({
                        word_id: this.display.word.word_id,
                        word_scrambled: this.display.word.word_scrambled,
                        user_answer: this.pickedChar.join(""),
                        user_score: result.result.user_score
                    });

                    this.reset();

                    this.getScrambledWord();
                    this.$forceUpdate();
                }


            },

            async selectDifficulty(type){
                this.game.type = type;

                Swal.fire({
                    title: 'Please Wait...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });

                this.getScrambledWord();

                $("#card-difficulty").hide();
                $("#card-main").show();

                Swal.close();

            },

            async getScrambledWord(){
                this.display.charOption = [];
                this.display.user_answer = "";

                const result = await $.ajax({
                    url: this.api.scrambleWord + "/" + this.game.type,
                    type: "GET"
                });

                this.dataScrambleWord = result.result;
                this.display.word = this.dataScrambleWord[0];
                this.display.currentIndex = 0;

                this.display.word.word_scrambled.split("").forEach((char) => {
                    this.display.charOption.push({
                        char: char,
                        isPicked: false
                    });
                });
            },

            async gameOver(){
                this.game.stage--;
                Swal.fire({
                    type: 'error',
                    title: 'You Got it Wrong',
                    text: "GAME OVER!",
                    confirmButtonText: 'Ok',
                    preConfirm: async () => {
                        var data = {
                            game: this.game,
                            game_detail: this.game_detail
                        }

                        const result = await $.ajax({
                            url: this.api.submit,
                            type: "POST",
                            data: data
                        });

                        if(result.status == "S"){
                            window.location.href = '/game/result/' + result.result.game.id;
                        }
                    }
                });
            }
        },
    });
</script>
