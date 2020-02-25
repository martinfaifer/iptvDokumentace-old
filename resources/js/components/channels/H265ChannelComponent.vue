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
    <br />
    <div v-for="transcoder in channelData.transcoder" v-bind:key="transcoder.tag">
      <div v-if="transcoder.tag != 'NoTranscoder'">
        <v-toolbar
          v-if="userData.role === '1' || userData.role === '2'"
          dense
          flat
          color="transparent"
        >
          <v-spacer></v-spacer>
          <div class="text-center d-flex align-center">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  class="mr-3"
                  color="primary"
                  fab
                  x-small
                  dark
                  v-on="on"
                  @click="modalHlOutputs = true, kvalita1080p = channelData.K1080, kvalita720p = channelData.K720"
                >
                  <v-icon>mdi-ip</v-icon>
                </v-btn>
              </template>
              <span class="font-weight-medium">Editovat výstup do H265</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  class="mr-3"
                  color="primary"
                  fab
                  x-small
                  dark
                  v-on="on"
                  @click="modalTranscoderFn()"
                >
                  <v-icon>mdi-tablet-cellphone</v-icon>
                </v-btn>
              </template>
              <span class="font-weight-medium">Přidat / editovat trasncoder</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  class="mr-3"
                  color="primary"
                  fab
                  x-small
                  dark
                  v-on="on"
                  @click="modalDeleteHls()"
                >
                  <v-icon>mdi-delete-outline</v-icon>
                </v-btn>
              </template>
              <span class="font-weight-medium">Odebrat HLS data od kanálu</span>
            </v-tooltip>
          </div>
        </v-toolbar>
        <v-card class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-card-text>
                <v-row>
                  <span class="ma-3">
                    <strong>Výstup do 1080p:</strong>
                    <span>{{channelData.K1080}}</span>
                  </span>
                </v-row>
                <v-row>
                  <span class="ma-3">
                    <strong>Výstup do 720p:</strong>
                    <span>{{channelData.K720}}</span>
                  </span>
                </v-row>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>

        <!-- transcoder -->
        <div v-for="transcoder in channelData.transcoder" v-bind:key="transcoder.id">
          <!-- NSP -->
          <v-card v-if="transcoder.tag == 'Transcoder_NSP'" class="ma-3" width="1600" outlined>
            <v-list-item>
              <v-list-item-content>
                <v-card-text>
                  <span class="ma-3">
                    <strong>Transcoder :</strong>
                    <v-btn v-bind:to="'/device/' + transcoder.id " target="_blank" dark text>
                      <span class="blue--text">{{transcoder.nazev}}</span>
                    </v-btn>
                  </span>
                  <span class="ma-3">
                    <strong>IP transcoderu :</strong>
                    <a :href="'http://'+transcoder.ip" target="_blank">{{transcoder.ip}}</a>
                  </span>
                  <span class="ma-3">
                    <strong>Přihlášení do transcodéru :</strong>
                    {{transcoder.login}}
                  </span>
                  <span class="ma-3">
                    <strong>Poznámka :</strong>
                    {{transcoder.poznamka}}
                  </span>
                </v-card-text>
              </v-list-item-content>
            </v-list-item>
          </v-card>

          <!-- Transcoders Q0x -->
          <v-card v-if="transcoder.tag == 'Transcoder_Grape'" class="ma-3" width="1600" outlined>
            <v-list-item>
              <v-list-item-content>
                <v-card-text>
                  <span class="ma-3">
                    <strong>Transcoder :</strong>
                    <v-btn v-bind:to="'/device/' + transcoder.id " target="_blank" dark text>
                      <span class="blue--text">{{transcoder.nazev}}</span>
                    </v-btn>
                  </span>
                  <span class="ma-3">
                    <strong>IP aplikace na ovládání kanálů:</strong>
                    <a :href="'http://'+transcoder.ip" target="_blank">{{transcoder.ip}}</a>
                  </span>
                  <span class="ma-3">
                    <strong>Poznámka :</strong>
                    {{transcoder.poznamka}}
                  </span>
                </v-card-text>
              </v-list-item-content>
            </v-list-item>
          </v-card>
        </div>

        <!-- modal -->

        <!-- modal transcoder -->
        <v-row justify="center">
          <v-dialog v-model="modalTranscoder" persistent max-height="200px" max-width="800px">
            <v-card>
              <v-card-text>
                <v-container>
                  <!-- select trancoder -->
                  <v-row>
                    <v-col cols="12" sm="3" md="8">
                      <v-select
                        v-model="device"
                        :items="transcoders"
                        item-value="id"
                        item-text="name"
                        label="Transcoder"
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="modalTranscoder = false">Zavřít</v-btn>
                <v-btn color="blue darken-1" text @click="saveTranscoder()">Uložit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <!-- end modal transcoder -->

        <!-- modal pro pridani hls vystupu -->
        <v-row justify="center">
          <v-dialog v-model="modalHlOutputs" persistent max-height="200px" max-width="800px">
            <v-card>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 1080p " v-model="kvalita1080p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 720p " v-model="kvalita720p"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="modalHlOutputs = false">Zavřít</v-btn>
                <v-btn color="blue darken-1" text @click="saveHlsOutputData()">Uložit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <!-- end modal pro pridani hls vystupu -->

        <!-- modal delete HLS -->

        <v-row justify="center">
          <v-dialog v-model="modalDelete" persistent max-height="200px" max-width="800px">
            <v-card>
              <v-card-text>
                <v-container>
                  <!-- select trancoder -->
                  <v-row>
                    <v-col cols="12" sm="3" md="8">
                      <p>Odebrání HLS</p>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="modalDelete = false">Zavřít</v-btn>
                <v-btn color="blue darken-1" text @click="sendDeleteHls()">Smazat</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>

        <!-- end modal HSL -->

        <!-- end modal -->
      </div>
      <div v-else>
        <v-toolbar dense flat color="transparent">
          <v-spacer></v-spacer>
          <div class="text-center d-flex align-center">
            <v-tooltip v-if="userData.role === '1' || userData.role === '2'" bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  @click="addHlsOutputAndTranscoderFn()"
                  class="mr-3"
                  color="primary"
                  fab
                  x-small
                  dark
                  v-on="on"
                >
                  <v-icon>mdi-antenna</v-icon>
                </v-btn>
              </template>
              <span class="font-weight-medium">Přidat transcodér</span>
            </v-tooltip>
          </div>
        </v-toolbar>
        <v-alert type="warning">
          <strong>Kanál nemá výstup do H265.</strong>
        </v-alert>

        <!-- modal pro pridani hls vystupu -->
        <v-row justify="center">
          <v-dialog
            v-model="modalAddHlsOutputAndTranscoder"
            persistent
            max-height="200px"
            max-width="800px"
          >
            <v-card>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 1080p " v-model="newKvalita1080p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 720p " v-model="newKvalita720p"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="3" md="8">
                      <v-select
                        v-model="newDevice"
                        :items="transcoders"
                        item-value="id"
                        item-text="name"
                        label="Transcoder"
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="modalAddHlsOutputAndTranscoder = false"
                >Zavřít</v-btn>
                <v-btn color="blue darken-1" text @click="saveHlsOutputAndTranscoder()">Uložit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <!-- end modal pro pridani hls vystupu -->
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      device: "",
      transcoders: [],
      modalTranscoder: false,
      modalHlOutputs: false,
      modalDelete: false,
      modalAddHlsOutputAndTranscoder: false,
      channelData: "",
      kvalita1080p: "",
      kvalita720p: "",
      userData: "",
      status: "",
      newKvalita1080p: "",
      newKvalita720p: "",
      newDevice: ""
    };
  },

  methods: {
    addHlsOutputAndTranscoderFn() {
      let currentObj = this;
      currentObj.modalAddHlsOutputAndTranscoder = true;

      axios
        .get("/api/device/transcoder")
        .then(response => (this.transcoders = response.data))
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },

    saveHlsOutputAndTranscoder() {
      let currentObj = this;
      currentObj.modalAddHlsOutputAndTranscoder = false;

      axios
        .post("/api/h265/output/add", {
          newChannelId: this.$route.params.id,
          newKvalita1080p: this.newKvalita1080p,
          newKvalita720p: this.newKvalita720p,
          newDevice: this.newDevice
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/channel/get/h265", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.channelData = response.data;
            })
            .catch(function(error) {
              console.log("chyba" + error);
            });
        })
        .catch(function(error) {
          currentObj.status = "err";
        });
    },
    // modal pro smazani hls dat od kanálu
    modalDeleteHls() {
      let currentObj = this;
      currentObj.modalDelete = true;
    },
    sendDeleteHls() {
      let currentObj = this;

      axios
        .post("/api/channel/h265/delete", {
          channelId: this.$route.params.id
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalDelete = false;

          axios
            .post("/api/channel/get/h265", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.channelData = response.data;
            })
            .catch(function(error) {
              console.log("chyba" + error);
            });
        })
        .catch(function(error) {
          currentObj.status = "err";
        });
    },
    saveTranscoder() {
      let currentObj = this;
      currentObj.modalTranscoder = false;

      axios
        .post("/api/h265/transcoder/edit", {
          channelId: this.$route.params.id,
          device: this.device
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/channel/get/h265", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.channelData = response.data;
            })
            .catch(function(error) {
              console.log("chyba" + error);
            });
        })
        .catch(function(error) {
          currentObj.status = "err";
        });
    },
    modalTranscoderFn() {
      let currentObj = this;
      currentObj.modalTranscoder = true;

      axios
        .get("/api/device/transcoder")
        .then(response => (this.transcoders = response.data))
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    saveHlsOutputData() {
      let currentObj = this;
      currentObj.modalHlOutputs = false;

      axios
        .post("/api/h265/output/edit", {
          channelId: this.$route.params.id,
          kvalita1080p: this.kvalita1080p,
          kvalita720p: this.kvalita720p
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/channel/get/h265", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.channelData = response.data;
            })
            .catch(function(error) {
              console.log("chyba" + error);
            });
        })
        .catch(function(error) {
          currentObj.status = "err";
        });
    }
  },

  watch: {
    status: function() {
      setTimeout(() => (this.status = false), 5000);
    },

    $route(to, from) {
      let currentObj = this;
      axios
        .post("/api/channel/get/h265", {
          id: this.$route.params.id
        })
        .then(function(response) {
          currentObj.channelData = response.data;
        })
        .catch(function(error) {
          currentObj.channelData = "error";
        });
    }
  },
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));

    // získání dat
    let currentObj = this;
    axios
      .post("/api/channel/get/h265", {
        id: this.$route.params.id
      })
      .then(function(response) {
        currentObj.channelData = response.data;
      })
      .catch(function(error) {
        currentObj.channelData = "error";
      });
  }
};
</script>
