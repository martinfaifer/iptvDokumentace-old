<template>
  <div>
    <br />
    <br />
    <br />
    <v-card elevation="0">
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Hledání"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        class="font-weight-regular"
        :headers="headers"
        :items="channels"
        :search="search"
      ></v-data-table>
    </v-card>
  </div>
</template>
<script>
export default {
  data() {
    return {
      search: "",
      status: [],
      channels: [],
      userData: [],
      headers: [
        {
          text: "Název kanálu",
          value: "nazev"
        },
        { text: "IP k STB", value: "ipKstb" }
      ]
    };
  },
  watch: {
    status: function() {
      setTimeout(() => (this.status = false), 5000);
    },
    $route(to, from) {
      let currentObj = this;
      axios
        .post("/api/iptvpackage", {
          packageName: this.$route.params.nazev
        })
        .then(function(response) {
          currentObj.channels = response.data;
        })
        .catch(function(error) {
          currentObj.status = "error";
        });
    }
  },
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
    let currentObj = this;
    axios
      .post("/api/iptvpackage", {
        packageName: this.$route.params.nazev
      })
      .then(function(response) {
        currentObj.channels = response.data;
      })
      .catch(function(error) {
        currentObj.status = "error";
      });
  }
};
</script>


