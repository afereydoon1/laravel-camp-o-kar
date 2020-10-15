<template>
    <div class="container mt-5">
        <div class="row vh-80">
            <div class="col-3 mt-r5">
                <p>آکمپ و کار: آموزش با طعم استخدام</p>
            </div>
            <main-svg class="col-9"></main-svg>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12" v-for="(file, index) in  files.data">
                <file :file="file" />
            </div>
        </div>
        <div class="row">
            <pagination :data="files" @pagination-change-page="fetchFiles" :limit="5"></pagination>
        </div>
    </div>
</template>

<script>
    import MainSvg from "@/components/svg/MainSvg";
    import { mapState } from 'vuex';
    import File from '@/components/File.vue';

    export default {
        name: "Home",

        metaInfo: {
            title: 'کمپ و کار'
        },

        components: {
            MainSvg,
            File
        },

        computed: {
            ...mapState('file', ['files'])
        },

        created() {
            this.fetchFiles(this.$route.query.page);
        },

        methods: {
            fetchFiles(page = 1) {
                let queries = this.$route.query;
                queries.page = page;

                this.$store.dispatch('file/getUserFiles', queries);
            }
        },
    }
</script>

<style scoped>
    .vh-80 {
        height: 80vh;
    }
    .mt-r5 {
        margin-top: 5rem;
    }
</style>
