<template>
    <div class="col-md-4 mt-5">
        <div class="card card-profile">
            <div class="card-avatar">
                <a>
                    <img class="img" :src="'/' + item.image_src">
                </a>
            </div>
            <div class="card-body">
                <h6 class="card-category text-gray">
                    قیمت: {{ item.price_in_toman }} /
                    اشتراک ویژه: {{ item.membership_name }}
                </h6>
                <h4 class="card-title">{{ item.name }}</h4>
                <p class="card-description mb-3">
                    {{ item.description }}
                </p>
                <div class="card-footer d-flex justify-content-center">
                    <router-link :to="{ name: 'file-show', params: { url: 'show', slug: item.slug } }"
                                 class="btn btn-primary btn-round"
                    >خرید</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShowRelatedFiles",

        props: {
            item: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
