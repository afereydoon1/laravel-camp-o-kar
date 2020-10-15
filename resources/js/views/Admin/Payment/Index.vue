<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">لیست تراکنش های سایت</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                نام کاربر
                            </th>
                            <th>
                                نوع تکمیل
                            </th>
                            <th>
                                نوع پرداخت
                            </th>
                            <th>
                                نام جنس خریداری شده
                            </th>
                            <th>
                                قیمت پرداخت شده
                            </th>
                            <th>
                                قیمت اصلی
                            </th>
                            <th>
                                مقدار تخفیف داده شده
                            </th>
                            <th>
                                شناسه پرداخت
                            </th>
                            <th>
                                تاریخ پرداخت
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(payment, index) in payments.data" :key="payment.id">
                            <td>
                                {{ payment.id }}
                            </td>
                            <td>
                                {{ payment.user.name }}
                            </td>
                            <td>
                                {{ payment.payed_type }}
                            </td>
                            <td>
                                {{ payment.type_class }}
                            </td>
                            <td>
                                {{ payment.paymentable.name }}
                            </td>
                            <td>
                                {{ payment.price_in_toman }}
                            </td>
                            <td>
                                {{ payment.original_price_in_toman }}
                            </td>
                            <td>
                                {{ payment.discounted_in_toman }}
                            </td>
                            <td>
                                {{ payment.ref_id }}
                            </td>
                            <td>
                                {{ moment(payment.created_at).format('jYY/jM/jD') }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <pagination :data="payments" @pagination-change-page="getPayments"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment-jalaali';

    export default {
        name: "Index",

        metaInfo: {
            title: 'لیست تراکنش های سایت'
        },

        data() {
            return {
                payments: {},
                moment
            }
        },

        created() {
            this.getPayments(this.$route.query.page);
        },

        methods: {
            async getPayments(page = 1) {
                let { data } = await axios.get(`/api/admin/payment?page=${page}`);

                window.history.pushState('payment', 'Payment', `/admin/payment?page=${page}`);

                this.payments = data;
            }
        },
    }
</script>

<style scoped>

</style>
