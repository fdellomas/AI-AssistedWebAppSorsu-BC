<template>
    <div class="w-full min-h-screen">
        <section class="absolute hidden md:block w-3/5 h-full top-0 left-0 bg-slate-300">
            <div class="flex flex-col justify-center items-center w-full h-full gap-5">
                <Logo imgClass="w-48 h-48" />
                <div class="text-center">
                    <h1 class="text-2xl font-bold">AI-Assisted Web App For Queries</h1>
                    <p>Sorsogon State University Bulan Campus</p>
                </div>
            </div>
        </section>
        <section class="w-full md:w-2/5 fixed top-0 right-0 p-5">
            <h1 class="text-2xl font-bold mb-5">Sign Up</h1>
            <form @submit.prevent="handleSubmit">
                <div class="w-full flex flex-col items-center gap-5 text-sm">
                    <input 
                        type="text" 
                        name="first_name" 
                        id="first_name" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="First name"
                        v-model="this.first_name"
                        required
                    />
                    <input 
                        type="text" 
                        name="middle_name" 
                        id="middle_name" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="Middle name"
                        v-model="this.middle_name"
                        required
                    />
                    <input 
                        type="text" 
                        name="last_name" 
                        id="last_name" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="Last name"
                        v-model="this.last_name"
                        required
                    />
                    <input 
                        type="text" 
                        name="extension" 
                        id="extension" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="Extension"
                        v-model="this.extension"
                    />
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="Email"
                        v-model="this.email"
                        required
                    />
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="Password"
                        v-model="this.password"
                        required
                    />
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="w-full p-2 rounded ring-1 ring-gray-200" 
                        placeholder="Confirm password"
                        v-model="this.password_confirmation"
                        required
                    />
                    <button type="submit" class="w-full p-2 rounded bg-blue-400 hover:bg-blue-900 text-white font-bold">sign up</button>
                    <p class="text-center text-xs">
                        already have an account? 
                        <router-link to="/sign-in" class="text-blue-400 hover:text-blue-900 font-bold">sign in</router-link>
                    </p>
                </div>
            </form>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import Logo from '../../components/Logo.vue';
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';
    import { useAuthStore } from '../../stores/auth';

    export default {
        data() {
            return {
                first_name: '',
                middle_name: '',
                last_name: '',
                extension: '',
                email: '',
                password: '',
                password_confirmation: '',
            }
        },
        components: {
            Logo
        },
        methods: {
            handleSubmit() {
                const store = useAuthStore()
                axios.post('/api/user/register', {
                    first_name: this.first_name,
                    middle_name: this.middle_name,
                    last_name: this.last_name,
                    extension: this.extension,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                .then(response => {
                    alertify.success('SUCCESS')
                    store.getUser(response.data?.user)
                    store.getToken(response.data?.token)
                    this.first_name = ''
                    this.middle_name = ''
                    this.last_name = ''
                    this.extension = ''
                    this.email = ''
                    this.password = ''
                    this.password_confirmation = ''
                    this.$router.push('/')
                })
                .catch(error => {
                    console.log(error)
                    alertify.error('ERROR')
                })
            }
        },
    }
</script>