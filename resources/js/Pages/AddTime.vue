<script>
    import axios from "axios";
    export default {
        props: ["activities","projects"],
        data(){
            return {
                form: {project_id: "",activity_id: "",date: "",hours: 0,minutes: 0,description: ""},
                errors: []
            }
        },
        methods: {
            onSubmit: function(){
                if(this.submitting){
                    return;
                }
                if(!this.handleValidate()){
                    return;
                }
                this.handleSubmit();
            },
            handleValidate: function(){
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
                if(this.form.hours == 0 && this.form.minutes == 0){
                    newError["hours"] = "Required";
                    newError["minutes"] = "Required";
                    positionFocus = positionFocus || "hours";
                }
                if(!this.form.description || !this.form.description.trim()){
                    newError["description"] = "Required";
                    positionFocus = positionFocus || "description";
                }
                this.errors = newError;
                if(positionFocus){
                    return false;
                }
                return true;
            },
            handleSubmit: function(){
                let $vm = this;
                try{
                    document.getElementById("rt-custom-loader").style.display = "block";
                    axios.post($vm.route('time-logs.add'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.form = {project_id: "",activity_id: "",date: "",hours: 0,minutes: 0,description: ""};
                            $vm.addEditForm = false;
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            }
        }
    }
</script>
<script setup>
    import UserLayout from "@/Layouts/UserLayout.vue";
    import {Head} from "@inertiajs/vue3";
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <Head title="Add Time"/>
    <UserLayout>
        <section class="py-5 px-3" >
            <div class="container form-row bg-light p-5 sm-p-2 ">
                <h1 class="mb-4">Add Time</h1>
                <form @submit.prevent="onSubmit" class="row">
                    <div class="form-group col-sm-6">
                        <label for="project_id">Select Project</label>
                        <select class="form-control" :class="hasValidationError(errors,'project_id') ? 'has-cust-error' : ''" id="project_id" v-model="form.project_id">
                            <option value="">Select</option>
                            <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
                        </select>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'project_id')">{{ validationError(errors,'project_id') }}</div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="select_activity">Select Activity</label>
                        <select class="form-control" :class="hasValidationError(errors,'activity_id') ? 'has-cust-error' : ''" id="activity_id" v-model="form.activity_id">
                            <option value="">Select</option>
                            <option v-for="activity in activities" :key="activity.id" :value="activity.id">{{ activity.name }}</option>
                        </select>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'activity_id')">{{ validationError(errors,'activity_id') }}</div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="project_date">Project Date</label>
                        <input type="date" class="form-control" :class="hasValidationError(errors,'date') ? 'has-cust-error' : ''" id="project_date" v-model="form.date"/>
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'date')">{{ validationError(errors,'date') }}</div>
                    </div>
                    <div class="form-group col-sm-6">
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
                        <div class="has-cust-error-msg" v-if="hasValidationError(errors,'hours')">{{ validationError(errors,'hours') }}</div>
                    </div>
                    <div class="col-sm-12 mt-1">
                        <button type="submit" class="btn btn-dark py-2 ">Save</button>
                    </div>
                </form>
            </div>
        </section>
    </UserLayout>
</template>