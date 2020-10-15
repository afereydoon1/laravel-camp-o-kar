<template>
    <div class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card card-profile">
                    <show-file-header :file="file"
                                      :like="like"
                                      v-if="file"
                    ></show-file-header>
                    <show-file-body v-if="file && fileStatus"
                                    :data-file="file"
                                    :data-item="item"
                                    :data-form="form"
                                    :data-file-status="fileStatus"
                    ></show-file-body>
                </div>
            </div>
        </div>
        <div class=" my-5">
            <div class="form-group bmd-form-group" :class="isFocused">
                <label for="comment" class="bmd-label-floating pl-3">نظر خود را در اینجا بنویسید</label>
                <textarea name="comment"
                          id="comment"
                          cols="30"
                          rows="10"
                          class="form-control p-3"
                          @focus="isFocused = 'is-focused'"
                          @blur="blurTextarea"
                          v-model="comment.body"
                ></textarea>
            </div>
            <base-btn :loading="comment.busy"
                      btn="info"
                      v-if="comment.body"
                      @click="submitComment"
            >ثبت نظر</base-btn>
        </div>
        <div id="comments" class="my-5 pr-4 pb-4" v-if="file">
            <h2 class="p-3">نظرات</h2>
            <file-comment :data="comment"
                          v-for="comment in file.confirmed_comments"
                          :key="comment.id"
                          @delete-comment="hideComment"
            ></file-comment>
        </div>
        <h4>فایل های مربوطه</h4>
        <div class="row mt-5" id="related_files" v-if="file">
            <show-related-files v-for="item in file.related_files"
                                :key="item.id"
                                :item="item"
            ></show-related-files>
        </div>
    </div>
</template>

<script>
    import { Form } from 'vform';
    import { mapState } from 'vuex';
    import ShowFileHeader from "./ShowFileHeader";
    import ShowRelatedFiles from "./ShowRelatedFiles";
    import ShowFileBody from "./ShowFileBody";
    import FileComment from "./FileComment";

    export default {
        name: "Show",
        components: {
            FileComment,
            ShowFileBody,
            ShowRelatedFiles,
            ShowFileHeader
        },
        metaInfo() {
            return {
                title: 'فایل ' + this.slug
            }
        },

        data() {
            return {
                form: new Form({
                    discount: null,
                    price: null
                }),
                comment: new Form({
                    body: null,
                    file_id: null,
                }),
                isFocused: '',
                file: null,
                slug: this.$route.params.slug,
                item: {
                    file_id: null,
                    discount_id: null,
                    price: null,
                },
                fileStatus: 0,
                like: false
            }
        },

        computed: {
            isUserHasHigherPriorityThanFile() {
                return this.file.membership
                    && this.user.current_membership
                    && this.user.current_membership.priority >= this.file.membership.priority;
            },
            ...mapState('auth', ['user'])
        },

        async created() {
            await this.getFile();
            this.setFileStatus();
        },

        methods: {
            async getFile() {
                let { data } = await axios.get('/api/file/' + this.slug)
                    .catch(({ response }) => {
                        if(response.status === 404)
                            this.$router.push({ name: 'not-found'})
                    });

                this.file = data;
                this.form.price = data.price;
                this.item.price = data.price + '000';
                this.item.file_id = data.id;
                this.like = data.is_user_liked;
                this.comment.file_id = data.id;
            },
            setFileStatus() {
                if(this.isUserHasHigherPriorityThanFile) {
                    this.fileStatus = 1;
                } else {
                    this.fileStatus = this.file.price ? 2 : 3;
                }
            },
            blurTextarea() {
                if (! this.comment.body) {
                    this.isFocused = '';
                }
            },
            submitComment() {
                this.comment.post('/api/comment')
            },
            hideComment(comment) {
                let index = this.file.confirmed_comments.indexOf(comment);

                this.file.confirmed_comments.splice(index, 1);
            }
        },
    }
</script>

<style scoped>
    .card-footer {
        border-top: 1px solid #eee;
    }
    #related_files {
        border-top: 1px solid #d1d1d1;
    }
    #comment, #comments {
        background: #fff;
    }
</style>
