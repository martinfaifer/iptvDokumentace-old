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
        <!-- end alert -->
        <br />
        <v-toolbar
            v-show="userData.role === '1' || userData.role === '2'"
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
                            @click="modalProNovyMulticast = true"
                        >
                            <v-icon>mdi-ip</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium"
                        >Přidat multicastový příjem</span
                    >
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
                            @click="takeDataForPrijem"
                        >
                            <v-icon>mdi-antenna</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium"
                        >Přidat / editovat přijímač</span
                    >
                </v-tooltip>
                <div
                    v-for="backup in channelData.backup"
                    v-bind:key="backup.tag"
                >
                    <div v-if="backup.tag == 'KO'">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    @click="takeDataForBackupPrijem"
                                    class="mr-3"
                                    color="primary"
                                    fab
                                    x-small
                                    dark
                                    v-on="on"
                                >
                                    <v-icon>mdi-backup-restore</v-icon>
                                </v-btn>
                            </template>
                            <span class="font-weight-medium"
                                >Přidat / editovat záložní přijímač</span
                            >
                        </v-tooltip>
                    </div>
                    <div v-else>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    class="mr-3"
                                    color="grey"
                                    fab
                                    x-small
                                    dark
                                    v-on="on"
                                >
                                    <v-icon>mdi-backup-restore</v-icon>
                                </v-btn>
                            </template>
                            <span class="font-weight-medium"
                                >Přidat / editovat záložní přijímač</span
                            >
                        </v-tooltip>
                    </div>
                </div>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            class="mr-3"
                            color="primary"
                            fab
                            x-small
                            dark
                            v-on="on"
                            @click="editMultiplexer = true"
                        >
                            <v-icon>mdi-file-document-box-multiple</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium"
                        >Přidat / editovat multiplexer</span
                    >
                </v-tooltip>

                <!-- přidání kanálu do programového balíčku -->
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            class="mr-3"
                            color="primary"
                            fab
                            x-small
                            dark
                            v-on="on"
                            @click="getIptvPackages()"
                        >
                            <v-icon>mdi-television-guide</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium"
                        >Přidat / editovat programový balíček</span
                    >
                </v-tooltip>

                <!-- založení kanálu na dohledu -->
                <!-- <v-tooltip bottom v-if="dohledData === false">
                    <template v-slot:activator="{ on }">
                        <v-btn
                            class="mr-3"
                            color="primary"
                            fab
                            x-small
                            dark
                            v-on="on"
                            @click="openDialogForCreateDohledChannel()"
                        >
                            <v-icon color="green">mdi-monitor-multiple</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium"
                        >Založit kanál do dohledu</span
                    >
                </v-tooltip> -->

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            @click="openDialogNewEvent()"
                            class="mr-3"
                            color="primary"
                            fab
                            x-small
                            dark
                            v-on="on"
                        >
                            <v-icon color="white">mdi-calendar</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium">Události u kanálu</span>
                </v-tooltip>

                <!-- odebrání kanálu -->
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            class="mr-3"
                            color="primary"
                            fab
                            x-small
                            dark
                            v-on="on"
                            @click="deleteChannel()"
                        >
                            <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <span class="font-weight-medium">Odebrat kanál</span>
                </v-tooltip>
            </div>
        </v-toolbar>
        <v-row no-gutters>
            <v-col class="col-6">
                <!-- obecné informace o kanálu -->
                <v-card class="ma-3 elevation-0" color="grey lighten-4">
                    <v-list-item>
                        <v-card-text>
                            <strong>Název: </strong>
                            <span class="ma-3">{{ channelData.nazev }}</span>
                            <v-btn
                                v-show="
                                    userData.role === '1' ||
                                        userData.role === '2'
                                "
                                color="primary"
                                x-small
                                dark
                                text
                                @click="
                                    (editChannelName = true),
                                        (channelName = channelData.nazev)
                                "
                            >
                                <v-icon small>mdi-pencil</v-icon>
                            </v-btn>
                            <!-- Programovy balicek -->
                            <span v-if="channelData.balicek != ''">
                                <span class="ma-3">
                                    <strong>Programový balíček:</strong>
                                    <span v-for="balicek in channelData.balicek"
                                        >{{ balicek }} ,</span
                                    >
                                    <!-- {{channelData.balicek}} -->
                                </span>
                                <v-btn
                                    v-show="
                                        userData.role === '1' ||
                                            userData.role === '2'
                                    "
                                    color="red"
                                    x-small
                                    dark
                                    text
                                    @click="packageDelete()"
                                >
                                    <v-icon small>mdi-delete</v-icon>
                                </v-btn>
                            </span>
                        </v-card-text>
                    </v-list-item>
                </v-card>

                <!-- plánované události -->
                <v-card class="ma-3 elevation-0" color="grey lighten-4">
                    <v-card-text>
                        <div v-if="event == 'empty'">
                            <v-alert type="info" outlined text>
                                Není neplánovaná žádná událost
                            </v-alert>
                        </div>
                        <div v-else>
                            <v-data-table
                                dense
                                :headers="eventHeaders"
                                :items="event"
                                :single-expand="singleExpand"
                                :expanded.sync="expanded"
                                item-key="id"
                                show-expand
                                class="elevation-0"
                            >
                                <template
                                    v-slot:expanded-item="{ headers, item }"
                                >
                                    <td :colspan="headers.length">
                                        {{ item.event }}
                                    </td>
                                </template>
                                <!-- akce  u jednotivých událostí -->
                                <template v-slot:item.akce="{ item }">
                                    <!-- delete -->
                                    <v-icon
                                        @click="
                                            openDeleteDalog(),
                                                (startTime = item.start_time),
                                                (endTime = item.end_time),
                                                (eventId = item.id)
                                        "
                                        small
                                        color="red"
                                        >mdi-delete</v-icon
                                    >
                                </template>
                            </v-data-table>
                        </div>
                    </v-card-text>
                </v-card>
                <!-- konec plánovaných událostí -->
                <!-- modal pro editaci Názvu kanálu -->
                <v-row justify="center">
                    <v-dialog
                        v-model="editChannelName"
                        persistent
                        max-height="200px"
                        max-width="500px"
                    >
                        <v-card>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="8">
                                            <v-text-field
                                                required
                                                v-model="channelName"
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
                                    @click="editChannelName = false"
                                    >Zavřít</v-btn
                                >
                                <v-btn
                                    color="green darken-1"
                                    text
                                    @click="channelNameEdit()"
                                    >Uložit</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-row>
            </v-col>
            <v-col class="col-6">
                <v-card class="ma-3 elevation-0" color="grey lighten-4">
                    <v-row class="mr-3 mt-1">
                        <v-spacer></v-spacer>
                        <v-btn
                            v-show="
                                userData.role === '1' || userData.role === '2'
                            "
                            color="primary"
                            x-small
                            dark
                            text
                            @click="
                                (editChannelISP = true),
                                    (channelIsp = multicast.isp),
                                    (channelMulticastId = multicast.id),
                                    (multicastIp = multicast.ip),
                                    (stbIp = channelData.ipKstb),
                                    (ispMulticastId = multicast.ispId),
                                    (dohledUrl = channelData.dohledUrl)
                            "
                        >
                            <v-icon small>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn
                            v-show="
                                userData.role === '1' || userData.role === '2'
                            "
                            color="primary"
                            x-small
                            dark
                            text
                            @click="
                                deleteMulticastdata(
                                    (channelMulticastId = multicast.id)
                                )
                            "
                        >
                            <v-icon color="red" small>mdi-delete</v-icon>
                        </v-btn>
                    </v-row>

                    <v-list-item>
                        <v-list-item-content>
                            <v-card-text
                                v-for="multicast in channelData.multicast"
                                v-bind:key="multicast.id"
                            >
                                <v-row>
                                    <span class="ma-3">
                                        <strong>Kanál je od :</strong>
                                        <span>{{ multicast.isp }}</span>
                                    </span>
                                </v-row>
                                <v-row>
                                    <span class="ma-3">
                                        <strong>Muticast IP :</strong>
                                        <span>{{ multicast.ip }}</span>
                                    </span>
                                    <span class="ma-3">
                                        <strong>IP k STB :</strong>
                                        <span>{{ channelData.ipKstb }}</span>
                                    </span>
                                </v-row>
                                <v-row>
                                    <span class="ma-3">
                                        <strong>Url z udpxy: </strong>
                                        <span>{{ channelData.dohledUrl }}</span>
                                    </span>
                                </v-row>
                            </v-card-text>

                            <!-- modal edit channel -->
                            <!-- modal delete multicast data -->
                            <v-row justify="center">
                                <v-dialog
                                    v-model="deleteChannelISP"
                                    persistent
                                    max-height="200px"
                                    max-width="500px"
                                >
                                    <v-card>
                                        <v-card-text>
                                            <v-container>
                                                <p>Smazat?</p>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                v-show="
                                                    userData.role === '1' ||
                                                        userData.role === '2'
                                                "
                                                color="red darken-1"
                                                text
                                                @click="
                                                    deleteChannelISP = false
                                                "
                                                >Zavřít</v-btn
                                            >
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-row>
                            <!-- end modal delete mutlticast data -->

                            <!-- modal edit channel ISP -->
                            <v-row justify="center">
                                <v-dialog
                                    v-model="editChannelISP"
                                    persistent
                                    max-height="200px"
                                    max-width="500px"
                                >
                                    <v-card>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        sm="3"
                                                        md="8"
                                                    >
                                                        <v-select
                                                            v-model="isp"
                                                            :items="isps"
                                                            item-value="id"
                                                            item-text="isp"
                                                            label="ISP"
                                                        ></v-select>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        sm="3"
                                                        md="8"
                                                    >
                                                        <v-text-field
                                                            required
                                                            label="Multicastová IP"
                                                            v-model="
                                                                multicastIp
                                                            "
                                                        ></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        sm="3"
                                                        md="8"
                                                    >
                                                        <v-text-field
                                                            required
                                                            label="IP k STB"
                                                            v-model="stbIp"
                                                        ></v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        sm="3"
                                                        md="8"
                                                    >
                                                        <v-text-field
                                                            required
                                                            label="Dohledová URL"
                                                            v-model="dohledUrl"
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
                                                @click="editChannelISP = false"
                                                >Zavřít</v-btn
                                            >
                                            <v-btn
                                                color="green darken-1"
                                                text
                                                @click="channelIspEdit()"
                                                >Uložit</v-btn
                                            >
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-row>

                            <!-- end modal channel -->
                        </v-list-item-content>
                    </v-list-item>
                </v-card>
            </v-col>
        </v-row>

        <!-- modal pro editaci zdroje ISP, Multicast ...  -->
        <v-row justify="center">
            <v-dialog
                v-model="modalProNovyMulticast"
                persistent
                max-height="200px"
                max-width="500px"
            >
                <v-card>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="3" md="8">
                                    <v-select
                                        v-model="isp"
                                        :items="isps"
                                        item-value="id"
                                        item-text="isp"
                                        label="ISP"
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="3" md="8">
                                    <v-text-field
                                        required
                                        label="multicastová adresa od zdroje"
                                        v-model="newMulticastIp"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="3" md="8">
                                    <v-text-field
                                        v-if="channelData.ipKstb == ''"
                                        required
                                        label="multicastová adresa k STB"
                                        v-model="newStbIp"
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
                            @click="modalProNovyMulticast = false"
                            >Zavřít</v-btn
                        >
                        <v-btn
                            color="green darken-1"
                            text
                            @click="addNewMulticast()"
                            >Uložit</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- end modal pro editaci zdroje ISP, Multicast ...  -->

        <!-- multiplexer -->
        <v-row no-gutters>
            <v-col class="col-6">
                <div v-for="mux in channelData.multiplexer" v-bind:key="mux.id">
                    <div v-if="mux.status != 'KO'">
                        <v-card class="ma-3 elevation-0" color="grey lighten-4">
                            <v-list-item>
                                <v-list-item-content>
                                    <v-toolbar
                                        dense
                                        flat
                                        height="10"
                                        color="transparent"
                                    >
                                        <v-spacer></v-spacer>
                                        <div
                                            class="text-center d-flex align-center"
                                        >
                                            <span>
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-show="
                                                                userData.role ===
                                                                    '1' ||
                                                                    userData.role ===
                                                                        '2'
                                                            "
                                                            icon
                                                            x-small
                                                            v-on="on"
                                                            @click="
                                                                deleteMultiplexer()
                                                            "
                                                        >
                                                            <v-icon color="red"
                                                                >mdi-delete</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>
                                                    <span
                                                        class="font-weight-medium"
                                                        >Odebrat
                                                        multiplexer</span
                                                    >
                                                </v-tooltip>
                                            </span>
                                        </div>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Multiplexer :</strong>
                                                <span>{{ mux.nazev }}</span>
                                            </span>

                                            <span class="ma-3">
                                                <strong
                                                    >IP multiplexeru :</strong
                                                >
                                                <a
                                                    :href="'http://' + mux.ip"
                                                    target="_blank"
                                                    >{{ mux.ip }}</a
                                                >
                                            </span>
                                            <span class="ma-3">
                                                <strong
                                                    >Přihlášení do multiplexeru
                                                    :</strong
                                                >
                                                {{ mux.login }}
                                            </span>
                                        </v-row>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Poznámka :</strong>
                                                {{ mux.poznamka }}
                                            </span>
                                        </v-row>
                                    </v-card-text>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>
                    </div>
                    <div v-else>
                        <v-alert type="warning">
                            <strong>Není evidován multiplexer</strong>
                        </v-alert>
                    </div>
                </div>
            </v-col>

            <v-col class="col-6">
                <!-- přijímač -->
                <div
                    v-for="prijem in channelData.prijem"
                    v-bind:key="prijem.tag"
                >
                    <div v-if="prijem.tag != 'KO'">
                        <!--  tag PoIP -->
                        <v-card
                            v-if="prijem.tag === 'PoIP'"
                            class="ma-3"
                            color="grey lighten-4"
                            outlined
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-toolbar
                                        dense
                                        flat
                                        height="10"
                                        color="transparent"
                                    >
                                        <v-spacer></v-spacer>
                                        <div
                                            class="text-center d-flex align-center"
                                        >
                                            <span>
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-show="
                                                                userData.role ===
                                                                    '1' ||
                                                                    userData.role ===
                                                                        '2'
                                                            "
                                                            icon
                                                            x-small
                                                            v-on="on"
                                                            @click="
                                                                deletePrijem()
                                                            "
                                                        >
                                                            <v-icon color="red"
                                                                >mdi-delete</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>
                                                    <span
                                                        class="font-weight-medium"
                                                        >Odebrat Přijímač</span
                                                    >
                                                </v-tooltip>
                                            </span>
                                        </div>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Příjem kanálu :</strong>
                                                {{ prijem.prijem }}
                                            </span>
                                        </v-row>
                                    </v-card-text>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>

                        <!-- tag Blankom -->
                        <v-card
                            v-if="prijem.tag === 'Blankom'"
                            class="ma-3"
                            color="grey lighten-4"
                            outlined
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-toolbar
                                        dense
                                        flat
                                        height="10"
                                        color="transparent"
                                    >
                                        <v-spacer></v-spacer>
                                        <div
                                            class="text-center d-flex align-center"
                                        >
                                            <span>
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-show="
                                                                userData.role ===
                                                                    '1' ||
                                                                    userData.role ===
                                                                        '2'
                                                            "
                                                            icon
                                                            x-small
                                                            v-on="on"
                                                            @click="
                                                                deletePrijem()
                                                            "
                                                        >
                                                            <v-icon color="red"
                                                                >mdi-delete</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>
                                                    <span
                                                        class="font-weight-medium"
                                                        >Odebrat Přijímač</span
                                                    >
                                                </v-tooltip>
                                            </span>
                                        </div>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Příjem kanálu :</strong>
                                                <v-btn
                                                    v-bind:to="
                                                        '/device/' + prijem.id
                                                    "
                                                    target="_blank"
                                                    dark
                                                    text
                                                >
                                                    <span class="blue--text">{{
                                                        prijem.nazev
                                                    }}</span>
                                                </v-btn>
                                            </span>
                                        </v-row>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>RF :</strong>
                                                <span
                                                    v-for="rf in channelData.rf"
                                                    v-bind:key="rf.id"
                                                    >{{ rf }}</span
                                                >
                                            </span>
                                            <span class="ma-3">
                                                <strong>CI :</strong>
                                                <span
                                                    v-for="ci in channelData.ci"
                                                    v-bind:key="ci.id"
                                                    >{{ ci }}</span
                                                >
                                            </span>
                                            <span class="ma-3">
                                                <strong
                                                    >Nastavení Blankomu
                                                    :</strong
                                                >
                                                <a
                                                    :href="
                                                        'http://' + prijem.ip
                                                    "
                                                    target="_blank"
                                                    >{{ prijem.ip }}</a
                                                >
                                            </span>
                                            <span class="ma-3">
                                                <strong>Přihlášení :</strong>
                                                {{ prijem.login }}
                                            </span>
                                        </v-row>
                                        <!-- CA modul -->
                                        <v-row>
                                            <span
                                                v-show="
                                                    channelData.ca_modul !=
                                                        'false'
                                                "
                                                class="ma-3"
                                            >
                                                <strong>Typ CA Moduli :</strong>
                                                {{ channelData.ca_modul }}
                                            </span>
                                            <span
                                                v-show="
                                                    channelData.ca_modul !=
                                                        'false'
                                                "
                                                class="ma-3"
                                            >
                                                <strong
                                                    >Maximální počet kanálů
                                                    :</strong
                                                >
                                                {{
                                                    channelData.max_ca_module_channels
                                                }}
                                            </span>
                                            <v-row class="ml-1">
                                                <span
                                                    v-show="
                                                        channelData.ca_modul !=
                                                            'false'
                                                    "
                                                    class="ma-3"
                                                >
                                                    <strong
                                                        >Číslo CA modulu
                                                        :</strong
                                                    >
                                                    {{
                                                        channelData.ca_modul_number
                                                    }}
                                                </span>
                                            </v-row>
                                        </v-row>

                                        <!-- konec CA modulu -->
                                    </v-card-text>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>

                        <!-- TAG FTE -->
                        <v-card
                            v-if="prijem.tag === 'FTE'"
                            class="ma-3"
                            color="grey lighten-4"
                            outlined
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-toolbar
                                        dense
                                        flat
                                        height="10"
                                        color="transparent"
                                    >
                                        <v-spacer></v-spacer>
                                        <div
                                            class="text-center d-flex align-center"
                                        >
                                            <span>
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-show="
                                                                userData.role ===
                                                                    '1' ||
                                                                    userData.role ===
                                                                        '2'
                                                            "
                                                            icon
                                                            x-small
                                                            v-on="on"
                                                            @click="
                                                                deletePrijem()
                                                            "
                                                        >
                                                            <v-icon color="red"
                                                                >mdi-delete</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>
                                                    <span
                                                        class="font-weight-medium"
                                                        >Odebrat Přijímač</span
                                                    >
                                                </v-tooltip>
                                            </span>
                                        </div>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Příjem kanálu :</strong>
                                                <v-btn
                                                    v-bind:to="
                                                        '/device/' + prijem.id
                                                    "
                                                    target="_blank"
                                                    dark
                                                    text
                                                >
                                                    <span class="blue--text">{{
                                                        prijem.nazev
                                                    }}</span>
                                                </v-btn>
                                            </span>
                                        </v-row>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>IP FTE :</strong>
                                                {{ prijem.ip }}
                                            </span>
                                        </v-row>

                                        <!-- CA modul -->
                                        <v-row>
                                            <span
                                                v-show="
                                                    channelData.ca_modul !=
                                                        'false'
                                                "
                                                class="ma-3"
                                            >
                                                <strong>Typ CA Moduli :</strong>
                                                {{ channelData.ca_modul }}
                                            </span>
                                            <span
                                                v-show="
                                                    channelData.ca_modul !=
                                                        'false'
                                                "
                                                class="ma-3"
                                            >
                                                <strong
                                                    >Maximální počet kanálů
                                                    :</strong
                                                >
                                                {{
                                                    channelData.max_ca_module_channels
                                                }}
                                            </span>
                                            <span
                                                v-show="
                                                    channelData.ca_modul !=
                                                        'false'
                                                "
                                                class="ma-3"
                                            >
                                                <strong
                                                    >Číslo CA modulu :</strong
                                                >
                                                {{
                                                    channelData.ca_modul_number
                                                }}
                                            </span>
                                        </v-row>
                                        <!-- konec CA modulu -->
                                    </v-card-text>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>

                        <!-- tag PowerVu -->
                        <v-card
                            v-if="prijem.tag === 'PowerVu'"
                            class="ma-3"
                            color="grey lighten-4"
                            outlined
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-toolbar
                                        dense
                                        flat
                                        height="10"
                                        color="transparent"
                                    >
                                        <v-spacer></v-spacer>
                                        <div
                                            class="text-center d-flex align-center"
                                        >
                                            <span>
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-show="
                                                                userData.role ===
                                                                    '1' ||
                                                                    userData.role ===
                                                                        '2'
                                                            "
                                                            icon
                                                            x-small
                                                            v-on="on"
                                                            @click="
                                                                deletePrijem()
                                                            "
                                                        >
                                                            <v-icon color="red"
                                                                >mdi-delete</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>
                                                    <span
                                                        class="font-weight-medium"
                                                        >Odebrat Přijímač</span
                                                    >
                                                </v-tooltip>
                                            </span>
                                        </div>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Příjem kanálu :</strong>
                                                <v-btn
                                                    v-bind:to="
                                                        '/device/' + prijem.id
                                                    "
                                                    target="_blank"
                                                    dark
                                                    text
                                                >
                                                    <span class="blue--text">{{
                                                        prijem.nazev
                                                    }}</span>
                                                </v-btn>
                                            </span>
                                        </v-row>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>IP :</strong>
                                                <a
                                                    :href="
                                                        'http://' + prijem.ip
                                                    "
                                                    target="_blank"
                                                    >{{ prijem.ip }}</a
                                                >
                                            </span>
                                            <span class="ma-3">
                                                <strong>Přihlášení :</strong>
                                                {{ prijem.login }}
                                            </span>
                                        </v-row>
                                    </v-card-text>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>

                        <!-- tag Linux -->

                        <v-card
                            v-if="prijem.tag === 'Linux'"
                            class="ma-3"
                            color="grey lighten-4"
                            outlined
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-toolbar
                                        dense
                                        flat
                                        height="10"
                                        color="transparent"
                                    >
                                        <v-spacer></v-spacer>
                                        <div
                                            class="text-center d-flex align-center"
                                        >
                                            <span>
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-show="
                                                                userData.role ===
                                                                    '1' ||
                                                                    userData.role ===
                                                                        '2'
                                                            "
                                                            icon
                                                            x-small
                                                            v-on="on"
                                                            @click="
                                                                deletePrijem()
                                                            "
                                                        >
                                                            <v-icon color="red"
                                                                >mdi-delete</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>
                                                    <span
                                                        class="font-weight-medium"
                                                        >Odebrat Přijímač</span
                                                    >
                                                </v-tooltip>
                                            </span>
                                        </div>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>Příjem kanálu :</strong>
                                                <v-btn
                                                    v-bind:to="
                                                        '/device/' + prijem.id
                                                    "
                                                    target="_blank"
                                                    dark
                                                    text
                                                >
                                                    <span class="blue--text">{{
                                                        prijem.nazev
                                                    }}</span>
                                                </v-btn>
                                            </span>
                                        </v-row>
                                        <v-row>
                                            <span class="ma-3">
                                                <strong>IP :</strong>
                                                <a
                                                    :href="
                                                        'ssh:' +
                                                            prijem.ssh_user +
                                                            '@' +
                                                            prijem.ip
                                                    "
                                                    target="_blank"
                                                    >{{ prijem.ip }}</a
                                                >
                                            </span>
                                            <span class="ma-3">
                                                <strong>Poznámka :</strong>
                                                {{ prijem.poznamka }}
                                            </span>
                                            <span class="ma-3">
                                                <strong>
                                                    Příkaz pro spuštění:
                                                </strong>
                                                {{ channelData.pathToReboot }}
                                            </span>
                                        </v-row>
                                    </v-card-text>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>
                        <!-- end devices -->
                    </div>

                    <div v-else>
                        <v-alert type="warning">
                            <strong>Není evidován přijímač</strong>
                        </v-alert>
                    </div>

                    <!-- backup prijem -->
                    <div
                        v-for="backup in channelData.backup"
                        v-bind:key="backup.tag"
                    >
                        <div v-if="backup.tag != 'KO'">
                            <!--  tag PoIP -->
                            <v-card
                                v-if="backup.tag === 'PoIP'"
                                class="ma-3"
                                color="grey lighten-4"
                                outlined
                            >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-toolbar
                                            dense
                                            flat
                                            height="10"
                                            color="transparent"
                                        >
                                            <v-spacer></v-spacer>
                                            <div
                                                class="text-center d-flex align-center"
                                            >
                                                <span>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{
                                                                on
                                                            }"
                                                        >
                                                            <v-btn
                                                                v-show="
                                                                    userData.role ===
                                                                        '1' ||
                                                                        userData.role ===
                                                                            '2'
                                                                "
                                                                icon
                                                                x-small
                                                                v-on="on"
                                                                @click="
                                                                    deleteBackupPrijem()
                                                                "
                                                            >
                                                                <v-icon
                                                                    color="red"
                                                                    >mdi-delete</v-icon
                                                                >
                                                            </v-btn>
                                                        </template>
                                                        <span
                                                            class="font-weight-medium"
                                                            >Odebrat
                                                            Přijímač</span
                                                        >
                                                    </v-tooltip>
                                                </span>
                                            </div>
                                        </v-toolbar>
                                        <v-card-text>
                                            <span class="ma-3">
                                                <strong>Backup kanálu :</strong>
                                                {{ backup.prijem }}
                                            </span>
                                        </v-card-text>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-card>

                            <!-- tag Blankom -->
                            <v-card
                                v-if="backup.tag === 'Blankom'"
                                class="ma-3"
                                color="grey lighten-4"
                                outlined
                            >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-toolbar
                                            dense
                                            flat
                                            height="10"
                                            color="transparent"
                                        >
                                            <v-spacer></v-spacer>
                                            <div
                                                class="text-center d-flex align-center"
                                            >
                                                <span>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{
                                                                on
                                                            }"
                                                        >
                                                            <v-btn
                                                                v-show="
                                                                    userData.role ===
                                                                        '1' ||
                                                                        userData.role ===
                                                                            '2'
                                                                "
                                                                icon
                                                                x-small
                                                                v-on="on"
                                                                @click="
                                                                    deleteBackupPrijem()
                                                                "
                                                            >
                                                                <v-icon
                                                                    color="red"
                                                                    >mdi-delete</v-icon
                                                                >
                                                            </v-btn>
                                                        </template>
                                                        <span
                                                            class="font-weight-medium"
                                                            >Odebrat
                                                            Přijímač</span
                                                        >
                                                    </v-tooltip>
                                                </span>
                                            </div>
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong
                                                        >Backup kanálu :</strong
                                                    >
                                                    <v-btn
                                                        v-bind:to="
                                                            '/device/' +
                                                                backup.id
                                                        "
                                                        target="_blank"
                                                        dark
                                                        text
                                                    >
                                                        <span
                                                            class="blue--text"
                                                            >{{
                                                                backup.nazev
                                                            }}</span
                                                        >
                                                    </v-btn>
                                                </span>
                                            </v-row>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong>RF :</strong>
                                                    <span
                                                        v-for="rf in channelData.rf"
                                                        v-bind:key="rf.id"
                                                        >{{ rf }}</span
                                                    >
                                                </span>
                                                <span class="ma-3">
                                                    <strong
                                                        >Nastavení Blankomu
                                                        :</strong
                                                    >
                                                    <a
                                                        :href="
                                                            'http://' +
                                                                backup.ip
                                                        "
                                                        target="_blank"
                                                        >{{ backup.ip }}</a
                                                    >
                                                </span>
                                                <span class="ma-3">
                                                    <strong
                                                        >Přihlášení :</strong
                                                    >
                                                    {{ backup.login }}
                                                </span>
                                            </v-row>
                                        </v-card-text>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-card>

                            <!-- TAG FTE -->
                            <v-card
                                v-if="backup.tag === 'FTE'"
                                class="ma-3"
                                color="grey lighten-4"
                                outlined
                            >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-toolbar
                                            dense
                                            flat
                                            height="10"
                                            color="transparent"
                                        >
                                            <v-spacer></v-spacer>
                                            <div
                                                class="text-center d-flex align-center"
                                            >
                                                <span>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{
                                                                on
                                                            }"
                                                        >
                                                            <v-btn
                                                                v-show="
                                                                    userData.role ===
                                                                        '1' ||
                                                                        userData.role ===
                                                                            '2'
                                                                "
                                                                icon
                                                                x-small
                                                                v-on="on"
                                                                @click="
                                                                    deleteBackupPrijem()
                                                                "
                                                            >
                                                                <v-icon
                                                                    color="red"
                                                                    >mdi-delete</v-icon
                                                                >
                                                            </v-btn>
                                                        </template>
                                                        <span
                                                            class="font-weight-medium"
                                                            >Odebrat
                                                            Přijímač</span
                                                        >
                                                    </v-tooltip>
                                                </span>
                                            </div>
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong
                                                        >Backup kanálu :</strong
                                                    >
                                                    <v-btn
                                                        v-bind:to="
                                                            '/device/' +
                                                                backup.id
                                                        "
                                                        target="_blank"
                                                        dark
                                                        text
                                                    >
                                                        <span
                                                            class="blue--text"
                                                            >{{
                                                                backup.nazev
                                                            }}</span
                                                        >
                                                    </v-btn>
                                                </span>
                                            </v-row>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong>IP FTE :</strong>
                                                    {{ backup.ip }}
                                                </span>
                                            </v-row>
                                        </v-card-text>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-card>

                            <!-- tag PowerVu -->
                            <v-card
                                v-if="backup.tag === 'PowerVu'"
                                class="ma-3"
                                color="grey lighten-4"
                                outlined
                            >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-toolbar
                                            dense
                                            flat
                                            height="10"
                                            color="transparent"
                                        >
                                            <v-spacer></v-spacer>
                                            <div
                                                class="text-center d-flex align-center"
                                            >
                                                <span>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{
                                                                on
                                                            }"
                                                        >
                                                            <v-btn
                                                                v-show="
                                                                    userData.role ===
                                                                        '1' ||
                                                                        userData.role ===
                                                                            '2'
                                                                "
                                                                icon
                                                                x-small
                                                                v-on="on"
                                                                @click="
                                                                    deleteBackupPrijem()
                                                                "
                                                            >
                                                                <v-icon
                                                                    color="red"
                                                                    >mdi-delete</v-icon
                                                                >
                                                            </v-btn>
                                                        </template>
                                                        <span
                                                            class="font-weight-medium"
                                                            >Odebrat
                                                            Přijímač</span
                                                        >
                                                    </v-tooltip>
                                                </span>
                                            </div>
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong
                                                        >Backup kanálu :</strong
                                                    >
                                                    <v-btn
                                                        v-bind:to="
                                                            '/device/' +
                                                                backup.id
                                                        "
                                                        target="_blank"
                                                        dark
                                                        text
                                                    >
                                                        <span
                                                            class="blue--text"
                                                            >{{
                                                                prijem.nazev
                                                            }}</span
                                                        >
                                                    </v-btn>
                                                </span>
                                            </v-row>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong>IP :</strong>
                                                    <a
                                                        :href="
                                                            'http://' +
                                                                backup.ip
                                                        "
                                                        target="_blank"
                                                        >{{ backup.ip }}</a
                                                    >
                                                </span>
                                                <span class="ma-3">
                                                    <strong
                                                        >Přihlášení :</strong
                                                    >
                                                    {{ backup.login }}
                                                </span>
                                            </v-row>
                                        </v-card-text>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-card>

                            <!-- tag Linux -->

                            <v-card
                                v-if="backup.tag === 'Linux'"
                                class="ma-3"
                                color="grey lighten-4"
                                outlined
                            >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-toolbar
                                            dense
                                            flat
                                            height="10"
                                            color="transparent"
                                        >
                                            <v-spacer></v-spacer>
                                            <div
                                                class="text-center d-flex align-center"
                                            >
                                                <span>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{
                                                                on
                                                            }"
                                                        >
                                                            <v-btn
                                                                v-show="
                                                                    userData.role ===
                                                                        '1' ||
                                                                        userData.role ===
                                                                            '2'
                                                                "
                                                                icon
                                                                x-small
                                                                v-on="on"
                                                                @click="
                                                                    deleteBackupPrijem()
                                                                "
                                                            >
                                                                <v-icon
                                                                    color="red"
                                                                    >mdi-delete</v-icon
                                                                >
                                                            </v-btn>
                                                        </template>
                                                        <span
                                                            class="font-weight-medium"
                                                            >Odebrat
                                                            Přijímač</span
                                                        >
                                                    </v-tooltip>
                                                </span>
                                            </div>
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong
                                                        >Backup kanálu :</strong
                                                    >
                                                    <v-btn
                                                        v-bind:to="
                                                            '/device/' +
                                                                backup.id
                                                        "
                                                        target="_blank"
                                                        dark
                                                        text
                                                    >
                                                        <span
                                                            class="blue--text"
                                                            >{{
                                                                backup.nazev
                                                            }}</span
                                                        >
                                                    </v-btn>
                                                </span>
                                            </v-row>
                                            <v-row>
                                                <span class="ma-3">
                                                    <strong>IP :</strong>
                                                    <a
                                                        :href="
                                                            'ssh:' +
                                                                backup.ssh_user +
                                                                '@' +
                                                                backup.ip
                                                        "
                                                        target="_blank"
                                                        >{{ backup.ip }}</a
                                                    >
                                                </span>
                                                <span class="ma-3">
                                                    <strong>Poznámka :</strong>
                                                    {{ backup.poznamka }}
                                                </span>
                                            </v-row>
                                        </v-card-text>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-card>
                            <!-- end devices -->
                        </div>

                        <div v-else>
                            <v-alert type="warning" width="100%">
                                <strong>Není evidován backup</strong>
                            </v-alert>
                        </div>

                        <!-- end backup prijem -->
                    </div>

                    <!-- modal multiplexer -->

                    <v-row justify="center">
                        <v-dialog
                            v-model="editMultiplexer"
                            persistent
                            max-height="200px"
                            max-width="500px"
                        >
                            <v-card>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="multiplexerId"
                                                    :items="multiplexers"
                                                    item-value="id"
                                                    item-text="name"
                                                    label="multiplexer"
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
                                        @click="editMultiplexer = false"
                                        >Zavřít</v-btn
                                    >
                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="saveMultiplexer()"
                                        >Uložit</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <!-- end modal multiplexer -->

                    <!-- modal pro editaci programových balícku / pridani programovaneho balicku ke kanalu -->

                    <v-row justify="center">
                        <v-dialog
                            v-model="editIPTVpackage"
                            persistent
                            max-height="200px"
                            max-width="500px"
                        >
                            <v-card>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="iptvPackageName"
                                                    :items="iptvPackage"
                                                    item-value="nazev"
                                                    item-text="nazev"
                                                    label="Programový balíček"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row
                                            v-if="
                                                channelData.balicek != 'false'
                                            "
                                        >
                                            <v-checkbox
                                                v-model="addIPTVPackage"
                                                label="Přidat k již existujícímu"
                                            ></v-checkbox>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="editIPTVpackage = false"
                                        >Zavřít</v-btn
                                    >
                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="saveIPTVPackage()"
                                        >Uložit</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <!-- end programovy balicek modal -->

                    <!-- modal prijem -->

                    <v-row justify="center">
                        <v-dialog
                            v-model="addSatelit"
                            persistent
                            max-height="200px"
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="kategorieId"
                                                    :items="prijemKategorie"
                                                    item-value="id"
                                                    item-text="kategorie"
                                                    label="kategorie"
                                                ></v-select>
                                            </v-col>
                                        </v-row>

                                        <!-- CA modul -->
                                        <v-row v-if="kategorieId == '2'">
                                            <v-col cols="6" sm="6" md="4">
                                                <v-select
                                                    v-model="camodulId"
                                                    :items="camodul"
                                                    item-value="id"
                                                    item-text="ca_modul"
                                                    label="CA Modul"
                                                ></v-select>
                                            </v-col>
                                            <v-col
                                                v-show="camodulId != ''"
                                                cols="6"
                                                sm="6"
                                                md="6"
                                            >
                                                <v-select
                                                    v-model="camodulChannelId"
                                                    :items="camodulChannel"
                                                    item-value="id"
                                                    item-text="pocet_podporovanych_kanalu"
                                                    label="Počet kanálů, které CA Modul podporuje"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row v-if="camodulChannelId != ''">
                                            <v-col cols="12" sm="3" md="8">
                                                <v-text-field
                                                    type="number"
                                                    label="Číslo CA modulu"
                                                    v-model="camodulNumber"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <!-- konec CA modulu -->

                                        <v-row v-if="kategorieId == '5'">
                                            <v-col cols="12" sm="6" md="8">
                                                <v-text-field
                                                    v-model="linuxPath"
                                                    label="Příkaz pro spuštění kanálu"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row v-if="devices != ''">
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="deviceId"
                                                    :items="devices"
                                                    item-value="id"
                                                    item-text="name"
                                                    label="Zařízení"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row
                                            v-if="rf != '' && rf != 'submit'"
                                        >
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="rfId"
                                                    :items="rf"
                                                    item-value="id"
                                                    item-text="rf"
                                                    label="RF na Blankomu"
                                                ></v-select>
                                            </v-col>
                                        </v-row>

                                        <v-row v-if="rfId != ''">
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="ci"
                                                    :items="cis"
                                                    label="CI"
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
                                        @click="closeSatelit()"
                                        >Zavřít</v-btn
                                    >
                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="savePrijem()"
                                        >Uložit</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <!-- end modal prijem -->

                    <!-- modal backup prijem -->

                    <v-row justify="center">
                        <v-dialog
                            v-model="addBackupSatelit"
                            persistent
                            max-height="200px"
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="kategorieId"
                                                    :items="prijemKategorie"
                                                    item-value="id"
                                                    item-text="kategorie"
                                                    label="kategorie"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <!-- CA modul -->
                                        <v-row v-if="kategorieId == '2'">
                                            <v-col cols="6" sm="6" md="4">
                                                <v-select
                                                    v-model="camodulId"
                                                    :items="camodul"
                                                    item-value="id"
                                                    item-text="ca_modul"
                                                    label="CA Modul"
                                                ></v-select>
                                            </v-col>
                                            <v-col
                                                v-show="camodulId != ''"
                                                cols="6"
                                                sm="6"
                                                md="6"
                                            >
                                                <v-select
                                                    v-model="camodulChannelId"
                                                    :items="camodulChannel"
                                                    item-value="id"
                                                    item-text="pocet_podporovanych_kanalu"
                                                    label="Počet kanálů, které CA Modul podporuje"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row v-if="camodulChannelId != ''">
                                            <v-col cols="12" sm="3" md="8">
                                                <v-text-field
                                                    type="number"
                                                    label="Číslo CA modulu"
                                                    v-model="camodulNumber"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <!-- konec CA modulu -->
                                        <v-row v-if="kategorieId == '5'">
                                            <v-col cols="12" sm="6" md="8">
                                                <v-text-field
                                                    v-model="linuxPath"
                                                    label="Příkaz pro spuštění kanálu"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row v-if="devices != ''">
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="deviceId"
                                                    :items="devices"
                                                    item-value="id"
                                                    item-text="name"
                                                    label="Zařízení"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row
                                            v-if="rf != '' && rf != 'submit'"
                                        >
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="rfId"
                                                    :items="rf"
                                                    item-value="id"
                                                    item-text="rf"
                                                    label="RF na Blankomu"
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-row v-if="rfId != ''">
                                            <v-col cols="12" sm="6" md="8">
                                                <v-select
                                                    v-model="ci"
                                                    :items="cis"
                                                    label="CI"
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
                                        @click="closeBackupSatelit()"
                                        >Zavřít</v-btn
                                    >
                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="saveBackupPrijem()"
                                        >Uložit</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <!-- end modal backup prijem -->

                    <!-- Vytvoření nové události u kanálu -->

                    <v-row justify="center">
                        <v-dialog
                            v-model="newEventDialog"
                            persistent
                            max-height="200px"
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-title>
                                    Vytvoření nové události
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :disabled="
                                                        repeatEveryDay == true
                                                    "
                                                    :readonly="
                                                        repeatEveryDay == true
                                                    "
                                                    label="Datum začátku výpadku"
                                                    v-model="start_day"
                                                    type="date"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    label="Hodiny začátku výpadku"
                                                    v-model="start_time"
                                                    type="time"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :disabled="
                                                        repeatEveryDay == true
                                                    "
                                                    :readonly="
                                                        repeatEveryDay == true
                                                    "
                                                    label="Datum konce výpadku"
                                                    v-model="end_day"
                                                    type="date"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    label="Hodiny konce výpadku"
                                                    v-model="end_time"
                                                    type="time"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-textarea
                                                    v-model="eventPopis"
                                                    label="Popis výpadku"
                                                >
                                                </v-textarea>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-checkbox
                                                    v-model="repeatEveryDay"
                                                    label="Opakovat každý den?"
                                                ></v-checkbox>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="closeEventDialog()"
                                        >Zavřít</v-btn
                                    >
                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="saveNewEvent()"
                                        >Uložit</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <!-- konec modal pro založení kanálu do dohledu -->

                    <!-- odebrání události -->

                    <v-row justify="center">
                        <v-dialog
                            v-model="deleteEventDialog"
                            persistent
                            max-height="200px"
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-text>
                                    <v-container class="pt-12">
                                        <!-- obsah -->
                                        <h2>
                                            Vážně si přejete odebrat událost?
                                        </h2>

                                        <!-- konec obsahu odebrání události -->
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="closeEventDialog()"
                                        >Zavřít</v-btn
                                    >
                                    <v-btn
                                        color="red darken-1"
                                        text
                                        @click="deleteEvent()"
                                        >Odebrat</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>

                    <!-- konec dialogu -->
                </div>
            </v-col>
        </v-row>
    </div>
</template>
<script>
export default {
    data() {
        return {
            startTime: null,
            endTime: null,
            eventId: null,
            deleteEventDialog: false,
            expanded: [],
            singleExpand: false,
            eventHeaders: [
                {
                    text: "Plánovaný výpadek od dne",
                    align: "start",
                    value: "start_day"
                },
                { text: "Hodin", value: "start_time" },
                { text: "Plánovaný výpadek do dne", value: "end_day" },
                { text: "Hodin", value: "end_time" },
                { text: "", value: "data-table-expand" },
                { text: "", value: "akce" }
            ],

            repeatEveryDay: false,
            end_day: "",
            end_time: "",
            start_day: "",
            start_time: "",
            newEventDialog: false,
            event: [],
            eventPopis: "",
            linuxPath: "",
            vytvoritNahled: true,
            channelHistory: [],
            sendSMS: false,
            sendMailAlert: true,
            dohledBitrate: true,
            dohledovatStream: true,
            dohledVolume: true,
            cis: ["1", "2", "3", "4"],
            ci: "",
            camodulNumber: "",
            camodulChannel: [],
            camodulChannelId: "",
            camodulId: "",
            camodul: [],
            image: "",
            isp: "",
            isps: "",
            iptvPackageName: "",
            multiplexers: "",
            editChannelName: false,
            editMulticastIp: false,
            editChannelISP: false,
            editStbIp: false,
            editMultiplexer: false,
            addMultiplexer: false,
            addSatelit: false,
            editMulticastInput: false,
            deleteChannelISP: false,
            modalProNovyMulticast: false,
            addBackupSatelit: false,
            editIPTVpackage: false,
            editChannelNote: false,
            channelNote: "",
            deleted: "",
            multiplexer: "",
            channelData: "",
            multicastIp: "",
            multiplexerId: "",
            stbIp: "",
            prijemKategorie: [],
            kategorieId: "",
            devices: [],
            status: "",
            deviceId: "",
            rf: [],
            rfId: "",
            channelName: "",
            channelIsp: "",
            channelMulticastId: "",
            multicastIp: "",
            stbIp: "",
            newStbIp: "",
            newMulticastIp: "",
            ispMulticastId: "",
            priority: "",
            userData: "",
            history: "",
            iptvPackage: [],
            addIPTVPackage: false,
            dohledUrl: "",
            dohledDialog: false,
            dohledData: false,
            volumeChart: false,
            birateChart: false,
            interval: null
        };
    },

    created() {
        this.loadEventData();
        try {
            this.loadMulticastData();
        } catch (error) {
            console.log("nepodařilo se načíst multicast");
        }
        axios
            .get("/api/getUser")
            .then(response => (this.userData = response.data));
        let currentObj = this;

        axios
            .get("/api/isps/get")
            .then(response => (this.isps = response.data))
            .catch(function(error) {
                console.log("chyba" + error);
            });

        axios
            .get("/api/device/multiplexers")
            .then(response => (this.multiplexers = response.data))
            .catch(function(error) {
                console.log("chyba" + error);
            });

        axios
            .get("/api/device/multiplexers")
            .then(response => (this.multiplexers = response.data))
            .catch(function(error) {
                console.log("chyba" + error);
            });
    },
    methods: {
        deleteEvent() {
            let currentObj = this;
            axios.post("channel/event/delete", {
                channelId: this.$route.params.id,
                startTime: this.startTime,
                endTime: this.endTime,
                eventId: this.eventId
            })
            .then(function(response) {
                currentObj.status = response.data;
                currentObj.deleteEventDialog = false,
                currentObj.loadEventData();
            })
        },
        openDeleteDalog() {
            this.deleteEventDialog = true;
        },

        openDialogNewEvent() {
            this.newEventDialog = true;
        },
        closeEventDialog() {
            this.newEventDialog = false;
            this.deleteEventDialog = false;
            this.repeatEveryDay = false;
            this.end_day = "";
            this.end_time = "";
            this.start_day = "";
            this.start_time = "";
            this.eventPopis = "";
        },
        saveNewEvent() {
            let currentObj = this;
            axios
                .post("channel/newEvent", {
                    channelId: this.$route.params.id,
                    repeatEveryDay: this.repeatEveryDay,
                    end_day: this.end_day,
                    end_time: this.end_time,
                    start_day: this.start_day,
                    start_time: this.start_time,
                    eventPopis: this.eventPopis
                })
                .then(function(response) {
                    currentObj.status = response.data;
                    currentObj.loadEventData();
                    currentObj.closeEventDialog();
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },
        loadEventData() {
            let currentObj = this;
            axios
                .post("/api/channel/get/event", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    if (response.data.status == "empty") {
                        currentObj.event = "empty";
                    } else {
                        currentObj.event = response.data;
                    }
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        loadMulticastData() {
            let currentObj = this;
            axios
                .post("/api/channel/get/multicast", {
                    id: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.channelData = response.data;
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        saveStoreChannelToDohled() {
            let currentObj = this;
            axios
                .post("/api/storeChannelToDohled", {
                    id: this.$route.params.id,
                    url: this.channelData.dohledUrl,
                    nazev: this.channelData.nazev,
                    vytvoritNahled: this.vytvoritNahled,
                    sendSMS: this.sendSMS,
                    sendMailAlert: this.sendMailAlert,
                    dohledBitrate: this.dohledBitrate,
                    dohledovatStream: this.dohledovatStream,
                    dohledVolume: this.dohledVolume
                })
                .then(function(response) {
                    currentObj.status = response.data;
                    currentObj.dohledDialog = false;
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },
        loadBitrateChartFromDohled() {
            let currentObj = this;
            axios
                .post("/api/getBitrateDataFromDohledForChart", {
                    id: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.birateChart = response.data;
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        closeDohledDialog() {
            this.dohledDialog = false;
        },
        openDialogForCreateDohledChannel() {
            this.dohledDialog = true;
        },

        saveChannelNote() {
            let currentObj = this;
            axios
                .post("/api/channel/note/edit", {
                    channelId: this.$route.params.id,
                    channelNote: this.channelNote
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    currentObj.editChannelNote = false;

                    axios
                        .post("/api/channel/get/multicast", {
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
        //   zavreni modal okna pro pridani prijimace a smazani vsech hodnot
        closeSatelit() {
            let currentObj = this;
            (currentObj.camodulNumber = ""),
                (currentObj.camodul = []),
                (currentObj.camodulId = ""),
                (currentObj.camodulChannelId = ""),
                (currentObj.camodulChannel = []),
                (currentObj.prijemKategorie = []),
                (currentObj.kategorieId = ""),
                (currentObj.devices = []),
                (currentObj.deviceId = ""),
                (currentObj.rf = []),
                (currentObj.rfId = "");
            currentObj.addSatelit = false;
        },
        closeBackupSatelit() {
            let currentObj = this;
            (currentObj.camodulNumber = ""),
                (currentObj.camodul = []),
                (currentObj.camodulId = ""),
                (currentObj.camodulChannelId = ""),
                (currentObj.camodulChannel = []),
                (currentObj.prijemKategorie = []),
                (currentObj.kategorieId = ""),
                (currentObj.devices = []),
                (currentObj.deviceId = ""),
                (currentObj.rf = []),
                (currentObj.rfId = "");
            currentObj.addBackupSatelit = false;
        },

        // fn pro získání iptvBalicku
        getIptvPackages() {
            let currentObj = this;

            axios
                .get("/api/iptvpackage/getAll")
                .then(
                    response => (
                        (this.iptvPackage = response.data),
                        (currentObj.editIPTVpackage = true)
                    )
                )
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        saveIPTVPackage() {
            let currentObj = this;
            axios
                .post("/api/channel/save/iptvPackage", {
                    channelId: this.$route.params.id,
                    iptvPackageName: this.iptvPackageName,
                    addIPTVPackage: this.addIPTVPackage
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    currentObj.editIPTVpackage = false;
                    currentObj.addIPTVPackage = false;

                    axios
                        .post("/api/channel/get/multicast", {
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

        packageDelete() {
            let currentObj = this;
            axios
                .post("/api/channel/package/delete", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    axios
                        .post("/api/channel/get/multicast", {
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

        // fn pro editace zdroju multicastu (ISP, multicast k STB, multicast od Prijmu)
        channelIspEdit() {
            let currentObj = this;
            axios
                .post("/api/channel/isp/edit", {
                    channelId: this.$route.params.id,
                    channelIsp: this.channelIsp,
                    channelMulticastId: this.channelMulticastId,
                    multicastIp: this.multicastIp,
                    stbIp: this.stbIp,
                    ispMulticastId: this.ispMulticastId,
                    isp: this.isp,
                    dohledUrl: this.dohledUrl
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    currentObj.editChannelISP = false;

                    axios
                        .post("/api/channel/get/multicast", {
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
        addNewMulticast() {
            let currentObj = this;
            axios
                .post("/api/channel/isp/add", {
                    channelId: this.$route.params.id,
                    newMulticastIp: this.newMulticastIp,
                    isp: this.isp,
                    priority: this.priority
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    currentObj.modalProNovyMulticast = false;

                    axios
                        .post("/api/channel/get/multicast", {
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
        deleteMulticastdata() {
            let currentObj = this;

            axios
                .post("/api/channel/isp/delete", {
                    channelId: this.$route.params.id,
                    channelMulticastId: this.channelMulticastId
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    currentObj.deleteChannelISP = false;

                    axios
                        .post("/api/channel/get/multicast", {
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

        takeDataForPrijem() {
            let currentObj = this;
            axios
                .get("/api/prijem/get")
                .then(
                    response => (
                        (this.prijemKategorie = response.data),
                        (currentObj.addSatelit = true)
                    )
                )
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        takeDataForBackupPrijem() {
            let currentObj = this;
            axios
                .get("/api/prijem/get")
                .then(
                    response => (
                        (this.prijemKategorie = response.data),
                        (currentObj.addBackupSatelit = true)
                    )
                )
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        channelNameEdit() {
            let currentObj = this;
            currentObj.editChannelName = false;

            axios
                .post("/api/channel/name/edit", {
                    channelId: this.$route.params.id,
                    channelName: this.channelName
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    axios
                        .post("/api/channel/get/multicast", {
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

        saveMultiplexer() {
            let currentObj = this;
            currentObj.editMultiplexer = false;
            axios
                .post("/api/channel/multiplexer/set", {
                    multiplexerId: this.multiplexerId,
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    axios
                        .post("/api/channel/get/multicast", {
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
        deleteMultiplexer() {
            let currentObj = this;
            currentObj.editMultiplexer = false;
            axios
                .post("/api/channel/multiplexer/delete", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    axios
                        .post("/api/channel/get/multicast", {
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
        saveBackupPrijem() {
            let currentObj = this;
            currentObj.addBackupSatelit = false;
            axios
                .post("/api/channel/saveBackupPrijem", {
                    camodulNumber: this.camodulNumber,
                    camodulId: this.camodulId,
                    camodulChannelId: this.camodulChannelId,
                    channelId: this.$route.params.id,
                    kategorieId: this.kategorieId,
                    deviceId: this.deviceId,
                    rfId: this.rfId,
                    ci: this.ci,
                    linuxPath: this.linuxPath
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    (currentObj.camodulNumber = ""),
                        (currentObj.camodulId = ""),
                        (currentObj.camodulChannelId = ""),
                        (currentObj.deviceId = "");
                    currentObj.rfId = "";
                    currentObj.prijemKategorie = [];
                    currentObj.kategorieId = "";
                    currentObj.devices = [];
                    currentObj.deviceId = "";
                    currentObj.rf = [];
                    currentObj.ci = "";

                    axios
                        .post("/api/channel/get/multicast", {
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
        savePrijem() {
            let currentObj = this;
            currentObj.addSatelit = false;
            axios
                .post("/api/channel/savePrijem", {
                    camodulNumber: this.camodulNumber,
                    camodulId: this.camodulId,
                    camodulChannelId: this.camodulChannelId,
                    channelId: this.$route.params.id,
                    kategorieId: this.kategorieId,
                    deviceId: this.deviceId,
                    rfId: this.rfId,
                    ci: this.ci,
                    linuxPath: this.linuxPath
                })
                .then(function(response) {
                    console.log(response.data);
                    currentObj.status = response.data;

                    (currentObj.camodulNumber = ""),
                        (currentObj.camodulId = ""),
                        (currentObj.camodulChannelId = ""),
                        (currentObj.deviceId = "");
                    currentObj.rfId = "";
                    currentObj.prijemKategorie = [];
                    currentObj.kategorieId = "";
                    currentObj.devices = [];
                    currentObj.deviceId = "";
                    currentObj.rf = [];
                    currentObj.ci = "";

                    axios
                        .post("/api/channel/get/multicast", {
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
        deleteBackupPrijem() {
            let currentObj = this;
            axios
                .post("/api/channel/backupprijem/delete", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    axios
                        .post("/api/channel/get/multicast", {
                            id: currentObj.$route.params.id
                        })
                        .then(function(response) {
                            currentObj.channelData = response.data;
                            currentObj.addBackupSatelit = false;
                        })
                        .catch(function(error) {
                            console.log("chyba" + error);
                        });
                })
                .catch(function(error) {
                    currentObj.status = "err";
                });
        },
        deletePrijem() {
            let currentObj = this;
            axios
                .post("/api/channel/prijem/delete", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    axios
                        .post("/api/channel/get/multicast", {
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
        deleteChannel() {
            let currentObj = this;
            axios
                .post("/api/channel/delete", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.status = response.data;

                    if (currentObj.status.stat == "success") {
                        currentObj.deleted = "true";
                    }
                })
                .catch(function(error) {
                    currentObj.status = "err";
                });
        }
    },

    mounted() {},

    watch: {
        history: function() {
            if (this.history.length === 0) {
                this.history = "false";
            }
        },

        deleted: function() {
            if (this.deleted === "true") {
                this.deleted = "";
                setTimeout(
                    function() {
                        this.$router.push("/");
                    }.bind(this),
                    2000
                );
            }
        },

        status: function() {
            setTimeout(() => (this.status = false), 3000);
        },

        $route(to, from) {
            this.dohledData = false;
            this.volumeChart = false;

            this.birateChart = false;
            this.channelData = false;

            let currentObj = this;

            try {
                this.loadMulticastData();
                this.loadEventData();
            } catch (error) {
                currentObj.channelData = false;
                console.log("nepodařilo se načíst multicast");
            }
        },

        kategorieId: function() {
            if (this.kategorieId != "") {
                let currentObj = this;
                axios
                    .get("/api/devices", {
                        params: {
                            kategorieId: this.kategorieId
                        }
                    })
                    .then(function(response) {
                        currentObj.devices = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

                axios
                    .get("/api/ca_modul/get")
                    .then(function(response) {
                        currentObj.camodul = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },

        deviceId: function() {
            if (this.deviceId != "") {
                let currentObj = this;
                axios
                    .get("/api/rf/get", {
                        params: {
                            deviceId: this.deviceId
                        }
                    })
                    .then(function(response) {
                        currentObj.rf = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },

        camodulId: function() {
            if (this.camodulId != "") {
                let currentObj = this;
                axios
                    .get("/api/ca_modul/channels/get")
                    .then(function(response) {
                        currentObj.camodulChannel = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        }
    }
};
</script>
