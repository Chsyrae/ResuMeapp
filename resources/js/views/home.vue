<script setup>
import router from "@/router";
import { userAuthStore } from "@/stores/userAuth";
const userAuth = userAuthStore();

const launchBuilder = () => {
    if (userAuth.status == 'success') {
        router.push({ name: 'Builder' });
    } else{
        router.push({ name: 'Login' });
    }
}
</script>
<template>
    <v-container>
        <v-card elevation="0" class="mx-auto rounded mt-n6">
            <v-card-text>
                <v-row no-gutters>
                    <v-col cols="12" >
                        <div align="center" class="text-uppercase mt-n2 mb-3">
                            <b>Welcome to ResuMe</b>
                        </div>
                    </v-col>
                    <v-col cols="12" >
                        <div align="center" class="text-h4 mt-3 mb-3">
                            Only 2% of resumes make it past the first round. Be in the top 2%. You do you!!!
                        </div>
                    </v-col>
                    <v-col cols="12" >
                        <div  class="text-body-1 text-center mt-3">
                            Use professional field-tested resume templates that follow the exact 'resume rules' employers look for. Easy to use and done within minutes - try now for free!
                        </div>
                    </v-col>
                    <v-col cols="12" >
                        <div align="center" class="text-body-1 mb-6 mt-4">
                            <v-btn
                                depressed
                                color="primary"
                                variant="flat" 
                                class="text-none mr-2 mb-3 mt-4"
                                @click="launchBuilder"  
                            >
                                CREATE MY RESUME
                            </v-btn>
                        </div>
                    </v-col>
                    <v-col cols="12" class="mt-6">
                        <div align="center" class="mt-4 mb-8">
                            <v-img src="assets/images/dublin-resume-templates.avif" :width="800"  alt="Resume Templates" />
                        </div>
                    </v-col>
                </v-row>
                <v-row no-gutters class="mt-8">
                    <v-col cols="12" md="6">
                        <div align="center" class="text-body-1 mt-4">
                            <v-img src="assets/images/visual-0c7080adf17f1f207276f613447c924f667dab34b7ac415cd7ef653172defd0b.svg" alt="Resume Templates" />
                        </div>
                    </v-col>
                    <v-col cols="12" md="6">
                        <div class="text-h4 text-center mt-4">
                            Use the best resume maker as your guide!
                        </div>
                        <div class="text-center text-body-1 mt-4">
                            Getting that dream job can seem like an impossible task. We're here to change that. Give yourself a real advantage with the best online resume maker: created by experts, improved by data, trusted by millions of professionals.
                        </div>
                    </v-col>
                </v-row>
                <v-row no-gutters class="mt-8">
                    <v-col cols="12" md="4" class="pa-5">
                        <div align="left" class="mt-4">
                            <v-img class="w-25" src="assets/images/feature-1-edf4481d69166ac81917d1e40e6597c8d61aa970ad44367ce78049bf830fbda5.svg" alt="Resume Templates" />
                        </div>
                        <div class="text-h5 mb-3">
                            Make a resume that wins interviews!
                        </div>
                        <div class="text-body-1 mt-3">
                            Use our resume maker with its advanced creation tools to tell a professional story that engages recruiters, hiring managers, and even CEOs.
                        </div>
                    </v-col>
                    <v-col cols="12" md="4" class="pa-5">
                        <div align="left" class="mt-4">
                            <v-img class="w-25" src="assets/images/feature-2-a7a471bd973c02a55d1b3f8aff578cd3c9a4c5ac4fc74423d94ecc04aef3492b.svg" alt="Resume Templates" />
                        </div>
                        <div class="text-h5 mb-3">
                            Resume writing made easy!
                        </div>
                        <div class="text-body-1 mt-3">
                            Resume writing has never been this effortless. Pre-generated text, visual designs, and more - all already integrated into the resume maker. Just fill in your details.
                        </div>
                    </v-col>
                    <v-col cols="12" md="4" class="pa-5">
                        <div align="left" class="mt-4">
                            <v-img class="w-25" src="assets/images/feature-3-4e87a82f83e260488c36f8105e26f439fdc3ee5009372bb5e12d9421f32eabdd.svg" alt="Resume Templates" />
                        </div>
                        <div class="text-h5 mb-3">
                            A recruiter-tested CV maker tool!
                        </div>
                        <div class="text-body-1 mt-3">
                            Our resume builder and its pre-generated content are tested by recruiters and IT experts. We help your CV become truly competitive in the hiring process.
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>

        </v-card>
    </v-container>
    <v-divider></v-divider>
    <footer>
        <div class="text-center">
            &copy; Copyright  {{ new Date().getFullYear() }}. All Rights Reserved &reg;
        </div>
    </footer>
</template>
