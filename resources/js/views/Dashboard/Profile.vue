<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h2>پروفایل من</h2>
            </div>
            <div class="card-body">
                <form @submit.prevent="changeProfile">
                    <base-input name="name"
                                label="نام"
                                v-model="form.name"
                    ></base-input>
                    <base-input name="email"
                                type="email"
                                label="ایمیل"
                                v-model="form.email"
                    ></base-input>
                    <base-input name="password"
                                type="password"
                                label="رمز عبور"
                                v-model="form.password"
                    ></base-input>

                    <base-photo-input name="profile"
                                      v-model="form.profile"
                                      @src="changeProfileSrc"
                    ></base-photo-input>

                    <base-btn :loading="form.busy" btn="info">ویرایش</base-btn>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { Form } from 'vform';
    import { mapGetters } from 'vuex';

    export default {
        name: "Profile",

        data() {
            return {
                form: new Form({
                    profile: null
                })
            }
        },

        computed: {
            ...mapGetters('auth', ['user'])
        },

        created() {
            this.form = new Form({
                id: this.user.id,
                name: this.user.name,
                email: this.user.email,
                password: null,
                profile: this.user.profile_src,
                profile_name: null,
            })
        },

        methods: {
            changeProfile() {
                this.$store.dispatch('auth/profile', this.form);
            },
            changeProfileSrc(event) {
                this.form.profile_name = event;
            }
        },
    }
</script>

<style scoped>

</style>
