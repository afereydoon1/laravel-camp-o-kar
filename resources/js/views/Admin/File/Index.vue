<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary d-flex justify-content-between">
                <h4 class="card-title pt-2">لیست فایل های سایت</h4>
                <router-link :to="{ name: 'admin-files', params: { url: 'create' } }" class="btn btn-info"><i class="fa fa-user-plus"></i> فایل جدید</router-link>
            </div>
            <div class="card-body">
                <div class="col-md-6 d-flex justify-content-around">
                    <base-input label="جستجو" name="search" v-model="form.search" />
                    <base-btn btn="default" :loading="searchLoading" @click="searchFiles"><i class="fa fa-search"></i></base-btn>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                        <tr>
                            <th @click="changeSort('i')">#
                                <i class="fa text-info" :class="sortDirClass" v-show="currentSortBy === 'i'"></i>
                            </th>
                            <th @click="changeSort('n')">نام
                                <i class="fa text-info" :class="sortDirClass" v-show="currentSortBy === 'n'"></i>
                            </th>
                            <th @click="changeSort('d')">توضیحات
                                <i class="fa text-info" :class="sortDirClass" v-show="currentSortBy === 'd'"></i>
                            </th>
                            <th @click="changeSort('p')">قیمت
                                <i class="fa text-info" :class="sortDirClass" v-show="currentSortBy === 'p'"></i>
                            </th>
                            <th @click="changeSort('m')">اشتراک ویژه
                                <i class="fa text-info" :class="sortDirClass" v-show="currentSortBy === 'm'"></i>
                            </th>
                            <th @click="changeSort('ca')">تاریخ ساخت فایل
                                <i class="fa text-info" :class="sortDirClass" v-show="currentSortBy === 'ca'"></i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(file, index) in files.data">
                            <th>{{ file.id }}</th>
                            <th>{{ file.name }}</th>
                            <th>{{ file.description }}</th>
                            <th>{{ file.price }}</th>
                            <th>{{ file.membership_name }}</th>
                            <th>{{ moment(file.created_at).format('jYY/jM/jD') }}</th>
                            <th>
                                <a class="btn btn-primary" :href="`/download/${file.slug}?access_token=${$store.state.auth.token}`">دانلود</a>
                                <router-link :to="{ name: 'admin-file-edit', params: { url: 'edit', slug: file.slug } }" class="btn btn-info">
                                    ویرایش
                                </router-link>
                                <button class="btn btn-danger" @click="deleteFile({ slug: file.slug, index: index })">حذف</button>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                    <pagination :data="files" @pagination-change-page="getFiles"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    import { Form } from 'vform';
    import moment from 'moment-jalaali';

    export default {
        name: "Index",

        metaInfo: {
            title: 'لیست فایل ها'
        },

        data() {
            return {
                moment: moment,
                currentSortBy: null,
                currentSortDir: null,
                form: new Form({ search: null }),
                searchLoading: false
            }
        },

        computed: {
            ...mapState('file', ['files']),
            sortDirClass() {
                return this.currentSortDir === 'asc' ? 'fa-arrow-down' : 'fa-arrow-up';
            }
        },

        created() {
            let sortby = this.$route.query.sortby;
            let sortdir = this.$route.query.sortdir;

            let columns = ['i', 'n', 'd', 'm', 'p', 'ca'];
            let dir = ['asc', 'desc'];

            this.currentSortBy = columns.includes(sortby) ? sortby : 'i';
            this.currentSortDir = dir.includes(sortdir) ? sortdir : 'asc';
            this.getFiles(this.$route.query.page);
        },

        methods: {
            ...mapActions('file', ['deleteFile']),
            getFiles(page = 1) {
                let queries = this.$route.query;
                queries.page = page;
                queries.sortby = this.currentSortBy;
                queries.sortdir = this.currentSortDir;

                return this.$store.dispatch('file/getFiles', queries);
            },
            changeSort(sortBy) {
                if (this.currentSortBy === sortBy) {
                    this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
                }
                this.currentSortBy = sortBy;
                this.getFiles(this.$route.query.page);
            },
            searchFiles() {
                let queries = this.$route.query;
                queries.search = this.form.search;

                this.searchLoading = true;

                this.getFiles(this.$route.query.page).finally(() => this.searchLoading = false)
            }
        },
    }
</script>

<style scoped>

</style>
