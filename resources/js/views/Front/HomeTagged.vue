<template>
    <div class="container mt-5">
        <div class="row">
            <p>دسته بندی {{ category }}</p>
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
    import { mapState } from 'vuex';
    import File from '@/components/File.vue';

    export default {
        name: "HomeTagged",

        metaInfo: {
            title: 'کمپ و کار'
        },

        data() {
            return {
                category: null
            }
        },

        components: {
            File
        },

        computed: {
            ...mapState('file', ['files'])
        },

        created() {
            this.fetchFiles(this.$route.query.page);
        },

        methods: {
            async fetchFiles(page = 1) {
                let queries = this.$route.query;
                queries.page = page;

                await this.$store.dispatch('file/getTaggedFiles', {
                    queries,
                    params: this.$route.params
                });
                this.category = this.$store.state.file.files.data[0].categories[0].name
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
