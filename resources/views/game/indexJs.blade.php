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

                console.log(profile);

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
        },
    });
</script>
