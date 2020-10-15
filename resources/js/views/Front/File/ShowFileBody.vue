<template>
    <div class="card-body">
        <h6 class="card-category text-gray">
            قیمت: {{ file.price_in_toman }} /
            اشتراک ویژه: {{ file.membership_name }}
        </h6>
        <h4 class="card-title">{{ file.name }}</h4>
        <p class="card-description mb-3">
            {{ file.description }}
        </p>
        <div class="card-footer">
            <button class="btn btn-info"
                    @click="addToMyFiles"
                    v-if="fileStatus === 1">
                اضافه کردن به فایل های من
            </button>
            <form action="/buy" method="POST" v-if="fileStatus === 2">
                <input type="hidden" name="_token" :value="csrf">
                <input type="hidden" name="file_id" :value="item.file_id">
                <input type="hidden" name="discount_id" :value="item.discount_id">
                <input type="hidden" name="access_token" :value="$store.state.auth.token">
                <button class="btn btn-primary btn-round" v-show="! form.discount">خرید</button>
            </form>
            <router-link class="btn btn-info"
                         :to="{ name: 'dashboards', params: { url: 'membership' } }"
                         v-if="fileStatus === 3"
            >
                خرید اشتراک ویژه
            </router-link>
            <template v-if="fileStatus === 2">
                <base-btn v-show="form.discount"
                          btn="info"
                          :loading="form.busy"
                          @click="applyDiscount"
                >اعمال کد تخفیف</base-btn>
                <p v-if="dataForm.price">قیمت تمام شده {{ item.price }}</p>
                <div class="col-md-3" v-if="file.price && ! item.discount_id">
                    <base-input label="کد تخفیف" name="discount" v-model="form.discount" />
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import {Form} from "vform";
    import {mapState} from "vuex";

    export default {
        name: "ShowFileBody",

        props: {
            dataFile: {
                type: Object,
                required: true
            },
            dataForm: {
                type: Object,
                required: true
            },
            dataItem: {
                type: Object,
                required: true
            },
            dataFileStatus: {
                type: Number,
                required: true
            },
        },

        data() {
            return {
                form: this.dataForm,
                file: this.dataFile,
                item: this.dataItem,
                fileStatus: this.dataFileStatus,
            }
        },

        computed: {
            csrf() {
                return window.csrf;
            },
        },

        methods: {
            addToMyFiles() {
                axios.post('/api/add-to-files', {
                    file_id: this.file.id
                }).then(() => {
                    swal.message('فایل به درستی به فایل های شما اضافه شد')
                })
            },
            applyDiscount() {
                this.form.post('/api/discount')
                    .then(({ data }) => {
                        this.form = new Form({});
                        this.item.discount_id = data.id;
                        this.item.price = data.price;
                    })
                    .catch(() => {
                        this.form.discount = null;
                    })
            },
        },
    }
</script>

<style scoped>

</style>
