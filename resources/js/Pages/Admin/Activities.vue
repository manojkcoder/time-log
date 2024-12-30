<script>
    import axios from "axios";
    export default {
        data(){
            return {
                loading: true,
                items: {current_page: 1,data: [],from: 1,to: 1,total: 0,last_page: 1},
                form: {id: '',name: ''},
                errors: [],
                addEditForm: false
            }
        },
        methods: {
            loadItems(page = 1,length = 10){
                let $vm = this;
                try{
                    $vm.loading = true;
                    axios.get($vm.route("admin.activities.all"),{params: {page,length}}).then(({data}) => {
                        $vm.loading = false;
                        $vm.items = data.items;
                    });
                }catch(e){
                    $vm.loading = false;
                }
            },
            handlePagination(page){
                this.loadItems(page);
            },
            async updateStatus(item){
                try{
                    document.getElementById("rt-custom-loader").style.display = "block";
                    axios.post(this.route('admin.activities.status',item.id),{status: item.status}).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    });
                }catch(e){
                    item.status = !item.status;
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            actionHandler(item,type){
                if(type == "add"){
                    this.form = {id: '',name: ''};
                    this.addEditForm = true;
                }else if(type == "edit"){
                    this.form = {id: item.id,name: item.name};
                    this.addEditForm = true;
                }else if(type == "delete"){
                    this.confirmBoxForRecordDeletion(this.route('admin.activities.destroy',item.id));
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
                if(!this.form.name || !this.form.name.trim()){
                    newError["name"] = "Required";
                    positionFocus = positionFocus || "name";
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
                    axios.post($vm.route('admin.activities.add-update'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.form = {id: '',name: ''};
                            $vm.addEditForm = false;
                            $vm.loadItems();
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            closePopup(){
                this.form = {id: '',name: ''};
                this.addEditForm = false;
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
    import "vuetify/styles";
</script>
<template>
    <Head title="Activities"/>
    <AdminLayout>
        <div class="bg-white p-4 rounded-30px ">
            <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                <h1 class="d-flex align-items-center gap-2"><span>Activities </span></h1>
                <button type="button" class="btn btn-dark btn-sm" @click="actionHandler(null,'add')">Add Activity</button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Activity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="items.data.length > 0 && !loading" v-for="item in items.data" :key="item.id">
                            <td data-title="ID">{{ item.id }}</td>
                            <td data-title="Activity">{{ item.name }}</td>
                            <td  data-title="Status">
                                <v-switch color="primary" v-model="item.status" @change="updateStatus(item)" inset hide-details></v-switch>
                            </td>
                            <td data-title="Action">
                                <button class="btn btn-dark small-btn" title="edit" type="button" @click="actionHandler(item,'edit')"><i class="fa fa-pencil"></i></button>&nbsp;
                                <button class="btn btn-danger small-btn" title="Delete" type="button" @click="actionHandler(item,'delete')"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-else-if="loading">
                            <td colspan="4">
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
                            <td colspan="4">No record found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="co-sm-12 project-info mt-3 d-flex justify-content-between align-items-center gap-2 bg-light px-4 py-2 rounded" v-if="items.data.length > 0 && !loading">
                <div class="info-row">Showing {{ items.from }} - {{ items.to }} of {{ items.total }} Records</div>
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
                    <h1 class="d-flex">{{form.id ? "Update" : "Create"}} Activity</h1>
                </div>
                <form @submit.prevent="onSubmit" class="row">
                    <div class="form-group col-sm-12">
                        <label for="name">Activity Name</label>
                        <input type="text" class="form-control" :class="hasValidationError(errors,'name') ? 'has-cust-error' : ''" id="name" v-model="form.name" placeholder="Enter activity name">
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'name')">{{ validationError(errors,'name') }}</div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-dark mt-2 py-3">{{form.id ? "Update" : "Create"}} Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>