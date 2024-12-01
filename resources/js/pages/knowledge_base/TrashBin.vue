<template>
    <div class="w-full min-h-screen p-3 md:p-10 pt-20 space-y-5">
        <section class="w-full p-3 md:p-10 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex justify-start items-center gap-2">
                <router-link to="/knowledge-base" class="p-2 rounded text-white bg-blue-400 hover:bg-blue-600">
                    <v-icon name="fa-arrow-left" />
                </router-link>
                <h1 class="md:text-2xl font-bold">Knowledge Base Archive</h1>
            </header>
            <div class="relative h-80 2xl:h-96 overflow-y-auto">
                <table class="table-auto w-full">
                    <thead class="sticky top-0 bg-gray-400 text-white">
                        <tr>
                            <th>File</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(file, index) in this.files" :key="index">
                            <td class="p-2 border-b border-gray-400 font-bold">{{ file }}</td>
                            <td class="p-2 border-b border-gray-400">
                                <div class="w-full flex flex-wrap justify-center items-center gap-2">
                                    <button @click="confirmRestore(file)" class="p-2 text-xs text-white font-bold bg-green-400 hover:bg-green-600 rounded">
                                        <v-icon name="md-restorepage-sharp" />
                                    </button>
                                    <button @click="confirmDelete(file)" class="p-2 text-xs text-white font-bold bg-red-400 hover:bg-red-600 rounded">
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
                files: []
            }
        },
        methods: {
            getArchive() {
                axios.get('/api/knowledge-base/trashbin')
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                })
            },
            confirmRestore(file) {
                alertify.confirm(
                    'Restore Confirmation',
                    'Do you want to restore file?',
                    () => {
                        this.restoreFile(file)
                    },
                    () => {
                        alertify.error('Canceled')
                    }
                )
            },
            restoreFile(file) {
                axios.post('/api/knowledge-base/restore', { file_name: file })
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                    alertify.alertify('Error', error.response?.data?.message)
                })
            },
            confirmDelete(file) {
                alertify.confirm(
                    'Delete Confirmation',
                    'Do you want to permanently delete file?',
                    () => {
                        this.deleteFile(file)
                    },
                    () => {
                        alertify.error('Canceled')
                    }
                )
            },
            deleteFile(file) {
                axios.post('/api/knowledge-base/delete', { file_name: file })
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                    alertify.alertify('Error', error.response?.data?.message)
                })
            },
        },
        mounted() {
            this.getArchive()
        },
    }
</script>