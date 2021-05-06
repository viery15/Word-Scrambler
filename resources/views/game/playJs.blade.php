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
            hint: 3,
            score: 0,
            hintChar: [],
            indexHintPicked: []
        },

        mounted(){
            this.game.name = "{{ Session::get('name') }}";
            this.game.email = "{{ Session::get('email') }}";
        },

        methods: {

            pickChar(index){

                var find = true;
                this.pickedChar.forEach((char, i) => {
                    if(char == "" && find == true){
                        this.pickedChar[i] = this.display.charOption[index].char;
                        find = false;
                    }
                });

                this.display.charOption[index].isPicked = true;
                this.display.userAnswer = this.pickedChar.join(" ");
                this.$forceUpdate();
            },

            reset(){
                this.display.charOption.forEach((char, index) => {
                    if(char.isHint != true){
                        char.isPicked = false;
                    }
                });
                this.display.userAnswer = "";

                this.pickedChar.forEach((picked, i) => {
                    var reset = true;
                    this.indexHintPicked.forEach((ind) => {
                        if(i == ind){
                            reset = false;
                        }
                    });
                    if(reset === true){
                        this.pickedChar[i] = "";
                    }
                })
            },

            async check(){
                Swal.fire({
                    title: 'Please Wait...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });
                this.indexHintPicked = [];
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
                    Swal.close();
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
                    Swal.close();

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
                this.pickedChar = [];
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
                        isPicked: false,
                        isHint: false
                    });
                    this.pickedChar.push("");
                    this.hintChar.push("");
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
            },

            setHint(){
                this.reset();
                for (let i = 0; i < this.pickedChar.length; i++) {
                    if(this.pickedChar[i] == ""){
                        this.pickedChar[i] = this.display.word.word.charAt(i);
                        this.indexHintPicked.push(i);

                        for (let j = 0; j < this.display.charOption.length; j++) {
                            if(this.display.charOption[j].char == this.display.word.word.charAt(i) && this.display.charOption[j].isHint != true){
                                this.display.charOption[j].isPicked = true;
                                this.display.charOption[j].isHint = true;
                                break;
                            }

                        }
                        this.hint--;
                        break;
                    }

                }

                this.$forceUpdate();
            },

            getRandomInt(min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
        },
    });
</script>
