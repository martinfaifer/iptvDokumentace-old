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
                  @click="modalHlOutputs = true, chunkStoreId = channelData.chundStoreId, kvalita1080p = channelData.K1080, kvalita720p = channelData.K720, kvalita576p = channelData.K576, kvalita404p = channelData.K404, hlsKdekoliv = channelData.m3u8_HLS_kdekoliv, hlsLocal = channelData.m3u8_stb, hlsMobile = channelData.m3u8_mobile"
                >
                  <v-icon>mdi-ip</v-icon>
                </v-btn>
              </template>
              <span class="font-weight-medium">Editovat výstup do H264</span>
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
                  <v-icon color="red">mdi-delete-outline</v-icon>
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
                    <strong>Chunk store ID:</strong>
                    {{channelData.chundStoreId}}
                  </span>
                </v-row>

                <v-divider></v-divider>

                <!-- Kvality -->
                <v-row>
                  <v-toolbar
                    v-if="kvalities != 'false'"
                    dense
                    flat
                    color="transparent"
                    height="15px"
                  >
                    <v-spacer></v-spacer>
                    <div class="text-center d-flex align-center">
                      <v-tooltip width="100px" left>
                        <template v-slot:activator="{ on }">
                          <v-icon color="primary" icon small v-on="on">mdi-comment-question-outline</v-icon>
                        </template>
                        <span
                          v-for="kvalita in kvalities"
                          v-bind:key="kvalita.id"
                          v-if="kvalita.video === 'H.264'"
                          class="font-weight-medium"
                        >
                          <p
                            v-if="kvalita.kvalita === '1920x1080'"
                          >Bitrate pro 1080p : {{kvalita.bitrate}} Kbps</p>
                          <p
                            v-if="kvalita.kvalita === '1280x720'"
                          >Bitrate pro 720p : {{kvalita.bitrate}} Kbps</p>
                          <p
                            v-if="kvalita.kvalita === '1024x576'"
                          >Bitrate pro 576p : {{kvalita.bitrate}} Kbps</p>
                          <p
                            v-if="kvalita.kvalita === '720x404'"
                          >Bitrate pro 404p : {{kvalita.bitrate}} Kbps</p>
                        </span>
                      </v-tooltip>
                    </div>
                  </v-toolbar>
                </v-row>
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
                <v-row>
                  <span class="ma-3">
                    <strong>Výstup do 576p:</strong>
                    <span>{{channelData.K576}}</span>
                  </span>
                </v-row>
                <v-row>
                  <span class="ma-3">
                    <strong>Výstup do 404p:</strong>
                    <span>{{channelData.K404}}</span>
                  </span>
                </v-row>

                <v-divider></v-divider>

                <!-- m3u8 -->
                <v-row>
                  <span class="ma-3">
                    <strong>HLS kdekoliv:</strong>
                    <span>{{channelData.m3u8_HLS_kdekoliv}}</span>
                  </span>
                  <span class="ma-3">
                    <strong>HLS local:</strong>
                    <span>{{channelData.m3u8_stb}}</span>
                  </span>
                  <span class="ma-3">
                    <strong>HLS pro mobily:</strong>
                    <span>{{channelData.m3u8_mobile}}</span>
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
        <!-- modals -->

        <!-- modal pro pridani hls vystupu -->
        <v-row justify="center">
          <v-dialog v-model="modalHlOutputs" persistent max-height="200px" max-width="800px">
            <v-card>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field required label="Chunk store ID " v-model="chunkStoreId"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 1080p " v-model="kvalita1080p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 720p " v-model="kvalita720p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 576p " v-model="kvalita576p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 404p " v-model="kvalita404p"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field label="výstup ve pro HLS kdekoliv " v-model="hlsKdekoliv"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field label="výstup ve pro HLS local " v-model="hlsLocal"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field label="výstup ve pro HLS mobile " v-model="hlsMobile"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="modalHlOutputs = false">Zavřít</v-btn>
                <v-btn color="green darken-1" text @click="saveHlsOutputData()">Uložit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <!-- end modal pro pridani hls vystupu -->

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
                <v-btn color="green darken-1" text @click="saveTranscoder()">Uložit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <!-- end modal transcoder -->

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
                <v-btn color="green darken-1" text @click="sendDeleteHls()">Smazat</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>

        <!-- end modal HSL -->

        <!-- end modals -->
      </div>
      <div v-else>
        <v-toolbar
          v-if="userData.role === '1' || userData.role === '2'"
          dense
          flat
          color="transparent"
        >
          <v-spacer></v-spacer>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn
                class="mr-3"
                color="primary"
                fab
                x-small
                dark
                v-on="on"
                @click="addHlsOutputAndTranscoderFn()"
              >
                <v-icon>mdi-antenna</v-icon>
              </v-btn>
            </template>
            <span class="font-weight-medium">Přidat transcodér + výstupy</span>
          </v-tooltip>
        </v-toolbar>
        <v-alert type="warning">
          <strong>Kanál se netranscoduje.</strong>
        </v-alert>

        <!-- modals -->

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
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field required label="Chunk store ID " v-model="newChunkStoreId"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 1080p " v-model="newKvalita1080p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 720p " v-model="newKvalita720p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 576p " v-model="newKvalita576p"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="5">
                      <v-text-field label="výstup ve 404p " v-model="newKvalita404p"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field label="výstup ve pro HLS kdekoliv " v-model="newHlsKdekoliv"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field label="výstup ve pro HLS local " v-model="newHlsLocal"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3" md="8">
                      <v-text-field label="výstup ve pro HLS mobile " v-model="newHlsMobile"></v-text-field>
                    </v-col>
                  </v-row>
                  <v-divider></v-divider>
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
                <v-btn color="green darken-1" text @click="saveHlsOutputAndTranscoder()">Uložit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <!-- end modal pro pridani hls vystupu -->

        <!-- end modals -->
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      userData: "",
      channelData: "",
      status: "",
      modalHlOutputs: false,
      modalTranscoder: false,
      modalAddHlsOutputAndTranscoder: false,
      modalDelete: false,
      chunkStoreId: "",
      kvalita1080p: "",
      kvalita720p: "",
      kvalita576p: "",
      kvalita404p: "",
      hlsKdekoliv: "",
      hlsLocal: "",
      hlsMobile: "",
      newChunkStoreId: "",
      newKvalita1080p: "",
      newKvalita720p: "",
      newKvalita576p: "",
      newKvalita404p: "",
      newHlsKdekoliv: "",
      newHlsLocal: "",
      newHlsMobile: "",
      transcoders: [],
      device: "",
      kvalities: "",
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
        .post("/api/h264/output/add", {
          newChannelId: this.$route.params.id,
          newChunkStoreId: this.newChunkStoreId,
          newKvalita1080p: this.newKvalita1080p,
          newKvalita720p: this.newKvalita720p,
          newKvalita576p: this.newKvalita576p,
          newKvalita404p: this.newKvalita404p,
          newHlsKdekoliv: this.newHlsKdekoliv,
          newHlsLocal: this.newHlsLocal,
          newHlsMobile: this.newHlsMobile,
          newDevice: this.newDevice
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/channel/get/h264", {
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
    saveTranscoder() {
      let currentObj = this;
      currentObj.modalTranscoder = false;

      axios
        .post("/api/h264/transcoder/edit", {
          channelId: this.$route.params.id,
          device: this.device
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/channel/get/h264", {
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
    saveHlsOutputData() {
      let currentObj = this;
      currentObj.modalHlOutputs = false;

      axios
        .post("/api/h264/output/edit", {
          channelId: this.$route.params.id,
          chunkStoreId: this.chunkStoreId,
          kvalita1080p: this.kvalita1080p,
          kvalita720p: this.kvalita720p,
          kvalita576p: this.kvalita576p,
          kvalita404p: this.kvalita404p,
          hlsKdekoliv: this.hlsKdekoliv,
          hlsLocal: this.hlsLocal,
          hlsMobile: this.hlsMobile
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/channel/get/h264", {
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
        .post("/api/channel/h264/delete", {
          channelId: this.$route.params.id
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalDelete = false;

          axios
            .post("/api/channel/get/h264", {
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
    $route(to, from) {
      let currentObj = this;
      axios
        .post("/api/channel/get/h264", {
          id: this.$route.params.id
        })
        .then(function(response) {
          currentObj.channelData = response.data;
        })
        .catch(function(error) {
          currentObj.channelData = "error";
        });
    },
    status: function() {
      setTimeout(() => (this.status = false), 5000);
    }
  },
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));

    axios
      .get("/api/channels/hls/kvality")
      .then(response => (this.kvalities = response.data));

    let currentObj = this;
    axios
      .post("/api/channel/get/h264", {
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
