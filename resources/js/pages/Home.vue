<template>
    <div class="w-full min-h-screen px-20 py-10">
        <section class="w-full flex justify-center items-center">

        </section>
        <div class="w-full h-96 2xl:h-[500px] overflow-y-auto flex flex-col justify-center items-center scroll-smooth pt-20">
            <article class="w-80 md:w-[600px] rounded-lg shadow-xl p-10 border border-gray-400">
                <header class="mb-5 text-blue-400">
                    <h1 class="text-xl font-bold">Title</h1>
                    <p class="">date</p>
                </header>
                <div class="w-full mb-5 border-b border-gray-400">
                    <div class="w-full h-full border border-gray-400">
                        
                    </div>
                    <p>post</p>
                </div>
                <button class="text-xs hover:text-blue-400">
                    <v-icon name="fa-comment" scale="0.7" />
                    Comment
                </button>
            </article>
        </div>
        <section class="w-5/6 fixed right-0 bottom-0" v-if="this.role == 'admin'">
            <form @submit.prevent="submitPost" class="space-y-2" enctype="multipart/form-data">
                <div class="flex gap-2 items-center px-10">
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        v-model="this.title" class="w-full p-2 rounded text-sm outline-none border border-slate-900" 
                        placeholder="About / Title"
                    />
                    <button class="w-1/6 p-2 rounded text-white text-sm bg-yellow-400 hover:bg-yellow-600" type="button">
                        <v-icon name="fa-paperclip" />
                    </button>
                    <input 
                        type="file" 
                        name="" 
                        id="" 
                        class="hidden" 
                    />
                </div>
                <div class="flex gap-2 items-center px-10 pb-10">
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
            submitPost() {
                axios.post('', {

                })
            },
            getUser() {
                const store = useAuthStore()
                this.role = store.user.role
            },
            handleImages(e) {
                
            },
        },
        mounted() {
            this.getUser()
        },
    }
</script>