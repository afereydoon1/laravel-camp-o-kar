<template>
    <div class="col-md-12">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6" v-for="(payment, index) in data" :key="index">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon my-3">
                        <div class="card-icon">
                            <i class="fa fa-coins"></i>
                        </div>
                        <p class="card-category">{{ payment.title }}</p>
                        <h4 class="card-title">{{ payment.price }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <dashboard-chart v-for="(chart, index) in charts"
                             :key="index"
                             :chart="chart"
                             :index="index"
                             laoded
            ></dashboard-chart>
        </div>
    </div>
</template>

<script>
    import DashboardChart from "@/components/DashboardChart";

    export default {
        name: "AdminIndex",

        components: {
            DashboardChart
        },

        metaInfo: {
            title: 'داشبورد ادمین'
        },

        data() {
            return {
                data: {},
                loaded: false,
                charts: null
            }
        },

        created() {
            this.getInformation()
        },

        async mounted() {
            try {
                this.loaded = false;
                let { data } = await axios.get('/api/admin/dashboard/charts');

                this.charts = data;

                this.loaded = true;
            } catch (e) {

            }

        },

        methods: {
            async getInformation() {
                let { data } = await axios.get('/api/admin/dashboard');

                let total_price = data.membership.price + data.file.price;

                this.data = {
                    price: {
                        title: 'مجموع فروش سایت',
                        price: total_price.toLocaleString()
                    },
                    files_price: {
                        title: 'مجموع فروش فایل ها',
                        price: data.file.price.toLocaleString()
                    },
                    memberships_price: {
                        title: 'مجموع فروش اشتراک های ویژه',
                        price: data.membership.price.toLocaleString()
                    },
                    discounted_price: {
                        title: 'مقدار تخفیف داده شده',
                        price: data.file.discounted_price.toLocaleString()
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
