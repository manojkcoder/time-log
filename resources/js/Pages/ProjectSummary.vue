<script>
    import axios from "axios";
    export default {
        props: ["projects"],
        data(){
            return {
                loading: true,
                items: {current_page: 1,data: [],from: 1,to: 1,total: 0,last_page: 1},
                form: {project_id: "",start_date: "",end_date: ""},
                currentTime: 0,
                totalTime: 0
            }
        },
        methods: {
            loadItems(page = 1,length = 10){
                let $vm = this;
                try{
                    $vm.loading = true;
                    axios.get($vm.route("project-summary.all"),{params: {page,length,project_id: $vm.form.project_id,start_date: $vm.form.start_date,end_date: $vm.form.end_date}}).then(({data}) => {
                        $vm.items = data.items;
                        $vm.totalTime = data.totalTime;
                        let time = 0;
                        data.items.data.map((p) => {
                            time += parseInt(p.time);
                        });
                        $vm.currentTime = time;
                        $vm.loading = false;
                    });
                }catch(e){
                    $vm.loading = false;
                }
            },
            handlePagination(page){
                this.loadItems(page);
            },
            handleFilter(){
                this.loadItems();
            }
        },
        mounted(){
            this.loadItems();
        }
    }
</script>
<script setup>
    import UserLayout from "@/Layouts/UserLayout.vue";
    import {Head} from "@inertiajs/vue3";
</script>
<template>
    <Head title="Project Summary"/>
    <UserLayout>
        <section class="py-5 px-3 time-log-section">
            <div class="container">
                <div class="inner-content inner-content-users bg-white p-4">
                    <div class="d-flex gap-3 header-bar align-items-center justify-content-between mb-4">
                        <h1 class="mb-0">Project Summary</h1>
                        <div class="bar-form-wrapper   gap-3 d-flex justify-content-between align-items-center">
                            <div class="field d-flex gap-2 align-items-center">
                                <label class="d-flex">Sort By Project</label>
                                <select id="project-option" v-model="form.project_id" class="form-control">
                                    <option value="">Select</option>
                                    <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
                                </select>
                            </div>
                            <div class="field d-flex gap-2 align-items-center">
                                <label>Select dates *</label>
                                <input type="date" class="form-control" v-model="form.start_date">
                                <input type="date" class="form-control" v-model="form.end_date">
                            </div>
                            <button class="btn btn-dark" @click="handleFilter">Filter</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Total Spent time</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="items.data.length > 0 && !loading" v-for="item in items.data" :key="item.id">
                                    <td data-title="Project Name">{{ item.project_name }}</td>
                                    <td data-title="Total Spent time">{{ convertMinutesToTime(item.time) }}</td>
                                    <td data-title="Action">
                                        <a class="btn btn-dark small-btn" title="edit" :href="route('project-summary.view',item.id)"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                <tr v-else-if="loading">
                                    <td colspan="3">
                                        <svg viewBox="0 0 38 38" width="40" height="40" stroke="#cfcfcf">
                                            <g fill="none" fillRule="evenodd">
                                                <g transform="translate(1 1)" stroke-width="3">
                                                    <circle stroke-opacity=".25" cx="18" cy="18" r="18"/>
                                                    <path d="M36 18c0-9.94-8.06-18-18-18">
                                                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.8s" repeatCount="indefinite"/>
                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="3">No record found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="co-sm-12 project-info mt-3 d-flex justify-content-between align-items-center gap-2 bg-light p-4 py-2 rounded" v-if="items.data.length > 0 && !loading">
                        <div class="info-row">Showing {{ items.from }} - {{ items.to }} of {{ items.total }} Records</div>
                        <div v-if="currentTime > 0" class="info-row">Current: <strong>{{ convertMinutesToTime(currentTime) }}</strong></div>
                        <div v-if="totalTime > 0" class="info-row">Total: <strong>{{ convertMinutesToTime(totalTime) }}</strong></div>
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item">
                                    <button class="page-link" :disabled="!items.prev_page_url" @click="handlePagination(items.current_page - 1)">&laquo;</button>
                                </li>
                                <li class="page-item" v-for="(pageLink,index) in items.links.slice(1,-1)" :key="index">
                                    <button class="page-link" v-if="pageLink.label === '...'">...</button>
                                    <button class="page-link active" v-else-if="pageLink.active">{{ pageLink.label }}</button>
                                    <button class="page-link" v-else @click="handlePagination(parseInt(pageLink.label))">{{ pageLink.label }}</button>
                                </li>
                                <li class="page-item">
                                    <button class="page-link" :disabled="!items.next_page_url" @click="handlePagination(items.current_page + 1)">&raquo;</button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>