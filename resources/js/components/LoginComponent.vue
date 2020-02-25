<template>
  <div>
    <!-- <div id="alertDiv">
      <div id="alertDiv">
        <template>
          <div class="white--text font-weight-normal">
            <transition v-if="loginResponse === true" name="fade" mode="out-in">
              <v-alert type="success">Přihlášen</v-alert>
            </transition>
            <transition v-if="loginResponse === false" name="fade" mode="out-in">
              <v-alert type="warning">Odhlášen</v-alert>
            </transition>
            <transition v-if="chybaOdpovediServeru === true" name="fade" mode="out-in">
              <v-alert type="error">Nepodařilo se ověřit token, zkuste refresh stránky.</v-alert>
            </transition>
          </div>
        </template>
      </div>
    </div>-->
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <v-card width="450" class="mx-auto mt-12">
      <v-card-title>
        <h1 class="display-1">IPTV Dokumentace</h1>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-form @submit.prevent="login()">
          <v-text-field
            label="E-mail"
            type="email"
            v-model="email"
            prepend-icon="mdi-account-circle"
          />
          <v-text-field type="password" label="Heslo" v-model="password" prepend-icon="mdi-lock" />
          <v-divider></v-divider>
          <v-btn type="submit" color="success">Přihlásit se</v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
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
    axios.get("/api/getUser").then(response => (this.userData = response.data));
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

