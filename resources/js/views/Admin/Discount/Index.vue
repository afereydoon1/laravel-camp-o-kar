<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">کد تخفیف ها</h4>
                <router-link :to="{ name: 'admin-discounts', params: { url: 'create' } }" class="btn btn-info"> <i class="fa fa-plus-circle"></i> کد تخفیف جدید</router-link>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                        <tr>
                            <th>#</th>
                            <th>کد</th>
                            <th>درصد تخفیف</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(discount, index) in discounts.data" :key="discount.id">
                            <td>{{ discount.id }}</td>
                            <td>{{ discount.code }}</td>
                            <td>{{ discount.percent }}</td>
                            <td>
                                <router-link class="btn btn-info"
                                             :to="{  name: 'admin-discount-edit', params: { url: 'edit', code: discount.code } }">ویرایش</router-link>
                                <button class="btn btn-danger" @click="removeDiscount(discount.code, index)">حذف</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <pagination :data="discounts"
                                :limit="5"
                                @pagination-change-page="getDiscounts"
                    ></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Index",

        metaInfo: {
            title: 'لیست کد تخفیف ها'
        },

        data() {
            return {
                discounts: {}
            }
        },

        created() {
            this.getDiscounts();
        },

        methods: {
            getDiscounts(page = 1) {
                axios.get('/api/admin/discount?page=' + page)
                    .then(({ data }) => {
                        this.discounts = data;
                    })
            },

            removeDiscount(code, index) {
                axios.delete(`/api/admin/discount/${code}`)
                    .then(() => {
                        swal.message('کد تخفیف به درستی حذف شد.');
                        this.discounts.data.splice(index, 1);
                    })
            }
        },
    }
</script>

<style scoped>

</style>
