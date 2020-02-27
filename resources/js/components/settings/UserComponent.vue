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
    <br />
    <br />
    <template>
      <v-card max-width="1400">
        <v-card-title>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Hledání"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="users"
          :search="search"
          class="font-weight-regular"
        >
          <template v-slot:top>
            <v-toolbar flat>
              <v-spacer></v-spacer>

              <v-dialog v-model="dialog" max-width="500px">
                <template v-slot:activator="{ on }">
                  <v-btn color="primary" dark class="mb-2" @click="newUser()">Nový uživatel</v-btn>
                </template>
              </v-dialog>
            </v-toolbar>
          </template>

          <!-- editace a mazani uzivatelu -->

          <!-- Editace -->
          <template v-slot:item.action="{ item }">
            <v-btn @click="ShowEditUserDialog(userId = item.id)" icon>
              <v-icon small>edit</v-icon>
            </v-btn>

            <!-- Smazání -->
            <v-btn
              @click="userId = item.id, userName = item.surname, modalDeleteUser = true"
              class="ma-2"
              type="submit"
              icon
            >
              <v-icon small class="red--text">delete</v-icon>
            </v-btn>

            <!-- end modals -->
          </template>

          <!-- end editace a mazani -->
        </v-data-table>
      </v-card>
    </template>

    <!-- modal -->

    <!-- modal new user -->

    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="5">
                  <v-text-field required label="Jméno" v-model="newUsername"></v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="5">
                  <v-text-field required label="Příjmení" v-model="newSurname"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field required label="email" v-model="newMail"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field type="password" required label="Heslo" v-model="newPassword"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-select
                    v-model="userRole"
                    :items="userRoles"
                    item-value="id"
                    item-text="role"
                    label="Uživatelská role"
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="saveNewUser()">Uložit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- modal end new user -->

    <!-- mmodal user edit -->

    <v-row justify="center">
      <v-dialog v-model="modalUserEdit" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="5">
                  <v-text-field required label="Jméno" v-model="userData.name"></v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="5">
                  <v-text-field required label="Příjmení" v-model="userData.surname"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field required label="email" v-model="userData.email"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-text-field
                    type="password"
                    required
                    label="Heslo -> pokud nechcete měnit nevyplňujte"
                    v-model="editedPassword"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6" md="10">
                  <v-select
                    v-model="userRole"
                    :items="userRoles"
                    item-value="id"
                    item-text="role"
                    label="Uživatelská role"
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="modalUserEdit = false">Zavřít</v-btn>
            <v-btn color="green darken-1" text @click="saveEditUser()">Editovat</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end modal edit -->

    <!-- modal delete user -->

    <v-row justify="center">
      <v-dialog v-model="modalDeleteUser" persistent max-height="200px" max-width="600px">
        <v-card>
          <v-card-text>
            <v-container>
              <v-row>
                <p>Odebrat uživatele {{userName}}</p>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="modalDeleteUser = false">Zavřít</v-btn>
            <v-btn color="red darken-1" text @click="deleteUser()">Odebrat</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- end modal delete user -->
    <!-- end modal -->
  </div>
</template>
<script>
export default {
  data() {
    return {
      userRole: "",
      userName: "",
      newUsername: "",
      newSurname: "",
      newPassword: "",
      editedPassword: "",
      newMail: "",
      userData: [],
      userRoles: [],
      dialog: false,
      modalUserEdit: false,
      modalDeleteUser: false,
      userId: "",
      status: "",
      search: "",
      users: [],
      userData: "",
      headers: [
        {
          text: "Jméno",
          align: "left",
          value: "name"
        },
        { text: "Příjmení", value: "surname" },
        { text: "Email", value: "email" },
        { text: "Role", value: "role" },
        { text: "Akce", value: "action" }
      ]
    };
  },

  created() {
    axios.get("/api/getUser").then(response => (this.userData = response.data));
    axios.get("/api/users/get").then(response => (this.users = response.data));
  },

  methods: {
    ShowEditUserDialog() {
      let currentObj = this;
      axios
        .post("/api/user/get", {
          userId: this.userId
        })
        .then(function(response) {
          currentObj.userData = response.data;
          currentObj.getUserRoles();
          currentObj.modalUserEdit = true;
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    saveEditUser() {
      let currentObj = this;

      axios
        .post("/api/user/edit", {
          userId: this.userId,
          editName: this.userData.name,
          editSurname: this.userData.surname,
          editMail: this.userData.email,
          editPassword: this.editedPassword,
          editUserRole: this.userRole
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.modalUserEdit = false;

          axios
            .get("/api/users/get")
            .then(response => (currentObj.users = response.data));

          editName = "";
          editSurname = "";
          editMail = "";
          editPassword = "";
          editUserRole = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    deleteUser() {
      let currentObj = this;

      axios
        .post("/api/user/delete", {
          userId: this.userId
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.modalDeleteUser = false;

          axios
            .get("/api/users/get")
            .then(response => (currentObj.users = response.data));
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    },
    getUserRoles() {
      axios
        .get("/api/roles/get")
        .then(response => (this.userRoles = response.data));
    },
    getUsers() {
      axios
        .get("/api/users/get")
        .then(response => (this.users = response.data));
    },
    // modal
    newUser() {
      let currentObj = this;
      currentObj.dialog = true;
      currentObj.getUserRoles();
    },
    saveNewUser() {
      let currentObj = this;
      axios
        .post("/api/user/create", {
          newUsername: this.newUsername,
          newSurname: this.newSurname,
          newPassword: this.newPassword,
          userRole: this.userRole,
          newMail: this.newMail
        })
        .then(function(response) {
          currentObj.status = response.data;
          currentObj.dialog = false;

          axios
            .get("/api/users/get")
            .then(response => (currentObj.users = response.data));

          newUsername = "";
          newSurname = "";
          newPassword = "";
          userRole = "";
          newMail = "";
        })
        .catch(function(error) {
          console.log("chyba" + error);
        });
    }
  },
  watch: {
    status: function() {
      setTimeout(() => (this.status = false), 5000);
    }
  }
};
</script>
