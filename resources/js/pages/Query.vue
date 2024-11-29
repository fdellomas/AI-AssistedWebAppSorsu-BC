<template>
    <div class="w-full min-h-screen pt-20">
        <section class="w-full h-96 p-10 overflow-y-auto scroll-smooth space-y-5" ref="chatContainer" @scroll="onScroll">
            <div v-for="(log, index) in logs" :key="index" class="w-full p-3 flex flex-col gap-5">
                <div class="w-80 md:w-3/5 self-end rounded-lg bg-blue-400/20 border border-blue-400 p-5">
                    <p class="text-sm"><span class="font-bold">You:</span> <span>{{ log?.question }}</span></p>
                </div>
                <div class="w-80 md:w-3/5 self-start rounded-lg bg-gray-400/20 border border-gray-400 p-5">
                    <p v-html="log.answer.visibleAnswer" class="text-sm"></p>
                </div>
            </div>
        </section>
        <section class="w-full sticky bottom-0">
            <form @submit.prevent="submitQuestion">
                <div class="flex gap-2 items-center p-10">
                    <input type="text" name="question" id="question" v-model="question" class="w-full p-2 rounded text-sm outline-none border border-slate-900" />
                    <button class="w-1/6 p-2 rounded text-white text-sm bg-blue-400 hover:bg-blue-600" type="submit" :disabled="disableBtn">
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
                disableBtn: false,
                logs: [],
            }
        },
        methods: {
            formatResponse(responseText) {
                let formattedText = responseText.replace(/(###\s)([A-Za-z ]+)/g, '<strong>$2:</strong><br>');

                formattedText = formattedText.replace(/(?:^|\n)([\*\-])\s/g, '<br>&bull; ');

                formattedText = formattedText.replace(/(?:^|\n)(\d+\.)\s/g, '<br>$1 ');

                formattedText = formattedText.replace(/\n/g, '<br>');

                formattedText = formattedText.replace(/(<br>)+/g, '<br>');

                return formattedText;
            },
            typeText(fullText) {
                let index = 0;
                const currentLogIndex = this.logs.length - 1; 

                const interval = setInterval(() => {
                    if (index < fullText.length) {
                        this.logs[currentLogIndex].answer.visibleAnswer += fullText[index];
                        index++;
                    } else {
                        clearInterval(interval);
                    }
                }, 40);
            },
            showAnswerEffect(qlg) {
                const newLog = {
                    id: qlg.id,
                    question: qlg.question,
                    answer: {
                        full: qlg.answer,
                        visibleAnswer: '',
                    }
                }
                newLog.answer.full = this.formatResponse(qlg.answer)
                this.logs = [...this.logs, newLog]
                this.typeText(newLog.answer.full)
            },
            logTransfer(queries) {
                let newLogs = []
                queries.forEach(element => {
                    newLogs.push({
                        id: element.id,
                        question: element.question,
                        answer: {
                            full: element.answer,
                            visibleAnswer: this.formatResponse(element.answer)
                        }
                    }) 
                });
                this.logs = newLogs
            },
            submitQuestion() {
                const store = useAuthStore()
                axios.post('/api/query', {
                    user_id: store?.user?.id,
                    question: this.question
                })
                .then(response => {
                    console.log(response)
                    const newLog = response.data?.answer
                    this.question = ''
                    this.showAnswerEffect(newLog)
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
                axios.post('/api/queries', { user_id: store.user?.id })
                .then(response => {
                    const qlg = response.data?.query_log
                    this.logTransfer(qlg)
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
                const container = this.$refs.chatContainer
                const scrollHeightBefore = container.scrollHeight
                const store = useAuthStore()
                axios.post('/api/queries/old', {
                    user_id: store.user.id,
                    last_query_id: this.logs[0].id
                })
                .then(response => {
                    let temp = response.data?.query_log
                    const newLog = {
                        id: temp.id,
                        question: temp.question,
                        answer: {
                            full: temp.answer,
                            visibleAnswer: temp.answer,
                        }
                    }
                    this.logs = [...newLog, ...this.logs]
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