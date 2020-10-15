<template>
    <div class="container-fluid pt-4 pl-4">
        <div class="d-flex justify-content-between">
            <div>
                <img :src="data.user.profile_src" alt="" width="75" class="pr-2">
                <span><strong>{{ data.user.name }}</strong></span>
            </div>
            <div>
                <i class="fa fa-reply pr-2 cursor" @click="showTextarea"></i>
                <i class="fa fa-trash cursor"
                   @click="deleteComment"
                   v-if="userCanDelete"
                ></i>
            </div>
        </div>
        <p class="mt-3">{{ data.body }}</p>
        <div v-if="isReply">
            <div class="form-group bmd-form-group" :class="isFocused">
                <label for="comment" class="bmd-label-floating pl-3">نظر خود را در اینجا بنویسید</label>
                <textarea name="comment"
                          id="comment"
                          cols="30"
                          rows="10"
                          class="form-control p-3"
                          @focus="isFocused = 'is-focused'"
                          @blur="blurTextarea"
                          v-model="reply.body"
                ></textarea>
            </div>
            <base-btn :loading="reply.busy"
                      btn="info"
                      v-if="reply.body"
                      @click="submitReply"
            >ثبت نظر</base-btn>
        </div>
        <file-comment :data="comment"
                      v-for="comment in data.confirmed_comments"
                      :key="comment.id"
                      @delete-comment="hideComment"
        ></file-comment>
    </div>
</template>

<script>
    import { Form } from 'vform';
    import { mapGetters } from 'vuex';

    export default {
        name: "FileComment",

        props: {
            data: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                isReply: false,
                isFocused: '',
                reply: null,
            }
        },

        computed: {
            userCanDelete() {
                return this.data.user_id === this.user.id || this.user.is_admin === 1;
            },
            ...mapGetters('auth', ['user'])
        },

        methods: {
            blurTextarea() {
                if (! this.reply.body) {
                    this.isFocused = '';
                }
            },
            showTextarea() {
                this.isReply = true;
                this.reply = new Form({
                    body: null,
                    comment_id: this.data.id,
                    file_id: this.data.file_id,
                })
            },
            submitReply() {
                this.reply.post('/api/comment')
                    .then(({ data }) => {
                        this.isReply = false;
                        this.reply = null;
                        // this.data.confirmed_comments.push(data.data)
                    })
            },
            deleteComment() {
                this.$emit('delete-comment', this.data);
                axios.delete(`/api/comment/${this.data.id}`)
            },
            hideComment(comment) {
                let index = this.data.confirmed_comments.indexOf(comment);

                this.data.confirmed_comments.splice(index, 1);
            }
        },
    }
</script>

<style scoped>
    .cursor {
        cursor: pointer;
    }
</style>
