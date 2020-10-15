<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">لیست کاربران سایت</h4>
                <router-link :to="{ name: 'admin-users', params: { url: 'create'}}" class="btn btn-info"><i class="fa fa-user-plus"></i> کاربر جدید</router-link>
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
                                نام
                            </th>
                            <th>
                                ایمیل
                            </th>
                            <th>
                                نوع حساب کاربری
                            </th>
                            <th>
                                تاریخ ساخت حساب
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(user, index) in users.data" :key="user.id">
                            <td>
                                {{ user.id }}
                            </td>
                            <td>
                                {{ user.name }}
                            </td>
                            <td>
                                {{ user.email }}
                            </td>
                            <td>
                                {{ user.type }}
                            </td>
                            <td>
                                {{ moment(user.created_at).format('jYY/jM/jD') }}
                            </td>
                            <td>
                                <router-link :to="{ name: 'admin-users-edit', params: { url: 'edit', id: user.id } }" class="btn btn-info">ویرایش</router-link>
                                <button class="btn btn-danger" @click="deleteUser(user.id, index)">حذف</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <pagination :data="users" @pagination-change-page="getUsers"></pagination>
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
            title: 'لیست کاربران'
        },

        data() {
            return {
                users: {},
                moment: moment
            }
        },

        created() {
            this.getUsers(this.$route.query.page);
        },

        methods: {
            deleteUser(id, index) {
                swal.confirm().then((result) => {
                    if (result.value) {
                        axios.delete(`/api/admin/users/${id}`)
                            .then(data => {
                                this.users.data.splice(index, 1);
                            })
                    }
                })
            },
            getUsers(page = 1) {
                axios.get(`/api/admin/users?page=${page}`)
                    .then(({ data }) => {
                        this.users = data;
                        window.history.pushState('users', 'Users', `/admin/user?page=${page}`)
                    })
            }
        },
    }
</script>

<style scoped>

</style>
