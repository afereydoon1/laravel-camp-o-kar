<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ویرایش کاربر {{ form.name }}</h4>
                <router-link :to="{ name: 'admin-user' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="updateUser">
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
        name: "Edit",

        metaInfo: {
            title: 'ویرایش کاربر'
        },

        data() {
            return {
                form: new Form({})
            }
        },

        computed: {
            url() {
                return `/api/admin/users/${this.$route.params.id}`;
            }
        },

        created() {
            axios.get(this.url)
                .then(({ data }) => {
                    this.form = new Form({
                        id: data.id,
                        name: data.name,
                        email: data.email,
                        password: null,
                        is_admin: !! data.is_admin
                    });
                });
        },

        methods: {
            updateUser() {
                this.form.patch(this.url, this.form)
                    .then((data) => {
                        this.$router.push({ name: 'admin-user'});
                        swal.message();
                    })
            }
        },
    }
</script>

<style scoped>

</style>
