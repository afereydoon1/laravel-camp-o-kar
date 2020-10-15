<template>
    <component :is="componentName"></component>
</template>

<script>
    import Show from "./Show";
    import NotFound from '@/views/errors/NotFound';

    export default {
        name: "App",

        props: ['url'],

        components: {
            Show, NotFound
        },

        computed: {
            componentName() {
                return ['show'].includes(this.url) ? this.url : 'not-found'
            }
        }
    }
</script>

<style scoped>

</style>
