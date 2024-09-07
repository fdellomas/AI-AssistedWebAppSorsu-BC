<template>
    <div class="w-full min-h-screen p-10">
        <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Knowledge Base</h1>
                <router-link class="p-2 text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded" to="/knowledge-base/create">Add new</router-link>
            </header>
            <div class="relative h-80 2xl:h-96 overflow-y-auto">
                <table class="table-fixed w-full text-center">
                    <thead class="sticky top-0 bg-gray-400 text-white">
                        <tr>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(answer, index) in this.answers" :key="index">
                            <td class="p-2 border-b border-gray-400 font-bold">{{ answer.category }}</td>
                            <td class="p-2 border-b border-gray-400 text-indigo-600">{{ answer.sub_category }}</td>
                            <td class="p-2 border-b border-gray-400 text-blue-400">{{ getDate(answer.created_at) }}</td>
                            <td class="p-2 border-b border-gray-400">
                                <div class="w-full flex flex-wrap justify-center items-center gap-2">
                                    <button class="p-1 text-xs rounded bg-green-400 hover:bg-green-600 text-white">
                                        <v-icon name="fa-eye" />
                                    </button>
                                    <button class="p-1 text-xs rounded bg-blue-400 hover:bg-blue-600 text-white">
                                        <v-icon name="fa-pencil-alt" />
                                    </button>
                                    <button class="p-1 text-xs rounded bg-red-400 hover:bg-red-600 text-white">
                                        <v-icon name="fa-trash" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                answers: [],
            }
        },
        methods: {
            getAnswers() {
                axios.post('/answer')
                .then(response => {
                    this.answers = response.data?.answers
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getDate(date) {
                const formatted = new Date(date)
                return formatted.toLocaleDateString('en-US')
            }
        },
        mounted() {
            this.getAnswers()
        }
    }
</script>