;(function () {
    const puente = new Vue();
    
    //********* Vue Component Form Time *********//
    
    Vue.component('formtime', {
        inheritAttrs: false,
        props: {
            metakey: {
                type: String,
                default: 'iJHuNI',
            },
            idcolumn: {
                type: String,
                default: 'ihyrt'
            },
            min: {
                type: String,
                default: '07'
            },
            seg: {
                type: String,
                default: '01'
            },
            symbol: {
                type: String,
                default: 'pm'
            },
            colored:{
                type: Boolean,
                default: false
            }
    
        },
        data: function () {
            return {
                minutes: this.min,
                seconds: this.seg,
                timeType: this.symbol,
                meta: this.metakey,
                idcol: this.idcolumn,
                validate: 0,
                backgroundcolor: '',
                activered: this.colored,
            }
        },
        
        mounted:function () {
                puente.$on('my-event',(parametro)=>{
                    this.validate = parametro;
                });
                
                if(this.activered === true){
                    this.backgroundcolor = "red";
                }
                
            },

        updated:function(){
            if(this.backgroundcolor === "#c1c1c1"){
                 this.backgroundcolor = '';
            }
        },    
        methods: {
            insertTime: function (e) {
                e.preventDefault();
                this.backgroundcolor = "#c1c1c1";
                
                let id_post = object_name.id
                let time_new = [this.minutes, this.seconds, this.timeType]
                let metakey = this.idcol
                let type = '_time_booking_meta_'
                let key = this.meta

                this.$parent.$parent.insertData(id_post, metakey, time_new, type, key);
            
            },
            
            onChangeInput: function(event){
                this.backgroundcolor = "red";
            }

        },
        template: '#formTime'

    });
    //********* End Vue Component Form Time *********//
    
    //********* Vue Component List *********//

    Vue.component('list-date', {
        inheritAttrs: false,
        props: ['metageneral', 'dategeneral', 'canstart'],

        data: function () {
            // @Meta Key: _list_min_booking_
            return {
                todos_time: [],
                nextTodoId: '',
                date: this.dategeneral,
                idcolumn: '',
                metakey: this.metageneral,
                symbol: this.symbol,
                loadertime: 0,
                savedate: 0,
                alertDate: '',
                validateTime: this.canstart,
                activecolor: false,
            }
        },

        mounted: function () {
           if(localStorage.actioncopy){
               if(localStorage.actioncopy == '1'){
                   let metakey = localStorage.metaKey;
                   this.copyTime(metakey, 'copy_time');
                   localStorage.actioncopy = 0;
                   this.validateTime = false;
               }else{
                   this.consulTime();
               }
           }else{
               this.consulTime();
           }
           
        },

        updated: function(){
            
            this.date === ""? this.alertDate = "<div class='alert-red-warnning'><em class='icon-calendar-alert'></em> Enter the date to save the data.</div>" : this.alertDate = " ";
            
        },


        methods: {
            addTime: function (e) {
                e.preventDefault()
                this.todos_time.push({
                    id: this.nextTodoId++,
                    metakey: this.$parent.generateNumber(6),
                    min: '',
                    seg: '',
                    symbol: ''
                })
            },
            removeTime: function (index, idcolumn) {
                let metakey = this.metakey
                let type = 'remove_time'
                this.$parent.removeData(idcolumn, metakey, type)
                this.todos_time.splice(index, 1)
            },
            removeDate: function (e) {
                e.preventDefault()
                let metakey = this.metakey
                let idcolumn = ''
                let type = 'remove_general'
                this.$emit('remove')
                this.$parent.removeData(idcolumn, metakey, type)
            },

            insertDate: function (e) {
                e.preventDefault();
                
                this.validateTime = true;
                
                let id_post = object_name.id
                let date_new = this.date
                let metakey = this.metakey
                let type = '_date_booking_meta_'

                this.$parent.insertData(id_post, metakey, date_new, type);

            },
            copyTime: function (metakey, copy) {
                this.loadertime = 1;
                this.activecolor= true;
                axios
                    .get(object_name.consult_url_time, {
                        params: {
                            id_product: object_name.id,
                            key: metakey,
                            type: copy
                        }
                    })
                    .then((response) => {
                        this.todos_time = response.data
                        this.nextTodoId = response.data.length + 1
                        this.loadertime = 0
                    })
                    .catch((e) => {
                        this.errors.push(e)
                    })
            },

            consulTime: function () {
                this.loadertime = 1
                axios
                    .get(object_name.consult_url_time, {
                        params: {
                            id_product: object_name.id,
                            key: this.metakey
                        }
                    })
                    .then((response) => {
                        this.todos_time = response.data
                        this.nextTodoId = response.data.length + 1
                        this.loadertime = 0
                    })
                    .catch((e) => {
                        this.errors.push(e)
                    })
            },

            duplicateTime: function(e){
                e.preventDefault();
                let lista_date = {
                    id: this.nextTodoId++,
                    metakey: this.$parent.generateNumber(6),
                    date:''
                };
                localStorage.actioncopy = 1;
                localStorage.metaKey = this.metakey;
                this.$parent.updateNew(lista_date);
            }

        },

        template: '#inboxDate'

    });
    
    //********* End Vue Component List *********//
    
    //********* Vue Start *********//

    window.app = new Vue({
        el: '#todo-list-example',
        // @Meta Key: _list_general_booking_
        // @Meta General _meta_general_booking
        data: {
            todos_list: [],
            nextTodoId: '',
            loader: 0,
            savedate: 0,
            showmodal: false,
            childState: true,
        },

        created() {
            this.consultDate()
        },

        methods: {

            updateNew: function(lista){
                this.todos_list.push(lista);
            },

            addNewInputs: function (e) {
                e.preventDefault();
                this.childState = false;
                this.todos_list.push({
                    id: this.nextTodoId++,
                    metakey: this.generateNumber(6)
                })
            },

            addNew: function(e){
                e.preventDefault();
                this.showmodal = true;
            },

            closeModal: function(e){
                e.preventDefault();
                this.showmodal = false
            },

            consultDate: function () {
                this.loader = 1
                this.showmodal = false
                axios
                    .get(object_name.consult_url, {
                        params: {
                            id_product: object_name.id
                        }
                    })
                    .then((response) => {
                        this.todos_list = response.data
                        this.nextTodoId = response.data.length + 1
                        this.loader = 0
                    })
                    .catch((e) => {
                        this.errors.push(e)
                    })

            },

            consultUpdate:function(){
                this.loader = 1
                this.showmodal = false
                this.todos_list = []
                this.nextTodoId = 0

                axios
                    .get(object_name.consult_url, {
                        params: {
                            id_product: object_name.id
                        }
                    })
                    .then((response) => {
                        this.todos_list = response.data
                        this.nextTodoId = response.data.length + 1
                        this.loader = 0
                    })
                    .catch((e) => {
                        this.errors.push(e)
                    })

            },

            insertData: function (id_post, metakey, time_seg_type, type, key) {
                
                const button_save = document.getElementById("publish"); 
                button_save.disabled = true;
                
                puente.$emit('my-event', '1');
                
                axios
                    .post(object_name.insert_url, {
                        id_post,
                        metakey,
                        time_seg_type,
                        type,
                        key
                    })
                    .then((response) => {
                        console.log('Insert new data');
                        this.savedate = 1;
                        setTimeout(() => {
                            this.savedate = 0, 
                            button_save.disabled = false,
                            puente.$emit('my-event', '0')
                        }, 3000);
                       
                    })
                    .catch((e) => {
                        this.errors.push(e)
                        button_save.disabled = true
                    })
            },

            removeData: function (idcolumn, metakey, type) {
                const button_save = document.getElementById("publish"); 
                button_save.disabled = true;
                
                let idpost = object_name.id
                axios
                    .post(object_name.delete_url, {
                        idpost,
                        idcolumn,
                        metakey,
                        type
                    })
                    .then((response) => {
                        console.log('Eliminate new Data'),
                        button_save.disabled = false
                    })
                    .catch((e) => {
                        this.errors.push(e)
                        button_save.disabled = true
                    })
            },

            generateNumber: function (length) {
                let result = ''
                let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
                let charactersLength = characters.length
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * charactersLength))
                }
                return result
            }
        }
    })
    
    //********* End Vue Start *********//
    
})()
