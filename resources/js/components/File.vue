<template>
    <div class="card card-stats">
        <div class="card-header card-header-success card-header-icon">
            <img class="card-icon rounded"
                 :src="`/${file.image_src}`"
                 :alt="file.name" width="100"
            />
            <p class="card-category" v-if="file.name">{{ file.price_in_toman }}</p>
            <p class="card-category" v-if="file.membership_id">{{ file.membership_name }}</p>
            <h3 class="card-title mt-2">{{ file.name }}</h3>
            <div class="card-category mt-4">
                <p>دسته بندی ها</p>
                <router-link class="badge badge-info p-2 mr-2"
                              v-for="category in file.categories"
                              :key="category.slug"
                             :to="{ name: 'home-tagged', params: { slug: category.slug } }"
                >{{ category.name }}</router-link>
            </div>
        </div>
        <div class="card-footer">
            <div class="stats w-100 d-flex justify-content-between">
                <p class="my-auto"><i class="fa fa-clock"></i> {{ moment(file.created_at).format('jYYYY/jM') }} </p>
                <router-link :to="{ name: 'file-show',  params: { url: 'show', slug: file.slug } }" class="btn btn-info">خرید</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment-jalaali';

    export default {
        name: "File",

        props: {
            file: Object
        },

        data() {
            return {
                moment: moment
            }
        },
    }
</script>

<style scoped>
    .card [class*=card-header-] .card-icon, .card [class*=card-header-] .card-text {
        padding: 0;
    }
</style>
