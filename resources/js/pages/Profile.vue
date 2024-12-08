<template>
    <div class="w-full min-h-screen p-10">
        <header class="mb-5">
            <h1 class="text-2xl font-bold">Profile</h1>
        </header>
        <div class="flex flex-col md:flex-row gap-2 justify-center items-center">
            
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
                            v-model="this.first_name" 
                        />
                        <p class="text-xs text-red-400">{{ errors.first_name }}</p>
                    </div>
                    <div class="group">
                        <label for="middle_name" class="text-xs font-bold">Middle name:</label>
                        <input 
                            type="text" 
                            name="middle_name" 
                            id="middle_name" 
                            class="w-full p-2 rounded border border-black text-sm"
                            v-model="this.middle_name" 
                        />
                        <p class="text-xs text-red-400">{{ errors.middle_name }}</p>
                    </div>
                    <div class="group">
                        <label for="last_name" class="text-xs font-bold">Last name:</label>
                        <input 
                            type="text" 
                            name="last_name" 
                            id="last_name" 
                            class="w-full p-2 rounded border border-black text-sm"
                            v-model="this.last_name" 
                        />
                        <p class="text-xs text-red-400">{{ errors.last_name }}</p>
                    </div>
                    <div class="group">
                        <label for="extension" class="text-xs font-bold">Extension:</label>
                        <input 
                            type="text" 
                            name="extension" 
                            id="extension" 
                            class="w-full p-2 rounded border border-black text-sm"
                            v-model="this.extension" 
                        />
                    </div>
                    <button @click="validateUser" class="p-2 rounded text-white font-bold bg-blue-400 hover:bg-blue-600 w-full md:w-3/5">save</button>
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
    import axios from 'axios';
    import { useAuthStore } from '../stores/auth';
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';

    export default {
        data() {
            return {
                password_change: false,
                first_name: '',
                middle_name: '',
                last_name: '',
                extension: '',
                email: '',
                old_password: '',
                password: '',
                password_confirmation: '',
                errors: {}
            }
        },
        computed: {
            
        },
        methods: {
            validateAccount() {
                this.errors = {}

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.email) {
                    this.errors.email = 'Email is required.';
                } else if (!emailPattern.test(this.email)) {
                    this.errors.email = 'Please enter a valid email address.';
                }

                if (this.password_change && !this.old_password) {
                    this.errors.old_password = 'Old password is required'
                }

                if (this.password_change && !this.password) {
                    this.errors.password = 'New password is required'
                }

                if (this.password_change && !this.password_confirmation) {
                    this.errors.password_confirmation = 'Please confirm your password'
                } else if (this.password_change && this.password != this.password_confirmation) {
                    this.errors.password_confirmation = 'Password mismatch'
                }

                if (Object.keys(this.errors).length === 0) {
                    this.updateAccount()
                }
            },
            validateUser() {
                this.errors = {}

                if (!this.first_name) {
                    this.errors.first_name = 'First name is required'
                }

                if (!this.middle_name) {
                    this.errors.middle_name = 'Middle name is required'
                }

                if (!this.last_name) {
                    this.errors.last_name = 'Last name is required'
                }

                if (Object.keys(this.errors).length === 0) {
                    this.updateUserProfile()
                }
            },
            updateAccount() {
                const store = useAuthStore()
                axios.post('/api/user/update-account', {
                    user_id: store.user.id,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                .then(response => {
                    alertify.success('SUCCESS')
                    const user = response.data?.user
                    store.getUser(user)
                    this.password = ''
                    this.old_password = ''
                    this.password_confirmation = ''
                    this.password_change = false
                    this.email = user.email
                    alertify.alert('Changes has been saved')
                })
                .catch(error => {
                    alertify.error('ERROR')
                    alertify.alert(error.response?.data?.message)
                })
            },
            updateUserProfile() {
                const store = useAuthStore()
                axios.post('/api/user/update', {
                    user_id: store.user.id,
                    first_name: this.first_name,
                    middle_name: this.middle_name,
                    last_name: this.last_name,
                    extension: this.extension,
                })
                .then(response => {
                    alertify.success('SUCCESS')
                    const user = response.data?.user
                    store.getUser(user)
                    this.first_name = user.first_name
                    this.middle_name = user.middle_name
                    this.last_name = user.last_name
                    this.extension = user.extension
                    alertify.alert('Changes has been saved')
                })
                .catch(error => {
                    alertify.error('ERROR')
                    alertify.alert(error?.response?.data?.message)
                })
            },
            getUserData() {
                const store = useAuthStore()
                const user = store.user
                this.first_name = user.first_name
                this.middle_name = user.middle_name
                this.last_name = user.last_name
                this.extension = user.extension
                this.email = user.email
            },
            changePassword() {
                this.password_change = !this.password_change
            },
        },
        mounted() {
            this.getUserData()
        }
    }
</script>