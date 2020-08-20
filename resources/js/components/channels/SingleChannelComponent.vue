<template>
    <div>
        <div class="rightFromSIdePanel">
            <br />
            <br />
            <v-container fluid>
                <!-- tags -->
                <v-row v-if="tags != false" end>
                    <v-toolbar
                        dense
                        flat
                        color="transparent"
                    >
                        <v-spacer></v-spacer>
                        <span v-for="tag in tags" :key="tag.id" end>
                            <v-btn
                                readonly
                                x-small
                                outlined
                                :color="tag.color"
                                >{{ tag.tagName }}</v-btn
                            >
                        </span>
                    </v-toolbar>
                </v-row>

                <!-- Tabs -->
                <template>
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
                                    '/channel/' +
                                        this.$route.params.id +
                                        '/h264'
                                "
                                >H264</v-tab
                            >

                            <v-tab
                                link
                                v-bind:to="
                                    '/channel/' +
                                        this.$route.params.id +
                                        '/h265'
                                "
                                >H265</v-tab
                            >
                        </v-tabs>
                    </v-card>
                </template>

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
            tags: false
        };
    },
    created() {
        this.loadTags();
    },

    methods: {
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
        }
    },

      watch: {
           $route(to, from) {
               this.loadTags();
        },

      }


};
</script>
