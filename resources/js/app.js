require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify';
import Chart from 'chart.js';
import Chartkick from 'vue-chartkick'


import VueRouter from 'vue-router';

import CalendarComponent from './components/CalendarComponent.vue';
import LoginComponent from './components/LoginComponent.vue';
import NavBarComponent from './components/NavBarComponent.vue';
import PageNotFoundComponent from './components/PageNotFoundComponent';
import SettingsComponent from './components/SettingsComponent.vue';
import ChannelPackage from './components/channelPackages/ChannelPackageComponent.vue';
import ChannelPackagesComonent from './components/channelPackages/ChannelPackagesComonent.vue';
import AllChannelsComponent from './components/channels/AllChannelsComponent.vue';
import ChannelsDefaultComponent from './components/channels/ChannelsDefaultComponent.vue';
import H264ChannelComponent from './components/channels/H264ChannelComponent.vue';
import H265ChannelComponent from './components/channels/H265ChannelComponent.vue';
import MulticastChannelComponent from './components/channels/MulticastChannelComponent.vue';
import SingleChannelComponent from './components/channels/SingleChannelComponent.vue';
import DeviceComponent from './components/devices/DeviceComponent.vue';
import DevicesComponent from './components/devices/DevicesComponent.vue';
import DevicesDefaultComponent from './components/devices/DevicesDefaultComponent.vue';
import UserComponent from './components/settings/UserComponent.vue';
import WikiComponent from './components/wiki/WikiComponent.vue';
import WikiTopicComponent from './components/wiki/WikiTopicComponent.vue';
import WikiDefaultComponent from './components/wiki/WikiDefaultComponent';
import ChannelPackageDefaultComponent from './components/channelPackages/ChannelPackageDefaultComponent';
import SettingsDefaultComponent from './components/SettingsDefaultComponent';
import TagComponent from './components/settings/TagComponent';

// midleware
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(Chartkick.use(Chart));


// routing
let routes = [
    {
        path: '/',
        component: NavBarComponent,
        children: [
            {
                path: '',
                component: AllChannelsComponent,
                children: [
                    {
                        path: '',
                        component: ChannelsDefaultComponent
                    },
                    {
                        path: '/channel/:id',
                        component: SingleChannelComponent,
                        children: [
                            {
                                path: '',
                                component: MulticastChannelComponent
                            },
                            {
                                path: 'multicast',
                                component: MulticastChannelComponent
                            },
                            {
                                path: 'h264',
                                component: H264ChannelComponent
                            },
                            {
                                path: 'h265',
                                component: H265ChannelComponent
                            }
                        ]
                    },
                ]
            },
            {
                path: 'wiki',
                component: WikiComponent,
                children: [
                    {
                        path: '',
                        component: WikiDefaultComponent
                    },
                    {
                        path: '/wiki/:id',
                        component: WikiTopicComponent
                    },
                ]
            },
            {
                path: 'channelPackages',
                component: ChannelPackagesComonent,
                children: [
                    {
                        path: '',
                        component: ChannelPackageDefaultComponent
                    },
                    {
                        path: '/channelPackages/:nazev',
                        component: ChannelPackage
                    }
                ]
            },
            {
                path: 'devices',
                component: DevicesComponent,
                children: [
                    {
                        path: "",
                        component: DevicesDefaultComponent
                    },
                    {
                        path: '/device/:id',
                        component: DeviceComponent
                    }
                ]
            },
            {
                path: 'settings',
                component: SettingsComponent,
                children: [
                    {
                        path: '',
                        component: SettingsDefaultComponent
                    },
                    {
                        path: "users",
                        component: UserComponent
                    },
                    {
                        path: "tags",
                        component: TagComponent
                    }
                ]
            },
            {
                path: 'calendar',
                component: CalendarComponent
            }

        ]
    },
    {
        path: '/login',
        component: LoginComponent
    },
    {
        path: '*',
        component: PageNotFoundComponent
    },
];


// definice konstant
const router = new VueRouter({
    routes
})
const opts = {}

//module.export
const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        opts
    })
});
