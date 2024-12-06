<template>
    <div class="w-full min-h-screen flex flex-col justift-center items-center p-3">
        <section class="w-full md:w-[600px] pt-12 md:pt-5">
                <header class="text-blue-400 text-center">
                    <h1 class="text-2xl font-bold">Edit Announcement</h1>
                </header>
                <form @submit.prevent="handleSubmit" class="space-y-2" enctype="multipart/form-data">
                    <div class="flex gap-2 items-center">
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            v-model="this.post.title" class="w-full p-2 rounded text-sm outline-none border border-slate-900" 
                            placeholder="About / Title"
                        />
                        <label class="block w-1/6 p-2 rounded text-center text-white text-sm bg-yellow-400 hover:bg-yellow-600 cursor-pointer" for="images">
                            <v-icon name="fa-paperclip" />
                        </label>
                        <input 
                            type="file" 
                            name="images" 
                            id="images" 
                            class="hidden" 
                            @change="fileUpload"
                            accept="image/jpeg,image/jpg,image/png,image/gif,image/svg"
                            multiple
                        />
                    </div>
                    <div class="flex gap-2 items-center pb-10">
                        <input 
                            type="text" 
                            name="post" 
                            id="post" 
                            v-model="this.post.message" 
                            class="w-full p-2 rounded text-sm outline-none border border-slate-900" 
                            placeholder="Type here..."
                        />
                    </div>
                </form>
            </section>
            <article class="w-full md:w-[600px] rounded-lg shadow-xl py-5 px-10 border border-gray-400">
                <header class="mb-5 text-blue-400">
                    <div class="flex justify-end items-center w-full">
                        <button @click="confirmDelete(post.id)" class="p-1 rounded text-white bg-rose-400 hover:bg-rose-600 flex justify-center items-center">
                            <v-icon name="fa-trash" scale="0.7" />
                        </button>
                    </div>
                    <h1 class="text-xl font-bold">{{ post.title }}</h1>
                    <p class="">{{ formatDateTo12Hour(post.created_at) }}</p>
                </header>
                <div class="w-full">
                    <div class="mb-5">
                        <p>{{ post.message }}</p>
                    </div>
                    <div v-if="post.attachments" class="w-full h-full border border-gray-400">
                        <div v-if="images.length>0">
                            <div v-for="(fileImg,inx) in this.imageUrls" :key="inx">
                                <img :src="fileImg" alt="uploaded-img">
                            </div>
                        </div>
                        <div v-else>
                            <div v-for="(img,idx) in post.attachments" :key="idx" class="relative border-b border-gray-400">
                                <button @click="removeAttachment(idx)" class="absolute top-1 right-1 p-1 rounded bg-rose-400 hover:bg-rose-600 text-white flex justify-center items-center">
                                    <v-icon name="fa-trash" scale="0.7" />
                                </button>
                                <img :src="'/storage/'+img" class="w-full h-full object-contain" />
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        
            <button @click="handleSubmit" class="w-1/6 p-2 rounded text-white text-sm bg-blue-400 hover:bg-blue-600 mt-10 mb-20">
                            <v-icon name="md-update" />
                            Update
                        </button>
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
                post: {
                    id: null,
                    title: '',
                    message: '',
                    attachments: [],
                    created_at: new Date(0),
                },
                images: [],
                imageUrls: [],
            }
        },
        methods: {
            fileUpload(event) {
                const files = event.target.files
                this.images = Array.from(event.target.files)
                Array.from(files).forEach((file) => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        this.imageUrls.push(e.target.result);
                    };

                    reader.readAsDataURL(file); 
                    } else {
                    alert("Please select only image files.");
                    }
                })
            },
            async handleSubmit() {
                const imageBase64Array = []
                for (const image of this.images) {
                    const base64 = await this.convertToBase64(image)
                    imageBase64Array.push(base64)
                }
                const id = this.$route.params.post_id
                await axios.patch(`/api/post/edit/${id}`, 
                {
                    title: this.post.title,
                    message: this.post.message,
                    attachments: this.post.attachments,
                    images: imageBase64Array
                }
                )
                .then(response => {
                    console.log(response)
                    this.post = response.data?.post
                    alertify.success('UPDATED')
                })
                .catch(error => {
                    console.log(error)
                    alertify.alert(
                        'Update Error',
                        error.response?.data?.message
                    )
                })
            },
            async getPost() {
                const id = this.$route.params.post_id
                await axios.get(`/api/post/show/${id}`)
                .then(response => {
                    this.post = response.data?.post
                })
            },
            formatDateTo12Hour(date) {
                const newDate = new Date(date)
                const options = {
                    year: "numeric",
                    month: "long",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: true
                };
                return new Intl.DateTimeFormat("en-PH", options).format(newDate);
            },
            removeAttachment(index) {
                const temp = [...this.post.attachments]
                temp.splice(index, 1)
                this.post.attachments = temp
            },
            convertToBase64(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader()
                    reader.readAsDataURL(file)

                    reader.onload = () => resolve(reader.result)
                    reader.onerror = (error) => reject(error)
                })
            },
            confirmDelete(id) {
                alertify.confirm(
                    'Delete Confirmation',
                    'Do you want to permanently delete this post?',
                    () => {
                        this.deletePost(id)
                    },
                    () => {
                        alertify.error('Canceled')
                    }
                )
            },
            deletePost(id) {
                axios.delete(`/api/post/delete/${id}`)
                .then(() => {
                    this.$router.push('/')
                })
                .catch(error => {
                    console.log(error)
                })
            }
        },
        mounted() {
            this.getPost()
        },
    }
</script>