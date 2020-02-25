<template>
  <div>
    <div v-if="topic != ''">
      <v-toolbar v-if="userData.role === '1'" dense flat color="transparent">
        <v-spacer></v-spacer>
        <div class="text-center d-flex align-center">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn
                @click="openEditModal = true, editedText = topic.text"
                class="mr-3"
                color="primary"
                fab
                x-small
                dark
                v-on="on"
              >
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
            </template>
            <span class="font-weight-medium">Editovat Text</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn @click="removeTopic()" class="mr-3" color="primary" fab x-small dark v-on="on">
                <v-icon color="red">mdi-delete</v-icon>
              </v-btn>
            </template>
            <span class="font-weight-medium">Odebrat text</span>
          </v-tooltip>
        </div>
      </v-toolbar>
      <br />
      <!-- text -->
      <div v-html="topic.text">{{topic.text}}</div>
      <!-- konec textu -->
      <!-- edit modal -->
      <v-row justify="center">
        <v-dialog v-model="openEditModal" persistent max-height="200px" max-width="800px">
          <v-card>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12">
                    <v-textarea
                      label="Obsah článku"
                      hint="pište v html"
                      required
                      v-model="editedText"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="openEditModal = false">Zavřít</v-btn>
              <v-btn color="green darken-1" text @click="saveEditText()">Uložit</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
      <!-- end edit modal -->
    </div>
    <div v-else>
      <v-toolbar v-if="userData.role === '1'" dense flat color="transparent">
        <v-spacer></v-spacer>
        <div class="text-center d-flex align-center">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn
                @click="openCreateModal = true"
                class="mr-3"
                color="primary"
                fab
                x-small
                dark
                v-on="on"
              >
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
            </template>
            <span class="font-weight-medium">Editovat Text</span>
          </v-tooltip>
        </div>
      </v-toolbar>

      <!-- modal pro vytvoreni noveho textu -->
      <v-row justify="center">
        <v-dialog v-model="openCreateModal" persistent max-height="200px" max-width="800px">
          <v-card>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12">
                    <v-textarea label="Obsah článku" hint="pište v html" required v-model="newText"></v-textarea>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="openCreateModal = false">Zavřít</v-btn>
              <v-btn color="green darken-1" text @click="saveNewText()">Uložit</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
      <!-- modal pro vytvoreni noveho textu -->
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      status: [],
      topic: [],
      userData: [],
      openEditModal: false,
      openCreateModal: false,
      editedText: "",
      newText: ""
    };
  },

  methods: {
    saveEditText() {
      let currentObj = this;
      axios
        .post("/api/topic/edit", {
          editedText: this.editedText,
          topicId: this.$route.params.id,
          user: this.userData.surname
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.openEditModal = false;

          axios
            .post("/api/topic/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.topic = response.data;
              currentObj.editedText = "";
            })
            .catch(function(error) {
              currentObj.status = "error";
            });

          currentObj.editedText = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    saveNewText() {
      let currentObj = this;
      axios
        .post("/api/topic/create", {
          newText: this.newText,
          topicId: this.$route.params.id,
          user: this.userData.surname
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.openCreateModal = false;

          axios
            .post("/api/topic/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.topic = response.data;
              currentObj.newText = "";
            })
            .catch(function(error) {
              currentObj.status = "error";
            });

          currentObj.newText = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    removeTopic() {
      let currentObj = this;

      axios
        .post("/api/topic/remove", {
          topicId: this.$route.params.id
        })
        .then(function(response) {
          currentObj.status = response.data;

          axios
            .post("/api/topic/get", {
              id: currentObj.$route.params.id
            })
            .then(function(response) {
              currentObj.topic = response.data;
            })
            .catch(function(error) {
              currentObj.status = "error";
            });
        })
        .catch(function(error) {
          console.log("chyba" + error);
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
        .post("/api/topic/get", {
          id: this.$route.params.id
        })
        .then(function(response) {
          currentObj.topic = response.data;
          currentObj.editedText = "";
          currentObj.newText = "";
        })
        .catch(function(error) {
          currentObj.status = "error";
        });
    }
  },
  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
    let currentObj = this;
    axios
      .post("/api/topic/get", {
        id: this.$route.params.id
      })
      .then(function(response) {
        currentObj.topic = response.data;
        currentObj.editedText = "";
        currentObj.newText = "";
      })
      .catch(function(error) {
        currentObj.status = "error";
      });
  }
};
</script>

