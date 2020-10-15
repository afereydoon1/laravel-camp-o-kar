<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ویرایش اشتراک ویژه {{ form.name }}</h4>
                <router-link :to="{ name: 'admin-membership' }" class="btn btn-outline-primary text-white"> بازگشت <i class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="updateMembership">
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
        name: "Edit",

        metaInfo: {
            title: 'ویرایش اشتراک ویژه'
        },

        data() {
            return {
                form: new Form({})
            }
        },

        created() {
            this.$store.dispatch('membership/get', this.$route.params.id)
                .then((data) => {
                    this.form = new Form({
                        id: data.id,
                        name: data.name,
                        price: data.price,
                        description: data.description,
                        is_yearly: data.is_yearly,
                        priority: data.priority
                    });
                })
        },

        methods: {
            updateMembership() {
                this.$store.dispatch('membership/update', this.form)
                    .then(() => {
                        this.$router.push({ name: 'admin-membership' });
                        swal.message('اشتراک ویژه به درستی اپدیت شد.');
                    })
            }
        },
    }
</script>

<style scoped>

</style>
