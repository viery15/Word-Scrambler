<script>
    var app = new Vue({
        el: '#app',
        data: {
            api: {
                scrambleWord: "/word/scrambleWord",
                submit: "/game/scoring"
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
                type: ""
            }
        },

        mounted(){
            this.game.name = "{{ Session::get('name') }}";
            this.game.email = "{{ Session::get('email') }}";
        },

        methods: {
            async getScrambleWord(type){

            },

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

            next(){
                Swal.fire({
                    title: 'Confirmation',
                    text: "Are you sure? you can't go back again.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                    }).then((result) => {
                    if (result.value) {

                        this.game_detail.push({
                            word_id: this.display.word.word_id,
                            word_scrambled: this.display.word.word_scrambled,
                            user_answer: this.pickedChar.join("")
                        });

                        this.display.charOption = [];
                        this.display.currentIndex++;
                        this.display.word = this.dataScrambleWord[this.display.currentIndex];
                        this.display.word.word_scrambled.split("").forEach((char) => {
                            this.display.charOption.push({
                                char: char,
                                isPicked: false
                            });
                        })
                        this.reset();

                        this.$forceUpdate();
                    }
                });

            },

            async selectDifficulty(type){
                Swal.fire({
                    title: 'Please Wait...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });

                this.display.charOption = [];

                const result = await $.ajax({
                    url: this.api.scrambleWord + "/" + type,
                    type: "GET"
                });

                this.game.type = type;

                this.dataScrambleWord = result.result;
                this.display.word = this.dataScrambleWord[0];
                this.display.currentIndex = 0;

                this.display.word.word_scrambled.split("").forEach((char) => {
                    this.display.charOption.push({
                        char: char,
                        isPicked: false
                    });
                });

                $("#card-difficulty").hide();
                $("#card-main").show();

                Swal.close();

            },

            async submit(){

                this.game_detail.push({
                    word_id: this.display.word.word_id,
                    word_scrambled: this.display.word.word_scrambled,
                    user_answer: this.pickedChar.join("")
                });


                Swal.fire({
                    title: 'Please Wait...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                });

                var data = {
                    game: this.game,
                    game_detail: this.game_detail
                }

                const result = await $.ajax({
                    url: this.api.submit,
                    type: "POST",
                    data: data
                });

                Swal.close();

                window.location.href = '/game/result/' + result.result.game.id;
            }
        },
    });
</script>
