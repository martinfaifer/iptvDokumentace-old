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
                                >{{ status.msg }}</v-alert
                            >
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
                        <v-btn class="white--text" text router to="/" v-on="on"
                            >Kanály</v-btn
                        >
                    </template>
                    <v-list
                        v-if="userData.role === '1' || userData.role === '2'"
                    >
                        <v-list-item>
                            <v-btn @click="openChannelModal()" text small>
                                <v-icon dark left>mdi-plus mdi-18px</v-icon
                                >Přidat nový kanál
                            </v-btn>
                        </v-list-item>
                        <v-list-item>
                            <a
                                style="text-decoration:none"
                                target="_blank"
                                href="/api/channels/pdf"
                            >
                                <v-btn text small>
                                    <v-icon dark left
                                        >mdi-briefcase-download-outline
                                        mdi-18px</v-icon
                                    >Export kanálů do pdf
                                </v-btn>
                            </a>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <v-menu open-on-hover offset-y>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            class="white--text"
                            text
                            router
                            to="/devices"
                            v-on="on"
                            >Zařízení</v-btn
                        >
                    </template>
                    <v-list
                        v-if="userData.role === '1' || userData.role === '2'"
                    >
                        <v-list-item>
                            <v-btn @click="openDeviceModal()" text small>
                                <v-icon dark left>mdi-plus mdi-18px</v-icon
                                >Přidat nové zařízení
                            </v-btn>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <v-btn class="white--text" text router to="/wiki">Wiki</v-btn>
                <v-btn class="white--text" text router to="/channelPackages"
                    >Programové balíčky</v-btn
                >
                <v-btn
                    v-if="userData.role === '1'"
                    class="white--text"
                    text
                    router
                    to="/settings"
                    >Nastavení</v-btn
                >
            </v-toolbar-items>

            <v-spacer></v-spacer>
            <!-- alerting -->

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
                                <v-btn small text @click="modalEditUser = true"
                                    >Editovat</v-btn
                                >
                            </v-form>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
            <!-- Konec User casti -->
            <v-badge
                bordered
                bottom
                color="red"
                :content="alertCount"
                offset-x="10"
                offset-y="10"
            >
                <v-icon @click="drawer = !drawer">mdi-bell-outline</v-icon>
            </v-badge>
        </v-app-bar>

        <!-- stream alerting -->
        <v-navigation-drawer
            v-model="drawer"
            class="mt-12"
            right
            fixed
            temporary
            color="transparent"
        >
            <div
                id="alerty"
                class="pl-2 pr-2"
                v-for="alert in alerts"
                :key="alert.id"
            >
                <v-alert
                    dense
                    border="left"
                    :type="alert.status"
                    class="body-2 mt-2"
                >
                    <strong>{{ alert.msg }}</strong>
                    <div v-show="alert.data">
                        <v-row
                            class="ml-3"
                            v-for="issueData in alert.data"
                            :key="issueData.id"
                        >
                            <small>
                                <strong>
                                    {{ issueData.message }}
                                </strong>
                            </small>
                        </v-row>
                    </div>
                </v-alert>
            </div>
        </v-navigation-drawer>
        <!-- konec alertingu -->

        <!-- end test -->
        <!-- modal  -->

        <v-row justify="center">
            <v-dialog
                v-model="modalNewChannel"
                persistent
                max-height="200px"
                max-width="600px"
            >
                <v-card>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field
                                        required
                                        label="Název kanálu"
                                        v-model="channelName"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
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
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field
                                        required
                                        label="Multicastová adresa"
                                        v-model="channelMulticast"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field
                                        required
                                        label="Multicast k STB"
                                        v-model="channelMulticastKStb"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="modalNewChannel = false"
                            >Zavřít</v-btn
                        >
                        <v-btn
                            color="green darken-1"
                            text
                            @click="saveNewChannel()"
                            >Uložit</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- device modal -->

        <v-row justify="center">
            <v-dialog
                v-model="modalNewDevice"
                persistent
                max-height="200px"
                max-width="600px"
            >
                <v-card>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="6" md="10">
                                    <v-text-field
                                        required
                                        label="Název zařízení"
                                        v-model="deviceName"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="6" md="10">
                                    <v-text-field
                                        required
                                        label="IP"
                                        v-model="deviceIp"
                                    ></v-text-field>
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
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="modalNewDevice = false"
                            >Zavřít</v-btn
                        >
                        <v-btn
                            color="green darken-1"
                            text
                            @click="saveNewDevice()"
                            >Uložit</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- end device modal -->

        <!-- modal edit user -->

        <v-row justify="center">
            <v-dialog
                v-model="modalEditUser"
                persistent
                max-height="200px"
                max-width="600px"
            >
                <v-card>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="6" md="10">
                                    <v-text-field
                                        type="password"
                                        required
                                        label="Nové heslo"
                                        v-model="newPassword"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="closeEditModal()"
                            >Zavřít</v-btn
                        >
                        <v-btn
                            color="green darken-1"
                            text
                            @click="saveNewPass()"
                            >Uložit</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- end modal edit user -->

        <!-- search modal -->

        <v-row justify="center">
            <v-dialog
                v-model="searchModal"
                persistent
                max-height="200px"
                max-width="600px"
            >
                <v-card>
                    <v-card-text>
                        <v-col cols="12" sm="12" md="12">
                            <v-autocomplete
                                :items="searchData"
                                item-text="description"
                                label="hledejte v dokumentaci"
                            ></v-autocomplete>
                        </v-col>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="searchModal = false"
                            >Zavřít</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- end of search modal -->

        <!-- nofication -->

        <v-row justify="center">
            <v-dialog
                v-model="notification"
                persistent
                max-height="200px"
                max-width="600px"
            >
                <v-card>
                    <v-card-title centered>Novinky ve verzi</v-card-title>
                    <v-card-text>
                        <v-container>
                            <span>
                                <ol>
                                    <li>
                                        Přidána validace vstupních dat při
                                        zakládání / editaci
                                    </li>
                                    <li>Přidán export všech kanálů do PDF</li>
                                </ol>
                            </span>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="closeNotifaction()"
                            >Zavřít</v-btn
                        >
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

        <!-- iptv dohled -->
        <v-snackbar
            class="mt-12"
            v-if="dohledConnection != null"
            :value="dohledConnection"
            color="primary"
            absolute
            rounded="pill"
            top
        >
            <span class="ml-12" v-if="dohledConnectionStatus == 'success'">
                Připojeno k IPTV dohledu
            </span>
            <span class="ml-12" v-if="dohledConnectionStatus == 'error'">
                Nepodařilo se připojit k IPTV dohledu
            </span>
        </v-snackbar>
    </div>
</template>
<script>
export default {
    data() {
        return {
            alerts: [],
            alertCount: "",
            drawer: null,
            dohledConnectionStatus: null,
            dohledConnection: null,
            searchData: null,
            searchModal: false,
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
            status: "",
            export: ""
        };
    },
    created() {
        this.loadAlerts();
        try {
            this.testConnectionToDohled();
        } catch (error) {
            this.dohledConnectionStatus = "error";
            setTimeout(function() {
                this.dohledConnectionStatus = null;
            }, 5000);
        }
        axios
            .get("/api/getUser")
            .then(response => (this.userData = response.data));
        axios
            .get("/api/getAlerts")
            .then(response => (this.iptvErrs = response.data));
    },
    methods: {
        loadAlerts() {
            window.axios.get("/api/getAlertsFromDohled").then(response => {
                this.alerts = response.data;
                // this.$store.commit("updateAlert", response.data);
                this.alertCount = response.data.length;
            });
        },
        testConnectionToDohled() {
            axios.get("/api/testConnectionToDohled").then(response => {
                this.dohledConnection = response.data;
                if (this.dohledConnection == "success") {
                    this.dohledConnectionStatus = "success";
                    setTimeout(function() {
                        this.dohledConnectionStatus = null;
                    }, 5000);
                } else {
                    this.dohledConnectionStatus = "error";
                }
            });
        },
        searchModalOpen() {
            axios
                .get("/api/search")
                .then(
                    response => (
                        (this.searchData = response.data),
                        (this.searchModal = true)
                    )
                );
        },

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

                    currentObj.channelIsp = "";
                    currentObj.channelName = "";
                    currentObj.channelMulticast = "";
                    currentObj.channelMulticastKStb = "";
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

                    currentObj.deviceName = "";
                    currentObj.deviceVendor = "";
                    currentObj.deviceKategorie = "";
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
                60000
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
            setTimeout(() => (this.status = false), 3000);
        }
    },
    mounted() {
        setInterval(
            function() {
                try {
                    this.loadAlerts();
                } catch (error) {}
            }.bind(this),
            10000
        );
    }
};
</script>
