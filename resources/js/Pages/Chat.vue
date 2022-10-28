<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

import moment from "moment";
</script>
<script>

export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            moment: moment,
            users: [],
            messages: [],
            userActive: null,
            message: '',
            userLogged: '',
        }
    },
    methods: {
        scrollToBottom: function () {
            if (this.messages.length) {
                document.querySelectorAll('.message:last-child')[0].scrollIntoView()
            }
        },
        loadMessages: async function (userId) {
            axios.get(`api/users/${userId}`).then(response => {
                this.userActive = response.data.user
            })

            await axios.get(`api/messages/${userId}`).then(response => {
                this.messages = response.data.messages
            })

            this.scrollToBottom()
        },
        sendMessage: function () {
            axios.get('api/user/logged').then(response => {
                this.userLogged = response.data.userLogged
            })
            axios.post('api/messages/store', {
                'content': this.message,
                'to': this.userActive.id
            }).then(response => {
                this.messages.push({
                    'from': this.userLogged,
                    'to': this.userActive.id,
                    'content': this.message,
                    'created_at': new Date().toISOString(),
                    'updated_at': new Date().toISOString(),
                })
                this.message = ''
            })
            this.scrollToBottom()
        }
    },
    mounted() {
        axios.get('api/users').then(response => {
            this.users = response.data.users
        })
        axios.get('api/user/logged').then(response => {
            this.userLogged = response.data.userLogged
        
        Echo.private(`user.${this.userLogged}`).listen('.SendMessage', (e)=>{
                console.log(e.message)
            })
        })
    }
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7x1 sm:px-6 lg:px-8">
                <div class="flex overflow-hidden bg-white shadow-xl sm:rounded-lg"
                    style="min-height: 600px; max-height: 600px">
                    <!-- {{-- List Clients --}} -->
                    <div class="w-3/12 flex overflow-y-auto border-r border-gray-200 bg-gray-200 bg-opacity-50">
                        <ul class="w-full">
                            <!-- {{-- Foreach from queue --}} -->
                            <li v-for="user in users" :key="user.id" @click="() => { loadMessages(user.id) }"
                                :class="(userActive && userActive.id == user.id) ? 'bg-gray-400 bg-opacity-50' : ''"
                                class="cursor-pointer border-b border-gray-300 p-6 text-lg font-semibold leading-7 text-gray-600 hover:bg-gray-200 hover:bg-opacity-200">
                                <p class="flex items-center">
                                    {{ user.name }}
                                    <span class="ml-2 h-2 w-2 rounded-full bg-blue-500"></span>
                                </p>
                            </li>
                        </ul>
                    </div>
                    <!--  {{-- Box Message --}} -->
                    <div class="flex w-9/12 flex-1 flex-col justify-between">
                        <div class="flex w-full flex-col overflow-y-auto p-6">
                            <div v-for="message in messages" :key="message.id"
                                :class="(message.from == $page.props.user.id) ? 'text-right' : ''"
                                class="mb-3 w-full message">
                                <p :class="(message.from == $page.props.user.id) ? 'messageFromMe' : 'messageToMe'"
                                    class="inline-block rounded-md bg-indigo-500 bg-opacity-25 p-2"
                                    style="max-width: 75%;">
                                    {{ message.content }}
                                </p>
                                <span
                                    class="mt-1 block text-xs text-gray-500">{{ moment(message.created_at).format("DD/MM/YYY HH:mm") }}</span>
                            </div>

                        </div>
                        <!--   {{-- Form --}} -->
                        <div v-if="userActive" class="w-full border-t border-gray-200 bg-gray-200 bg-opacity-25 p-6">
                            <form v-on:submit.prevent="sendMessage">
                                <div class="flex overflow-hidden rounded-md border border-gray-300">
                                    <input v-model="message" type="text"
                                        class="flex-1 px-4 py-2 text-sm focus:outline-none">
                                    <button type="submit"
                                        class="rounded-full bg-indigo-500 px-4 py-2 text-white hover:bg-indigo-600">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style>
.messageFromMe {
    @apply bg-indigo-300 bg-opacity-25;
}

.messageToMe {
    @apply bg-gray-300 bg-opacity-25;
}
</style>