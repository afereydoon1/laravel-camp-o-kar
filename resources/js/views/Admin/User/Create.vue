<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ایجاد کاربر جدید</h4>
                <router-link :to="{ name: 'admin-user' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="storeUser">
                    <base-input label="نام"
                                name="name"
                                v-model="form.name"
                    />
                    <base-input label="ایمیل"
                                type="email"
                                name="email"
                                v-model="form.email"
                    />
                    <base-input label="رمز عبور"
                                type="password"
                                name="password"
                                v-model="form.password"
                    />
                    <base-check label="ادمین"
                                name="is_admin"
                                v-model="form.is_admin"
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
            title: 'ایجاد کاربر جدید'
        },

        data() {
            return {
                form: new Form({
                    name: null,
                    email: null,
                    password: 'password',
                    is_admin: false
                })
            }
        },

        methods: {
            storeUser() {
                this.form.post('/api/admin/users', this.form)
                    .then(() => {
                        this.$router.push({ name: 'admin-user' });
                        swal.message();
                    })
            }
        },
    }
</script>

<style scoped>

</style>
