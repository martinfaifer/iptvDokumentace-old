<template>
  <div>
    <div class="rightFromSIdePanel">
      <br />
      <br />
      <v-container fluid>
        <!-- Tabs -->
        <template>
          <v-card flat>
            <v-tabs centered>
              <v-tabs-slider></v-tabs-slider>

              <v-tab link v-bind:to="'/channel/' + this.$route.params.id">Multicast</v-tab>

              <v-tab link v-bind:to="'/channel/' + this.$route.params.id + '/h264'">H264</v-tab>

              <v-tab link v-bind:to="'/channel/' + this.$route.params.id + '/h265'">H265</v-tab>
            </v-tabs>
          </v-card>
        </template>

        <transition name="fade" mode="out-in">
          <router-view />
        </transition>
      </v-container>
    </div>
  </div>
</template>

