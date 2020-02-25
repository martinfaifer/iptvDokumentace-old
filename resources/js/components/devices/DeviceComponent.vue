<template>
  <div>
    <br />
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
    <div>
      <div class="rightFromSIdePanel">
        <div v-for="device in deviceData" v-bind:key="device.ip">
          <!-- MULTIPLEXERY -->
          <v-row>
            <div v-if="device.tag === 'Multiplexer'">
              <v-card class="ma-3" width="1400" outlined>
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
                                @click="modalEditMultiplexer = true, deviceIp = device.ip, deviceLogin = device.login, deviceNote = device.poznamka, deviceName = device.nazev, deviceId = device.id"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat multiplexer</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat multiplexer</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Kategorie:</strong>
                          {{device.kategorie}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Název:</strong>
                          {{device.nazev}}
                        </span>
                        <span class="ma-3">
                          <strong>IP:</strong>
                          <a :href="'http://'+device.ip" target="_blank">{{device.ip}}</a>
                        </span>
                        <span class="ma-3">
                          <strong>Přihlášení:</strong>
                          {{device.login}}
                        </span>
                        <span class="ma-3">
                          <strong>Poznámka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>

              <template>
                <v-card class="ma-3" width="1400" outlined>
                  <v-card-title>
                    Kanály na multiplexeru
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="search"
                      label="hledat kanál"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="device.channels"
                    :search="search"
                    class="font-weight-regular"
                  ></v-data-table>
                </v-card>
              </template>
              <!-- modal editMultiplexer -->
              <v-row justify="center">
                <v-dialog
                  v-model="modalEditMultiplexer"
                  persistent
                  max-height="200px"
                  max-width="500px"
                >
                  <v-card>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Login" v-model="deviceLogin"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Poznámka" v-model="deviceNote"></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="modalEditMultiplexer = false">Zavřít</v-btn>
                      <v-btn color="blue darken-1" text @click="saveMultiplexer()">Uložit</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-row>
              <!-- end modal -->
            </div>
          </v-row>
          <!-- Blankom -->
          <div v-if="device.tag === 'Blankom'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditBlankom = true, deviceIp = device.ip, deviceLogin = device.login, deviceNote = device.poznamka, deviceName = device.nazev, deviceId = device.id"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat prijimac</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat prijimac</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <div class="text-center">
                        <v-row>
                          <span class="ma-3">
                            <strong>Název</strong>
                            {{device.nazev}}
                          </span>
                          <span class="ma-3">
                            <strong>IP:</strong>
                            <a :href="'http://'+device.ip" target="_blank">{{device.ip}}</a>
                          </span>
                          <span class="ma-3">
                            <strong>Přihlášení:</strong>
                            {{device.login}}
                          </span>
                          <span class="ma-3">
                            <strong>Poznámka :</strong>
                            {{device.poznamka}}
                          </span>
                        </v-row>
                      </div>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>

              <!-- modal edit blankom -->
              <v-row justify="center">
                <v-dialog
                  v-model="modalEditBlankom"
                  persistent
                  max-height="200px"
                  max-width="500px"
                >
                  <v-card>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Login" v-model="deviceLogin"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Poznámka" v-model="deviceNote"></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="modalEditBlankom = false">Zavřít</v-btn>
                      <v-btn color="blue darken-1" text @click="saveBlankomData()">Uložit</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-row>
              <!-- end modal -->

              <!-- modal editRf -->
              <v-row justify="center">
                <v-dialog v-model="modalEditRF" persistent max-height="200px" max-width="500px">
                  <v-card>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <!-- <v-text-field required label="DVB" v-model="rfDVB"></v-text-field> -->
                            <v-select
                              v-model="rfDVB"
                              :items="dvbs"
                              item-value="name"
                              item-text="name"
                              label="Vberte typ vysílání"
                            ></v-select>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <!-- <v-text-field required label="Satelit" v-model="rfSatelit"></v-text-field> -->
                            <v-select
                              v-model="rfSatelit"
                              :items="satelits"
                              item-value="name"
                              item-text="name"
                              label="Vyberte satelit"
                            ></v-select>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Frekvence" v-model="rfFreq"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <!-- <v-text-field required label="Polarizace" v-model="rfPolarizace"></v-text-field> -->
                            <v-select
                              v-model="rfPolarizace"
                              :items="polarizace"
                              item-value="name"
                              item-text="name"
                              label="Vberte polarizaci"
                            ></v-select>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="Symbol rate" v-model="rfSymbol"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="FEC" v-model="rfFec"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="6" md="8">
                            <v-text-field required label="CI " v-model="rfCI"></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="modalEditRF = false">Zavřít</v-btn>
                      <v-btn color="blue darken-1" text @click="saveBlankomRf()">Uložit</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-row>
              <!-- end modal -->
            </v-row>
            <!-- Blankom Nastavení jednotlivých inputu -->
            <v-row>
              <!-- 1. input -->
              <v-card class="ma-3" max-width="356" min-width="300" outlined>
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
                                @click="rf = 'RF1',rfPolarizace = device.RF1_polarizace, deviceId = device.id, rfDVB = device.RF1_dvb, rfSatelit = device.RF1_satelit, rfFreq = device.RF1_freq, rfSymbol = device.RF1_Symbol, rfFec = device.RF1_fec, rfCI = device.CI_1, modalEditRF = true "
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat RF1</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <v-list-item-title class="title text-center">RF 1</v-list-item-title>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>DVB:</strong>
                          {{device.RF1_dvb}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Satelit:</strong>
                          {{device.RF1_satelit}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Frekvence:</strong>
                          {{device.RF1_freq}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Polarizace:</strong>
                          {{device.RF1_polarizace}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Symbol rate:</strong>
                          {{device.RF1_Symbol}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>FEC:</strong>
                          {{device.RF1_fec}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>CI 1:</strong>
                          {{device.CI_1}}
                        </span>
                      </v-row>

                      <v-divider></v-divider>

                      <div v-for="channel in device.channels" v-bind:key="channel.id">
                        <div>
                          <v-row v-if="channel.rf === 'RF1'">
                            <v-btn
                              v-bind:to="'/channel/' + channel.channelId "
                              target="_blank"
                              dark
                              text
                              small
                            >
                              <span class="blue--text">{{channel.nazev}}</span>
                            </v-btn>
                          </v-row>
                        </div>
                      </div>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
              <!-- 2. input -->
              <v-card class="ma-3" max-width="356" min-width="300" outlined>
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
                                @click="rf = 'RF2', rfPolarizace = device.RF2_polarizace, deviceId = device.id, rfDVB = device.RF2_dvb, rfSatelit = device.RF2_satelit, rfFreq = device.RF2_freq, rfSymbol = device.RF2_Symbol, rfFec = device.RF2_fec, rfCI = device.CI_2, modalEditRF = true "
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat RF2</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <v-list-item-title class="title text-center">RF 2</v-list-item-title>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>DVB:</strong>
                          {{device.RF2_dvb}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Satelit:</strong>
                          {{device.RF2_satelit}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Frekvence:</strong>
                          {{device.RF2_freq}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Polarizace:</strong>
                          {{device.RF2_polarizace}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Symbol rate:</strong>
                          {{device.RF2_Symbol}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>FEC:</strong>
                          {{device.RF2_fec}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>CI 2:</strong>
                          {{device.CI_2}}
                        </span>
                      </v-row>
                      <v-divider></v-divider>

                      <div v-for="channel in device.channels" v-bind:key="channel.id">
                        <div>
                          <v-row v-if="channel.rf === 'RF2'">
                            <v-btn
                              v-if="userData.role === '1' || userData.role === '2'"
                              v-bind:to="'/channel/' + channel.channelId "
                              target="_blank"
                              dark
                              text
                              small
                            >
                              <span class="blue--text">{{channel.nazev}}</span>
                            </v-btn>
                          </v-row>
                        </div>
                      </div>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
              <!-- 3. input -->
              <v-card class="ma-3" max-width="356" min-width="300" outlined>
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
                                @click="rf = 'RF3', rfPolarizace = device.RF3_polarizace, deviceId = device.id, rfDVB = device.RF3_dvb, rfSatelit = device.RF3_satelit, rfFreq = device.RF3_freq, rfSymbol = device.RF3_Symbol, rfFec = device.RF3_fec, rfCI = device.CI_3, modalEditRF = true "
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat RF3</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <v-list-item-title class="title text-center">RF 3</v-list-item-title>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>DVB:</strong>
                          {{device.RF3_dvb}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Satelit:</strong>
                          {{device.RF3_satelit}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Frekvence:</strong>
                          {{device.RF3_freq}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Polarizace:</strong>
                          {{device.RF3_polarizace}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Symbol rate:</strong>
                          {{device.RF3_Symbol}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>FEC:</strong>
                          {{device.RF3_fec}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>CI 3:</strong>
                          {{device.CI_3}}
                        </span>
                      </v-row>

                      <v-divider></v-divider>

                      <div v-for="channel in device.channels" v-bind:key="channel.id">
                        <div>
                          <v-row v-if="channel.rf === 'RF3'">
                            <v-btn
                              v-bind:to="'/channel/' + channel.channelId "
                              target="_blank"
                              dark
                              text
                              small
                            >
                              <span class="blue--text">{{channel.nazev}}</span>
                            </v-btn>
                          </v-row>
                        </div>
                      </div>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
              <!-- 4. input -->
              <v-card class="ma-3" max-width="356" min-width="300" outlined>
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
                                @click="rf = 'RF4', rfPolarizace = device.RF4_polarizace, deviceId = device.id, rfDVB = device.RF4_dvb, rfSatelit = device.RF4_satelit, rfFreq = device.RF4_freq, rfSymbol = device.RF4_symbol, rfFec = device.RF4_fec, rfCI = device.CI_4, modalEditRF = true "
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat RF4</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <v-list-item-title class="title text-center">RF 4</v-list-item-title>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>DVB:</strong>
                          {{device.RF4_dvb}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Satelit:</strong>
                          {{device.RF4_satelit}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Frekvence:</strong>
                          {{device.RF4_freq}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Polarizace:</strong>
                          {{device.RF4_polarizace}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>Symbol rate:</strong>
                          {{device.RF4_symbol}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>FEC:</strong>
                          {{device.RF4_fec}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>CI 4:</strong>
                          {{device.CI_4}}
                        </span>
                      </v-row>

                      <v-divider></v-divider>

                      <div v-for="channel in device.channels" v-bind:key="channel.id">
                        <div>
                          <v-row v-if="channel.rf === 'RF4'">
                            <v-btn
                              v-bind:to="'/channel/' + channel.channelId "
                              target="_blank"
                              dark
                              text
                              small
                            >
                              <span class="blue--text">{{channel.nazev}}</span>
                            </v-btn>
                          </v-row>
                        </div>
                      </div>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
          </div>
          <!-- FTE -->
          <div v-if="device.tag === 'FTE'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditFte = true, deviceIp = device.ip, deviceNote = device.poznamka, deviceName = device.nazev, deviceId = device.id"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat FTE</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat FTE</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Název</strong>
                          {{device.nazev}}
                        </span>
                        <span class="ma-3">
                          <strong>IP:</strong>
                          {{device.ip}}
                        </span>
                        <span class="ma-3">
                          <strong>Poznámka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditFteSat = true, deviceId = device.id, FteDVB = device.FTE_dvb, FteFreq =  device.FTE_freq,FteSatelit = device.FTE_satelit, FteSymbol = device.FTE_Symbol, FteFec = device.FEC_fec, FteCI_A = device.CI_A, FteCI_B = device.CI_B, FtePolarizace = device.FTE_polarizace  "
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat přijímač</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>DVB:</strong>
                          {{device.FTE_dvb}}
                        </span>
                        <span class="ma-3">
                          <strong>Satelit:</strong>
                          {{device.FTE_satelit}}
                        </span>
                        <span class="ma-3">
                          <strong>Frekvence:</strong>
                          {{device.FTE_freq}}
                        </span>
                        <span class="ma-3">
                          <strong>Polarizace:</strong>
                          {{device.FTE_polarizace}}
                        </span>
                        <span class="ma-3">
                          <strong>Symbol rate:</strong>
                          {{device.FTE_Symbol}}
                        </span>
                        <span class="ma-3">
                          <strong>FEC:</strong>
                          {{device.FEC_fec}}
                        </span>
                      </v-row>
                      <v-divider></v-divider>
                      <v-row>
                        <span class="ma-3">
                          <strong>CI A:</strong>
                          {{device.CI_A}}
                        </span>
                      </v-row>
                      <v-row>
                        <span class="ma-3">
                          <strong>CI B:</strong>
                          {{device.CI_B}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
                <v-list-item>
                  <v-list-item-content>
                    <v-card-text>
                      <v-row>
                        <v-list-item-title class="title text-center">Kanály na přijímači</v-list-item-title>
                      </v-row>
                      <template>
                        <v-simple-table fixed-header min-width="1500">
                          <template v-slot:default>
                            <thead>
                              <tr>
                                <th class="text-left">Název</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="item in device.channels" :key="item.name">
                                <td>{{ item.nazev }}</td>
                              </tr>
                            </tbody>
                          </template>
                        </v-simple-table>
                      </template>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>

            <!-- modal edit FTE -->
            <v-row justify="center">
              <v-dialog v-model="modalEditFte" persistent max-height="200px" max-width="500px">
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Poznámka" v-model="deviceNote"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditFte = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveBaseEditFte()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>

            <!-- modal fte edit prijem -->

            <v-row justify="center">
              <v-dialog v-model="modalEditFteSat" persistent max-height="200px" max-width="600px">
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <!-- <v-text-field required label="DVB" v-model="FteDVB"></v-text-field> -->
                          <v-select
                            v-model="FteDVB"
                            :items="dvbs"
                            item-value="name"
                            item-text="name"
                            label="Vberte typ vysílání"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <!-- <v-text-field required label="Satelit" v-model="FteSatelit"></v-text-field> -->
                          <v-select
                            v-model="FteSatelit"
                            :items="satelits"
                            item-value="name"
                            item-text="name"
                            label="Vberte satelit"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Frekvence" v-model="FteFreq"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <!-- <v-text-field required label="Polarizace" v-model="FtePolarizace"></v-text-field> -->
                          <v-select
                            v-model="FtePolarizace"
                            :items="polarizace"
                            item-value="name"
                            item-text="name"
                            label="Vberte polarizaci"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Symbol rate" v-model="FteSymbol"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="FEC" v-model="FteFec"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="CI A " v-model="FteCI_A"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="CI B " v-model="FteCI_B"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditFteSat = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveFteSat()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>

            <!-- end modal fte prijem -->
            <!-- end modal -->
          </div>
          <!-- tag Transcoder_NSP -->
          <div v-if="device.tag === 'Transcoder_NSP'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditNsp = true, deviceId = device.id, deviceIp = device.ip, deviceName = device.nazev, deviceLogin = device.login, deviceNote = device.poznamka"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat Transcoder</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat Transcoder</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Název</strong>
                          {{device.nazev}}
                        </span>
                        <span class="ma-3">
                          <strong>IP:</strong>
                          <a :href="'http://'+device.ip" target="_blank">{{device.ip}}</a>
                        </span>
                        <span class="ma-3">
                          <strong>Přihlášení</strong>
                          {{device.login}}
                        </span>
                        <span class="ma-3">
                          <strong>Poznámka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <template>
                <v-card class="ma-3" width="1500" outlined>
                  <v-card-title>
                    Kanály na Transcodéru
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="search"
                      label="hledat kanál"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="device.channels"
                    :search="search"
                    class="font-weight-regular"
                  ></v-data-table>
                </v-card>
              </template>
            </v-row>

            <!-- modal NSP -->

            <v-row justify="center">
              <v-dialog v-model="modalEditNsp" persistent max-height="200px" max-width="500px">
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Login" v-model="deviceLogin"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Poznámka" v-model="deviceNote"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditNsp = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveNsp()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>

            <!-- end modal NSP -->
          </div>
          <!-- tag Transcoder_Grape -->
          <div v-if="device.tag === 'Transcoder_Grape'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditTranscoder = true, deviceId = device.id, deviceIp = device.ip, deviceName = device.nazev, deviceNote = device.poznamka"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat Transcoder</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat Transcoder</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Název</strong>
                          {{device.nazev}}
                        </span>
                        <span class="ma-3">
                          <strong>IP:</strong>
                          <a :href="'http://'+device.ip" target="_blank">{{device.ip}}</a>
                        </span>
                        <span class="ma-3">
                          <strong>Poznámka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <template>
                <v-card class="ma-3" width="1400" outlined>
                  <v-card-title>
                    Kanály na Transcodéru
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="search"
                      label="hledat kanál"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="device.channels"
                    :search="search"
                    class="font-weight-regular"
                  ></v-data-table>
                </v-card>
              </template>
            </v-row>

            <!-- modal Grape Transcoder -->

            <v-row justify="center">
              <v-dialog
                v-model="modalEditTranscoder"
                persistent
                max-height="200px"
                max-width="500px"
              >
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Poznámka" v-model="deviceNote"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditTranscoder = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveGrapeTranscoder()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>

            <!-- end modal transcoder -->
          </div>
          <!-- tag Linux -->
          <div v-if="device.tag === 'Linux'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditLinux = true, deviceName = device.nazev, deviceId = device.id, deviceIp = device.ip, deviceLogin = device.login, deviceNote = device.poznamka"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat Transcoder</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat Transcoder</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Příjem kanálu :</strong>
                          {{device.nazev}}
                        </span>
                        <span class="ma-3">
                          <strong>IP :</strong>
                          <a
                            :href="'ssh:'+ device.ssh_user + '@'+device.ip"
                            target="_blank"
                          >{{device.ip}}</a>
                        </span>
                        <span class="ma-3">
                          <strong>Přihlášení :</strong>
                          {{device.login}}
                        </span>
                        <span class="ma-3">
                          <strong>Poznámka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <template>
                <v-card class="ma-3" width="1500" outlined>
                  <v-card-title>
                    Přijímané kanály
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="search"
                      label="hledat kanál"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="device.channels"
                    :search="search"
                    class="font-weight-regular"
                  ></v-data-table>
                </v-card>
              </template>
            </v-row>

            <!-- modal Linux -->

            <v-row justify="center">
              <v-dialog v-model="modalEditLinux" persistent max-height="200px" max-width="500px">
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Poznámka" v-model="deviceIp"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Poznámka" v-model="deviceLogin"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Poznámka" v-model="deviceNote"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditLinux = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveModalLinux()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>

            <!-- end modal linux -->
          </div>
          <!-- Po IP  -->
          <div v-if="device.tag === 'PoIP'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
                <v-list-item>
                  <v-list-item-content>
                    <v-toolbar dense flat height="10" color="transparent">
                      <v-spacer></v-spacer>
                      <div class="text-center d-flex align-center">
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                @click="modalEditPoIp = true, deviceName = device.prijem, deviceId = device.id,  deviceNote = device.poznamka"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Příjem kanálu :</strong>
                          {{device.prijem}}
                        </span>
                        <span class="ma-3">
                          <strong>Poznámka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <template>
                <v-card class="ma-3" width="1500" outlined>
                  <v-card-title>
                    Přijímané kanály
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="search"
                      label="hledat kanál"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="device.channels"
                    :search="search"
                    class="font-weight-regular"
                  ></v-data-table>
                </v-card>
              </template>
            </v-row>

            <v-row justify="center">
              <v-dialog v-model="modalEditPoIp" persistent max-height="200px" max-width="500px">
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="poznámka" v-model="deviceNote"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditPoIp = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveModalPoIp()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>
          </div>
          <!-- PowerVu -->
          <div v-if="device.tag === 'PowerVu'">
            <v-row>
              <v-card class="ma-3" width="1500" outlined>
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
                                @click="modalEditPowerVu = true, deviceName = device.nazev, deviceId = device.id, deviceIp = device.ip, deviceLogin = device.login,  deviceNote = device.poznamka"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="primary">mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">editovat</span>
                          </v-tooltip>
                        </span>
                        <span>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                v-if="userData.role === '1' || userData.role === '2'"
                                @click="RemoveDevice(deviceId = device.id)"
                                icon
                                x-small
                                v-on="on"
                              >
                                <v-icon color="red">mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            <span class="font-weight-medium">smazat</span>
                          </v-tooltip>
                        </span>
                      </div>
                    </v-toolbar>
                    <v-card-text>
                      <v-row>
                        <span class="ma-3">
                          <strong>Příjem kanálu :</strong>
                          {{device.nazev}}
                        </span>
                        <span class="ma-3">
                          <strong>IP:</strong>
                          {{device.ip}}
                        </span>
                        <span class="ma-3">
                          <strong>Přihlášení :</strong>
                          {{device.login}}
                        </span>
                        <span class="ma-3">
                          <strong>Poznamka :</strong>
                          {{device.poznamka}}
                        </span>
                      </v-row>
                    </v-card-text>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-row>
            <v-row>
              <template>
                <v-card class="ma-3" width="1500" outlined>
                  <v-card-title>
                    Přijímané kanály
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      append-icon="search"
                      label="hledat kanál"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="device.channels"
                    :search="search"
                    class="font-weight-regular"
                  ></v-data-table>
                </v-card>
              </template>
            </v-row>
            <!-- modal -->
            <v-row justify="center">
              <v-dialog v-model="modalEditPowerVu" persistent max-height="200px" max-width="500px">
                <v-card>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Název zařízení" v-model="deviceName"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="IP" v-model="deviceIp"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="Přihlášení" v-model="deviceLogin"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="8">
                          <v-text-field required label="poznámka" v-model="deviceNote"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="modalEditPowerVu = false">Zavřít</v-btn>
                    <v-btn color="blue darken-1" text @click="saveModalPowerVu()">Uložit</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-row>

            <!-- emd modal -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: "",
      deviceData: "",
      deviceName: "",
      deviceIp: "",
      deviceNote: "",
      deviceLogin: "",
      deviceId: "",
      rf: "",
      rfDVB: "",
      rfSatelit: "",
      rfFreq: "",
      rfPolarizace: "",
      rfSymbol: "",
      rfFec: "",
      rfCI: "",
      FteDVB: "",
      FteSatelit: "",
      FteFreq: "",
      FtePolarizace: "",
      FteSymbol: "",
      FteFec: "",
      FteCI_A: "",
      FteCI_B: "",
      modalEditRF: false,
      modalEditMultiplexer: false,
      modalEditBlankom: false,
      modalEditFte: false,
      modalEditFteSat: false,
      modalEditTranscoder: "",
      modalEditNsp: false,
      modalEditLinux: false,
      modalEditPoIp: false,
      modalEditPowerVu: false,
      status: "",
      deleted: "",
      search: "",
      headers: [
        {
          text: "Kanály",
          value: "nazev"
        }
      ],
      satelits: [
        { name: "Astra 4A" },
        { name: "Astra 23,5" },
        { name: "Astra 19,2E" },
        { name: " Eutelsat 9A" },
        { name: " Eutelsat 9B at 9" },
        { name: "Hotbird 9 13E" },
        { name: "Hotbird 13C" },
        { name: " Hotbird13 13E" },
        { name: "Telstar 12 15W" },
        { name: "Thor 5 " },
        { name: "Thor6 " }
      ],
      dvbs: [
        { name: "DVB-S" },
        { name: "DVB-S2" },
        { name: "DVB-T" },
        { name: "DVB-T2" }
      ],
      polarizace: [{ name: "vertikální" }, { name: "horizontální" }]
    };
  },

  methods: {
    RemoveDevice() {
      let currentObj = this;
      axios
        .post("/api/device/remove", {
          deviceId: currentObj.$route.params.id
        })
        .then(function(response) {
          currentObj.status = response.data;
          if (currentObj.status.stat == "success") {
            currentObj.deleted = "true";
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    newDataLoad() {
      axios
        .post("/api/device/get", {
          id: currentObj.$route.params.id
        })
        .then(function(response) {
          currentObj.deviceData = response.data;
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },

    // interakce s multiplexer
    saveMultiplexer() {
      let currentObj = this;
      axios
        .post("/api/device/multiplexer/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceIp: this.deviceIp,
          deviceLogin: this.deviceLogin,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditMultiplexer = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },

    // interakce s blankomem
    saveBlankomData() {
      let currentObj = this;
      axios
        .post("/api/device/blankom/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceIp: this.deviceIp,
          deviceLogin: this.deviceLogin,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditBlankom = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },
    saveBlankomRf() {
      let currentObj = this;
      axios
        .post("/api/device/blankom/rf/edit", {
          deviceId: this.$route.params.id,
          rf: this.rf,
          rfDVB: this.rfDVB,
          rfSatelit: this.rfSatelit,
          rfFreq: this.rfFreq,
          rfPolarizace: this.rfPolarizace,
          rfSymbol: this.rfSymbol,
          rfFec: this.rfFec,
          rfCI: this.rfCI
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditRF = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },
    // interakce FTE
    saveBaseEditFte() {
      let currentObj = this;
      axios
        .post("/api/device/fte/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceIp: this.deviceIp,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditFte = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },
    saveFteSat() {
      let currentObj = this;
      axios
        .post("/api/device/fte/sat/edit", {
          deviceId: this.$route.params.id,
          FteDVB: this.FteDVB,
          FteSatelit: this.FteSatelit,
          FteFreq: this.FteFreq,
          FtePolarizace: this.FtePolarizace,
          FteSymbol: this.FteSymbol,
          FteFec: this.FteFec,
          FteCI_A: this.FteCI_A,
          FteCI_B: this.FteCI_B
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditFteSat = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },
    // end interaction fte
    // interaction nsp
    saveNsp() {
      let currentObj = this;
      axios
        .post("/api/device/nsp/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceLogin: this.deviceLogin,
          deviceIp: this.deviceIp,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditNsp = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },
    // grape transcoder
    saveGrapeTranscoder() {
      let currentObj = this;
      axios
        .post("/api/device/grapeTranscoder/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceIp: this.deviceIp,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditTranscoder = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },

    // linux
    saveModalLinux() {
      let currentObj = this;
      axios
        .post("/api/device/linux/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceLogin: this.deviceLogin,
          deviceIp: this.deviceIp,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditLinux = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },
    // po IP
    saveModalPoIp() {
      let currentObj = this;
      axios
        .post("/api/device/PoIp/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditPoIp = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    },

    // powerVu
    saveModalPowerVu() {
      let currentObj = this;
      axios
        .post("/api/device/powerVu/edit", {
          deviceId: this.$route.params.id,
          deviceName: this.deviceName,
          deviceIp: this.deviceIp,
          deviceLogin: this.deviceLogin,
          deviceNote: this.deviceNote
        })
        .then(function(response) {
          currentObj.status = response.data;

          currentObj.modalEditPowerVu = false;

          axios
            .post("/api/device/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.deviceData = response.data;
            })
            .catch(function(error) {
              currentObj.deviceData = "error";
            });
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    }
  },

  watch: {
    status: function() {
      setTimeout(() => (this.status = false), 5000);
    },

    deleted: function() {
      if (this.deleted === "true") {
        this.deleted = "";
        setTimeout(
          function() {
            this.$router.push("/devices");
          }.bind(this),
          2000
        );
      }
    },

    $route(to, from) {
      let currentObj = this;
      axios
        .post("/api/device/get", {
          id: this.$route.params.id
        })
        .then(function(response) {
          currentObj.deviceData = response.data;
        })
        .catch(function(error) {
          currentObj.deviceData = "error";
        });
    }
  },
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
    let currentObj = this;
    axios
      .post("/api/device/get", {
        id: this.$route.params.id
      })
      .then(function(response) {
        currentObj.deviceData = response.data;
      })
      .catch(function(error) {
        currentObj.deviceData = "error";
      });
  }
};
</script>
