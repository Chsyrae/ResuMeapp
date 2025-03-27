<script setup>
import { ref, computed } from "vue";
import { userStore } from "@/stores/user";
import { userAuthStore } from "@/stores/userAuth";
import apiCall from '@/utils/api';
import ConfirmDialog from '@/components/confirmDialog.vue';

const user         = userStore();
const userAuth     = userAuthStore();
const view         = ref('default');
const snackbar     = ref(false);
const message      = ref(null);
const color        = ref(null);
const loading      = ref(false);
const showSnackBar = (messageBody, messageType) => {
    message.value  = messageBody;
    color.value    = messageType;   
    loading.value  = false;
    snackbar.value = true;
}

const roles = ref({});
const getRoles = () => {
    apiCall({
        url: '/api/user?type=getRoles',
        method: 'GET'
    }).then(resp => {
        roles.value = resp;
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    }) 
}
getRoles();

const getUsers = () => {
    if(user.userLoader == false) user.changeLoaderStatus();
    apiCall({
        url: '/api/user?type=getUsers',
        method: 'GET'
    }).then(resp => {
        user.setUsers(resp);
        user.changeLoaderStatus();
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}
getUsers()

const changePage = () => {
    if(user.userLoader == false) user.changeLoaderStatus();
    apiCall({
        url: `/api/user?type=getUsers&page=${user.userPagination.current_page}`,
        method: 'GET'
    }).then(resp => {
        user.setUsers(resp);
        user.changeLoaderStatus();
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}

const inputRules = [
    (v) => !!v || "Input is Required",
];
const emailRules = [
	(v) => !!v || "E-mail is Required",
	(v) => /.+@.+/.test(v) || "E-mail must be valid",
];
const passwordRules = [
    (v) => !!v                   || "Password is required",
    (v) => (v && v.length >= 8)  || "Minimum 8 characters",
    (v) => /(?=.*[A-Z])/.test(v) || "Must have at least one uppercase character",
    (v) => /(?=.*\d)/.test(v)    || "Must have at least one number",
    (v) => /([!@#$%.])/.test(v)  || "Must have at least one special character [!@#$%.]",
];
const isValid          = ref(true);
const createForm       = ref('createForm');
const createFormDialog = ref(false);
const newUser    = ref({
    id:       null,
    fname:    null,
    lname:    null,
    email:    null,
    roleId:   null,
    password: null
});
const createUser = () => {
    apiCall({
        url: `/api/user?type=createUser&page=${user.userPagination.current_page}`,
        method: 'POST',
        data: newUser.value
    }).then(resp => {
        user.setUsers(resp);
        user.changeLoaderStatus();
        createFormDialog.value = false;
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}

const openEditDialog = (item) => {
    console.log(item)
    newUser.value.id       = item.id;
    newUser.value.fname    = item.first_name;
    newUser.value.lname    = item.last_name;
    newUser.value.email    = item.email;
    newUser.value.roleId   = item.roles[0];
    newUser.value.password = item.password;
    createFormDialog.value = true;
}

const closeEditDialog = () => {
    newUser.value = {};
    createFormDialog.value = false;
}
const editUser = (user) => {
    apiCall({
        url: `/api/user?type=editUser&page=${user.userPagination.current_page}`,
        method: 'POST',
        data: newUser.value
    }).then(resp => {
        user.setUsers(resp);
        user.changeLoaderStatus();
        createFormDialog.value = false;
        newUser.value = {};
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}

const deletionDialog     = ref(false);
const selectedUser       = ref({});
const selectedUserIndex  = ref(null);
const openDeletionDialog = (user, userIndex) => {
    selectedUser.value      = user;
    selectedUserIndex.value = userIndex;
    deletionDialog.value    = true;
}
const deleteUser = () => {
    deletionDialog.value = false;
    loading.value        = true;
    apiCall({ 
        url: `/api/user/${selectedUser.value.id}`, 
        method: "DELETE" 
    }).then((resp) => {
            loading.value       = false;
            showSnackBar(resp.message, resp.status)
            changePage();
        })
        .catch((error) => {
            loading.value = false;
            showSnackBar('An error occurred', 'error');
        });
}


const userLength = computed(() => {
    return Math.ceil(
        user.userPagination.total / user.userPagination.per_page
    );
});
</script>
<template>
    <v-snackbar v-model="snackbar" :color="color" elevation="24">
        {{ message }}
    </v-snackbar>
    <v-container>
        <div v-if="view == 'default'">
            <v-card class="pa-5 card rounded" elevation="0">
                <v-row no-gutters>
                    <v-col cols="12" xs="12" md="4">
                        <div class="text-h5 text-secondaryText mt-3">
                            Users
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
                            <v-btn rounded="xl" class="text-none" color="secondary" elevation="0" block @click="search()"
                                :loading="searchLoader" :disabled="searchButtonDisabled">
                                Search
                                <v-icon right>mdi-magnify</v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                    <v-col cols="12" xs="12" md="2">
                        <div class="pa-1">
                            <v-btn rounded="xl" class="text-none" color="primary" elevation="0" block @click="createFormDialog = true"
                                v-if="$can('user_create')">
                                Add User
                                <v-icon right> mdi-plus-circle-outline </v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
                <div class="mx-5">
                    <v-progress-linear 
                        v-if="user.userLoader" 
                        height="1" 
                        color="primary" 
                        indeterminate>
                    </v-progress-linear>
                </div>
                <v-divider class="mx-5"></v-divider>
                <div v-if="user.users.length == 0">
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
                                        <v-col class="text-center"> No User Found </v-col>
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
                                    <th class="text-left text-primary">Email</th>
                                    <th class="text-left text-primary">Role</th>
                                    <th class="text-left text-primary">Status</th>
                                    <th class="text-left text-primary">Date Created</th>
                                    <th class="text-center text-primary">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(item, index) in user.users" :key="item.id">
                                    <tr>
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.full_name }}</td>
                                        <td>{{ item.email }}</td>
                                        <td>
                                            <div v-if="item.roles.length > 0">
                                                <div v-for="item in item.roles" :key="item.id">
                                                    {{ item.name }}
                                                </div>
                                            </div>
                                            <div v-else>N/A</div>
                                        </td>
                                        <td>{{ item.status }}</td>
                                        <td>{{ $filters.formattedDateTime(item.created_at) }}</td>
                                        <td>
                                            <div align="right" class="my-2">
                                                <v-tooltip text="Edit" location="top" v-if="$can('user_edit')">
                                                    <template v-slot:activator="{ on, props }">
                                                        <v-btn 
                                                            icon
                                                            size="small"
                                                            elevation="0"
                                                            v-bind="props"
                                                            variant="tonal"
                                                            class="button mr-1 text-success" 
                                                            :loading="selectedUser.id == item.id ? true:false"
                                                            @click="openEditDialog(item)" >
                                                            <v-icon small> mdi-pencil </v-icon>
                                                        </v-btn>
                                                    </template>
                                                </v-tooltip>
                                                <v-tooltip text="Delete" location="top" v-if="$can('user_archive')">
                                                    <template v-slot:activator="{ props }">
                                                        <v-btn icon
                                                            size="small"
                                                            elevation="0"
                                                            v-bind="props"
                                                            variant="tonal"
                                                            class="button mr-1 text-error"
                                                            @click="openDeletionDialog(item, index)"
                                                            :disabled="(item.deleted_at == null ? false : true) || (item.id == userAuth.user.id ? true : false)"
                                                            :loading="loading && selectedUser.id == item.id">
                                                            <v-icon small> mdi-delete </v-icon>
                                                        </v-btn>
                                                    </template>
                                                </v-tooltip>
                                                <ConfirmDialog
                                                    :title="'Confirm Action'"
                                                    :content="`Do You Want to Delete User #${selectedUserIndex + 1}?`"
                                                    :model-value="deletionDialog"
                                                    @update:modelValue="value => deletionDialog = value"
                                                    @confirm="deleteUser()"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </v-table>
                    </div>
                    <div class="hidden-md-and-up">
                        <v-row no-gutters class="mt-3">
                            <template v-for="(userRecord, index) in user.users" :key="userRecord.id">
                                <v-col cols="12" class="mb-2">
                                    <v-card elevation="0" class="mb-5">
                                        <v-row no-gutters>
                                            <v-col cols="12">
                                                <v-row no-gutters>
                                                    <v-col cols="11">
                                                        <div class="text-h6 text-primaryText mt-5">
                                                            <b>{{ userRecord.full_name }}</b>
                                                        </div>
                                                    </v-col>
                                                    <v-col cols="1">
                                                        <div align="right">
                                                            <v-btn v-if="$can('user_archive')" 
                                                                icon
                                                                size="small"
                                                                elevation="0"
                                                                variant="tonal" 
                                                                class="text-red"  
                                                                @click="openDeletionDialog(item, index)"
                                                                :disabled="(userRecord.deleted_at == null ? false : true) || (userRecord.id == userAuth.user.id ? true : false)"
                                                                :loading="loading && selectedUser.id == item.id">                                                                <v-icon> mdi-delete </v-icon>
                                                            </v-btn>
                                                            <ConfirmDialog
                                                                :title="'Confirm Action'"
                                                                :content="`Do You Want to Delete User #${selectedUserIndex + 1}?`"
                                                                :model-value="deletionDialog"
                                                                @update:modelValue="value => deletionDialog = value"
                                                                @confirm="deleteUser()"
                                                            />
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
                                                        <div>{{ userRecord.email }}</div>
                                                    </v-col>
                                                </v-row>
                                                <v-row no-gutters>
                                                    <v-col xs="4" md="4">
                                                        <div><b>Role:</b></div>
                                                    </v-col>
                                                    <v-col xs="8" md="8">
                                                        <div v-if="userRecord.roles.length > 0">
                                                            <div v-for="item in userRecord.roles" :key="item.id">
                                                                {{ item.name }}
                                                            </div>
                                                        </div>
                                                        <div v-else>N/A</div>
                                                    </v-col>
                                                </v-row>
                                                <v-row no-gutters>
                                                    <v-col xs="4" md="4">
                                                        <div><b>Status:</b></div>
                                                    </v-col>
                                                    <v-col xs="8" md="8">
                                                        <div>{{ userRecord.status }}</div>
                                                    </v-col>
                                                </v-row>
                                                <v-row no-gutters>
                                                    <v-col xs="4" md="4">
                                                        <b>Date Registered:</b>
                                                    </v-col>
                                                    <v-col xs="8" md="8">
                                                        <div>{{ $filters.formattedDateTime(userRecord.created_at) }}</div>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                    <v-divider></v-divider>
                                </v-col>
                            </template>
                        </v-row>
                    </div>
                    <v-row no-gutters class="mt-5">
                        <v-col cols="12">
                            <div align="center">
                                <v-pagination 
                                    v-if="userLength != 0" 
                                    :length="userLength" 
                                    :total-visible="user.userPagination.visible"
                                    v-model="user.userPagination.current_page" 
                                    @update:modelValue="changePage()" 
                                    circle>
                                </v-pagination>
                            </div>
                        </v-col>
                        <v-col cols="12">
                            <div align="center" class="mt-5" v-if="userLength != 0">
                                <b>Total: </b>{{ user.userPagination.total }}
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </div>
        <v-dialog v-model="createFormDialog" persistent max-width="600">
            <v-form ref="createForm" v-model="isValid" lazy-validation>
                <v-card class="card rounded" elevation="0">
                    <v-card-title classs="text-primary">
                        <v-row no-gutters>
                            <v-col cols="12" xs="12" md="11">
                                <div align="left">
                                    {{ newUser.id == null ? 'Create User' : 'Edit User' }}
                                </div>
                            </v-col>
                            <v-col cols="1">
                                <div class="mx-8">
                                    <v-btn
                                        depressed
                                        size="md"
                                        color="error"
                                        variant="tonal" 
                                        @click="closeEditDialog">
                                        <v-icon right> mdi-close</v-icon>
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" md="6">
                                <v-row no-gutters>
                                    <v-col cols="12" xs="12" md="12">
                                        <div>First Name</div>
                                    </v-col>
                                    <v-col cols="12" xs="12" md="12">
                                        <v-text-field
                                            v-model="newUser.fname"
                                            :rules="inputRules"
                                            density="compact">
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" md="6">
                                <v-row no-gutters>
                                    <v-col cols="12" xs="12" md="12">
                                        <div>Last Name</div>
                                    </v-col>
                                    <v-col cols="12" xs="12" md="12">
                                        <v-text-field
                                            v-model="newUser.lname"
                                            :rules="inputRules"
                                            density="compact">
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" md="6">
                                <v-row no-gutters>
                                    <v-col cols="12" xs="12" md="12">
                                        <div>Email</div>
                                    </v-col>
                                    <v-col cols="12" xs="12" md="12">
                                        <v-text-field
                                            v-model="newUser.email"
                                            :rules="emailRules"
                                            density="compact">
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" md="6">
                                <v-row no-gutters>
                                    <v-col cols="12" xs="12" md="12">
                                        <div>Role</div>
                                    </v-col>
                                    <v-col cols="12" xs="12" md="12">
                                        <v-select 
                                            :items="roles"
                                            item-value="id"
                                            item-title="name"
                                            :rules="inputRules"
                                            v-model="newUser.roleId"
                                            density="compact">
                                        </v-select>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions class="mt-n6">
                        <v-btn
                            class="text-none mx-1"
                            color="red-lighten-1"
                            text="Cancel"
                            variant="flat"
                            @click="closeEditDialog">
                        </v-btn>
                        <v-btn
                            :disabled="!isValid"
                            class="text-none mx-4"
                            color="primary"
                            text="Save"
                            variant="flat"
                            @click="newUser.id == null ? createUser : editUser">
                        </v-btn>
                    </v-card-actions>
                </v-card>    
            </v-form>
        </v-dialog>
    </v-container>
</template>