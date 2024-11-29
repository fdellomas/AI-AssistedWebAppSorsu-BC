<template>
    <div class="w-full min-h-screen p-10">
        <header class="mb-5">
            <h1 class="text-2xl font-bold">Profile</h1>
        </header>
        <div class="flex flex-col md:flex-row gap-2 justify-center items-center">
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
                            v-model="this.email"
                        />
                        <p class="text-xs text-red-400">{{ errors.email }}</p>
                        <button @click="changePassword" class="text-xs text-white bg-gray-400 hover:bg-gray-600 rounded p-1">change password</button>
                    </div>
                    <div class="group">
                        <label for="email" class="text-xs font-bold">Password:</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full p-2 rounded border border-black text-sm" 
                            :disabled="!this.password_change"
                            v-model="this.old_password"
                        />
                        <p class="text-xs text-red-400">{{ errors.old_password }}</p>
                    </div>
                    <div class="group">
                        <label for="new_password" class="text-xs font-bold">New Password:</label>
                        <input 
                            type="password" 
                            name="new_password" 
                            id="new_password" 
                            class="w-full p-2 rounded border border-black text-sm" 
                            :disabled="!this.password_change"
                            v-model="this.password"
                        />
                        <p class="text-xs text-red-400">{{ errors.password }}</p>
                    </div>
                    <div class="group">
                        <label for="password_confirmation" class="text-xs font-bold">Confirm Password:</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full p-2 rounded border border-black text-sm" 
                            :disabled="!this.password_change"
                            v-model="this.password_confirmation"
                        />
                        <p class="text-xs text-red-400">{{ errors.password_confirmation }}</p>
                    </div>
                    <button @click="validateAccount" class="p-2 rounded text-white bg-indigo-400 hover:bg-indigo-600 font-bold w-full md:w-3/5">save</button>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import { useAuthStore } from '../stores/auth';
    import axios from 'axios';
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';

    export default {
        data() {
            return {
                password_change: false,
                email: '',
                old_password: '',
                password: '',
                password_confirmation: '',
                errors: {}
            }
        },
        methods: {
            getUserData() {
                const store = useAuthStore()
                const user = store.user
                this.first_name = user.first_name
                this.middle_name = user.middle_name
                this.last_name = user.last_name
                this.extension = user.extension
                this.email = user.email
            },
            async updateAccount() {

            }
        },
        mounted() {
            this.getUserData()
        },
    }
</script>