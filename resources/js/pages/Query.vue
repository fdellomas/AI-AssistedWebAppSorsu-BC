<template>
    <div class="w-full min-h-screen pt-20">
        <section class="w-full h-96 p-10 overflow-y-auto scroll-smooth space-y-5" ref="chatContainer" @scroll="onScroll">
            <div v-for="(log, index) in logs" :key="index" class="w-full p-3 flex flex-col gap-5">
                <div class="w-80 md:w-3/5 self-end rounded-lg bg-blue-400/20 border border-blue-400 p-5">
                    <p class="text-sm"><span class="font-bold">You:</span> <span>{{ log.question }}</span></p>
                </div>
                <div class="w-80 md:w-3/5 self-start rounded-lg bg-gray-400/20 border border-gray-400 p-5">
                    <p v-for="(item, idx) in log.items" :key="idx" class="text-sm">{{ item?.answer?.answer }}</p>
                </div>
            </div>
        </section>
        <section class="w-full sticky bottom-0">
            <form @submit.prevent="submitQuestion">
                <div class="flex gap-2 items-center p-10">
                    <input type="text" name="question" id="question" v-model="this.question" class="w-full p-2 rounded text-sm outline-none border border-slate-900" />
                    <button class="w-1/6 p-2 rounded text-white text-sm bg-blue-400 hover:bg-blue-600" type="submit">
                        <v-icon name="fa-paper-plane" />
                    </button>
                </div>
            </form>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import { useAuthStore } from '../stores/auth';

    export default {
        data() {
            return {
                question: '',
                logs: [
                    
                ],
            }
        },
        methods: {
            submitQuestion() {
                const store = useAuthStore()
                axios.post('/query', {
                    user_id: store?.user?.id,
                    question: this.question
                })
                .then(response => {
                    this.question = ''
                    let temp = this.logs
                    temp.push(response.data?.answer)
                    this.logs = temp
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.scrollToBottom()
                        }, 600)
                    })
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getQueryLog() {
                const store = useAuthStore()
                axios.post('/queries', { user_id: store.user?.id })
                .then(response => {
                    this.logs = response.data?.query_log
                })
                .catch(error => {
                    console.log(error)
                })
            },
            scrollToBottom() {
                const container = this.$refs.chatContainer
                container.scrollTop = container.scrollHeight
            },
            loadOlderQueries() {
                console.log('loaded')
                const container = this.$refs.chatContainer
                const scrollHeightBefore = container.scrollHeight
                const store = useAuthStore()
                axios.post('/queries/old', {
                    user_id: store.user.id,
                    last_query_id: this.logs[0].id
                })
                .then(response => {
                    let temp = response.data?.query_log
                    this.logs = [...temp, ...this.logs]
                })
                .catch(error => {
                    console.log(error)
                })
                this.$nextTick(() => {
                    setTimeout(() => {
                        const scrollHeightAfter = container.scrollHeight
                        container.scrollTop = scrollHeightAfter - scrollHeightBefore
                    }, 600)
                })
            },
            onScroll() {
                const container = this.$refs.chatContainer
                if (container.scrollTop == 0) {
                    this.loadOlderQueries()
                }
            }
        },
        mounted() {
            this.getQueryLog()
            this.$nextTick(() => {
                setTimeout(() => {
                    this.scrollToBottom()
                }, 600)
            })
        }
    }
</script>