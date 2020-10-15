<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3>نظرات تایید نشده</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                            <tr>
                                <th>#</th>
                                <th>اسم کاربر</th>
                                <th>اسم فایل</th>
                                <th>نظر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(comment, index) in comments.data" :key="comment.id">
                                <th>{{ comment.id }}</th>
                                <th>{{ comment.user.name }}</th>
                                <th>{{ comment.file.name }}</th>
                                <th>{{ comment.body }}</th>
                                <th>
                                    <a class="btn btn-info btn-sm text-white"
                                       @click="confirmComment(comment.id, index)"
                                    >تایید نظر</a>
                                    <a class="btn btn-danger btn-sm text-white"
                                       @click="deleteComment(comment.id, index)"
                                    ><i class="fa fa-trash"></i></a>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :data="comments" @pagination-change-page="fetchComments"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Index",

        metaInfo: {
            title: 'نظرات تایید نشده'
        },

        data() {
            return {
                comments: {}
            }
        },

        created() {
            this.fetchComments(this.$route.params.page);
        },

        methods: {
            fetchComments(page = 1) {
                axios.get(`/api/admin/comment?page=${page}`)
                    .then(({ data }) => {
                        this.comments = data;
                        window.history.pushState('','', `/admin/comment?page=${page}`)
                    })
            },
            confirmComment(commentId, index) {
                axios.patch(`/api/admin/comment/${commentId}`, {
                    is_confirmed: 1
                })
                    .then(() => {
                        this.comments.data.splice(index, 1);
                    })
            },
            deleteComment(commentId, index) {
                swal.confirm().then((result) => {
                    if (result.value) {
                        axios.delete(`/api/admin/comment/${commentId}`)
                            .then(() => {
                                this.comments.data.splice(index, 1);
                            })
                    }
                })
            }
        },
    }
</script>

<style scoped>

</style>
