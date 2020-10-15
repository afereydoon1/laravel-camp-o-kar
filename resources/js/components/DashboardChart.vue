<template>
    <div class="col-md-12">
        <line-chart :data="data"
                    :height="200"
        ></line-chart>
    </div>
</template>

<script>
    import LineChart from "./LineChart";

    export default {
        name: "DashboardChart",

        components: {
            LineChart
        },

        props: {
            chart: {
                type: [Array, Object],
                required: true
            },
            index: {
                type: String,
                required: true
            },
            loaded: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                data: null,
                options: null
            }
        },

        created() {
            this.data = {
                labels: this.chart.labels,
                datasets: [
                    {
                        label: this.index,
                        data: this.chart.data
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>
