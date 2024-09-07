<template>
    <div class="fixed w-full md:w-1/6 h-full top-0 left-0 p-5 pt-20 md:pt-5 bg-gray-100">
        <h1 class="font-bold mb-5">AI-Assisted Web App</h1>
        <nav class="flex flex-col gap-3">
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
    export default {
        data() {
            return {
                user_role: null,
            }
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
            }
        },
        mounted() {
            this.setUser()
        },
    }
</script>