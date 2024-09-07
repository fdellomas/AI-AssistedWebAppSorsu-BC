<template>
    <div class="w-full min-h-screen p-10">
        <header class="mb-5">
            <h1 class="text-2xl font-bold">Profile</h1>
        </header>
        <div class="flex gap-2">
            
            <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
                <header class="mb-5">
                    <h1 class="text-xl font-bold">User Information</h1>
                </header>
                <div class="space-y-2">
                    <div class="group">
                        <label for="first_name" class="text-xs font-bold">First name:</label>
                        <input 
                            type="text" 
                            name="first_name" 
                            id="first_name" 
                            class="w-full p-2 rounded border border-black text-sm"
                            v-model="this.user_data.first_name" 
                        />
                    </div>
                </div>
            </section>
            <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
                <header class="mb-5">
                    <h1 class="text-xl font-bold">Account Settings</h1>
                </header>
                <div class="space-y-2">
                    <div class="group">
                        <label for="email" class="text-xs font-bold">Email:</label>
                        <input 
                            type="text" 
                            name="email" 
                            id="email" 
                            class="w-full p-2 rounded border border-black text-sm" 
                        />
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { useAuthStore } from '../stores/auth';

    export default {
        data() {
            return {
                user_data: {}
            }
        },
        computed: {
            getUserData() {
                const store = useAuthStore()
                this.user_data = store.user
                console.log(store.user)
            }
        },
        methods: {
            updateUserProfile() {
                axios.post('/user/update', {
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    extension: '',
                    email: '',
                    password: '',
                })
                .then(response => {
                    this.user_data = response.data?.user
                })
                .catch(error => {
                    console.log(error)
                })
            }
        },
        mounted() {
            this.getUserData
        }
    }
</script>