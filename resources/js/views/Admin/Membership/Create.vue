<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ایجاد اشتراک ویژه جدید</h4>
                <router-link :to="{ name: 'admin-membership' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="storeMembership">
                    <base-input label="نام"
                                name="name"
                                v-model="form.name"
                    />
                    <base-input label="قیمت(هزار تومان)"
                                name="price"
                                v-model="form.price"
                    />
                    <base-input label="اولیت"
                                name="priority"
                                v-model="form.priority"
                    />
                    <base-input label="توضیحات"
                                name="description"
                                v-model="form.description"
                    />
                    <base-check label="اشتراک سالیانه"
                                name="is_yearly"
                                field="is_yearly"
                                v-model="form.is_yearly"
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
            title: 'ایجاد اشتراک ویژه'
        },

        data() {
            return {
                form: new Form({
                    name: null,
                    price: null,
                    priority: null,
                    description: null,
                    is_yearly: false
                })
            }
        },

        methods: {
            storeMembership() {
                this.$store.dispatch('membership/store', this.form)
                    .then(data => {
                        this.$router.push({ name: 'admin-membership' });
                    });
            }
        },
    }
</script>

<style scoped>

</style>
