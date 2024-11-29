<template>
    <div class="w-full h-full">
        <section class="absolute hidden md:block w-3/4 h-full top-0 left-0 bg-slate-300">
            <div class="flex justify-center items-center w-full h-full gap-5">
                <Logo imgClass="w-48 h-48" />
                <div class="text-center">
                    <h1 class="text-2xl font-bold">AI-Assisted Web App For Queries</h1>
                    <p>Sorsogon State University Bulan Campus</p>
                </div>
            </div>
        </section>
        <section class="fixed top-0 right-0 w-full md:w-1/4 h-full p-5 bg- flex flex-col justify-center">
            <form class="space-y-5" @submit.prevent="handleSubmit">
                <h1 class="text-xl font-bold">Login</h1>
                <input 
                    type="text" 
                    name="email" 
                    id="email" 
                    class="w-full p-2 rounded outline-none border border-slate-900" 
                    placeholder="Email"
                    v-model="this.email"
                />
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full p-2 rounded outline-none border border-slate-900" 
                    placeholder="Password"
                    v-model="this.password"
                />
                <button type="submit" class="w-full p-2 rounded bg-slate-600 hover:bg-slate-900 text-white font-bold">sign in</button>
                <p class="text-center">no account yet?
                <router-link to="/sign-up" class="w-full text-center text-blue-400 hover:text-blue-900 hover:font-bold">sign up</router-link></p>
            </form>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import Logo from '../../components/Logo.vue';
    import { useAuthStore } from '../../stores/auth';
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';

    export default {
        data() {
            return {
                email: '',
                password: '',
            }
        },
        components: {
            Logo
        },
        methods: {
            handleSubmit() {
                const store = useAuthStore()
                axios.post('/api/user/login', {
                    email: this.email,
                    password: this.password,
                })
                .then(response => {
                    store.getToken(response.data?.token)
                    store.getUser(response.data?.user)
                    this.$router.push('/')
                })
                .catch(error => {
                    console.log(error.response)
                    alertify.error('ERROR')
                    alertify.alert(error.response?.data?.message)
                })
            }
        }
    }
</script>