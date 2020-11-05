<template>
    <div>
        <!-- obsah infomrací o dohledu -->

        <div v-if="dohledDataFromStream != null" class="mt-12">
            <v-data-table
                :headers="headers"
                :items="dohledDataFromStream"
                class="elevation-0 body-2 "
            >
                <template v-slot:item.img="{ item }">
                    <v-img :src="item.img" height="25" width="25"></v-img>
                </template>

                <template v-slot:item.name="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <span v-on="on" v-bind="attrs">
                                {{ item.name }}
                            </span>
                        </template>
                        <!-- time line  -->
                        <v-container v-if="item.history != 'none'">
                            <v-timeline dense>
                                <v-timeline-item
                                    v-for="stream in item.history"
                                    :key="stream.id"
                                    :color="stream.color"
                                    small
                                >
                                    <template v-slot:opposite>
                                        <span
                                            class="
                                                body-1 font-weight-bold
                                            "
                                            v-text="stream.created_at"
                                        ></span>
                                    </template>
                                    <div class="py-4">
                                        <div
                                            v-if="stream.status == 'stream_ok'"
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Stream je v pořádku
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'scrambledPids_warning'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Změněno pořadí Pidů
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'transporterrors_warning'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Objevily se TS chyby
                                                </strong>
                                            </small>
                                        </div>

                                        <div
                                            v-else-if="
                                                stream.status == 'stream_error'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Stream nefunguje
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'diagnostic_crash'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Problém s diagnostikou
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status == 'no_audio'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Nepodařilo se detekovat
                                                    audio
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="stream.status == 'issue'"
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Ve streamu se vyskytl
                                                    problém
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status == 'no_dekrypt'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Stream se nedekryptuje
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'no_video_bitrate'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Video nemá datový tok
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'no_audio_bitrate'
                                            "
                                        >
                                            <small
                                                :class="`${stream.color}--text`"
                                            >
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Audio nemá datový tok
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'sheduler_disable'
                                            "
                                        >
                                            <small class="green--text">
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Aplikováno automatická
                                                    vypnutí alertů
                                                </strong>
                                            </small>
                                        </div>
                                        <div
                                            v-else-if="
                                                stream.status ==
                                                    'sheduler_enable'
                                            "
                                        >
                                            <small class="green--text">
                                                <strong>
                                                    {{ stream.created_at }}
                                                    => Aplikováno automatická
                                                    zapnutí alertů
                                                </strong>
                                            </small>
                                        </div>
                                    </div>
                                </v-timeline-item>
                            </v-timeline>
                        </v-container>
                        <v-container v-else>
                            <div>
                                <v-alert dense text type="info">
                                    <strong
                                        >Není evidována žádná historie u
                                        streamu</strong
                                    >
                                </v-alert>
                            </div>
                        </v-container>
                        <!-- end time line  -->
                    </v-tooltip>
                </template>

                <template v-slot:item.audioDiscontinuities="{ item }">
                    <span v-if="item.audioDiscontinuities != '0'">
                        <span class="red--text">
                            {{ item.audioDiscontinuities }}
                        </span>
                    </span>
                    <span v-else>
                        <span class="green--text">
                            {{ item.audioDiscontinuities }}
                        </span>
                    </span>
                </template>

                <template v-slot:item.audioScrambled="{ item }">
                    <span v-if="item.audioScrambled != '0'">
                        <span class="red--text">
                            {{ item.audioScrambled }}
                        </span>
                    </span>
                    <span v-else>
                        <span class="green--text">
                            {{ item.audioScrambled }}
                        </span>
                    </span>
                </template>

                <template v-slot:item.videoDiscontinuities="{ item }">
                    <span v-if="item.videoDiscontinuities != '0'">
                        <span class="red--text">
                            {{ item.videoDiscontinuities }}
                        </span>
                    </span>
                    <span v-else>
                        <span class="green--text">
                            {{ item.videoDiscontinuities }}
                        </span>
                    </span>
                </template>

                <template v-slot:item.videoScrambled="{ item }">
                    <span v-if="item.videoScrambled != '0'">
                        <span class="red--text">
                            {{ item.videoScrambled }}
                        </span>
                    </span>
                    <span v-else>
                        <span class="green--text">
                            {{ item.videoScrambled }}
                        </span>
                    </span>
                </template>

                <template v-slot:item.streamStatus="{ item }">
                    <span v-if="item.streamStatus === 'success'">
                        <span class="green--text">
                            stream je v pořádku
                        </span>
                    </span>
                    <span v-else-if="item.streamStatus === 'issue'">
                        <span class="orange--text">
                            {ve streamu je chyba
                        </span>
                    </span>

                    <span v-else-if="item.streamStatus === 'error'">
                        <span class="red--text">
                            Stream Nefunguje
                        </span>
                    </span>
                </template>
            </v-data-table>
        </div>
        <div class="mt-12 body-1" v-else>
            <v-alert type="info">
                Nepodařilo se načíst informace o kanálu
            </v-alert>
        </div>
    </div>
</template>
<script>
// /api/getInformationsFromDohled
export default {
    data() {
        return {
            headers: [
                {
                    text: "Náhled",
                    align: "start",
                    sortable: false,
                    value: "img"
                },
                { text: "Stream", value: "name" },
                { text: "Jazyk audia", value: "audioLang" },
                { text: "Audio CC erory", value: "audioDiscontinuities" },
                { text: "Audio Scrambled", value: "audioScrambled" },
                { text: "Video CC erory", value: "videoDiscontinuities" },
                { text: "Video Scrambled", value: "videoScrambled" },
                { text: "Status", value: "streamStatus" }
            ],
            dohledDataFromStream: null
        };
    },
    created() {
        this.loadInformationFromDohled();
    },
    methods: {
        loadInformationFromDohled() {
            let currentObj = this;
            axios
                .post("/api/getInformationsFromDohled", {
                    channelId: this.$route.params.id
                })
                .then(function(response) {
                    if (response.data.status == "success") {
                        currentObj.dohledDataFromStream =
                            response.data.streamData;
                    } else {
                        currentObj.dohledDataFromStream = null;
                    }
                });
        }
    },
    watch: {
        $route(to, from) {
            this.loadInformationFromDohled();
        }
    },
    mounted() {
        setInterval(
            function() {
                try {
                    this.loadInformationFromDohled();
                } catch (error) {}
            }.bind(this),
            10000
        );
    }
};
</script>
