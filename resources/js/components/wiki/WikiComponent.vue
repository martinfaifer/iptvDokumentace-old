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
    <v-navigation-drawer class="mt-12" permanent left fixed>
      <v-treeview dense activatable transition :items="items">
        <template slot="label" slot-scope="props">
          <router-link
            class="font-weight-regular black--text caption"
            link
            v-bind:to="'/wiki/' +props.item.id"
          >{{ props.item.name }}</router-link>
        </template>
      </v-treeview>
      <br />
      <br />
      <br />
      <br />
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
      items: [],
      detail: []
    };
  },
  created() {
    axios.get("/api/tree").then(response => (this.items = response.data));
  }
};
</script>



