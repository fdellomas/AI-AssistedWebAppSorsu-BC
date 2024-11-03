<template>
    <div :class="`fixed w-full md:w-1/6 top-0 left-0 md:pt-5 bg-gray-100 z-10 overflow-hidden ${this.openMenu ? 'h-full' : 'h-14'}`">
        <div class="w-full p-2 md:hidden relative flex justify-end">
            <button @click="triggerMenu" class="bg-gray-200 rounded text-gray-600 ring-gray-900 active:ring-2 active:text-gray-900 p-1">
                <v-icon name="bi-menu-button-wide-fill" scale="1.8" />
            </button>
        </div>
        <div class="w-full flex justify-center items-center">
            <Logo imgClass="w-20 h-20" />
        </div>
        <h1 class="font-bold text-center mb-5">AI-Assisted Web App</h1>
        <nav class="flex flex-col gap-3 p-5">
            <router-link to="/" class="w-full p-2 rounded" :class="{'bg-slate-400 text-white' : this.isLinkActive('/')}">
                <v-icon name="fa-home" />
                Home
            </router-link>
            <router-link to="/queries" class="w-full p-2 rounded" :class="{'bg-slate-400 text-white' : this.isLinkActive('/queries')}">
                <v-icon name="fa-question-circle" />
                Ask AI
            </router-link>
            <router-link v-if="this.user_role == 'admin'" to="/knowledge-base" class="w-full p-2 rounded" :class="{'bg-slate-400 text-white' : this.isLinkActive('/knowledge-base')}">
                <v-icon name="si-knowledgebase" />    
                Knowledge Base
            </router-link>
            <router-link to="/profile" class="w-full p-2 rounded" :class="{'bg-slate-400 text-white' : isLinkActive('/profile')}">
                <v-icon name="bi-person-square" />    
                Profile
            </router-link>
            <button @click="logout" class="w-full p-2 rounded hover:bg-slate-400 hover:text-white">
                logout
            </button>
        </nav>
    </div>
</template>

<script>
    import { useAuthStore } from '../stores/auth'
    import Logo from './Logo.vue'
    export default {
        data() {
            return {
                user_role: null,
                openMenu: true,
            }
        },
        components: {
            Logo
        },
        methods: {
            isLinkActive(link) {
                if (link == '/') {
                    return link == this.$route.path
                }
                return this.$route.path.startsWith(link)
            },
            setUser() {
                const store = useAuthStore()
                this.user_role = store.user.role
            },
            logout() {
                const store = useAuthStore()
                store.logout()
                this.$router.push('/sign-in')
            },
            triggerMenu() {
                this.openMenu = !this.openMenu
            }
        },
        mounted() {
            this.setUser()
        },
    }
</script>