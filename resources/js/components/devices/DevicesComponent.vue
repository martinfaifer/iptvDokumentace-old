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
        <v-list v-for="device in filteredDevices" v-bind:key="device.id" nav dense>
          <v-list-item link v-bind:to="'/device/' + device.id">
            <v-list-item-title>{{device.name}}</v-list-item-title>
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
      devices: ""
    };
  },
  created() {
    axios
      .get("/api/devices/get")
      .then(response => (this.devices = response.data));
  },
  computed: {
    filteredDevices() {
      if (this.search) {
        return this.devices.filter(item => {
          if (item.FTE_satelit === null) {
            item.FTE_satelit = "false";
          }
          if (item.RF1_satelit === null) {
            item.RF1_satelit = "false";
          }
          if (item.RF2_satelit === null) {
            item.RF2_satelit = "false";
          }
          if (item.RF3_satelit === null) {
            item.RF3_satelit = "false";
          }
          if (item.RF4_satelit === null) {
            item.RF4_satelit = "false";
          }
          if (item.FTE_freq === null) {
            item.FTE_freq = "false";
          }
          if (item.RF1_freq === null) {
            item.RF1_freq = "false";
          }
          if (item.RF2_freq == null) {
            item.RF2_freq = "false";
          }
          if (item.RF3_freq === null) {
            item.RF3_freq = "false";
          }
          if (item.RF4_freq === null) {
            item.RF4_freq = "false";
          }

          //     CI
          if (item.CI_1 === null) {
            item.CI_1 = "false";
          }
          if (item.CI_2 === null) {
            item.CI_2 = "false";
          }
          if (item.CI_3 === null) {
            item.CI_3 = "false";
          }
          if (item.CI_4 === null) {
            item.CI_4 = "false";
          }
          if (item.CI_A === null) {
            item.CI_A = "false";
          }
          if (item.CI_B === null) {
            item.CI_B = "false";
          }

          return this.search
            .toLowerCase()
            .split(" ")
            .every(
              v =>
                item.name.toLowerCase().includes(v) ||
                item.FTE_satelit.toLowerCase().includes(v) ||
                item.RF1_satelit.toLowerCase().includes(v) ||
                item.RF2_satelit.toLowerCase().includes(v) ||
                item.RF3_satelit.toLowerCase().includes(v) ||
                item.RF4_satelit.toLowerCase().includes(v) ||
                item.FTE_freq.toLowerCase().includes(v) ||
                item.RF1_freq.toLowerCase().includes(v) ||
                item.RF2_freq.toLowerCase().includes(v) ||
                item.RF3_freq.toLowerCase().includes(v) ||
                item.RF4_freq.toLowerCase().includes(v) ||
                item.CI_1.toLowerCase().includes(v) ||
                item.CI_2.toLowerCase().includes(v) ||
                item.CI_3.toLowerCase().includes(v) ||
                item.CI_4.toLowerCase().includes(v) ||
                item.CI_A.toLowerCase().includes(v) ||
                item.CI_B.toLowerCase().includes(v) ||
                item.ip.toLowerCase().includes(v)
            );

          //   konecny else
        });
      } else {
        return this.devices;
      }
    }
  },
  methods: {}
};
</script>


