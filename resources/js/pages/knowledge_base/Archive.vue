<template>
    <div class="w-full min-h-screen p-10 space-y-5">
        <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex items-center justify-start gap-2">
                <router-link class="p-2 text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded" to="/knowledge-base">
                    <v-icon name="fa-arrow-left" />
                </router-link>
                <h1 class="text-2xl font-bold">Knowledge Base Archive</h1>
            </header>
            <div class="relative h-80 2xl:h-96 overflow-y-auto">
                <table class="table-fixed w-full text-center">
                    <thead class="sticky top-0 bg-gray-400 text-white">
                        <tr>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Date Deleted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(answer, index) in this.answers" :key="index">
                            <td class="p-2 border-b border-gray-400 font-bold">{{ answer.category }}</td>
                            <td class="p-2 border-b border-gray-400 text-indigo-600">{{ answer.sub_category }}</td>
                            <td class="p-2 border-b border-gray-400 text-blue-400">{{ getDate(answer.deletedAt) }}</td>
                            <td class="p-2 border-b border-gray-400">
                                <div class="w-full flex flex-wrap justify-center items-center gap-2">
                                    <button @click="confirmRestore(answer.id)" class="p-1 text-xs rounded bg-green-400 hover:bg-green-600 text-white">
                                        <v-icon name="fa-trash-restore-alt" />
                                    </button>
                                    <button @click="confirmDelete(answer.id)" class="p-1 text-xs rounded bg-red-400 hover:bg-red-600 text-white">
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
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';

    export default {
        data() {
            return {
                answers: [],
            }
        },
        methods: {
            async getArchive() {
                await axios.get('/api/answer/archive')
                .then(response => {
                    this.answers = response.data?.archive
                })
                .catch(error => {
                    console.log(error)
                })
            },
            async destroyItem(id) {
                await axios.delete(`/api/destroy/${id}`)
                .then(response => {
                    this.answers = response.data?.archive
                    alertify.success('DELETED')
                })
                .catch(error => {
                    console.log(error)
                    alertify.alert('Delete Error', error.response?.data?.message)
                })
            },
            async restoreItem(id) {
                await axios.patch(`/api/answer/restore/${id}`)
                .then(response => {
                    this.answers = response.data?.archive
                    alertify.success('RESTORED')
                })
                .catch(error => {
                    console.log(error)
                    alertify.alert('Restore Error', error.response?.data?.message)
                })
            },
            confirmDelete(id) {
                alertify.confirm(
                    'Delete Knowledge Base',
                    'Are you sure you want to permanently delete this?',
                    () => {
                        this.destroyItem(id)
                    },
                    () => {
                        alertify.message('CANCELLED')
                    }
                )
            },
            confirmRestore(id) {
                alertify.confirm(
                    'Restore Knowledge Base',
                    'Are you sure you want to restore this?',
                    () => {
                        this.restoreItem(id)
                    },
                    () => {
                        alertify.message('CANCELLED')
                    }
                )
            },
            getDate(date) {
                const formatted = new Date(date)
                return formatted.toLocaleDateString('en-US')
            },
        },
        mounted() {
            this.getArchive()
        }
    }
</script>