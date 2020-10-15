<template>
    <component :is="componentName"></component>
</template>

<script>
    import Index from "./Index";
    import MyFiles from "./MyFiles";
    import Membership from "./Membership";
    import Profile from "./Profile";
    import NotFound from '@/views/errors/NotFound';

    export default {
        name: "DashboardRoutes",
        props: {
            url: {
                type: String,
                default: 'index'
            }
        },
        components: {
            Index,
            MyFiles,
            Membership,
            Profile,
            NotFound
        },
        computed: {
            componentName() {
                return [
                    'membership', 'index', 'my-files', 'profile'
                ].includes(this.url) ? this.url : 'not-found';
            }
        }
    }
</script>

<style scoped>

</style>
