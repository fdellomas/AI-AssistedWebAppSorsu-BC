<template>
    <div class="w-full min-h-screen flex justify-center items-center p-10">
        <section class="w-full md:w-3/4 p-5 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex justift-start items-center gap-2">
                <router-link class="p-2 text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded" to="/knowledge-base">
                    <v-icon name="fa-arrow-left" />
                </router-link>
                <div class="">
                    <h1 class="text-2xl font-bold">View</h1>
                    <p class="text-sm">View your knowledge base</p>
                </div>
            </header>
            <form class="w-full p-5">
                <div class="flex items-center gap-2 w-full mb-2">
                    <div class="group w-full">
                        <label for="category" class="text-xs font-bold">Category:</label>
                        <input 
                            type="text" 
                            name="category" 
                            id="category" 
                            class="p-2 rounded border border-black w-full" 
                            placeholder="Category"
                            v-model="this.category"
                            readonly
                        />
                    </div>
                </div>
                <div class="w-full space-y-2 mb-2">
                    <div class="w-full group">
                        <label for="knowledge" class="text-xs font-bold">Knowledge:</label>
                        <textarea 
                            name="knowledge" 
                            id="knowledge" 
                            rows="3" 
                            class="w-full p-2 rounded border border-black resize-none"
                            v-model="this.answer"
                            readonly
                        >
                    
                        </textarea>
                    </div>
                </div>
                <div class="w-full group mb-2">
                    <p for="possible_questions" class="text-xs font-bold">Possible Questions:</p>
                    <div class="w-full flex justify-center items-center gap-2 mb-2" v-for="(question, index) in possible_questions" :key="index">
                        <input 
                            type="text" 
                            :name="`questtion-${index}`" 
                            :id="`questtion-${index}`"
                            v-model="possible_questions[index]" 
                            class="w-full p-2 rounded border border-black" 
                            readonly
                        />
                    </div>
                </div>
            </form>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';

    export default {
        data() {
            return {
                category: '',
                sub_category: '',
                answer: '',
                possible_questions: [''],
            }
        },
        methods: {
            async getKnowledgeBase() {
                const id = this.$route.params.answer_id
                await axios.get(`/api/answer/show/${id}`)
                .then(response => {
                    console.log(response)
                    const ans = response.data?.answer
                    this.category = ans?.category
                    this.answer = ans?.answer
                    this.possible_questions = ans?.possible_questions
                })
                .catch(error => {
                    console.log(error)
                })
            },
            // async handleSubmit() {
            //     const id = this.$route.params.answer_id
            //     await axios.patch(`/api/answer/update/${id}`, {
            //         category: this.category,
            //         answer: this.answer,
            //         possible_questions: this.possible_questions,
            //     })
            //     .then(() => {
            //         alertify.success('UPDATED')
            //     })
            //     .catch(error => {
            //         console.log(error)
            //         alertify.alert(
            //             'Update Error',
            //             error.response?.data?.message
            //         )
            //     })
            // },
            // addQuestion() {
            //     let temp = this.possible_questions
            //     temp.push('')
            //     this.possible_questions = temp
            // },
            // deleteQuestion(index) {
            //     let temp = this.possible_questions
            //     temp.splice(index, 1)
            //     this.possible_questions = temp
            // }
        },
        mounted() {
            this.getKnowledgeBase()
        },
    }
</script>