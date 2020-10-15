<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ایجاد کد تخفیف جدید</h4>
                <router-link :to="{ name: 'admin-discount' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="storeDiscount">
                    <base-input label="درصد تخفیف"
                                name="percent"
                                v-model="form.percent"
                    />
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
            title: 'ساخت کد تخفیف'
        },

        data() {
            return {
                form: new Form({
                    percent: null
                })
            }
        },

        methods: {
            storeDiscount() {
                this.form.post('/api/admin/discount')
                    .then(() => {
                        swal.message('کد تخفیف به درستی ایجاد شد');
                        this.$router.push({ name: 'admin-discount' });
                    })
            }
        },
    }
</script>

<style scoped>

</style>
