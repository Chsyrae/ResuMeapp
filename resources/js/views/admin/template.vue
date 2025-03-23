<script setup>
import { ref, computed } from "vue";
import { templateStore } from "@/stores/template";
import apiCall from '@/utils/api';

const resumeTemplate = templateStore();
const view           = ref('default');
const showDialog     = ref(false);
const snackbar       = ref(false);
const message        = ref(null);
const color          = ref(null);
const loading        = ref(false);
const showSnackBar   = (messageBody, messageType) => {
    message.value    = messageBody;
    color.value      = messageType;   
    loading.value    = false;
    snackbar.value   = true;
}

const changeView = (targetView) => {
    console.log(targetView)
    if(targetView == 'create') showDialog.value = true;  
    view.value = targetView;
}

const isValid    = ref(true);
const uploadForm = ref('uploadForm');
// const fileNameRules = [
//     (v) => !!v || 'File Name is Required',
//     (v) => v.length >= 5 || 'At least 5 characters long'
// ];

const templateData = ref({
    fileName:     '',
    uploadedFile: null
});

const uploadTemplate = () => {
    const formData = new FormData();
    formData.append('file', templateData.value.uploadedFile);
    formData.append('fileName', templateData.value.fileName);
    showDialog.value = false;
    apiCall({
        url: '/api/template?type=createTemplate',
        method: 'POST',
        data: formData,
        headers	: { 'Content-Type': 'multipart/form-data' },
    }).then(resp => {
        getTemplates();
        showSnackBar(resp.message, resp.status);
        cancelUpload();
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
        cancelUpload();
    })
}

const cancelUpload = () => {
    showDialog.value                = false;
    templateData.value.fileName     = '';
    templateData.value.uploadedFile = null;
    changeView('default');
}

const renderTemplateDialog = ref(false);
const selectedTemplate     = ref(null);
const viewTemplate = (template) =>  {
    selectedTemplate.value     = template;
    renderTemplateDialog.value = true;
}


const getTemplates = () => {
    apiCall({
        url: '/api/template?type=getTemplates',
        method: 'GET'
    }).then(resp => {
        resumeTemplate.setTemplates(resp);
        resumeTemplate.changeLoaderStatus();
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}
getTemplates()

const changePage = () => {
    apiCall({
        url: `/api/template?type=getTemplates&page=${resumeTemplate.templatePagination.current_page}`,
        method: 'GET'
    }).then(resp => {
        resumeTemplate.setTemplates(resp);
        resumeTemplate.changeLoaderStatus();
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}

const templateLength = computed(() => {
    return Math.ceil(
        resumeTemplate.templatePagination.total / resumeTemplate.templatePagination.per_page
    );
});

function testBot() {
    apiCall({
        url: '/api/test-bot',
        method: 'GET'
    }).then(resp => {
        console.log(resp)
    }).catch(err => {
        console.log(err)
    })
}
//testBot()
</script>
<template>
    <v-snackbar v-model="snackbar" :color="color" elevation="24">
        {{ message }}
    </v-snackbar>
    <v-container>
        <div v-if="view == 'default'">
            <v-card class="pa-5 card rounded" elevation="0">
                <v-row no-gutters>
                    <v-col cols="12" xs="12" md="3">
                        <div class="text-h5 text-secondaryText mt-3">
                            Templates
                        </div>
                    </v-col>
                    <v-col cols="12" xs="12" md="4">
                        <div class="pa-1">
                            <v-text-field class="text_field header" density="compact" variant="outlined" label="search"
                                append-icon="mdi-undo-variant" @click:append="resetSearch()" v-on:keyup.enter="search()"
                                v-model="searchTerm" @input="enableSearch()">
                            </v-text-field>
                        </div>
                    </v-col>
                    <v-col cols="12" xs="12" md="2">
                        <div class="pa-1">
                            <v-btn 
                                block
                                rounded="xl" 
                                elevation="0" 
                                class="text-none" 
                                color="secondary" 
                                @click="search()"
                                :loading="searchLoader" 
                                :disabled="searchButtonDisabled">
                                Search
                                <v-icon right>mdi-magnify</v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                    <v-col cols="12" xs="12" md="3">
                        <div class="pa-1">
                            <v-btn 
                                block
                                rounded="xl" 
                                elevation="0"
                                color="primary" 
                                class="text-none" 
                                @click="changeView('create')"
                                v-if="$can('template_create')">
                                Add Template
                                <v-icon right> mdi-plus-circle-outline </v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
                <div class="mx-5">
                    <v-progress-linear v-if="resumeTemplate.templateLoader" height="1" color="primary"
                        indeterminate>
                    </v-progress-linear>
                </div>
                <v-divider class="mx-5 mt-2"></v-divider>
                <div v-if="resumeTemplate.templates.length == 0">
                    <v-card elevation="0">
                        <v-row no-gutters>
                            <v-col cols="12" xs="12" md="1">
                                <v-container fill-height fluid>
                                    <v-row align="center" justify="center">
                                        <v-col class="text-center">
                                            <v-icon large class="primary--text">
                                                mdi-alert-circle-outline
                                            </v-icon>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-col>
                            <v-col cols="12" xs="12" md="11">
                                <v-container fill-height fluid>
                                    <v-row align="center" justify="center">
                                        <v-col class="text-center"> No Template Found </v-col>
                                    </v-row>
                                </v-container>
                            </v-col>
                        </v-row>
                    </v-card>
                </div>
                <div v-else>
                    <div class="hidden-sm-and-down">
                        <v-table class="text-caption" density="compact">
                            <thead>
                                <tr class="accent">
                                    <th>#</th>
                                    <th class="text-left text-primary">Name</th>
                                    <th class="text-left text-primary">Date Created</th>
                                    <th class="text-left text-primary">Created By</th>
                                    <th class="text-left text-primary">Status</th>
                                    <th class="text-center text-primary">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(item, index) in resumeTemplate.templates" :key="item.id">
                                    <tr>
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ $filters.formattedDateTime(item.created_at) }}</td>
                                        <td>{{ item.user.full_name }}</td>
                                        <td>{{ item.status }}</td>
                                        <td>
                                            <div align="right" class="my-2">
                                                <v-tooltip text="View" location="top" v-if="$can('template_view')">
                                                    <template v-slot:activator="{ props }">
                                                        <v-btn 
                                                            icon
                                                            size="small" 
                                                            elevation="0"  
                                                            v-bind="props"
                                                            variant="tonal" 
                                                            class="button mr-1" 
                                                            @click="viewTemplate(item)">
                                                            <v-icon small> mdi-eye </v-icon>
                                                        </v-btn>
                                                    </template>
                                                </v-tooltip>
                                                <v-tooltip text="Edit" location="top" v-if="$can('template_edit')">
                                                    <template v-slot:activator="{ on, props }">
                                                        <v-btn 
                                                            icon
                                                            size="small"
                                                            elevation="0"
                                                            v-bind="props"
                                                            variant="tonal"
                                                            class="button mr-1 text-success" 
                                                            @click="editUser(item)" >
                                                            <v-icon small> mdi-pencil </v-icon>
                                                        </v-btn>
                                                    </template>
                                                </v-tooltip>
                                                <v-tooltip text="Delete" location="top" v-if="$can('template_archive')">
                                                    <template v-slot:activator="{ props }">
                                                        <v-btn icon
                                                            size="small"
                                                            elevation="0"
                                                            v-bind="props"
                                                            variant="tonal"
                                                            class="button mr-1 text-error"
                                                            @click="deletionDialog = true"
                                                            :loading="loading && userIndex == item.id">
                                                            <v-icon small> mdi-delete </v-icon>
                                                        </v-btn>
                                                    </template>
                                                </v-tooltip>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </v-table>
                    </div>
                    <div class="hidden-md-and-up">
                        <v-row no-gutters class="mt-3">
                            <template v-for="(templateRecord, index) in resumeTemplate.templates" :key="templateRecord.id">
                                <v-col cols="12" class="mb-2">
                                    <v-card elevation="0" class="mb-5">
                                        <v-row no-gutters>
                                            <v-col cols="12">
                                                <v-row no-gutters>
                                                    <v-col cols="11">
                                                        <div class="text-h6 text-primaryText mt-5">
                                                            <b>{{ templateRecord.user.full_name }}</b>
                                                        </div>
                                                    </v-col>
                                                    <v-col cols="1">
                                                        <div align="right">
                                                            <v-btn v-if="$can('template_archive')" 
                                                                icon
                                                                size="small"
                                                                elevation="0"
                                                                variant="tonal" 
                                                                class="text-red" 
                                                                :loading="loading && templateIndex == templateRecord.id"  
                                                                @click="deletionDialog = true">
                                                                <v-icon> mdi-delete </v-icon>
                                                            </v-btn>
                                                        </div>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                            <v-col cols="12" class="mt-1">
                                                <v-row no-gutters>
                                                    <v-col xs="4" md="4">
                                                        <div><b>Email:</b></div>
                                                    </v-col>
                                                    <v-col xs="8" md="8">
                                                        {{  }}
                                                    </v-col>
                                                </v-row>
                                                <v-row no-gutters>
                                                    <v-col xs="4" md="4">
                                                        <b>Status:</b>
                                                    </v-col>
                                                    <v-col xs="8" md="8">
                                                        <div>{{  }}</div>
                                                    </v-col>
                                                </v-row>
                                                <v-row no-gutters>
                                                    <v-col xs="4" md="4">
                                                        <b>Date Registered:</b>
                                                    </v-col>
                                                    <v-col xs="8" md="8">
                                                        <div>{{ $filters.formattedDateTime(templateRecord.created_at) }}</div>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                </v-col>
                            </template>
                        </v-row>
                    </div>
                    <v-row no-gutters class="mt-5">
                        <v-col cols="12">
                            <div align="center">
                                <v-pagination 
                                    v-if="templateLength != 0" 
                                    :length="templateLength" 
                                    :total-visible="resumeTemplate.templatePagination.visible"
                                    v-model="resumeTemplate.templatePagination.current_page" 
                                    @update:modelValue="changePage()" 
                                    circle>
                                </v-pagination>
                            </div>
                        </v-col>
                        <v-col cols="12">
                            <div align="center" class="mt-5" v-if="templateLength != 0">
                                <b>Total: </b>{{ resumeTemplate.templatePagination.total }}
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </div>
        <div v-if="view == 'create'">
            <v-dialog v-model="showDialog" width="440" persistent>
                <v-form ref="uploadForm" v-model="isValid" lazy-validation>
                    <v-card>
                        <v-card-title class="text-primary">
                            <v-row no-gutters>
                                <v-col cols="12" xs="12" md="12">
                                    <div align="left">
                                        Create Template
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-row no-gutters>
                                <v-col cols="12" xs="12" md="12">
                                    <div align="left">Template Name</div>
                                </v-col>
                                <v-col cols="12" xs="12" md="12">
                                    <div align="left" class="mt-1">
                                        <v-text-field 
                                            v-model.trim="templateData.fileName"
                                            density="compact" 
                                            variant="outlined">
                                        </v-text-field>
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row no-gutters>
                                <v-col cols="12" xs="12" md="12">
                                    <div align="left">Template File</div>
                                </v-col>
                                <v-col cols="12" xs="12" md="12">
                                    <div align="left" class="mt-1">
                                        <v-file-input
                                            v-model="templateData.uploadedFile"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                            label="Upload Template File"
                                            density="compact">
                                        </v-file-input>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions class="mt-n8">
                            <v-btn
                                class="text-none mx-5"
                                color="red-lighten-1"
                                text="Cancel"
                                variant="flat"
                                @click="cancelUpload">
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn
                                :disabled="!isValid"
                                class="text-none mx-5"
                                color="primary"
                                text="Upload"
                                variant="flat"
                                @click="uploadTemplate">
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-dialog>
        </div>
        <v-dialog transition="dialog-bottom-transition" max-width="700" v-model="renderTemplateDialog">
            <v-card>
                <v-toolbar color="primary">
                    <v-row no-gutters>
                        <v-col cols="11">
                            <div class="ml-3 mt-3">{{ selectedTemplate.name }}</div>
                        </v-col>
                        <v-col cols="1">
                            <div align="right">
                                <v-btn
                                    icon
                                    @click="renderTemplateDialog = false;">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-toolbar>
                <v-card-text>
                    <div>
                        <iframe :src="`/storage/templates/${selectedTemplate.url}`" width="100%" height="500px"></iframe>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>