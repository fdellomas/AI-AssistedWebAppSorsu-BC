<template>
    <div class="w-full min-h-screen flex justify-center items-center p-10">
        <section class="w-full md:w-3/4 p-5 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5">
                <h1 class="text-2xl font-bold">Create</h1>
                <p class="text-sm">Add new knowledge to your AI</p>
            </header>
            <form class="w-full p-5" @submit.prevent="handleSubmit">
                <div class="flex items-center gap-2 w-full mb-2">
                    <div class="group w-full md:w-1/2">
                        <label for="category" class="text-xs font-bold">Category:</label>
                        <input 
                            type="text" 
                            name="category" 
                            id="category" 
                            class="p-2 rounded border border-black w-full" 
                            placeholder="Category"
                            v-model="this.category"
                            required
                        />
                    </div>
                    <div class="group w-full md:w-1/2">
                        <label for="subcategory" class="text-xs font-bold">Sub Category:</label>
                        <input 
                            type="text" 
                            name="subcategory" 
                            id="subcategory" 
                            class="p-2 rounded border border-black w-full" 
                            placeholder="Sub Category"
                            v-model="this.sub_category"
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
                            required
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
                            required
                        />
                        <button v-if="index == 0" @click="addQuestion" type="button" class="p-2 text-white bg-green-400 hover:bg-green-600 rounded">
                            <v-icon name="fa-plus-square" />
                        </button>
                        <button v-else @click="deleteQuestion(index)" type="button" class="p-2 text-white bg-red-400 hover:bg-red-600 rounded">
                            <v-icon name="fa-trash" />
                        </button>
                    </div>
                </div>
                <button class="submit w-full md:w-1/3 p-2 rounded bg-blue-400 hover:bg-blue-600 text-white font-bold">submit</button>
            </form>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';

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
            handleSubmit() {
                axios.post('/answer/store', {
                    category: this.category,
                    sub_category: this.sub_category,
                    answer: this.answer,
                    possible_questions: this.possible_questions,
                })
                .then(response => {
                    this.category = ''
                    this.sub_category = ''
                    this.answer = ''
                    this.possible_questions = ['']
                })
                .catch(error => {
                    console.log(error)
                })
            },
            addQuestion() {
                let temp = this.possible_questions
                temp.push('')
                this.possible_questions = temp
            },
            deleteQuestion(index) {
                let temp = this.possible_questions
                temp.splice(index, 1)
                this.possible_questions = temp
            }
        }
    }
</script>