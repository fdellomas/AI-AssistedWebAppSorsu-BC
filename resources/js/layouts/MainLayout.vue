<template>
    <main class="w-full h-full">
        <Navigation />
        <div class="w-full md:w-5/6 absolute top-0 right-0">
            <slot></slot>
        </div>
    </main>
</template>

<script>
    import Navigation from '../components/Navigation.vue';

    export default {
        components: {
            Navigation
        }
    }
</script>