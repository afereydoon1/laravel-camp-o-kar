<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">لیست دسته بندی ها</h4>
                <router-link :to="{ name: 'admin-categories', params: { url: 'create' } }" class="btn btn-info"> <i class="fa fa-plus-circle"></i> دسته بندی جدید</router-link>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(category, index) in categories.data">
                            <td>{{ category.id }}</td>
                            <td>{{ category.name}}</td>
                            <td>
                                <router-link class="btn btn-info" :to="{ name: 'admin-categories-edit', params: { url: 'edit', slug: category.slug } }">ویرایش</router-link>
                            </td>
                            <td>
                                <button class="btn btn-danger" @click="deleteCategory(category.slug, index)">حذف</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <pagination :data="categories" @pagination-change-page="getCategories"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Index",

        metaInfo: {
            title: 'لیست دسته بندی ها'
        },

        data() {
            return {
                categories: {}
            }
        },

        created() {
            this.getCategories(this.$route.query.page);
        },

        methods: {
            deleteCategory(slug, index) {
                swal.confirm().then((result) => {
                    if(result.value) {
                        this.$store.dispatch('category/delete', { slug: slug, index: index })
                            .then(() => {
                                this.categories.data.splice(index, 1);
                            });
                    }
                })
            },

            getCategories(page = 1) {
                this.$store.dispatch('category/categories', page)
                    .then(({ data }) => {
                        this.categories = data;
                        window.history.pushState('categories', 'Categories', `/admin/category?page=${page}`)
                    });
            }
        },
    }
</script>

<style scoped>

</style>
