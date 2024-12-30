<script>
    import axios from "axios";
    export default {
        props: ["project","user"],
        data(){
            return {
                loading: true,
                items: {current_page: 1,data: [],from: 1,to: 1,total: 0,last_page: 1},
                filters: {project_id: this.project.id,user_id: this.user.id,start_date: "",end_date: ""},
                currentTime: 0,
                totalTime: 0,
                selectedItem: null,
                showPopup: false
            }
        },
        methods: {
            loadItems(page = 1,length = 10){
                let $vm = this;
                try{
                    $vm.loading = true;
                    axios.get($vm.route("admin.project-logs.all"),{params: {page,length,...$vm.filters}}).then(({data}) => {
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
            },
            handlePopup(item){
                if(item){
                    this.selectedItem = item;
                    this.showPopup = !this.showPopup;
                }else{
                    this.selectedItem = null;
                    this.showPopup = false;
                }
            },
            async exportLogs(){
                try{
                    const response = await axios.get(this.route('admin.project-logs.export'),{params: this.filters,responseType: "blob"});
                    const blob = new Blob([response.data],{type: "text/csv"});
                    const link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "project_logs.csv";
                    link.click();
                    window.URL.revokeObjectURL(link.href);
                }catch(error){
                    console.error("Error downloading CSV:",error);
                }
            }
        },
        mounted(){
            this.loadItems();
        }
    }
</script>
<script setup>
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import {Head} from "@inertiajs/vue3";
</script>
<template>
    <Head title="View Employee Log"/>
    <AdminLayout>
        <div class="bg-white p-4 rounded-30px ">
            <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                <h1 class="d-flex align-items-center gap-2"><span>{{user.name}} - {{ project.name }} Logs</span></h1>
            </div>
            <div class="mb-4 bar-form-wrapper logging-form-bar d-flex justify-content-between align-items-center"> 
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div class="field d-flex align-items-center gap-3">    
                        <label>Select dates *</label>
                        <input type="date" class="form-control" v-model="filters.start_date">
                        <input type="date" class="form-control" v-model="filters.end_date">
                    </div> 
                    <button class="btn btn-dark" @click="handleFilter">Filter</button>
                </div>
                <button class="btn btn-dark btn-sm c exportscv-btn" @click="exportLogs">Export to CSV</button> 
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Activity</th>
                            <th>Description</th>
                            <th>Project Date</th>
                            <th>Spent Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="items.data.length > 0 && !loading" v-for="item in items.data" :key="item.id">
                            <td data-title="ID">{{ item.id }}</td>
                            <td data-title="Activity">{{ item.activity_name }}</td>
                            <td data-title="Description">{{ item.description }}</td>
                            <td data-title="Project Date">{{ item.date }}</td>
                            <td data-title="Spent Time">{{ convertMinutesToTime(item.time) }}</td>
                            <td data-title="Action">
                                <button class="btn btn-dark small-btn" title="View" type="button" @click="handlePopup(item)"><i class="fa fa-eye"></i></button>
                            </td>
                        </tr>
                        <tr v-else-if="loading">
                            <td colspan="6">
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
                            <td colspan="6">No record found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="co-sm-12 project-info mt-3 d-flex justify-content-between align-items-center gap-2 bg-light px-4 py-2 rounded" v-if="items.data.length > 0 && !loading">
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
    </AdminLayout>
    <div class="popup-wrapper active w-500" v-if="showPopup == true">
        <div class="inner-content">
            <a class="close" @click="handlePopup(null)"><i class="fa fa-window-close" aria-hidden="true"></i></a>
            <div class="bg-white p-5 rounded-30px form-wrapper">
                <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                    <h1 class="d-flex">{{ selectedItem.project_name }}</h1>
                </div>
                <div class="description">{{ selectedItem.description }}</div>
            </div>
        </div>
    </div>
</template>