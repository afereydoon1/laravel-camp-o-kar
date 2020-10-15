<template>
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header card-header-primary">
                <h4 class="card-title mt-0">فایل های من</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="">
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                نام
                            </th>
                            <th>
                                قیمت
                            </th>
                            <th>
                                دانلود
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <File v-for="file in files.data" :key="file.id" :file="file" />
                        </tbody>
                    </table>
                    <pagination :data="files" @pagination-change-page="getFiles"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import File from "./File.vue";
    export default {
        name: "MyFiles",
        components: {
            File
        },
        metaInfo: {
            title: 'فایل های من'
        },

        data() {
            return {
                files: {}
            }
        },

        created() {
            this.getFiles()
        },

        methods: {
            async getFiles(page = 1) {
                let { data } = await axios.get('/api/dashboard/my-files?page=' + page);

                this.files = data;
            }
        },
    }
</script>

<style scoped>

</style>
