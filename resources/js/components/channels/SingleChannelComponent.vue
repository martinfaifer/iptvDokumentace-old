<template>
    <div>
        <div class="rightFromSIdePanel">
            <br />
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
                                    >{{ status.msg }}</v-alert
                                >
                            </transition>
                        </div>
                    </template>
                </div>
            </div>
            <!-- end alert -->
            <v-container fluid>
                <!-- tags -->
                <v-row end>
                    <v-toolbar dense flat color="transparent" class="body-1">
                        <v-spacer></v-spacer>
                        <div v-if="tags != false">
                            <span v-for="tag in tags" :key="tag.id">
                                <!-- zobrazení jednotlibých tagů pro daný kanál -->

                                <v-chip
                                    small
                                    class="ma-2"
                                    close
                                    :color="tag.color"
                                    text-color="white"
                                    @click:close="closeDialog(tag.id)"
                                >
                                    {{ tag.tagName }}
                                </v-chip>

                                <!-- end zobrazení jednotlibých tagů pro daný kanál -->
                            </span>
                        </div>

                        <v-tooltip bottom v-if="tags === false">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    class="ma-2"
                                    tile
                                    small
                                    color="teal"
                                    icon
                                    @click="showDialogForAddNewTag()"
                                    v-on="on"
                                >
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </template>
                            <span class="font-weight-medium"
                                >Přidat štítek</span
                            >
                        </v-tooltip>
                    </v-toolbar>
                </v-row>

                <!-- Tabs -->

                <v-card flat>
                    <v-tabs centered>
                        <v-tabs-slider></v-tabs-slider>

                        <v-tab
                            link
                            v-bind:to="'/channel/' + this.$route.params.id"
                            >Multicast</v-tab
                        >

                        <v-tab
                            link
                            v-bind:to="
                                '/channel/' + this.$route.params.id + '/h264'
                            "
                            >H264</v-tab
                        >

                        <v-tab
                            link
                            v-bind:to="
                                '/channel/' + this.$route.params.id + '/h265'
                            "
                            >H265</v-tab
                        >
                        <v-tab
                            link
                            v-bind:to="
                                '/channel/' + this.$route.params.id + '/dohled'
                            "
                            >Informace z dohledu</v-tab
                        >
                    </v-tabs>
                </v-card>

                <!-- modal pro odebrání tagu -->


                <!-- dialog pro nový štítek -->

                <v-row justify="center">
                    <v-dialog v-model="newTagDialog" persistent max-width="290">
                        <v-card>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="12" class="body-1">
                                            <v-autocomplete
                                                v-model="tagId"
                                                :items="allTags"
                                                item-text="tagName"
                                                item-value="id"
                                                label="Štítek"
                                            ></v-autocomplete>
                                        </v-col>
                                    </v-row>
                                    <!-- zobrazení okna pro pripadne doplneni informaci pro uspesne pridani stitku -->

                                    <!-- konec doplnkoveho okna -->
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="red darken-1"
                                    text
                                    @click="newTagDialog = false"
                                    >Zavřít</v-btn
                                >
                                <v-btn
                                    color="green darken-1"
                                    text
                                    @click="saveNewTag()"
                                    >Uložit</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-row>

                <!-- konec dialogu pro nový štítek -->

                <transition name="fade" mode="out-in">
                    <router-view />
                </transition>
            </v-container>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            tags: false,
            tagDialogRemove: false,
            newTagDialog: false,
            allTags: false,
            tagId: "",
            status: false
        };
    },
    created() {
        this.loadTags();
    },

    methods: {
        showDialogForAddNewTag() {
            axios
                .get("/api/tags/get")
                .then(response => (this.allTags = response.data));
            this.newTagDialog = true;
        },
        saveNewTag() {
            let currentObj = this;
            axios
                .post("/api/channel/tag/add", {
                    id: this.$route.params.id,
                    tagId: this.tagId
                })
                .then(function(response) {
                    currentObj.status = response.data;
                    currentObj.loadTags();
                    currentObj.newTagDialog = false;
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },
        loadTags() {
            let currentObj = this;
            axios
                .post("/api/channel/get/tags", {
                    id: this.$route.params.id
                })
                .then(function(response) {
                    currentObj.tags = response.data;
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },
        closeDialog(id) {
            let currentObj = this;

            axios
                .post("/api/channel/delete/tag", {
                    id: this.$route.params.id,
                    tagId: id
                })
                .then(function(response) {
                    currentObj.tags = response.data;
                    currentObj.loadTags();
                    currentObj.tagDialogRemove = false;
                })
                .catch(function(error) {
                    console.log("chyba" + error);
                });
        },

        // deleteTag() {
        //     let currentObj = this;

        //     axios
        //         .post("/api/channel/delete/tag", {
        //             id: this.$route.params.id,
        //             tagId: id
        //         })
        //         .then(function(response) {
        //             currentObj.tags = response.data;
        //             currentObj.loadTags();
        //             currentObj.tagDialogRemove = false;
        //         })
        //         .catch(function(error) {
        //             console.log("chyba" + error);
        //         });
        // }
    },

    watch: {
        $route(to, from) {
            this.loadTags();
        },
        status: function() {
            setTimeout(() => (this.status = false), 3000);
        }
    }
};
</script>
