<script setup>
import { ref, computed } from "vue";
import { templateStore } from "@/stores/template";
import { userAuthStore } from "@/stores/userAuth";
import router from "@/router";
import apiCall from '@/utils/api';
import axios from 'axios';

const resumeTemplate = templateStore();
const userAuth       = userAuthStore();
const formView       = ref('one');
const isCardVisible  = ref(true);
const defaultSection = ref('Personal Information');
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

const changeFormView = (direction) => {
    console.log(formView.value)
    if (direction == 'next') {
        if (formView.value == 'one') {
            formView.value = 'two';
            defaultSection.value = 'Work Experience';
        } else if (formView.value == 'two') {
            formView.value = 'three';
            defaultSection.value = 'Education';
        } else if (formView.value == 'three') {
            formView.value = 'four';
            defaultSection.value = 'Skills and Interests';
        } else if (formView.value == 'four') {
            // router.push({ name: "Template" }); 
            // console.log('Personal Info')
            // console.log(personalInfo.value)

            // console.log('Work XP')
            // console.log(workExperiences.value)

            // console.log('Education')
            // console.log(educationExperiences.value)

            // console.log('Skills')
            // console.log(skills.value)

            // console.log('Interests')
            // console.log(interestsArray.value)
            isCardVisible.value = false;
            formView.value      = 'five';
            //generateResume()
        }
    } else if(direction == 'previous') {
        if (formView.value == 'three') {
            formView.value = 'two';
            defaultSection.value = 'Work Experience';
        } else if (formView.value == 'two') {
            formView.value = 'one';
            defaultSection.value ='Personal Information';
        } else if (formView.value == 'four') {
            formView.value = 'three';
            defaultSection.value='Education';
        } else if (formView.value == 'five') {
            isCardVisible.value  = true;
            formView.value       = 'four';
            defaultSection.value = 'Education';
        }
    }
}

const personalInfo = ref({
    fname:             userAuth.user.first_name,
    lname:             userAuth.user.last_name,
    email:             userAuth.user.email,
    physicalAddress:   null,
    phoneNo:           null,
    personalStatement: null
});

const workExperiences = ref([
    {organization: null, designation: null, dateJoined: null, dateLeft: null, achievementsResponsibilities: [] }
]);
const addWorkExperience = () => {
    workExperiences.value.push({organization: null, designation: null, dateJoined: null, dateLeft: null, achievementsResponsibilities: [] });
};
const removeWorkExperience = (index) => {
    workExperiences.value.splice(index,1);
};

const educationExperiences = ref([
    { institution: null, dateJoined: null, dateLeft: null, qualification: null }
]);
const addEducationExperience = () => {
    educationExperiences.value.push({ institution: null, dateJoined: null, dateLeft: null, qualification: null });
};
const removeEducationExperience = (index) => {
    educationExperiences.value.splice(index, 1);
};

const skills    = ref('');
const interests = ref('');

const skillsArray    = computed(() => skills.value.split("\n").filter(line => line.trim() !== ""));
const interestsArray = computed(() => interests.value.split("\n").filter(line => line.trim() !== ""));


const getTemplates = () => {
    apiCall({
        url: '/api/template?type=getTemplates',
        method: 'GET'
    }).then(resp => {
        resumeTemplate.setPublicTemplates(resp);
        resumeTemplate.changePublicLoaderStatus();
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
        resumeTemplate.setPublicTemplates(resp);
        resumeTemplate.changePublicLoaderStatus();
    }).catch(err => {
        showSnackBar('An error occurred', 'error');
    })
}

const templateLength = computed(() => {
    return Math.ceil(
        resumeTemplate.publicTemplatePagination.total / resumeTemplate.publicTemplatePagination.per_page
    );
});

const selectedTemplate = ref({});
const selectTemplate = (template) => {
    selectedTemplate.value = template;
}

const renderResumeDialog = ref(false);
const resumeLink         = ref(null); 
const generateResume = () => {
    axios({
        url: '/api/resume-generator',
        responseType: 'blob',
        method: 'POST',
        data: {
            personalInfo: personalInfo.value,
            workExperience: workExperiences.value,
            educationExperience: educationExperiences.value,
            skills: skillsArray.value,
            interests: interestsArray.value,
            templateName: selectedTemplate.value.name.toLowerCase()
        }
    }).then((resp) => {
        const blob       = new Blob([resp.data], { type: 'application/pdf' });
        resumeLink.value = window.URL.createObjectURL(blob);
        renderResumeDialog.value = true;
    })
}
 
const careerAdvice = ref(null);
const generateAdvice = () => {
    if(skillsArray.value.length == 0) {
        showSnackBar('No skills provided', 'error');
        return;
    }
    apiCall({
        url: '/api/resume-generator?type=getAdvice',
        method: 'POST',
        data: {skills: skillsArray.value}
    }).then(resp => {
        careerAdvice.value = resp;
    })
}

</script>
<style scoped>
    .on-hover {
        cursor: pointer;
        border: solid crimson 2px;
        border-radius: 2px;
        transition: transform 0.2s ease-in-out;
    }
    .selected {
        border: 3px solid #4caf50;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
</style>
<template>
    <v-container>
        <v-snackbar v-model="snackbar" :color="color" elevation="24">
            {{ message }}
        </v-snackbar>
        <v-dialog transition="dialog-bottom-transition" max-width="800" v-model="renderResumeDialog">
            <v-card>
                <v-toolbar color="primary">
                    <v-row no-gutters>
                        <v-col cols="11">
                            <div class="ml-3 mt-3">
                                {{ userAuth.user.full_name }}'s Resume
                            </div>
                        </v-col>
                        <v-col cols="1">
                            <div align="right">
                                <v-btn
                                    icon
                                    @click="renderResumeDialog = false;">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-toolbar>
                <v-card-text>
                    <div>
                        <iframe :src="resumeLink" width="100%" height="500px"></iframe>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-card v-if="isCardVisible" elevation="0" class="pa-2 pa-md-7 rounded">
            <v-toolbar color="primary">
                <v-row no-gutters>
                    <v-col cols="11">
                        <div class="ml-3 mt-3">{{ defaultSection }}</div>
                    </v-col>
                    <v-col cols="1">
                        <div align="right">
                            <v-btn
                                icon
                                @click="() => documentDialog = false">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-toolbar>
            <v-form>
                <div v-if="formView == 'one'">
                    <v-row no-gutters>
                        <v-col cols="12" xs="12" md="4" class="mt-2">
                            <div class="mx-1">
                                <div>
                                    <b>First Name</b><span class="text-error">*</span>
                                </div>
                                <v-text-field
                                    v-model="personalInfo.fname"
                                    density="compact">
                                </v-text-field>
                            </div>
                        </v-col>
                        <v-col cols="12" xs="12" md="4" class="mt-2">
                            <div class="mx-1">
                                <div>
                                    <b>Last Name</b><span class="text-error">*</span>
                                </div>
                                <v-text-field
                                    v-model="personalInfo.lname"
                                    density="compact">
                                </v-text-field>
                            </div>
                        </v-col>
                        <v-col cols="12" xs="12" md="4" class="mt-2">
                            <div class="mx-1">
                                <div>
                                    <b>Email Address</b><span class="text-error">*</span>
                                </div>
                                <v-text-field
                                    v-model="personalInfo.email"
                                    density="compact">
                                </v-text-field>
                            </div>
                        </v-col>
                        <v-col cols="12" xs="12" md="4">
                            <div class="mx-1">
                                <div>
                                    <b>Physical Address</b><span class="text-error">*</span>
                                </div>
                                <v-text-field
                                    v-model="personalInfo.physicalAddress"
                                    density="compact">
                                </v-text-field>
                            </div>
                        </v-col>
                        <v-col cols="12" xs="12" md="4">
                            <div class="mx-1">
                                <div>
                                    <b>Phone Number</b><span class="text-error">*</span>
                                </div>
                                <v-text-field
                                    v-model="personalInfo.phoneNo"
                                    density="compact">
                                </v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row no-gutters class="mt-3">
                        <v-col cols="12" xs="12" md="8" class="mt-2">
                            <div class="mx-1">
                                <div>
                                    <b>Personal Statement</b><span class="text-error">*</span>
                                </div>
                                <v-textarea
                                    v-model="personalInfo.personalStatement"
                                    density="compact">
                                </v-textarea>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <div v-if="formView == 'two'">
                    <template v-for="(workExperience, index) in workExperiences" :key="index">
                        <v-divider></v-divider>
                        <v-row no-gutters>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Organization Name</b><span class="text-error">*</span>
                                    </div>
                                    <v-text-field
                                        v-model="workExperiences[index].organization"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Date Joined</b><span class="text-error">*</span>
                                    </div>
                                    <v-text-field
                                        v-model="workExperiences[index].dateJoined"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Date Left </b><span class="text-caption"><i>- Optional</i></span>
                                    </div>
                                    <v-text-field
                                        v-model="workExperiences[index].dateLeft"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Designation</b><span class="text-error">*</span>
                                    </div>
                                    <v-text-field
                                        v-model="workExperiences[index].designation"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="8" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Achievements/Responsibilities</b><span class="text-error">*</span>
                                    </div>
                                    <v-textarea
                                        :model-value="workExperiences[index].achievementsResponsibilities.join('\n')"
                                        @update:modelValue="(newValue) => workExperiences[index].achievementsResponsibilities = newValue.split('\n').filter(line => line.trim() !== '')"

                                        density="compact">
                                    </v-textarea>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row no-gutters v-if="index != 0">
                            <v-spacer></v-spacer>
                            <v-col cols="12" xs="12" md="4">
                                <div align="right">
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        depressed
                                        color="error"
                                        variant="flat" 
                                        class="text-none mr-2 mb-3"  
                                        @click="removeWorkExperience(index)"
                                    >
                                        Remove
                                        <v-icon right> mdi-close</v-icon>
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </template>
                    <v-divider></v-divider>
                    <v-row no-gutters class="mt-6">
                        <v-spacer></v-spacer>
                        <v-col cols="12" xs="12" md="4">
                            <div align="right">
                                <v-spacer></v-spacer>
                                <v-btn
                                    depressed
                                    color="primary"
                                    variant="flat" 
                                    class="text-none mr-2 mb-3"  
                                    @click="addWorkExperience"
                                >
                                    Add
                                    <v-icon right> mdi-plus</v-icon>
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <div v-if="formView == 'three'">
                    <template v-for="(educationBackground, index) in educationExperiences" :key="index">
                        <v-divider></v-divider>
                        <v-row no-gutters>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Institution</b><span class="text-error">*</span>
                                    </div>
                                    <v-text-field
                                        v-model="educationExperiences[index].institution"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Date Joined</b><span class="text-error">*</span>
                                    </div>
                                    <v-text-field
                                        v-model="educationExperiences[index].dateJoined"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Date Left </b><span class="text-caption"><i>- Optional</i></span>
                                    </div>
                                    <v-text-field
                                        v-model="educationExperiences[index].dateLeft"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="12" xs="12" md="4" class="mt-2">
                                <div class="mx-1">
                                    <div>
                                        <b>Qualification</b><span class="text-error">*</span>
                                    </div>
                                    <v-text-field
                                        v-model="educationExperiences[index].qualification"
                                        density="compact">
                                    </v-text-field>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row no-gutters v-if="index != 0">
                            <v-spacer></v-spacer>
                            <v-col cols="12" xs="12" md="4">
                                <div align="right">
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        depressed
                                        color="error"
                                        variant="flat" 
                                        class="text-none mr-2 mb-3"  
                                        @click="removeEducationExperience(index)"
                                    >
                                        Remove
                                        <v-icon right> mdi-close</v-icon>
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </template>
                    <v-divider></v-divider>
                    <v-row no-gutters class="mt-6">
                        <v-spacer></v-spacer>
                        <v-col cols="12" xs="12" md="4">
                            <div align="right">
                                <v-spacer></v-spacer>
                                <v-btn
                                    depressed
                                    color="primary"
                                    variant="flat" 
                                    class="text-none mr-2 mb-3"  
                                    @click="addEducationExperience"
                                >
                                    Add
                                    <v-icon right> mdi-plus</v-icon>
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <div v-if="formView == 'four'">
                    <v-row no-gutters>
                        <v-col cols="12" xs="12" md="5" class="mt-2">
                            <div class="mx-1">
                                <div>
                                    <b>Skills</b><span class="text-error">*</span>
                                </div>
                                <v-textarea
                                    v-model="skills"
                                    density="compact">
                                </v-textarea>
                            </div>
                        </v-col>
                        <v-spacer></v-spacer>
                        <v-col cols="12" xs="12" md="5" class="mt-2">
                            <div class="mx-1">
                                <div>
                                    <b>Interests</b><span class="text-error">*</span>
                                </div>
                                <v-textarea
                                    v-model="interests"
                                    density="compact">
                                </v-textarea>
                            </div>
                        </v-col> 
                    </v-row>
                </div>
            </v-form>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    depressed
                    color="primary"
                    variant="flat" 
                    class="text-none mr-2 mb-3"  
                    @click="() => changeFormView('previous')"

                >
                    Back
                    <v-icon right class="pa-3"> mdi-page-previous-outline </v-icon>
                </v-btn>
                <v-btn
                    depressed
                    color="green"
                    variant="flat" 
                    class="text-none mb-3"  
                    @click="() => changeFormView('next')"

                >
                    Next
                    <v-icon right class="pa-3"> mdi-page-next-outline </v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>
        <div v-if="formView == 'five'">
            <div class="text-h5">Select Your Resume Template</div>
            <div align="left">
                <v-btn
                    depressed
                    color="green"
                    variant="flat" 
                    class="text-none mt-2"  
                    @click="() => changeFormView('previous')"
                >
                    Back
                    <v-icon right class="pa-3"> mdi-keyboard-backspace </v-icon>
                </v-btn>
            </div>
            
            <v-row no-gutters>
                <v-col cols="12" sm="6" md="3"
                    v-for="(resumeTemplate, index) in resumeTemplate.publicTemplates"
                    :key="resumeTemplate.id"
                    class="mt-4 mb-5">
                        <v-hover v-slot="{ isHovering, props }">
                            <v-card 
                                v-bind="props"
                                class="mb-2 ma-3 pa-3 pa-md-3 rounded"
                                :class="{ 
                                    'on-hover': isHovering, 
                                    'selected': selectedTemplate.id === resumeTemplate.id 
                                }"
                                :color="isHovering ? 'success' : 'primary'"
                                variant="tonal"
                                elevation="0"
                                @click="selectTemplate(resumeTemplate)"
                            >
                                <v-img 
                                    :src="`/storage/templates/${resumeTemplate.url}`"
                                    :alt="resumeTemplate.name"
                                    :height="260"
                                    class="pa-2"/>
                                <div class="mt-3">{{ resumeTemplate.name }}</div>
                            </v-card>
                        </v-hover>
                </v-col>
                <v-col cols="12" sm="12" md="12">
                    <div align="center">
                        <v-pagination 
                            v-if="length != 0" 
                            :length="templateLength" 
                            :total-visible="resumeTemplate.publicTemplatePagination.visible"
                            v-model="resumeTemplate.publicTemplatePagination.current_page" 
                            @update:modelValue="changePage()" 
                            circle>
                        </v-pagination>
                    </div>
                </v-col>
                <v-col cols="12">
                    <div align="center" class="mt-5" v-if="templateLength != 0">
                        <b class="text--text">Total: </b>{{ resumeTemplate.publicTemplatePagination.total }}
                    </div>
                    <div align="right">
                        <v-btn
                            depressed
                            color="blue-lighten-1"
                            variant="flat" 
                            class="text-none mb-3 ma-1"  
                            @click="generateAdvice"
                        >
                            Advice
                            <v-icon right class="pa-3"> mdi-eye </v-icon>
                        </v-btn>
                        <v-btn
                            depressed
                            color="blue-lighten-1"
                            variant="flat" 
                            class="text-none mb-3 ma-1"  
                            @click="generateResume"
                            :disabled="Object.keys(selectedTemplate).length == 0 ? true : false"
                        >
                            Preview
                            <v-icon right class="pa-3"> mdi-eye </v-icon>
                        </v-btn>
                    </div>
                </v-col>
            </v-row>
        </div>
        <div v-if="formView == 'five'" class="mt-6">
            <v-card>
                <v-card-title classs="text-primary">
                    <v-row no-gutters>
                        <v-col cols="12" xs="12" md="12">
                            <div align="left">
                                Career Advisory
                            </div>
                        </v-col>
                    </v-row>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-row no-gutters>
                        <v-col cols="12" xs="12" md="12">
                            <div class="mb-2">
                                Based on your skills, we share insights on plausible opportunities you may consider for exploration.
                                Please note that these recommendations are AI-generated and we advise that you consult further with experts.
                            </div>
                            <div>{{ careerAdvice }}</div>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </div>
    </v-container>
</template>