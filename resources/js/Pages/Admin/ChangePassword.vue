<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {old_password: '',password: '',confirm_password: ''},
                errors: []
            }
        },
        methods: {
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
                if(!this.form.old_password || !this.form.old_password.trim()){
                    newError["old_password"] = "Required";
                    positionFocus = positionFocus || "old_password";
                }
                if(!this.form.password || !this.form.password.trim()){
                    newError["password"] = "Required";
                    positionFocus = positionFocus || "password";
                }else if(this.form.old_password && this.form.password && this.form.old_password == this.form.password){
                    newError["password"] = "Old password and new password cannot be the same.";
                    positionFocus = positionFocus || "password";
                }
                if(!this.form.confirm_password || !this.form.confirm_password.trim()){
                    newError["confirm_password"] = "Required";
                    positionFocus = positionFocus || "confirm_password";
                }
                if(this.form.password && this.form.confirm_password && this.form.password != this.form.confirm_password){
                    newError["confirm_password"] = "New password and confirmation do not match.";
                    positionFocus = positionFocus || "confirm_password";
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
                    axios.post($vm.route('admin.update-password'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.form = {old_password: '',password: '',confirm_password: ''};
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
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import {Head} from "@inertiajs/vue3";
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <Head title="Change Password"/>
    <AdminLayout>
        <div class="bg-white p-5 rounded-30px form-wrapper small-form-wrapper ">
            <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                <h1 class="d-flex">Change Your Password</h1>
            </div>
            <form @submit.prevent="onSubmit" class="row">
                <div class="form-group col-sm-12">
                    <label for="old_password">Old Password</label>
                    <input type="password" class="form-control" :class="hasValidationError(errors,'old_password') ? 'has-cust-error' : ''" id="old_password" v-model="form.old_password" placeholder="Enter old password">
                    <div class="has-cust-error-msg" v-if="hasValidationError(errors,'old_password')">{{ validationError(errors,'old_password') }}</div>
                </div>
                <div class="form-group col-sm-12">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" :class="hasValidationError(errors,'password') ? 'has-cust-error' : ''" id="password" v-model="form.password" placeholder="Enter new password">
                    <div class="has-cust-error-msg" v-if="hasValidationError(errors,'password')">{{ validationError(errors,'password') }}</div>
                </div>
                <div class="form-group col-sm-12">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" class="form-control" :class="hasValidationError(errors,'confirm_password') ? 'has-cust-error' : ''" id="confirm_password" v-model="form.confirm_password" placeholder="Confirm new password">
                    <div class="has-cust-error-msg" v-if="hasValidationError(errors,'confirm_password')">{{ validationError(errors,'confirm_password') }}</div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-dark mt-2 py-3">Change Password</button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>