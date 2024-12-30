<script setup>
    import {Head,Link,useForm} from '@inertiajs/vue3';
    defineProps({
        canResetPassword: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    });
    const form = useForm({email: '',password: '',remember: false});
    const submit = () => {
        form.post(route('login'),{
            onFinish: () => form.reset('password')
        });
    };
</script>
<template>
    <Head title="Log in"/>
    <div class="content form-content dashboard-content">
        <div class="inner-content d-flex align-items-center justify-content-center p-5 bg-light">
            <div class="bg-white p-5 rounded-30px form-wrapper small-form-wrapper ">  
                <div class="d-flex gap-3 align-items-center justify-content-between mb-3">
                    <h1 class="d-flex">Login</h1>
                </div>
                <div v-if="status" class="mb-4 text-sm font-medium text-green-600">{{ status }}</div>
                <form class="row" @submit.prevent="submit">
                    <div class="form-group col-sm-12">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" :class="form.errors.email ? 'has-cust-error' : ''" id="email" v-model="form.email" placeholder="Enter  Email"/>
                        <div class="has-cust-error-msg" v-show="form.errors.email">{{ form.errors.email }}</div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" :class="form.errors.password ? 'has-cust-error' : ''" id="password" v-model="form.password" placeholder="Enter Password"/>
                        <div class="has-cust-error-msg" v-show="form.errors.password">{{ form.errors.password }}</div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-dark mt-2" :disabled="form.processing">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style>
    .form-wrapper select,
    .form-wrapper input{background:#fff;border:1px solid #9999;border-radius:10px;padding:14px 10px !important;display:block;font-size:16px;min-height:48px;}
    h1{font-size:1.8rem !important;}
    .btn{background:#2196f3;border-color:#2196f3;font-size:16px;color:#fff;border-radius:24px;font-weight:600;padding:7px 15px;min-width:120px;}
    .btn:hover{background:#111;border-color:#111;}
    @media(max-width:767px){
        h1{font-size:1.4rem !important;}
        .dashboard-content .inner-content,
        .small-form-wrapper{padding:2rem !important;}
    }
</style>