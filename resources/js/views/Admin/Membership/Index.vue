<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">لیست اشتراک های ویژه سایت</h4>
                <router-link :to="{ name: 'admin-memberships', params: { url: 'create' } }" class="btn btn-info"><i class="fa fa-user-plus"></i> اشتراک ویژه جدید</router-link>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>توضیحات</th>
                            <th>قیمت</th>
                            <th>اولیت</th>
                            <th>نوع اشتراک</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(membership, index) in memberships.data" :key="membership.id">
                            <td>{{ membership.id }}</td>
                            <td>{{ membership.name}}</td>
                            <td>{{ membership.description }}</td>
                            <td>{{ membership.price }}</td>
                            <td>{{ membership.priority }}</td>
                            <td>{{ membership.type }}</td>
                            <td>
                                <router-link class="btn btn-info"
                                             :to="{ name: 'admin-membership-edit',
                                             params: { url: 'edit', id: membership.id } }"
                                >ویرایش</router-link>
                                <button class="btn btn-danger"
                                        @click="deleteMembership({ index: index, id: membership.id })"
                                >حذف</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <pagination :data="memberships" @pagination-change-page="getMembership"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        name: "Index",

        metaInfo: {
            title: 'لیست اشتراک های ویژه'
        },

        computed: {
            ...mapState('membership', ['memberships'])
        },

        created() {
            this.getMembership(this.$route.query.page)
        },

        methods: {
            ...mapActions('membership', ['getMembership', 'deleteMembership'])
        },
    }
</script>

<style scoped>

</style>
