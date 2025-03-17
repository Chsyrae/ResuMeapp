<script setup>
import { ref } from "vue";
import router from "@/router";
import { userAuthStore } from "@/stores/userAuth";

const userAuth = userAuthStore();
const snackbar = ref(false)
const message  = ref(null)
const color    = ref(null)

const drawer = ref(false);

const logout = () => {
	userAuth.authLogout();
	router.push({ name: 'Home'});
}
</script>
<template>
	<v-snackbar v-model="snackbar" :color="color" elevation="24">
		{{ message }}
	</v-snackbar>
	<div>
		<v-navigation-drawer app v-model="drawer" disable-resize-watcher relative>
			<v-list density="compact" nav>
				<v-list-item class="text-caption" prepend-icon="mdi-home" router to="/" value="home">
					Home
				</v-list-item>
			</v-list>
		</v-navigation-drawer>
		<v-app-bar scroll-behavior="elevate" app height="40">
			<v-app-bar-nav-icon @click.stop="drawer = !drawer" class="hidden-md-and-up" />
			<v-row no-gutters class="hidden-md-and-up">
				<v-img max-width="50" class="mt-2 ml-2" src="images/resume_logo.jpeg"></v-img>
				<div align="center" class="text-primary mt-4 ml-5 hidden-sm-and-down">
					ResumeApp
				</div>
				<div align="center" class="text-primary mt-4 ml-5 hidden-md-and-up">
					ResumeApp
				</div>
				<v-spacer></v-spacer>
				<v-col cols="12">
					<v-divider class="mt-1"></v-divider>
				</v-col>
			</v-row>

			<template v-slot:extension>
				<v-img max-width="80" src="images/resume_logo.jpeg" class="ml-10 mb-2 hidden-sm-and-down"></v-img>
				<b class="mb-3 hidden-sm-and-down text-primary ">ResumeApp</b>
				<v-spacer class="hidden-sm-and-down"></v-spacer>
				<div align="center" class="mb-2 mr-1 hidden-sm-and-down">
					<v-btn size="small" block elevation="0" color="primary" class="text-none" to="/">
						Home
					</v-btn>
				</div>
				<div align="center" class="mb-2 mr-16 hidden-sm-and-down">
					<v-btn v-if="userAuth.status != 'success'"
						size="small" 
						block 
						elevation="0" 
						color="primary" 
						class="text-none"
						to="login" 
						variant="flat">
						Log In
						<v-icon>mdi-login</v-icon>
					</v-btn>
					<v-btn v-if="userAuth.status == 'success'"
						size="small" 
						block 
						elevation="0" 
						color="primary" 
						class="text-none"
						@click="logout" 
						variant="flat">
						Log Out
						<v-icon>mdi-login</v-icon>
					</v-btn>
				</div>
				<v-spacer class="hidden-md-and-up"></v-spacer>
				<v-btn size="small" elevation="0" color="primary" class="text-none mr-2 hidden-md-and-up"
					@click="system.changeLoaderStatus()" variant="flat">
					Log In
					<v-icon class="ml-2">mdi-login</v-icon>
				</v-btn>
			</template>
		</v-app-bar>
	</div>
</template>