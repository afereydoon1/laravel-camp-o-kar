<template>
    <div class="sidebar" data-color="purple" data-background-color="white">
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-dashboard' }">
                        <i class="fa fa-tachometer-alt"></i>
                        <p>داشبورد</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-user' }">
                        <i class="fa fa-users"></i>
                        <p>کاربران</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-category' }">
                        <i class="fa fa-list-alt"></i>
                        <p>دسته بندی</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-membership' }">
                        <i class="fa fa-user-circle"></i>
                        <p>اشتراک های ویژه</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-file' }">
                        <i class="fa fa-file"></i>
                        <p>فایل ها </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-comment' }">
                        <i class="fa fa-comment"></i>
                        <p>نظرات</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'admin-payment' }">
                        <i class="fa fa-exchange-alt"></i>
                        <p>تراکنش ها</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" @click.prevent="logout">
                        <i class="fa fa-sign-out-alt"></i>
                        <p>خروج</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DashboardSideBar",
        methods: {
            logout() {
                this.$store.dispatch('auth/logout');

                this.$router.push({ name: 'home' });
            }
        },
    }
</script>

<style scoped>

</style>
