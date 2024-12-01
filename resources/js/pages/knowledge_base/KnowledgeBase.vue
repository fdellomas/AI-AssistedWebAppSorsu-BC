<template>
    <div class="w-full min-h-screen p-3 md:p-10 pt-20 space-y-5">
        <section class="w-full p-5 rounded-lg shadow-xl border border-gray-400 flex justify-between items-center" v-if="notice">
            <p class="text-xs">
                <span class="text-amber-400 font-bold">Please note:</span>
                Knowledge Base file formatting should use Heading styles as it will be used to programmatically segment the file into sections.
            </p>
            <button @click="()=>notice=false" class="rounded text-black text-gray-400 hover:text-black">
                <v-icon name="bi-x" />
            </button>
        </section>
        <section class="w-full p-3 md:p-10 rounded-lg shadow-xl border border-gray-400">
            <form enctype="multipart/form-data">
                <header class="mb-5 flex items-center justify-between">
                    <h1 class="md:text-2xl font-bold">Knowledge Base</h1>
                    <div class="flex justify-center items-center gap-2">
                        <router-link to="/knowledge-base/archive" class="p-2 text-xs md:text-sm bg-rose-400 hover:bg-rose-600 text-white font-bold rounded">Archive</router-link>
                        <label for="file-upload" class="p-2 text-xs md:text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded cursor-pointer">Upload</label>
                        <input accept=".docx" @change="handleUpload" type="file" name="file-upload" id="file-upload" class="hidden">
                    </div>
                </header>
            </form>
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
                                    <button @click="confirmArchive(file)" class="p-2 text-xs text-yellow-300 hover:text-yellow-600 font-bold bg-gray-500 hover:bg-gray-700 rounded-full">
                                        <v-icon name="fa-archive" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Knowledge Base</h1>
                <div class="flex flex-col md:flex-row justify-center items-center gap-2">
                    <router-link class="p-2 text-sm bg-rose-400 hover:bg-rose-600 text-white font-bold rounded" to="/knowledge-base/archive">Archive</router-link>
                    <router-link class="p-2 text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded" to="/knowledge-base/create">Add new</router-link>
                </div>
            </header>
            <div class="relative h-80 2xl:h-96 overflow-y-auto">
                <table class="table-fixed w-full text-center">
                    <thead class="sticky top-0 bg-gray-400 text-white">
                        <tr>
                            <th>Category</th> -->
                            <!-- <th>Sub Category</th> -->
                            <!-- <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(answer, index) in this.answers" :key="index">
                            <td class="p-2 border-b border-gray-400 font-bold">{{ answer.category }}</td> -->
                            <!-- <td class="p-2 border-b border-gray-400 text-indigo-600">{{ answer.sub_category }}</td> -->
                            <!-- <td class="p-2 border-b border-gray-400 text-blue-400">{{ getDate(answer.created_at) }}</td>
                            <td class="p-2 border-b border-gray-400">
                                <div class="w-full flex flex-wrap justify-center items-center gap-2">
                                    <router-link :to="`/knowledge-base/view/${answer.id}`" class="p-1 text-xs rounded bg-green-400 hover:bg-green-600 text-white">
                                        <v-icon name="fa-eye" />
                                    </router-link>
                                    <router-link :to="`/knowledge-base/edit/${answer.id}`" class="p-1 text-xs rounded bg-blue-400 hover:bg-blue-600 text-white">
                                        <v-icon name="fa-pencil-alt" />
                                    </router-link>
                                    <button @click="confirmDelete(answer.id)" class="p-1 text-xs rounded bg-red-400 hover:bg-red-600 text-white">
                                        <v-icon name="fa-trash" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section> -->
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
                files: [],
                notice: true,
                selectedFile: null,
                disableSubmit: true,
            }
        },
        methods: {
            handleUpload(event) {
                alertify.confirm(
                    'Upload Confirmation',
                    'Continue upload?',
                    () => {
                        const file = event.target.files[0]
                        this.uploadFile(file)
                    },
                    () => {
                        alertify.error('Canceled')
                    }
                )
            },
            async uploadFile(file) 
            {
                const formData = new FormData()
                formData.append('knowledgeBaseFile', file)
                await axios.post('/api/knowledge-base/store', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(response => {
                    alertify.success('SUCCESS')
                    this.files = response.data?.files
                    console.log(response)
                })
                .catch(error => {
                    console.log(error)
                    alertify.error('ERROR')
                    alertify.alert(
                        'Error',
                        error?.response?.data?.message
                    )
                })
                .finally(() => {
                    this.disableSubmit = true
                    this.selectedFile = null
                })
            },
            // confirmDeleteFile(file) {
            //     alertify.confirm(
            //         'Delete',
            //         `Are you sure you want to delete ${file}?`,
            //         () => {
            //             this.deleteFile(file)
            //         },
            //         () => {
            //             alertify.error('Canceled')
            //         }
            //     )
            // },
            // deleteFile(file) {
            //     axios.post('/api/knowledge-base/delete', { file_name: file})
            //     .then(response => {
            //         this.files = response.data?.files
            //     })
            //     .catch(error => {
            //         console.log(error)
            //     })
            // },
            confirmArchive(file) {
                alertify.confirm(
                    'Archive Confirmation',
                    'Do you want to move file to archive?',
                    () => {
                        this.archiveFile(file)
                    },
                    () => {
                        alertify.message('Canceled')
                    }
                )
            },
            archiveFile(file) {
                axios.post('/api/knowledge-base/archive', { file_name: file })
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                    alertify.error('Error')
                    alertify.alert('Error', error.response?.data?.message)
                })
            },
            getKnowledgeBase() {
                axios.get('/api/knowledge-base')
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getAnswers() {
                axios.post('/api/answer')
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
            },
            confirmDelete(id) {
                alertify.confirm(
                    'Delete Confirmation',
                    'Are you sure you want to delete?',
                    () => {
                        this.deleteItem(id)
                    },
                    function() {
                        alertify.error('CANCELED')
                    }
                )
            },
            deleteItem(id) {
                axios.post('/api/answer/delete', { answer_id: id })
                .then(response => {
                    this.answers = response.data?.answers
                    alertify.success('SUCCESS')
                })
                .catch(error => {
                    console.log(error)
                    alertify.error('ERROR')
                    alertify.alert(error?.response?.data?.message)
                })
            },
        },
        mounted() {
            // this.getAnswers()
            this.getKnowledgeBase()
        }
    }
</script>