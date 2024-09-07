<template>
    <div>
        <img src="/public/assets/logo/sorsu-logo.png" :class="imgClass" alt="">
    </div>
</template>

<script>
    export default {
        props: {
            imgClass: {
                type: String,
                required: false,
            }
        }
    }
</script>