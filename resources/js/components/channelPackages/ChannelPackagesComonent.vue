<template>
  <div>
    <!-- alert -->
    <div id="alertDiv">
      <div id="alertDiv">
        <template>
          <div class="white--text font-weight-normal">
            <transition name="fade" mode="out-in">
              <v-alert
                v-if="status.isAlert === 'isAlert'"
                :type="status.stat"
                class="font-weight-regular"
              >{{status.msg}}</v-alert>
            </transition>
          </div>
        </template>
      </div>
    </div>
    <!-- end alert -->
    <v-navigation-drawer v-if="userData.role === '1'" class="mt-12" permanent left fixed>
      <v-list v-for="menuData in menuDatas" v-bind:key="menuData.id" nav dense>
        <v-list-item link v-bind:to="'/channelPackages/' + menuData.nazev">
          <v-list-item-title>{{menuData.nazev}}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <!-- Vuejs router view -->
    <v-content class="rightFromSidePanelSettings">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </v-content>
  </div>
</template>

<script>
export default {
  data() {
    return {
      status: "",
      search: "",
      userData: "",
      menuDatas: []
    };
  },

  methods: {},
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
    axios
      .get("/api/iptvpackage/getAll")
      .then(response => (this.menuDatas = response.data));
  },
  watch: {
    userData: function() {
      if (this.userData.role === "3") {
        this.status = {
          isAlert: "isAlert",
          stat: "error",
          msg: "Nemáte oprávnění tady být"
        };
        setTimeout(
          function() {
            this.$router.push("/");
          }.bind(this),
          2000
        );
      }
    }
  }
};
</script>


