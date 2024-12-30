import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp,h} from 'vue';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import VueSweetalert2 from 'vue-sweetalert2';
import {createVuetify} from 'vuetify';
import {aliases,mdi} from 'vuetify/iconsets/mdi'
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css'
const icons = {defaultSet: 'mdi',aliases,sets: {mdi}}
const vuetify = createVuetify({components,directives,icons});
import 'sweetalert2/dist/sweetalert2.min.css';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`,import.meta.glob('./Pages/**/*.vue')),
    setup({el,App,props,plugin}) {
        return createApp({render: () => h(App,props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(VueSweetalert2)
            .mixin({
                methods: {
                    route,
                    arrayDiff(arr1,arr2){
                        return arr1.filter(x => !arr2.includes(x));
                    },
                    isRoute(...routes){
                        return routes.some(route => this.route().current(route));
                    },
                    isNull(val){
                        return typeof(val) === 'undefined' || val === null;
                    },
                    confirmBoxForRecordDeletion(url){
                        let data = new FormData();
                        data.append('_method','DELETE');
                        this.$swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if(result.isConfirmed){
                                var vm = this;
                                this.$inertia.post(url,data).then(function(){
                                    vm.$swal.fire('Deleted!','Your record has been deleted successfully!','success');
                                });
                            }
                        });
                    },
                    hasValidationError: function(errors,field){
                        if(errors.hasOwnProperty(field)){
                            return errors[field] ? true : false;
                        }
                        return null;
                    },
                    validationError: function(errors,field){
                        if(errors.hasOwnProperty(field)){
                            if(!Array.isArray(errors[field])){
                                return errors[field];
                            }else{
                                return errors[field].toString();
                            }
                        }
                        return null;
                    },
                    isNumber(evt){
                        const keysAllowed = ['0','1','2','3','4','5','6','7','8','9'];
                        const keyPressed = evt.key;
                        if(!keysAllowed.includes(keyPressed)){
                            evt.preventDefault();
                        }
                    },
                    ucwords(str){
                        if(!str){
                            return str;
                        }
                        var string = str.toLowerCase().replace(/\b[a-z]/g,function(letter){
                            return letter.toUpperCase();
                        });
                        return string.replace(/[-_]/g,' ');
                    },
                    ucFirst(string){
                        return string.charAt(0).toUpperCase() + string.slice(1);
                    },
                    convertMinutesToTime(minutes,showText = true){
                        const hours = Math.floor(minutes / 60);
                        const remainingMinutes = minutes % 60;
                        return `${hours}:${remainingMinutes < 10 ? '0' + remainingMinutes : remainingMinutes}${showText ? ' hours' : ''}`;
                    }
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563'
    }
});