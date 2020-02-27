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

    <!-- test -->
    <v-app-bar color="#2B2B52" fixed dense dark>
      <v-toolbar-items>
        <v-menu open-on-hover offset-y>
          <template v-slot:activator="{ on }">
            <v-btn class="white--text" text router to="/" v-on="on">Kanály</v-btn>
          </template>
          <v-list v-if="userData.role === '1' || userData.role === '2'">
            <v-list-item>
              <v-btn @click="openChannelModal()" text small>Přidat nový kanál</v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-menu open-on-hover offset-y>
          <template v-slot:activator="{ on }">
            <v-btn class="white--text" text router to="/devices" v-on="on">Zařízení</v-btn>
          </template>
          <v-list v-if="userData.role === '1' || userData.role === '2'">
            <v-list-item>
              <v-btn @click="openDeviceModal()" text small>Přidat nové zařízení</v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-btn class="white--text" text router to="/wiki">Wiki</v-btn>
        <v-btn v-if="userData.role === '1'" class="white--text" text router to="/settings">Nastavení</v-btn>
      </v-toolbar-items>

      <v-spacer></v-spacer>
      <!-- alerting -->

      <template v-if="$vuetify.breakpoint.smAndUp">
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn class="white--text" icon v-on="on">
              <v-icon v-if="iptvErrs !== ''" color="red">mdi-alert-circle</v-icon>
              <v-icon v-else>mdi-alert-circle</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item v-for="iptvErr in iptvErrs" v-bind:key="iptvErr.id">
              <v-btn text small>{{iptvErr.nazev}}</v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>

      <!-- end of alerts -->
      <!-- User cast -->
      <template v-if="$vuetify.breakpoint.smAndUp">
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn class="white--text" icon v-on="on">
              <v-icon>mdi-account-circle</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item>
              <v-btn @click="LogOut()" text small>Odhlásit</v-btn>
            </v-list-item>
            <v-list-item>
              <v-form>
                <v-btn small text @click="modalEditUser = true">Editovat</v-btn>
              </v-form>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
      <!-- Konec User casti -->
    </v-app-bar>

    <!-- end test -->
    <!-- modal  -->

    <v-row justify="center">
      <v-dialog v-model="modalNewChannel" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field required label="Název kanálu" v-model="channelName"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-select
                    v-model="channelIsp"
                    :items="channelIsps"
                    item-value="id"
                    item-text="isp"
                    label="ISP, který poskytuje kanál"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="2" md="5">
                  <v-text-field required label="Multicastová adresa" v-model="channelMulticast"></v-text-field>
                </v-col>
                <v-col cols="12" sm="2" md="5">
                  <v-text-field required label="Multicast k STB" v-model="channelMulticastKStb"></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="modalNewChannel = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="saveNewChannel()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- device modal -->

    <v-row justify="center">
      <v-dialog v-model="modalNewDevice" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-select
                    v-model="deviceKategorie"
                    :items="deviceKategories"
                    item-value="id"
                    item-text="kategorie"
                    label="Typ zařízení"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-select
                    v-model="deviceVendor"
                    :items="deviceVendors"
                    item-value="id"
                    item-text="vendor"
                    label="Výrobce zařízení"
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="modalNewDevice = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="saveNewDevice()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end device modal -->

    <!-- modal edit user -->

    <v-row justify="center">
      <v-dialog v-model="modalEditUser" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field type="password" required label="Nové heslo" v-model="newPassword"></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeEditModal()">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="saveNewPass()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end modal edit user -->

    <!-- nofication -->

    <v-row justify="center">
      <v-dialog v-model="notification" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-title centered>Novinky ve verzi</v-card-title>
          <v-card-text>
            <v-container>
              <span>
                <ol>
                  <li>Upravena historie, nyní zobrazuje pouze čas, kdy byl na kanále evidovaný výpadek.</li>
                  <li>Přidána informace u H264, kdy je popup při najetí na "?" s informací o bitratu.</li>
                  <li>Přidán návod do Wiki na testovku.</li>
                </ol>
              </span>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeNotifaction()">Zavřít</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <!-- end od notification -->
    <!-- end modal -->
    <!-- Vuejs router view -->

    <transition name="fade" mode="out-in">
      <router-view />
    </transition>
  </div>
</template>
<script>
export default {
  data() {
    return {
      iptvErrs: [],
      newPassword: "",
      userData: "",
      modalNewChannel: false,
      modalEditUser: false,
      notification: false,
      channelIsps: [],
      channelIsp: "",
      channelMulticast: "",
      channelMulticastKStb: "",
      channelName: "",
      modalNewDevice: false,
      deviceKategories: [],
      deviceKategorie: "",
      deviceVendors: [],
      deviceVendor: "",
      deviceName: "",
      deviceIp: "",
      created: "",
      createdDevice: "",
      status: ""
    };
  },
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
    axios
      .get("/api/getAlerts")
      .then(response => (this.iptvErrs = response.data));
  },
  methods: {
    closeNotifaction() {
      let currentObj = this;
      currentObj.notification = false;

      axios
        .post("/api/notification/submit", {
          userId: this.userData.id
        })
        .then(function(response) {
          currentObj.status = response.data;
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },

    closeEditModal() {
      let currentObj = this;
      currentObj.modalEditUser = false;
      currentObj.newPassword = "";
    },
    saveNewPass() {
      let currentObj = this;
      axios
        .post("/api/user/changePass", {
          newPassword: this.newPassword,
          userId: this.userData.id
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.modalEditUser = false;

          currentObj.newPassword = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    LogOut() {
      let currentObj = this;
      axios
        .get("/api/user/logout")
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.$router.push("/login");
        })
        .catch(function(error) {});
    },
    getIsp() {
      axios
        .get("/api/isps/get")
        .then(response => (this.channelIsps = response.data))
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },

    getKategorie() {
      axios
        .get("/api/categories/get")
        .then(response => (this.deviceKategories = response.data))
        .catch(function(error) {
          console.log("kategorie" + error);
        });
    },

    getVendors() {
      axios
        .get("/api/vendors/get")
        .then(response => (this.deviceVendors = response.data))
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    openChannelModal() {
      let currentObj = this;
      currentObj.modalNewChannel = true;

      this.getIsp();
    },

    openDeviceModal() {
      let currentObj = this;
      currentObj.modalNewDevice = true;

      this.getKategorie();
      this.getVendors();
    },
    saveNewChannel() {
      let currentObj = this;
      axios
        .post("/api/channel/create", {
          channelIsp: this.channelIsp,
          channelName: this.channelName,
          channelMulticast: this.channelMulticast,
          channelMulticastKStb: this.channelMulticastKStb
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.modalNewChannel = false;

          if (currentObj.status.stat == "success") {
            currentObj.created = "true";
          }

          channelIsp = "";
          channelName = "";
          channelMulticast = "";
          channelMulticastKStb = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    saveNewDevice() {
      let currentObj = this;
      axios
        .post("/api/device/create", {
          deviceName: this.deviceName,
          deviceVendor: this.deviceVendor,
          deviceKategorie: this.deviceKategorie,
          deviceIp: this.deviceIp
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.modalNewDevice = false;

          if (currentObj.status.stat == "success") {
            currentObj.createdDevice = "true";
          }

          deviceName = "";
          deviceVendor = "";
          deviceKategorie = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    }
  },
  watch: {
    iptvErrs: function() {
      setTimeout(
        function() {
          let currentObj = this;
          axios
            .get("/api/getAlerts")
            .then(response => (this.iptvErrs = response.data));
        }.bind(this),
        3000
      );
    },
    // Presmerování na login pokud neni prihlasen uzivatel
    userData: function() {
      if (this.userData === false) {
        this.$router.push("/login");
      }

      if (this.userData.notification === null) {
        this.notification = true;
      }
    },

    created: function() {
      if (this.created === "true") {
        this.created = "";
        setTimeout(
          function() {
            this.$router.push("/channel/" + this.status.id);
          }.bind(this),
          1000
        );
      }
    },
    createdDevice: function() {
      if (this.createdDevice === "true") {
        this.createdDevice = "";
        setTimeout(
          function() {
            this.$router.push("/device/" + this.status.id);
          }.bind(this),
          1000
        );
      }
    },
    status: function() {
      setTimeout(() => (this.status = false), 5000);
    }
  }
};
</script>

