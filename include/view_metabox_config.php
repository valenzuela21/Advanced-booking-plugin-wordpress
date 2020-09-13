<?php

class metaboxViewConfig
{

    function __construct()
    {
        add_action('init', array($this, 'view_tab_config'));
        //Add require css js
        add_action('admin_enqueue_scripts', array($this, 'js_css_require_metabox'), 5, 1);
    }

    public function js_css_require_metabox($hook)
    {

        global $post;

        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            if ( 'product' === $post->post_type ) {
                wp_register_script('wpvue_vuejs', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
                wp_register_script('wpvue_vue_axios_js', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js');
                wp_register_script('script_vue_metabox', plugins_url('./../js/script_metabox.js', __FILE__), array(), 1.0, true);
                wp_enqueue_style('css_style_config', plugins_url('./../css/style.css', __FILE__));

                $translation_array = array(
                    'id' => get_the_ID(),
                    'insert_url' => plugins_url('./api/insert_data_time.php', __FILE__),
                    'delete_url' => plugins_url('./api/delete_data.php', __FILE__),
                    'consult_url' => plugins_url('./api/consult_data.php', __FILE__),
                    'consult_url_time' => plugins_url('./api/consult_min.php', __FILE__),
                );

                wp_localize_script('script_vue_metabox', 'object_name', $translation_array);
                wp_enqueue_script('juqery_min_admin', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', array(jquery), 3.2, false);
                wp_enqueue_script('select_min_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array(jquery), 4.0, true);
                wp_enqueue_script('script_select_js', plugins_url('./../js/script_select.js', __FILE__), array(jquery), 1.0, true);

                wp_localize_script( 'script_select_js', 'admin_url', array(
                    'url'=> plugins_url('/api/json_data_products.php', __FILE__),
                    'ajax_url' => admin_url('admin-ajax.php')
                ) );

            }
        }
    }

    public function view_tab_config()
    {
        //Add Vue.js
        wp_enqueue_script('wpvue_vuejs');
        wp_enqueue_script('wpvue_vue_axios_js');
        wp_enqueue_script('script_vue_metabox');

        $id_post = $_GET['post'];
        ?>
        
        
<div id="todo-list-example">

    <!-- Modal Import  -->
    <div class="modal-example" v-show="showmodal == true" >
        <em class="close_modal" v-on:click='closeModal'> </em>
        <div class="content-modal">
             <h1 style="margin: 0px; color:red; font-weight: 700" ><?php echo __('ALERT.', 'time-date-booking'); ?></h1>
             <p style="margin: 0px; color:red"><?php echo __('When importing you have the risk that what you have Now it will be eliminated.', 'time-date-booking'); ?></p>
            <h3 style="margin: 0px;" ><?php echo __('Import Information', 'time-date-booking');?></h3>
            <p style="margin: 0px;" ><?php echo __('Copy from one content to another.', 'time-date-booking'); ?></p>
            <input type="text" name="id-product" class="id-product" id="id-product" value='<?php echo $id_post; ?>' style="display:none" />
                <select id="select-id" class="select-id-product" name="select-id-product">
                    <option value="0" ><?php echo __('Select the info you want to import', 'time-date-booking'); ?></option>
                <select>
                    <button name="submit-send" id="btn-import-add"  class="btn-import-add">Import</button>
        </div>
    </div>
    <!-- End Modal Import -->

    <!--Loader Table Consult -->
    <div v-show="loader == 1" class="loaderbackground" >
        <div class="loader">
        <div class="lds-ripple"><div></div><div></div></div>
        <p> <?php echo __('Loader...', 'time-date-booking');?> </p>
        </div>
    </div>
    <!--End Loader Table Consult -->


    <div class="bar-css">
        <button class="btn-import" v-on:click="addNew" > <em class="import-icon"></em> Importar </button>
        <span class="title"><?php echo __('Add Appoinment','time-date-booking');?></span> <button class="btn-add-book" 
            v-on:click="addNewInputs"></button>
    </div>
    <div v-show="savedate == 1" class="save-sucess"><em class="save-data-icon"></em> <?php echo __('Date save time.', 'time-date-booking'); ?> </div>
    <ul class="container-general-config">
        <list-date v-for="(todo, index) in todos_list" :key="todo.id" :metageneral="todo.metakey"  :dategeneral="todo.date" :canstart="childState" 
            v-on:remove="todos_list.splice(index, 1)" >
        </list-date>
    </ul>
</div>

<!--Template Component Time First-->
<template id="inboxDate">
    <div>
        <div v-html="alertDate" ></div>
        <div class="container-table-booking">
            <div class="column-date">
                <p class="parrafo"><?php echo __('Date:','time-date-booking')?></p>
                <input @change="insertDate" v-model="date" type="date"  placeholder="dd-mm-yyyy" >
            </div>
            <div class="column-time" v-if="validateTime === true" >
                <div v-show="loadertime == 1">
                        <p><?php echo __('Loader...', 'time-date-booking');?></p>
                </div>
                <button class="btn-add" @click="addTime">
                    <em class="icon-add"></em><span style="padding-top: 3px;
                        padding-left: 4px;"><?php echo __('Add Time','time-date-booking');?></span></button>
                <div class="inputs-time" v-for="(time, index) in todos_time" :key="time.id">
                    <formtime :colored="activecolor" :seg="time.seg" :min="time.min" :symbol="time.symbol" :metakey="metakey"
                        :idcolumn="time.metakey" >
                    </formtime>
                    <button class="btn-list-remove" @click="removeTime(index, time.metakey)">
                        <em class="icon-close"></em>
                    </button>
                </div>
            </div>
            <div v-else>
                <p class='advertence-alert'><?php echo __('Enter the date to beable to set the schedules.', 'time-date-booking'); ?></div></p>
            <div>
                <button class="btn-copy-general" @click="duplicateTime" ><em class="icon-dupplicate"></em></button>
                <button class="btn-remove-general" @click="removeDate" ><em class="icon-eliminate"></em></button>
            </div>
        </div>
    </div>
</template>
<!--Template Component Time First-->

<!--Template Component Time Seconds-->
<template id="formTime">
    <div>
     <div class="container-time">
        <div class="content-col">
            <p class="parrafo"><?php echo __('Hours:','time-date-booking')?></p>
            <input type="text" class="input-form" name="minutes" v-model="minutes" @change="onChangeInput($event)" />
        </div>
        <div class="content-col">
            <p class="parrafo"><?php echo __('Min:','time-date-booking')?></p>
            <input type="text"  @change="onChangeInput($event)"  class="input-form" name="seconds" v-model="seconds" />
        </div>
        <div class="content-col">
            <p class="parrafo"><?php echo __('Type:','time-date-booking')?></p>
            <select class="input-form"  @change="onChangeInput($event)" name="timeType" v-model="timeType">
                <option value=""><?php echo __('Type','time-date-booking');?></option>
                <option value="pm">PM</option>
                <option value="am">AM</option>
            </select>
        </div>
        <button @click="insertTime" :disabled="validate == 1" :style="{'background-color': backgroundcolor}" class="save-btn-data"><i class="save-icon"></i></button>
    </div>
    </div>
</template>
<!--End Template Component Time Seconds-->

<?php
    }


}

new metaboxViewConfig();