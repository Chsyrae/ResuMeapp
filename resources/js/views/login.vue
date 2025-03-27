<script setup>
import { ref } from "vue";
import router from "@/router";
import apiCall from '@/utils/api';
import { userAuthStore } from "@/stores/userAuth";

const userAuth        = userAuthStore();
const isValid         = ref(true);
const loading         = ref(false);
const isDialogVisible = ref(true);
const view            = ref('login');
const snackbar        = ref(false);
const message         = ref(null);
const color           = ref(null);

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

const userData        = ref({
	first_name:       null,
    last_name:        null,
    email:            null,
    password:         null,
    confirm_password: null,
    otp:              ''
});

const showSnackBar = (messageBody, messageType) => {
    message.value  = messageBody;
    color.value    = messageType;   
    loading.value  = false;
    snackbar.value = true;
}

const accountCreationForm = ref('accountCreationForm');
const creationStage = ref('default');
const createAccount = () => {
    if(accountCreationForm.value.validate()) {
        loading.value = true;
        apiCall({
            url: '/api/user-account?type=signUp',
            data: userData.value,
            method: 'POST'
        }).then(resp => {
            loading.value       = false;
            creationStage.value = 'complete';
            showSnackBar(resp.message, resp.status);
        }).catch(err => {
            loading.value = false;
            showSnackBar('An error occurred', 'error');
        })
    }
}

const loginForm             = ref('loginForm');
const loginVerificationForm = ref('loginVerificationForm');
const login = () => {
    if(loginForm.value.validate()) {
        loading.value = true;
        apiCall({
            url: '/api/user-account?type=login',
            data: userData.value,
            method: 'POST'
        }).then(resp => {
            showSnackBar(resp.message, resp.status);
            loading.value = false;
            if(resp.status == 'success') {
                userAuth.authSuccess(resp);
                isDialogVisible.value = false;
                if (userAuth.user.ability.length > 0) {
                    router.push({ name: "User" });
                } else {
                    router.push({ name: "Builder" });
                }
                //view.value = 'loginVerification';
                //isDialogVisible.value = false;
                //router.push({ name: 'Builder' });
            }
        }).catch(err => {
            loading.value = false;
            showSnackBar('An error occurred', 'error');
        })
    }
}

const verifyLogin = () => {
    if(userData.value.otp.length == 0) {
        showSnackBar('OTP is required', 'warning');
    } else {
        loading.value = true;
        apiCall({
            url: '/api/user-account?type=loginVerification',
            data: userData.value,
            method: 'POST'
        }).then(resp => {
            loading.value = false;
            if(resp.status == 'success') {
                isDialogVisible.value = false;
                router.push({ name: 'Builder' });
            } else if(resp.status == 'expired' || resp.status == 'error') {
                showSnackBar(resp.message, 'error');
            }
        }).catch(err => {
            loading.value = false;
            showSnackBar('An error occurred', 'error');
        })
    }
}
</script>
<template>
    <v-snackbar v-model="snackbar" :color="color" elevation="24">
        <div align="center">{{ message }}</div>
    </v-snackbar>
    <v-dialog v-model="isDialogVisible" max-width="600" persistent>
        <div align="center" class="my-16">
            <v-card max-width="700" class="rounded" elevation="0">
                <v-row no-gutters>
                    <v-col cols="6">
                        <div align="left" v-if="view != 'login'">
                            <v-btn rounded="lg" elevation="0" @click="view = 'login'" class="text-none text-primary">
                                <v-icon class="text-primary mr-2">mdi-chevron-left</v-icon>
                                Back
                            </v-btn>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div align="right">
                            <v-btn icon elevation="0" to="/">
                                <v-icon class="text-primary">mdi-home</v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
                <div v-if="view=='login'">
                    <v-form ref="loginForm" v-model="isValid" lazy-validation>
                        <v-row no-gutters>
                            <v-col cols="12" xs="6" md="6" >
                                <div align="center">
                                    <v-img class="mt-1" max-width="200" src="images/resume_logo.jpeg"></v-img>
                                </div>
                            </v-col>														
                            <v-col cols="12" xs="6" md="6">
                                <div class="mx-2">
                                    <v-row no-gutters>
                                        <v-col cols="12" xs="12" md="12">
                                            <div align="center" class="mx-1 text-primaryText">
                                                Sign In To ResumeApp
                                            </div>
                                        </v-col>
                                        <v-col cols="12" xs="12" md="12" class="mt-1">
                                            <div align="left" class="mx-1 text-caption text-secondaryText">
                                                <b>Email</b>
                                            </div>
                                            <div class="mx-1">
                                                <v-text-field variant="outlined" class="text_field bg-text"
                                                    density="compact" 
                                                    v-model="userData.email" 
                                                    type="email"
                                                    :rules="emailRules"></v-text-field>
                                            </div>
                                        </v-col>
                                        <v-col cols="12" xs="12" md="12" class="mt-1">
                                            <div align="left" class="mx-1 text-caption text-secondaryText">
                                                <b>Password</b>
                                            </div>
                                            <div class="mx-1">
                                                <v-text-field variant="outlined" class="text_field bg-text"
                                                    density="compact" v-model="userData.password" type="password"
                                                    :rules="passwordRules"></v-text-field>
                                            </div>
                                        </v-col>					
                                        <v-col cols="12" xs="12" md="12">
                                            <div align="right" class="pa-1">
                                                <v-btn block variant="text" 
                                                    elevation="0" 
                                                    color="primary" 
                                                    @click="view = 'reset'"
                                                    class="text-none mb-1 mt-1">
                                                    Forgot Password?
                                                </v-btn>
                                            </div>
                                        </v-col>
                                        <v-col cols="12" xs="12" md="12">
                                            <div class="mx-1">
                                                <v-btn rounded="lg" block elevation="0" color="primary" class="text-none"
                                                    @click="login()" :disabled="!isValid" :loading="loading">
                                                    Log In
                                                    <v-icon>mdi-login</v-icon>
                                                </v-btn>
                                            </div>
                                        </v-col>
                                        <v-col cols="12" class="my-2">
                                            <div class="mx-1">
                                                <v-btn block variant="text" elevation="0" color="secondaryText" router
                                                    @click="view = 'create'" class="text-none text-caption">
                                                    Don't have an account? Sign Up
                                                </v-btn>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </div>
                            </v-col>
                        </v-row>
                    </v-form>
                </div>
                <!-- <div v-else-if="view=='loginVerification'">
                    <v-form ref="loginVerificationForm" v-model="isValid" lazy-validation>
                        <v-row no-gutters class="mt-2 ma-2">
                            <v-col cols="12" xs="12" md="3">
                                <div align="center">
                                    <v-icon size="80" color="primary">mdi-account-key</v-icon>
                                </div>
                            </v-col>
                            <v-spacer></v-spacer>
                            <v-col cols="12" xs="12" md="6">
                                <div align="left" class="mx-2">
                                    One Time Password
                                </div>
                                <div class="pa-2">
                                    <v-text-field class="text_field bg-text" 
                                        density="compact"
                                        variant="outlined"
                                        v-model.trim="userData.otp"
                                        hide-details>
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="12">
                                <div align="right">
                                    <v-btn rounded="lg" 
                                        elevation="0" 
                                        color="primary" 
                                        class="text-none mx-2"
                                        @click="verifyLogin" 
                                        :loading="loading">
                                        Verify <v-icon class="mx-1">mdi-key</v-icon>
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </v-form>
                </div> -->
                <div v-else-if="view=='create'">
                    <v-form ref="accountCreationForm" v-model="isValid" lazy-validation>
                        <div v-if="creationStage == 'default'">
                            <div align="center">
                                <v-img max-width="100" src="images/logo.jpeg"></v-img>
                            </div>
                            <div align="center" class="text-h7 font-weight-black my-1 text-primary">
                                Create an Account
                            </div>
                            <v-row no-gutters class="mx-1">
                                <v-col cols="12" xs="12" md="4">
                                    <div class="mx-1">
                                        <div align="left" class="mx-1 text-caption text-secondaryText">
                                            First Name <span class="text-red">*</span>
                                        </div>
                                        <v-text-field class="text_field bg-text" 
                                            density="compact"
                                            variant="outlined" 
                                            v-model="userData.first_name"
                                            :rules="inputRules">
                                        </v-text-field>
                                    </div>
                                </v-col>
                                <v-col cols="12" xs="12" md="4">
                                    <div class="mx-1">
                                        <div align="left" class="mx-1 text-caption text-secondaryText">
                                            Last Name <span class="text-red">*</span>
                                        </div>
                                        <v-text-field class="text_field bg-text" 
                                            density="compact"
                                            variant="outlined" 
                                            v-model="userData.last_name"
                                            :rules="inputRules">
                                        </v-text-field>
                                    </div>
                                </v-col>
                                <v-col cols="12" xs="12" md="4">
                                    <div class="mx-1">
                                        <div align="left" class="mx-1 text-caption text-secondaryText">
                                            Email <span class="text-red">*</span>
                                        </div>
                                        <v-text-field class="text_field bg-text" 
                                            density="compact"
                                            variant="outlined"
                                            v-model="userData.email" 
                                            type="email"
                                            :rules="emailRules">
                                        </v-text-field>
                                    </div>
                                </v-col>
                                <v-col cols="12" xs="12" md="6">
                                    <div class="mx-1 mt-1">
                                        <div align="left" class="mx-1 text-caption text-secondaryText">
                                            Password <span class="text-red">*</span>
                                        </div>
                                        <v-text-field class="text_field bg-text" 
                                            density="compact"
                                            variant="outlined" 
                                            v-model="userData.password" 
                                            type="password"
                                            :rules="passwordRules">
                                        </v-text-field>
                                    </div>
                                </v-col>
                                <v-col cols="12" xs="12" md="6">
                                    <div class="mx-1 mt-1">
                                        <div align="left" class="mx-1 text-caption text-secondaryText">
                                            Confirm Password <span class="text-red">*</span>
                                        </div>
                                        <v-text-field class="text_field bg-text" 
                                            density="compact"
                                            variant="outlined" 
                                            v-model="userData.confirm_password" 
                                            type="password"
                                            :rules="passwordRules">
                                        </v-text-field>
                                    </div>
                                </v-col>
                                <v-col cols="12" class="mt-2 mb-4">
                                    <div class="mx-1">
                                        <v-btn rounded="lg" 
                                            block 
                                            elevation="0" 
                                            color="primary" 
                                            class="text-none"
                                            @click="createAccount" 
                                            :loading="loading" 
                                            :disabled="!isValid">
                                            Create Account <v-icon right dark>mdi-account-plus</v-icon>
                                        </v-btn>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                        <div v-else-if="creationStage == 'complete'">
                            <v-row no-gutters class="mt-2">
                                <v-col cols="12" xs="3" md="3">
                                    <div align="center" class="mt-3">
                                        <v-icon size="80" color="success">mdi-account-check</v-icon>
                                    </div>
                                </v-col>
                                <v-col cols="12" xs="9" md="9">
                                    <div class="mx-2">
                                        <v-alert
                                            text="Let's get started!"
                                            title="Account Created"
                                            type="success">
                                        </v-alert>
                                    </div>
                                    <div align="center" class="mt-2">
                                        <v-btn 
                                            variant="tonal" 
                                            color="primary" 
                                            class="text-none mb-2" 
                                            @click="view='login'">
                                            Proceed to Login
                                            <v-icon class="ml-2">mdi-login</v-icon>
                                        </v-btn>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                    </v-form>
                </div>
            </v-card>
        </div>
    </v-dialog>
</template>