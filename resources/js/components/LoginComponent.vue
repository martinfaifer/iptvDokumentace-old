<template>
    <v-main>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="12" sm="8" md="4">
                    <v-card class="elevation-3">
                        <v-card-text>
                            <h1 class="text-center mb-4 mt-4">
                                <v-icon color="error" large>
                                    mdi-television-play
                                </v-icon>
                                <strong>IPTV Dokumentace</strong>
                            </h1>
                            <v-form>
                                <v-text-field
                                    label="E-mail"
                                    type="email"
                                    v-model="email"
                                    prepend-icon="mdi-account-circle"
                                ></v-text-field>

                                <v-text-field
                                    type="password"
                                    label="Heslo"
                                    v-model="password"
                                    prepend-icon="mdi-lock"
                                ></v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                @click="login()"
                                :disabled="password === null || email === null"
                                type="submit"
                                color="success"
                                >Přihlášení</v-btn
                            >
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</template>
<script>
export default {
    data() {
        return {
            userData: false,
            email: "",
            password: "",
            chybaOdpovediServeru: "",
            loginResponse: "" // odpoved od serveru zda je uzivatel uspesne overen
        };
    },

    created() {
        axios
            .get("/api/getUser")
            .then(response => (this.userData = response.data));
    },

    methods: {
        login() {
            let currentObj = this;
            axios
                .post("/api/user/login", {
                    email: this.email,
                    password: this.password
                })
                .then(function(response) {
                    currentObj.loginResponse = response.data;
                    currentObj.$router.push("/");
                })
                .catch(function(error) {
                    currentObj.chybaOdpovediServeru = true;
                    currentObj.loginResponse = "";
                    currentObj.email = "";
                    currentObj.password = "";
                });
        }
    },

    watch: {
        loginResponse: function() {
            setTimeout(() => (this.loginResponse = null), 5000);
        },
        chybaOdpovediServeru: function() {
            setTimeout(() => (this.loginResponse = null), 5000);
        },

        // Presmerování na mozaiku, pokud jiz je user prihlasen
        userData: function() {
            if (this.userData != false) {
                this.$router.push("/");
            }
        }
    }
};
</script>
