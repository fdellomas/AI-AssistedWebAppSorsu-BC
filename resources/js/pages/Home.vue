<template>
    <div class="w-full min-h-screen px-20 py-10">
        <section class="w-full flex justify-center items-center">

        </section>
        <div class="w-full 2xl:h-[500px] flex flex-col justify-center items-center scroll-smooth pt-20 pb-10 gap-10">   
            <section class="w-80 md:w-[600px]" v-if="this.role == 'admin'">
                <header class="text-blue-400 text-center">
                    <h1 class="text-2xl font-bold">Post Announcement</h1>
                </header>
                <form @submit.prevent="submitPost" class="space-y-2" enctype="multipart/form-data">
                    <div class="flex gap-2 items-center">
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            v-model="this.title" class="w-full p-2 rounded text-sm outline-none border border-slate-900" 
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
                            v-model="this.post" 
                            class="w-full p-2 rounded text-sm outline-none border border-slate-900" 
                            placeholder="Type here..."
                        />
                        <button class="w-1/6 p-2 rounded text-white text-sm bg-blue-400 hover:bg-blue-600" type="submit">
                            <v-icon name="fa-paper-plane" />
                        </button>
                    </div>
                </form>
            </section>
            <article v-for="(item, index) in posts" :key="index" class="w-80 md:w-[600px] rounded-lg shadow-xl p-10 border border-gray-400">
                <header class="mb-5 text-blue-400">
                    <h1 class="text-xl font-bold">{{ item.title }}</h1>
                    <p class="">{{ formatDateTo12Hour(item.created_at) }}</p>
                </header>
                <div class="w-full">
                    <div class="mb-5">
                        <p>{{ item.message }}</p>
                    </div>
                    <div v-if="item.attachments" class="w-full h-full border border-gray-400">
                        <img v-for="(img,idx) in item.attachments" :key="idx" :src="'/storage/'+img" class="w-full h-full object-contain" />
                    </div>
                </div>
                <!-- <button class="text-xs hover:text-blue-400">
                    <v-icon name="fa-comment" scale="0.7" />
                    Comment
                </button> -->
            </article>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { useAuthStore } from '../stores/auth';

    export default {
        data() {
            return {
                posts: [],
                post: '',
                title: '',
                attachments: [],
                role: '',
            }
        },
        methods: {
            fileUpload(event) {
                this.attachments = Array.from(event.target.files)
            },
            async submitPost() {
                const fd = new FormData()
                fd.append('message', this.post)
                fd.append('title', this.title)
                for (let i = 0; i < this.attachments.length; i++) {
                    fd.append(`images[]`, this.attachments[i]); 
                }
                await axios.post('/post/store', fd, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                })
                .then(response => {
                    console.log(response)
                    const p = response.data?.post
                    this.posts = [p, ...this.posts]
                    this.post = ''
                    this.title = ''
                    this.attachments = []
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getUser() {
                const store = useAuthStore()
                this.role = store.user.role
            },
            async getPosts() {
                await axios.post('/post')
                .then(response => {
                    console.log(response)
                    const p = response.data?.post
                    this.posts = p
                })
                .catch(error => {
                    console.log(error)
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
        },
        mounted() {
            this.getPosts()
            this.getUser()
        },
    }
</script>