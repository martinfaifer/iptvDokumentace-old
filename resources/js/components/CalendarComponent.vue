<template>
    <div>
        <v-container>
            <v-row justify="center" class="mt-12">
                <h3>Kalendář planovaných výpadků kanálů</h3>
            </v-row>
            <v-row justify="center" class="mt-3">
                <small
                    >Kanály, které jsou zde zavedené nebudou po dobu výpadku
                    zasílat alert</small
                >
            </v-row>
            <v-row class="mt-12">
                <v-col>
                    <v-sheet color="transparent" height="600">
                        <v-toolbar flat color="transparent">
                            <v-btn
                                @click="openModal()"
                                outlined
                                color="teal darken-2"
                            >
                                Přidat novou událost
                            </v-btn>
                            <v-btn
                                outlined
                                class="ml-6"
                                color="grey darken-2"
                                @click="setToday"
                            >
                                Dnes
                            </v-btn>
                            <v-btn
                                fab
                                text
                                small
                                color="grey darken-2"
                                @click="prev"
                            >
                                <v-icon small>mdi-chevron-left</v-icon>
                            </v-btn>
                            <v-btn
                                fab
                                text
                                small
                                color="grey darken-2"
                                @click="next"
                            >
                                <v-icon small>mdi-chevron-right</v-icon>
                            </v-btn>
                            <v-toolbar-title v-if="$refs.calendar">
                                {{ $refs.calendar.title }}
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-menu bottom right>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        outlined
                                        color="grey darken-2"
                                        v-bind="attrs"
                                        v-on="on"
                                    >
                                        <span>{{ typeToLabel[type] }}</span>
                                        <v-icon right>mdi-menu-down</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item @click="type = 'day'">
                                        <v-list-item-title
                                            >Denní</v-list-item-title
                                        >
                                    </v-list-item>
                                    <v-list-item @click="type = 'week'">
                                        <v-list-item-title
                                            >Týdenní</v-list-item-title
                                        >
                                    </v-list-item>
                                    <v-list-item @click="type = 'month'">
                                        <v-list-item-title
                                            >Měsíční</v-list-item-title
                                        >
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-toolbar>
                        <v-calendar
                            class="rounded"
                            ref="calendar"
                            :weekdays="weekday"
                            @click:event="showEvent"
                            @click:more="viewDay"
                            @click:date="viewDay"
                            event-color="red darken-3"
                            :events="events"
                            color="teal"
                            :type="type"
                            v-model="focus"
                        ></v-calendar>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>

        <!-- create event dialog -->
        <v-row justify="center">
            <v-dialog v-model="newEventDialog" persistent max-width="800">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-autocomplete
                                    v-model="channelId"
                                    :items="channels"
                                    item-text="nazev"
                                    item-value="id"
                                    label="Vyberte kanál"
                                ></v-autocomplete>
                            </v-col>
                        </v-row>
                        <!-- zacatek -->
                        <v-row class="ml-6" v-show="channelId != ''">
                            <v-col cols="12" sm="6" md="4">
                                <v-menu
                                    ref="menu"
                                    v-model="startMenu"
                                    :close-on-content-click="false"
                                    :return-value.sync="date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="startDate"
                                            label="datum začátku výpadku"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="startDate"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="startMenu = false"
                                            >Zavřít</v-btn
                                        >
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-spacer> </v-spacer>
                            <v-col cols="12" sm="6" md="4">
                                <v-menu
                                    ref="menu"
                                    v-model="startMenuTime"
                                    :close-on-content-click="false"
                                    :return-value.sync="date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="startTime"
                                            label="čas začátku výpadku"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-model="startTime"
                                        no-title
                                        format="24hr"
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="startMenuTime = false"
                                            >Zavřít</v-btn
                                        >
                                    </v-time-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <!-- konec -->
                        <v-row
                            class="ml-6"
                            v-show="
                                channelId != '' ||
                                    startDate != '' ||
                                    startTime != ''
                            "
                        >
                            <v-col cols="12" sm="6" md="4">
                                <v-menu
                                    ref="menu"
                                    v-model="endMenu"
                                    :close-on-content-click="false"
                                    :return-value.sync="date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="endDate"
                                            label="datum konce výpadku"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="endDate"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="endMenu = false"
                                            >Zavřít</v-btn
                                        >
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-spacer> </v-spacer>
                            <v-col cols="12" sm="6" md="4">
                                <v-menu
                                    ref="menu"
                                    v-model="endMenuTime"
                                    :close-on-content-click="false"
                                    :return-value.sync="date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="endTime"
                                            label="čas konce výpadku"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-model="endTime"
                                        format="24hr"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="endMenuTime = false"
                                            >Zavřít</v-btn
                                        >
                                    </v-time-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="red darken-1"
                            text
                            @click="closeDialogAndResetImputs()"
                            >Zavřít</v-btn
                        >
                        <v-btn
                            color="green darken-1"
                            text
                            :disabled="
                                endTime === '' ||
                                    startTime === '' ||
                                    endDate === '' ||
                                    startDate === '' ||
                                    channelId === ''
                            "
                            @click="saveEditDialog()"
                            >Uložit</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <!-- end create event dialog -->
    </div>
</template>
<script>
export default {
    data: () => ({
        date: new Date().toISOString().substr(0, 10),
        startMenu: false,
        endMenu: false,
        endMenuTime: false,
        startMenuTime: false,
        focus: "",
        endTime: "",
        startTime: "",
        endDate: "",
        startDate: "",
        channelId: "",
        channels: [],
        newEventDialog: false,
        today: new Date(),
        events: [],
        type: "month",
        typeToLabel: {
            month: "měsíční",
            week: "týdenní",
            day: "denní"
        },
        weekday: [1, 2, 3, 4, 5, 6, 0]
    }),
    created() {
        this.loadCalendarData();
    },
    mounted() {
        this.$refs.calendar.scrollToTime("08:00");
        this.$refs.calendar.checkChange();
    },
    methods: {
        loadCalendarData() {
            window.axios.get("/api/calendar").then(response => {
                this.events = response.data;
            });
        },
        viewDay({ date }) {
            this.focus = date;
            this.type = "day";
        },
        setToday() {
            this.focus = "";
        },
        prev() {
            this.$refs.calendar.prev();
        },
        next() {
            this.$refs.calendar.next();
        },
        loadChannels() {
            window.axios.get("/api/calendar/channels").then(response => {
                this.channels = response.data;
            });
        },
        openModal() {
            this.newEventDialog = true;
            this.loadChannels();
        },
        closeDialogAndResetImputs() {
            this.newEventDialog = false;
            this.startTime = "";
            this.startDate = "";
            this.endTime = "";
            this.endDate = "";
            this.channelId = "";
        },
        saveEditDialog() {
            let currentObj = this;
            axios
                .post("/api/calendar/save", {
                    channelId: this.channelId,
                    startTime: this.startTime,
                    startDate: this.startDate,
                    endTime: this.endTime,
                    endDate: this.endDate
                })
                .then(function(response) {
                    currentObj.status = response.data;
                    currentObj.newEventDialog = false;
                    currentObj.channelId = "";
                    currentObj.startDate = "";
                    currentObj.startTime = "";
                    currentObj.endTime = "";
                    currentObj.endDate = "";
                    currentObj.loadCalendarData();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        showEvent({ nativeEvent, event }) {
            const open = () => {
                this.selectedEvent = event;
                this.selectedElement = nativeEvent.target;
                setTimeout(() => (this.selectedOpen = true), 10);
            };
            if (this.selectedOpen) {
                this.selectedOpen = false;
                setTimeout(open, 10);
            } else {
                open();
            }
            nativeEvent.stopPropagation();
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a;
        }
    }
};
</script>
