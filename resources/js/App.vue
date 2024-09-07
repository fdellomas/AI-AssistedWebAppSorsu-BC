<template>
    <div>
        <template v-if="showLayout">
            <MainLayout>
                <router-view />
            </MainLayout>
        </template>
        <template v-else>
            <router-view />
        </template>
    </div>
</template>

<script>
    import MainLayout from './layouts/MainLayout.vue';

    export default {
        name: "AI-Assisted Web App",
        components: {
            MainLayout
        },
        computed: {
            showLayout() {
                return this.$route.meta.Layout
            }
        },
    }
</script>