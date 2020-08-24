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
        <br />
        <br />
        <template>
            <v-card max-width="1400" elevation="0">
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
                    dense
                    :headers="headers"
                    :items="tags"
                    :search="search"
                    class="font-weight-regular"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-spacer></v-spacer>

                            <v-dialog v-model="newTagDialog" max-width="500px">
                                <template v-slot:activator="{}">
                                    <v-btn
                                        color="primary"
                                        dark
                                        class="mb-2"
                                        @click="newTag()"
                                        >Nový štítek</v-btn
                                    >
                                </template>
                            </v-dialog>
                        </v-toolbar>
                    </template>

                    <!-- color show  -->

                    <template  v-slot:item.color="{ item }">
                        <v-card :color="item.color" width="30" height="20" flat>

                        </v-card>
                    </template>

                    <!-- end color show -->
                    <!-- editace a mazani uzivatelu -->

                    <!-- Editace -->
                    <template v-slot:item.action="{ item }">
                        <!-- <v-btn
                            @click="ShowTagEditDialog(item.id , item.color, item.tagName)"
                            icon
                        >
                            <v-icon small>edit</v-icon>
                        </v-btn> -->

                        <!-- Smazání -->
                        <v-btn
                            @click="
                                (tagId = item.id),
                                    (tagName = item.tagName),
                                    (modalTagDelete = true)
                            "
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
            <v-dialog
                v-model="newTagDialog"
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
                                        label="název štítku"
                                        v-model="tagName"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-color-picker
                                        v-model="colorPicker"
                                        flat
                                    ></v-color-picker>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="newTagDialog = false"
                            >Zavřít</v-btn
                        >
                        <v-btn color="green darken-1" text @click="saveNewTag()"
                            >Uložit</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- modal end new user -->

        <!-- mmodal user edit -->

        <v-row justify="center">
            <v-dialog
                v-model="modalTagEdit"
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
                                        label="název štítku"
                                        v-model="tagName"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-color-picker
                                        v-model="tagColor"
                                        flat
                                    ></v-color-picker>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="modalTagEdit = false"
                            >Zavřít</v-btn
                        >
                        <v-btn
                            color="green darken-1"
                            text
                            @click="saveEditTag()"
                            >Editovat</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- end modal edit -->

        <!-- modal delete tag -->

        <v-row justify="center">
            <v-dialog
                v-model="modalTagDelete"
                persistent
                max-height="200px"
                max-width="600px"
            >
                <v-card>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <h2>Odebrat {{ tagName }}</h2>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="modalTagDelete = false"
                            >Zavřít</v-btn
                        >
                        <v-btn color="red darken-1" text @click="deleteTag()"
                            >Odebrat</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        <!-- end modal delete tag -->
        <!-- end modal -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            tagColor: "",
            colorPicker: "",
            modalTagEdit: false,
            modalTagDelete: false,
            newTagDialog: false,
            tags: [],
            tagName: "",
            tagId: "",
            userId: "",
            status: "",
            search: "",
            users: [],
            userData: "",
            headers: [
                {
                    text: "Štítek",
                    align: "left",
                    value: "tagName"
                },
                { text: "Barva", value: "color" },
                { text: "Akce", value: "action" }
            ]
        };
    },

    created() {
        axios
            .get("/api/getUser")
            .then(response => (this.userData = response.data));
        this.getTags();
    },

    methods: {
        getTags() {
            axios
                .get("/api/tags/get")
                .then(response => (this.tags = response.data));
        },
        saveNewTag() {
            let currentObj = this;
            axios
                .post("/api/settings/tag/create", {
                    tagName: this.tagName,
                    color: this.colorPicker
                })
                .then(function(response) {
                    currentObj.status = response.data;
                    currentObj.newTagDialog = false;

                    currentObj.getTags();

                    tagName = "";
                    colorPicker = "";
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        newTag() {
            this.newTagDialog = true;
        },
        ShowTagEditDialog(tagId, tagColor, tagName) {
            this.tagId = tagId;
            this.tagColor = tagColor;
            this.tagName = tagName;

            this.modalTagEdit = true;
        },

        saveEditTag() {},

        deleteTag() {
            let currentObj = this;
            axios
                .post("/api/settings/tag/remove", {
                    tagId: this.tagId,
                })
                .then(function(response) {
                    currentObj.status = response.data;
                    currentObj.modalTagDelete = false;

                    currentObj.getTags();

                    tagId = "";
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
