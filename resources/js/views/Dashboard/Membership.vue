<template>
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header card-header-warning">
                <h4 class="card-title">اشتراک های ویژه</h4>
            </div>
            <div class="card-body table-responsive">
                <h3>اشتراک های سالیانه</h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4"
                         v-for="(membership, index) in memberships.yearly"
                         :key="membership.id"
                    >
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <div class="card-icon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <p class="card-category">{{ membership.price_in_toman }}</p>
                                <h3 class="card-title">
                                    {{ membership.name }}
                                </h3>
                                <p class="card-category">{{ membership.description }}</p>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <form action="/buy/membership" method="POST" v-if="isLowerMembership(membership)">
                                        <input type="hidden" name="membership_id" :value="membership.id">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="access_token" :value="token">
                                        <button class="btn btn-info">خرید</button>
                                    </form>
                                    <button class="btn btn-default" v-else>
                                        شما دارای اشتراک {{ user.current_membership.name }} هستید
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3>اشتراک های ماهیانه</h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4" v-for="(membership, index) in memberships.monthly">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <div class="card-icon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <p class="card-category">{{ membership.price_in_toman }}</p>
                                <h3 class="card-title">
                                    {{ membership.name }}
                                </h3>
                                <p class="card-category">{{ membership.description }}</p>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <form action="/buy/membership" method="POST" v-if="isLowerMembership(membership)">
                                        <input type="hidden" name="membership_id" :value="membership.id">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="access_token" :value="token">
                                        <button class="btn btn-info">خرید</button>
                                    </form>
                                    <button class="btn btn-default" v-else>
                                        شما دارای اشتراک {{ user.current_membership.name }} هستید
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: "Membership",

        metaInfo: {
            title: 'اشتراک های ویژه'
        },

        data() {
            return {
                memberships: {},
                csrf: window.csrf
            }
        },

        computed: {
            ...mapState('auth', ['token', 'user'])
        },

        async created() {
            let { data } = await axios.get('/api/membership');

            this.memberships = data.data;
        },

        methods: {
            isLowerMembership(membership) {
                if (this.user.current_membership) {

                    return this.user.current_membership.is_yearly !== membership.is_yearly
                        || this.user.current_membership.priority < membership.priority;
                }
                return true;
            }
        },
    }
</script>

<style scoped>

</style>
