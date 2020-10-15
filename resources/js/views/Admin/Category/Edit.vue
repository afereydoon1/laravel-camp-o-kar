<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ویرایش دسته بندی {{ form.name }}</h4>
                <router-link :to="{ name: 'admin-category' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="updateCategory">
                    <base-input label="نام" name="name" v-model="form.name"/>
                    <base-btn :loading="form.busy">ذخیره</base-btn>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { Form } from 'vform';

    export default {
        name: "Edit",

        metaInfo: {
            title: 'ویرایش دسته بندی'
        },

        data() {
            return {
                form: new Form({})
            }
        },

        created() {
            this.$store.dispatch('category/get', this.$route.params.slug)
                .then(({ data }) => {
                    this.form = new Form({
                        name: data.name,
                        slug: data.slug
                    })
                })
        },

        methods: {
            updateCategory() {
                this.$store.dispatch('category/update', this.form)
                    .then(data => {
                        this.$router.push({ name: 'admin-category'});
                        swal.message();
                    })
            }
        },
    }
</script>

<style scoped>

</style>
