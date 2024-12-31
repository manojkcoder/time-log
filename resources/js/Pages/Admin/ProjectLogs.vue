<script>
    import axios from "axios";
    export default {
        props: ["activities","projects","users"],
        data(){
            return {
                loading: true,
                items: {current_page: 1,data: [],from: 1,to: 1,total: 0,last_page: 1},
                form: {id: "",project_id: "",activity_id: "",date: "",hours: 0,minutes: 0,description: ""},
                filters: {user_id: "",start_date: "",end_date: ""},
                currentTime: 0,
                totalTime: 0,
                errors: [],
                addEditForm: false
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
            actionHandler(item,type){
                if(type == "edit"){
                    let hours = item.time ? Math.floor(item.time / 60) : 0;
                    let minutes = item.time ? (item.time % 60) : 0;
                    this.form = {id: item.id,project_id: item.project_id,activity_id: item.activity_id,date: item.date,hours,minutes,description: item.description};
                    this.addEditForm = true;
                }else if(type == "delete"){
                    this.confirmBoxForRecordDeletion(this.route('admin.project-logs.destroy',item.id));
                }
            },
            onSubmit(){
                if(this.submitting){
                    return;
                }
                if(!this.handleValidate()){
                    return;
                }
                this.handleSubmit();
            },
            handleValidate(){
                const newError = {};
                let positionFocus = "";
                if(!this.form.project_id){
                    newError["project_id"] = "Required";
                    positionFocus = positionFocus || "project_id";
                }
                if(!this.form.activity_id){
                    newError["activity_id"] = "Required";
                    positionFocus = positionFocus || "activity_id";
                }
                if(!this.form.date){
                    newError["date"] = "Required";
                    positionFocus = positionFocus || "date";
                }
                this.errors = newError;
                if(positionFocus){
                    return false;
                }
                return true;
            },
            handleSubmit(){
                let $vm = this;
                try{
                    document.getElementById("rt-custom-loader").style.display = "block";
                    axios.post($vm.route('admin.project-logs.update'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.form = {id: "",project_id: "",activity_id: "",date: "",hours: 0,minutes: 0,description: ""};
                            $vm.addEditForm = false;
                            $vm.loadItems();
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            closePopup(){
                this.form = {id: "",project_id: "",activity_id: "",date: "",hours: 0,minutes: 0,description: ""};
                this.addEditForm = false;
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
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <Head title="Project Logs"/>
    <AdminLayout>
        <div class="bg-white p-4 rounded-30px ">
            <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                <h1 class="d-flex align-items-center gap-2"><span>Project Logs</span></h1>
            </div>
            <div class="mb-4 bar-form-wrapper logging-form-bar d-flex justify-content-between align-items-center"> 
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div class="field d-flex align-items-center gap-3">
                        <label>Select user *</label>
                        <select name="name" class="form-control" v-model="filters.user_id">
                            <option value="">Select</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                    <div class="field d-flex align-items-center gap-3">    
                        <label>Select dates *</label>
                        <input type="date" class="form-control" v-model="filters.start_date">
                        <input type="date" class="form-control" v-model="filters.end_date">
                    </div> 
                    <button class="btn btn-dark" @click="handleFilter">Filter</button>
                </div>
                <button class="btn btn-dark  btn-sm c exportscv-btn" @click="exportLogs">Export to CSV</button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Activity</th>
                            <th>User</th>
                            <th>Project Date</th>
                            <th>Spent Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="items.data.length > 0 && !loading" v-for="item in items.data" :key="item.id">
                            <td data-title="ID">{{ item.id }}</td>
                            <td data-title="Project">{{ item.project_name }}</td>
                            <td data-title="Activity">{{ item.activity_name }}</td>
                            <td data-title="User">{{ item.user_name }}</td>
                            <td data-title="Project Date">{{ item.date }}</td>
                            <td data-title="Spent Time">{{ convertMinutesToTime(item.time) }}</td>
                            <td data-title="Action">
                                <button class="btn btn-dark small-btn" title="edit" type="button" @click="actionHandler(item,'edit')"><i class="fa fa-pencil"></i></button>&nbsp;
                                <button class="btn btn-danger small-btn" title="Delete" type="button" @click="actionHandler(item,'delete')"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-else-if="loading">
                            <td colspan="7">
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
                            <td colspan="7">No record found.</td>
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
    <div class="popup-wrapper active" v-if="addEditForm == true">
        <div class="inner-content">
            <a class="close" @click="closePopup"><i class="fa fa-window-close" aria-hidden="true"></i></a>
            <div class="bg-white p-5 rounded-30px form-wrapper">
                <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                    <h1 class="d-flex">Update Log</h1>
                </div>
                <form @submit.prevent="onSubmit" class="row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label for="project_id">Project</label>
                        <select class="form-control" :class="hasValidationError(errors,'project_id') ? 'has-cust-error' : ''" id="project_id" v-model="form.project_id">
                            <option value="">Select</option>
                            <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
                        </select>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'project_id')">{{ validationError(errors,'project_id') }}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label for="activity_id">Activity</label>
                        <select class="form-control" :class="hasValidationError(errors,'activity_id') ? 'has-cust-error' : ''" id="activity_id" v-model="form.activity_id">
                            <option value="">Select</option>
                            <option v-for="activity in activities" :key="activity.id" :value="activity.id">{{ activity.name }}</option>
                        </select>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'activity_id')">{{ validationError(errors,'activity_id') }}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <label for="project_date">Project Date</label>
                        <input type="date" class="form-control" :class="hasValidationError(errors,'date') ? 'has-cust-error' : ''" id="project_date" v-model="form.date"/>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'date')">{{ validationError(errors,'date') }}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <div class="row">
                            <label class="col-12">Spent Time</label>
                            <div class="col-sm-6">
                                <select id="hour_time" v-model="form.hours" class="form-control" :class="hasValidationError(errors,'hours') ? 'has-cust-error' : ''">
                                    <option value="0">0 Hour</option>
                                    <option value="1">1 Hour</option>
                                    <option value="2">2 Hours</option>
                                    <option value="3">3 Hours</option>
                                    <option value="4">4 Hours</option>
                                    <option value="5">5 Hours</option>
                                    <option value="6">6 Hours</option>
                                    <option value="7">7 Hours</option>
                                    <option value="8">8 Hours</option>
                                    <option value="9">9 Hours</option>
                                    <option value="10">10 Hours</option>
                                    <option value="11">11 Hours</option>
                                    <option value="12">12 Hours</option>
                                </select>
                                <div class="has-cust-error-msg" v-if="hasValidationError(errors,'hours')">{{ validationError(errors,'hours') }}</div>
                            </div>
                            <div class="col-sm-6 sm-mt-4">
                                <select id="minute_time" v-model="form.minutes" class="form-control" :class="hasValidationError(errors,'minutes') ? 'has-cust-error' : ''">
                                    <option value="0">0 Minute</option>
                                    <option value="5">5 Minutes</option>
                                    <option value="10">10 Minutes</option>
                                    <option value="15">15 Minutes</option>
                                    <option value="20">20 Minutes</option>
                                    <option value="25">25 Minutes</option>
                                    <option value="30">30 Minutes</option>
                                    <option value="35">35 Minutes</option>
                                    <option value="40">40 Minutes</option>
                                    <option value="45">45 Minutes</option>
                                    <option value="50">50 Minutes</option>
                                    <option value="55">55 Minutes</option>
                                </select>
                                <div class="has-cust-error-msg" v-if="hasValidationError(errors,'minutes')">{{ validationError(errors,'minutes') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label for="description">Description</label>
                        <textarea id="description" v-model="form.description" class="form-control" :class="hasValidationError(errors,'description') ? 'has-cust-error' : ''" rows="4" placeholder="Short Description"/>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'description')">{{ validationError(errors,'description') }}</div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-dark mt-2 py-3">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>