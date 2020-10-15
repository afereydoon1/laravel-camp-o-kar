<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">ویرایش فایل {{ form.name }}</h4>
                <router-link :to="{ name: 'admin-file' }" class="btn btn-outline-primary text-white"> بازگشت <i
                    class="fa fa-arrow-left"></i></router-link>
            </div>
            <div class="card-body">
                <form @submit.prevent="updateFile">
                    <base-input label="نام" name="name" v-model="form.name"/>
                    <div class="form-group">
                        <label for="description">توضیحات</label>
                        <div class="form-group bmd-form-group">
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      v-model="form.description"
                            ></textarea>
                            <has-error :form="form" field="description"></has-error>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="file">فایل</label>
                        <input id="file" type="file" @change="changeFile" />
                        <has-error :form="form" field="file"></has-error>
                    </div>
                    <div class="col-md-12 mt-4">
                        <label for="image">عکس</label>
                        <input id="image" type="file" @change="changeImage" />
                        <has-error :form="form" field="image"></has-error>
                    </div>
                    <base-input label="قیمت" name="price" v-model="form.price"/>
                    <tags-input element-id="tags"
                                :wrapper-class="form.errors.has('selectedTags.0') ? 'tags-input-wrapper-default tags-input form-control is-invalid' : 'tags-input-wrapper-default tags-input'"
                                v-model="form.selectedTags"
                                :only-existing-tags="true"
                                :existing-tags="categories"
                                placeholder="دسته بندی های فایل"
                                :typeahead="true"></tags-input>
                    <has-error :form="form" field="selectedTags.0"></has-error>
                    <base-select label="اشتراک ویژه"
                                 :items="memberships"
                                 field="membership_id"
                                 v-model="form.membership_id"
                    />
                    <base-btn :loading="form.busy">ذخیره</base-btn>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { Form, HasError } from 'vform';
    import TagsInput from '@voerro/vue-tagsinput'

    export default {
        name: "Edit",

        metaInfo: {
            title: 'ویرایش فایل'
        },

        components: {
            HasError,
            TagsInput
        },

        data() {
            return {
                form: new Form({}),
                categories: [],
                memberships: {}
            }
        },

        created() {
            axios.get('/api/admin/membership-search')
                .then(({ data }) => {
                    this.memberships = data;
                });
            axios.get('/api/admin/category-search')
                .then(({ data }) => {
                    this.categories = data.data;
                });
            this.$store.dispatch('file/get', this.$route.params.slug)
                .then((data) => {
                    this.form = new Form({
                        name: data.name,
                        slug: data.slug,
                        description: data.description,
                        file: null,
                        image: null,
                        price: data.price,
                        membership_id: data.membership_id ? data.membership_id.toString() : null,
                        selectedTags: data.selectedTags,
                        _method: 'patch',
                    });
                })
        },

        methods: {
            updateFile() {
                this.$store.dispatch('file/update', this.form)
                    .then(() => {
                        this.$router.push({ name: 'admin-file' });
                        swal.message('فایل به درستی اپدیت شد');
                    })

            },
            changeFile(event) {
                this.form.file = event.target.files[0];
            },
            changeImage(event) {
                this.form.image = event.target.files[0];
            }
        }
    }
</script>

<style scoped>

</style>
