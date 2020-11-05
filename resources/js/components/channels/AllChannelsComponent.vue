<template>
  <div>
    <div>
      <v-navigation-drawer class="mt-12" permanent left fixed>
        <div class="row">
          <v-text-field
            class="dashboardSearch font-weight-medium"
            v-model="search"
            right
            label="hledejte ..."
            autofocus
          ></v-text-field>
        </div>
      </v-navigation-drawer>
      <v-navigation-drawer permanent class="mt_6" left fixed>
        <v-list v-for="channel in filteredChannels" v-bind:key="channel.id" nav dense>
          <v-list-item link v-bind:to="'/channel/' + channel.id">
            <v-list-item-title>{{channel.nazev}}</v-list-item-title>
          </v-list-item>
        </v-list>
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
      </v-navigation-drawer>
    </div>
    <!-- Vuejs router view -->
    <v-main class="rightFromSIdePanel">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </v-main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: "",
      channels: ""
    };
  },
  created() {
    axios
      .get("/api/channels/get")
      .then(response => (this.channels = response.data));
  },

  computed: {
    filteredChannels() {
      if (this.search) {
        return this.channels.filter(item => {
          return this.search
            .toLowerCase()
            .split(" ")
            .every(
              v =>
                item.nazev.toLowerCase().includes(v) ||
                item.ipKstb.toLowerCase().includes(v) ||
                item.dohledUrl.toLowerCase().includes(v) ||
                item.ca_modul_number.toLowerCase().includes(v) ||
                item.pathToReboot.toLowerCase().includes(v) ||
                item.tag.toLowerCase().includes(v)
            );
        });
      } else {
        return this.channels;
      }
    }
  },

  methods: {}
};
</script>


