<template>
    <div class="d-flex flex-row">
        <div class="card-avatar">
            <a>
                <img class="img" :src="'/' + file.image_src">
            </a>
        </div>
        <i class="fa fa-heart fa-3x"
           @dblclick="likeFile"
           :class="{ 'text-danger': is_liked }"
        ></i>
    </div>
</template>

<script>
    export default {
        name: "ShowFileHeader",

        props: {
            file: {
                type: Object,
                required: true
            },
            like: {
                type: Boolean,
                required: true
            },
        },

        data() {
            return {
                slug: this.$route.params.slug,
                is_liked: this.like
            }
        },

        methods: {
            likeFile() {
                axios.get(`/api/file/${this.slug}/like`)
                    .then(() => {
                        this.is_liked = ! this.is_liked;
                    });
            },
        },
    }
</script>

<style scoped>

    .fa-heart {
        margin: -50px auto 50px;
        border-radius: 50%;
        background: #fff;
        overflow: hidden;
        padding: 20px;
        box-shadow: 0 16px 38px -12px rgba(0,0,0,.56), 0 4px 25px 0 rgba(0,0,0,.12), 0 8px 10px -5px rgba(0,0,0,.2);
    }
</style>
