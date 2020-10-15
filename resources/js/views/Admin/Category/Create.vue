<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ایجاد دسته بندی جدید</h4>
                <router-link :to="{ name: 'admin-category' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="storeCategory">
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
        name: "Create",

        metaInfo: {
            title: "ساخت دسته بندی جدید"
        },

        data() {
            return {
                form: new Form({
                    name: null
                })
            }
        },

        methods: {
            storeCategory() {
                this.$store.dispatch('category/store', this.form)
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
