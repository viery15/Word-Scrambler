<script>
    var app = new Vue({
        el: '#app',
        data: {
            api:{
                setProfile: "/game/profile"
            },
            name: "",
            email: ""
        },

        mounted(){

        },

        methods: {
            async start(){
                var profile = {
                    name: this.name,
                    email: this.email
                }

                if(this.name == "" || this.email == ""){
                    Swal.fire({
                        type: 'error',
                        title: 'Oopss',
                        text: "Username or Email is Empty!",
                        confirmButtonText: 'OK!',
                    });
                }
                else {
                    const result = await $.ajax({
                        url: this.api.setProfile,
                        type: 'POST',
                        data: profile,
                        dataType: "JSON"
                    });

                    if(result.status == "S"){
                        window.location.href = '/play';
                    }
                }
            }
        },
    });
</script>
