<template>
    <tr>
        <td>
            <img :src="'/' + file.image_src" :alt="file.name" width="50">
        </td>
        <td>
            {{ file.name }}
        </td>
        <td>
            {{ file.price_in_toman }}
        </td>
        <td>
            <a class="btn btn-default"
               v-if="download.link"
               :href="`/download-zip/${download.link}?access_token=${token}`">دانلود فایل</a>
            <template v-else>
                <base-btn :loading="loading"
                          @click="generateLink"
                          class="btn btn-info"
                >ایجاد لینک</base-btn>
                <base-btn :loading="ftpLoading"
                          @click="generateFtpLink"
                          class="btn btn-info"
                >انتقال به هاست</base-btn>
            </template>
        </td>
    </tr>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: "File",

        props: ['file'],

        data() {
            return {
                loading: false,
                ftpLoading: false,
                download: {}
            }
        },

        computed: {
            ...mapState('auth', ['token'])
        },

        methods: {
            async generateLink() {
                this.loading = true;
                let { data } = await axios.get(`/api/generate-zip/${this.file.slug}`);
                this.loading = false;

                this.download = data;

                swal.fire(
                    'فایل به درستی ایجاد شد.',
                    'رمز عبور فایل: ' + data.password
                );
            },

            async generateFtpLink() {
                this.ftpLoading = true;
                let { data } = await axios.get(`/api/ftp/generate-zip/${this.file.slug}`);
                this.ftpLoading = false;

                this.download = data;

                swal.fire(
                    'فایل به درستی ایجاد شد.',
                    'رمز عبور فایل: ' + data.password
                );
            }
        },
    }
</script>

<style scoped>

</style>
