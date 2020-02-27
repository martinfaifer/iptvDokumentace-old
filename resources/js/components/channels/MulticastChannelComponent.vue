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
    <v-toolbar v-if="userData.role === '1' || userData.role === '2'" dense flat color="transparent">
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
          <span class="font-weight-medium">Přidat multicastový příjem</span>
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
          <span class="font-weight-medium">Přidat / editovat přijímač</span>
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
              @click="editMultiplexer = true"
            >
              <v-icon>mdi-file-document-box-multiple</v-icon>
            </v-btn>
          </template>
          <span class="font-weight-medium">Přidat / editovat multiplexer</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn class="mr-3" color="primary" fab x-small dark v-on="on" @click="deleteChannel()">
              <v-icon color="red">mdi-delete</v-icon>
            </v-btn>
          </template>
          <span class="font-weight-medium">Odebrat kanál</span>
        </v-tooltip>
      </div>
    </v-toolbar>
    <v-card class="ma-3" width="1600" outlined>
      <v-list-item>
        <v-card-text>
          <span class="ma-3">{{channelData.nazev}}</span>
          <v-btn
            v-if="userData.role === '1' || userData.role === '2'"
            color="primary"
            x-small
            dark
            text
            @click="editChannelName = true, channelName = channelData.nazev"
          >
            <v-icon small>mdi-pencil</v-icon>
          </v-btn>
        </v-card-text>
      </v-list-item>
    </v-card>

    <!-- modal pro editaci Názvu kanálu -->

    <v-row justify="center">
      <v-dialog v-model="editChannelName" persistent max-height="200px" max-width="500px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="8">
                  <v-text-field required v-model="channelName"></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="editChannelName = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="channelNameEdit()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end modal pro editaci názvu kanálu -->

    <!-- modal pro zalozeni noveho multicastu -->

    <v-row justify="center">
      <v-dialog v-model="editChannelISP" persistent max-height="200px" max-width="500px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="3" md="8">
                  <v-select v-model="isp" :items="isps" item-value="id" item-text="isp" label="ISP"></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="3" md="8">
                  <v-text-field required v-model="multicastIp"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-checkbox v-model="priority" label="Zaškrtněte pokud se jedná o primární zdroj"></v-checkbox>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="editChannelISP = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="channelIspEdit()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end modal pro zalozeni noveho kanalu -->

    <!-- modal pro editaci zdroje ISP, Multicast ...  -->
    <v-row justify="center">
      <v-dialog v-model="modalProNovyMulticast" persistent max-height="200px" max-width="500px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="3" md="8">
                  <v-select v-model="isp" :items="isps" item-value="id" item-text="isp" label="ISP"></v-select>
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
                  <v-text-field required label="multicastová adresa k STB" v-model="newStbIp"></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="modalProNovyMulticast = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="addNewMulticast()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end modal pro editaci zdroje ISP, Multicast ...  -->

    <v-card class="ma-3" width="1600" outlined>
      <v-list-item>
        <v-list-item-content>
          <v-card-text v-for="multicast in  channelData.multicast" v-bind:key="multicast.id">
            <span class="ma-3">
              <strong>Kanál je od :</strong>
              <span>{{multicast.isp}}</span>
            </span>
            <span class="ma-3">
              <strong>Muticast IP :</strong>
              <span>{{multicast.ip}}</span>
            </span>
            <span class="ma-3">
              <strong>IP k STB :</strong>
              <span>{{channelData.ipKstb}}</span>
            </span>
            <v-btn
              v-if="userData.role === '1' || userData.role === '2'"
              color="primary"
              x-small
              dark
              text
              @click="editChannelISP = true, channelIsp = multicast.isp, channelMulticastId = multicast.id, multicastIp = multicast.ip, stbIp = channelData.ipKstb, ispMulticastId = multicast.ispId"
            >
              <v-icon small>mdi-pencil</v-icon>
            </v-btn>
            <v-btn
              v-if="userData.role === '1' || userData.role === '2'"
              color="primary"
              x-small
              dark
              text
              @click="deleteMulticastdata()"
            >
              <v-icon color="red" small>mdi-delete</v-icon>
            </v-btn>
          </v-card-text>

          <!-- modal edit channel -->
          <!-- modal delete multicast data -->
          <v-row justify="center">
            <v-dialog v-model="deleteChannelISP" persistent max-height="200px" max-width="500px">
              <v-card>
                <v-card-text>
                  <v-container>
                    <p>Smazat?</p>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    v-if="userData.role === '1' || userData.role === '2'"
                    color="red darken-1"
                    text
                    @click="deleteChannelISP = false"
                  >Zavřít</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>
          <!-- end modal delete mutlticast data -->

          <!-- modal edit channel ISP -->
          <v-row justify="center">
            <v-dialog v-model="editChannelISP" persistent max-height="200px" max-width="500px">
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
                        <v-text-field required v-model="multicastIp"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" sm="3" md="8">
                        <v-text-field required v-model="stbIp"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="editChannelISP = false">Zavřít</v-btn>
                  <v-btn color="green darken-1" text @click="channelIspEdit()">Uložit</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>

          <!-- end modal channel -->
        </v-list-item-content>
      </v-list-item>
    </v-card>

    <!-- multiplexer -->
    <div v-for="mux in channelData.multiplexer" v-bind:key="mux.id">
      <div v-if="mux.status != 'KO'">
        <v-card class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-toolbar dense flat height="10" color="transparent">
                <v-spacer></v-spacer>
                <div class="text-center d-flex align-center">
                  <span>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-if="userData.role === '1' || userData.role === '2'"
                          icon
                          x-small
                          v-on="on"
                          @click="deleteMultiplexer()"
                        >
                          <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span class="font-weight-medium">Odebrat multiplexer</span>
                    </v-tooltip>
                  </span>
                </div>
              </v-toolbar>
              <v-card-text>
                <span class="ma-3">
                  <strong>Multiplexer :</strong>
                  <span>{{mux.nazev}}</span>
                </span>
                <span class="ma-3">
                  <strong>IP multiplexeru :</strong>
                  <a :href="'http://'+mux.ip" target="_blank">{{mux.ip}}</a>
                </span>
                <span class="ma-3">
                  <strong>Přihlášení do multiplexeru :</strong>
                  {{mux.login}}
                </span>
                <span class="ma-3">
                  <strong>Poznámka :</strong>
                  {{mux.poznamka}}
                </span>
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

    <!-- přijímač -->
    <div v-for="prijem in channelData.prijem" v-bind:key="prijem.tag">
      <div v-if="prijem.tag != 'KO'">
        <!--  tag PoIP -->
        <v-card v-if="prijem.tag === 'PoIP'" class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-toolbar dense flat height="10" color="transparent">
                <v-spacer></v-spacer>
                <div class="text-center d-flex align-center">
                  <span>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-if="userData.role === '1' || userData.role === '2'"
                          icon
                          x-small
                          v-on="on"
                          @click="deletePrijem()"
                        >
                          <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span class="font-weight-medium">Odebrat Přijímač</span>
                    </v-tooltip>
                  </span>
                </div>
              </v-toolbar>
              <v-card-text>
                <span class="ma-3">
                  <strong>Příjem kanálu :</strong>
                  {{prijem.prijem}}
                </span>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>

        <!-- tag Blankom -->
        <v-card v-if="prijem.tag === 'Blankom'" class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-toolbar dense flat height="10" color="transparent">
                <v-spacer></v-spacer>
                <div class="text-center d-flex align-center">
                  <span>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-if="userData.role === '1' || userData.role === '2'"
                          icon
                          x-small
                          v-on="on"
                          @click="deletePrijem()"
                        >
                          <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span class="font-weight-medium">Odebrat Přijímač</span>
                    </v-tooltip>
                  </span>
                </div>
              </v-toolbar>
              <v-card-text>
                <span class="ma-3">
                  <strong>Příjem kanálu :</strong>
                  <v-btn v-bind:to="'/device/' + prijem.id " target="_blank" dark text>
                    <span class="blue--text">{{prijem.nazev}}</span>
                  </v-btn>
                </span>
                <span class="ma-3">
                  <strong>RF :</strong>
                  <span v-for="rf in channelData.rf" v-bind:key="rf.id">{{rf}}</span>
                </span>
                <span class="ma-3">
                  <strong>Nastavení Blankomu :</strong>
                  <a :href="'http://'+prijem.ip" target="_blank">{{prijem.ip}}</a>
                </span>
                <span class="ma-3">
                  <strong>Přihlášení :</strong>
                  {{prijem.login}}
                </span>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>

        <!-- TAG FTE -->
        <v-card v-if="prijem.tag === 'FTE'" class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-toolbar dense flat height="10" color="transparent">
                <v-spacer></v-spacer>
                <div class="text-center d-flex align-center">
                  <span>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-if="userData.role === '1' || userData.role === '2'"
                          icon
                          x-small
                          v-on="on"
                          @click="deletePrijem()"
                        >
                          <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span class="font-weight-medium">Odebrat Přijímač</span>
                    </v-tooltip>
                  </span>
                </div>
              </v-toolbar>
              <v-card-text>
                <span class="ma-3">
                  <strong>Příjem kanálu :</strong>
                  <v-btn v-bind:to="'/device/' + prijem.id " target="_blank" dark text>
                    <span class="blue--text">{{prijem.nazev}}</span>
                  </v-btn>
                </span>
                <span class="ma-3">
                  <strong>IP FTE :</strong>
                  {{prijem.ip}}
                </span>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>

        <!-- tag PowerVu -->
        <v-card v-if="prijem.tag === 'PowerVu'" class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-toolbar dense flat height="10" color="transparent">
                <v-spacer></v-spacer>
                <div class="text-center d-flex align-center">
                  <span>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-if="userData.role === '1' || userData.role === '2'"
                          icon
                          x-small
                          v-on="on"
                          @click="deletePrijem()"
                        >
                          <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span class="font-weight-medium">Odebrat Přijímač</span>
                    </v-tooltip>
                  </span>
                </div>
              </v-toolbar>
              <v-card-text>
                <span class="ma-3">
                  <strong>Příjem kanálu :</strong>
                  <v-btn v-bind:to="'/device/' + prijem.id " target="_blank" dark text>
                    <span class="blue--text">{{prijem.nazev}}</span>
                  </v-btn>
                </span>
                <span class="ma-3">
                  <strong>IP :</strong>
                  <a :href="'http://'+prijem.ip" target="_blank">{{prijem.ip}}</a>
                </span>
                <span class="ma-3">
                  <strong>Přihlášení :</strong>
                  {{prijem.login}}
                </span>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>

        <!-- tag Linux -->

        <v-card v-if="prijem.tag === 'Linux'" class="ma-3" width="1600" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-toolbar dense flat height="10" color="transparent">
                <v-spacer></v-spacer>
                <div class="text-center d-flex align-center">
                  <span>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-if="userData.role === '1' || userData.role === '2'"
                          icon
                          x-small
                          v-on="on"
                          @click="deletePrijem()"
                        >
                          <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                      </template>
                      <span class="font-weight-medium">Odebrat Přijímač</span>
                    </v-tooltip>
                  </span>
                </div>
              </v-toolbar>
              <v-card-text>
                <span class="ma-3">
                  <strong>Příjem kanálu :</strong>
                  <v-btn v-bind:to="'/device/' + prijem.id " target="_blank" dark text>
                    <span class="blue--text">{{prijem.nazev}}</span>
                  </v-btn>
                </span>
                <span class="ma-3">
                  <strong>IP :</strong>
                  <a :href="'ssh:'+ prijem.ssh_user + '@'+prijem.ip" target="_blank">{{prijem.ip}}</a>
                </span>
                <span class="ma-3">
                  <strong>Poznámka :</strong>
                  {{prijem.poznamka}}
                </span>
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

      <!-- náhled -->
      <v-row>
        <v-card v-if="channelData.img !== 'false'" class="ma-6" width="400" outlined>
          <v-list-item>
            <v-list-item-content>
              <div class="overline mb-4">Náhled</div>
              <v-card-text>
                <v-row>
                  <span width="350" height="200">
                    <v-img :src="'http://'+channelData.img" width="350" height="200"></v-img>
                  </span>
                </v-row>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        <v-card loading v-else class="ma-6" width="400" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-card-text>
                <p>Pokouším se získat náhled</p>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        <!-- konec náhledu -->
        <!-- historie -->
        <v-card v-if="history === 'false'" class="ma-6" width="900" height="300px" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-card-text>
                <v-row>
                  <p>Na kanálu nebyl za poslední měsíc žádný výpadek.</p>
                </v-row>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        <v-card v-else-if="history !== ''" class="ma-6" width="900" height="300px" outlined>
          <v-list-item>
            <v-list-item-content>
              <div class="overline mb-4">Náhled za poslední měsíc, kdy měl kanál výpadek</div>
              <v-card-text>
                <v-simple-table height="200px">
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-left">Status</th>
                        <th class="text-left">Čas</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="data in history" :key="data.id">
                        <td>{{ data.akce }}</td>
                        <td>{{ data.created_at }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        <v-card v-else loading class="ma-6" width="900" height="300px" outlined>
          <v-list-item>
            <v-list-item-content>
              <v-card-text>
                <v-row>
                  <p>Pokouším se načíst historii kanálu</p>
                </v-row>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        <!-- konec historie -->
      </v-row>
      <!-- modal multiplexer -->

      <v-row justify="center">
        <v-dialog v-model="editMultiplexer" persistent max-height="200px" max-width="500px">
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
              <v-btn color="blue darken-1" text @click="editMultiplexer = false">Zavřít</v-btn>
              <v-btn color="green darken-1" text @click="saveMultiplexer()">Uložit</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
      <!-- end modal multiplexer -->

      <!-- modal prijem -->

      <v-row justify="center">
        <v-dialog v-model="addSatelit" persistent max-height="200px" max-width="500px">
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
                <v-row v-if="devices !=''">
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
                <v-row v-if="rf !='' && rf != 'submit'">
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
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeSatelit()">Zavřít</v-btn>
              <v-btn color="green darken-1" text @click="savePrijem()">Uložit</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
      <!-- end modal prijem -->
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      image: "",
      isp: "",
      isps: "",
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
      deleted: "",
      multiplexer: "",
      channelData: "",
      multicastIp: "",
      multiplexerId: "",
      stbIp: "",
      prijemKategorie: "",
      kategorieId: "",
      devices: "",
      status: "",
      deviceId: "",
      rf: "",
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
      history: ""
    };
  },

  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
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

    axios
      .post("/api/channelHistory", {
        id: this.$route.params.id
      })
      .then(function(response) {
        currentObj.history = response.data;
      })
      .catch(function(error) {
        console.log("chyba" + error);
      });

    // axios
    //   .post("/api/channel/getNahled", {
    //     id: this.$route.params.id
    //   })
    //   .then(function(response) {
    //     currentObj.image = response.data;
    //   })
    //   .catch(function(error) {
    //     console.log("chyba" + error);
    //   });
  },
  methods: {
    //   zavreni modal okna pro pridani prijimace a smazani vsech hodnot
    closeSatelit() {
      let currentObj = this;
      (currentObj.prijemKategorie = ""),
        (currentObj.kategorieId = ""),
        (currentObj.devices = ""),
        (currentObj.deviceId = ""),
        (currentObj.rf = ""),
        (currentObj.rfId = "");
      currentObj.addSatelit = false;
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
          isp: this.isp
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
          ispMulticastId: this.channelMulticastId
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
    savePrijem() {
      let currentObj = this;
      currentObj.addSatelit = false;
      axios
        .post("/api/channel/savePrijem", {
          channelId: this.$route.params.id,
          kategorieId: this.kategorieId,
          deviceId: this.deviceId,
          rfId: this.rfId
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.deviceId = "";
          currentObj.rfId = "";
          currentObj.prijemKategorie = "";
          currentObj.kategorieId = "";
          currentObj.devices = "";
          currentObj.deviceId = "";
          currentObj.rf = "";

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

    channelData: function() {
      setTimeout(
        function() {
          let currentObj = this;
          axios
            .post("/api/channel/get/multicast", {
              id: this.$route.params.id
            })
            .then(function(response) {
              currentObj.channelData = response.data;
            });
        }.bind(this),
        3000
      );
    },

    // image: function() {
    //   setTimeout(
    //     function() {
    //       let currentObj = this;
    //       axios
    //         .post("/api/channel/getNahled", {
    //           id: this.$route.params.id
    //         })
    //         .then(function(response) {
    //           currentObj.image = response.data;
    //         });
    //     }.bind(this),
    //     3000
    //   );
    // },

    status: function() {
      setTimeout(() => (this.status = false), 5000);
    },

    $route(to, from) {
      let currentObj = this;
      axios
        .post("/api/channel/get/multicast", {
          id: this.$route.params.id
        })
        .then(function(response) {
          currentObj.channelData = response.data;
          currentObj.history = "";
          currentObj.image = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });

      axios
        .post("/api/channelHistory", {
          id: this.$route.params.id
        })
        .then(function(response) {
          currentObj.history = response.data;
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });

      //   axios
      //     .post("/api/channel/getNahled", {
      //       id: this.$route.params.id
      //     })
      //     .then(function(response) {
      //       currentObj.image = response.data;
      //     })
      //     .catch(function(error) {
      //       console.log("chyba" + error);
      //     });
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
    }
  }
};
</script>
