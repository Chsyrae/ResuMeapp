<script setup>
import router from "@/router";
import apiCall from '@/utils/api';
import { ref } from "vue";
import { userAuthStore } from "@/stores/userAuth";
import { useRoute } from "vue-router";

const route	 	   = useRoute();
const userAuth 	   = userAuthStore();

const snackbar = ref(false);
const message  = ref(null);
const color	   = ref(null);

const drawer			= ref(false);
const adminDrawer		= ref(true);
const openBills 		= ref(['Bills']);
const openReports	    = ref(['Reports']);


function openProfile() {
	router.push({ name: "Profile" });
}

function signOut() {
	userAuth.authLogout();
	router.push({ name: "Home" });
}


// function userRequest() {
// 	apiCall({ url: '/api/get-user', method: 'GET' }).then(resp => {
// 		userAuth.userUpdate(resp);
// 	}).catch(err => {
// 		userAuth.authLogout();
// 		router.push({ name: "Dashboard" });
// 	})
// }
// userRequest();
</script>
<template>
	<v-snackbar v-model="snackbar" :color="color" elevation="24">
		<div align="center">{{ message }}</div>
	</v-snackbar>

	<v-navigation-drawer app v-model="adminDrawer" relative v-if="userAuth.user.ability.length != 0">
		<v-list>
			<v-list-item :prepend-avatar="'images/resume_logo.jpeg' + userAuth.user.profile.profile_image"
				:subtitle="userAuth.user.email" :title="userAuth.user.first_name"
				v-if="userAuth.user.profile !=null && userAuth.user.profile.profile_image != null">
			</v-list-item>
			<v-list-item prepend-avatar="images/resume_logo.jpeg" :subtitle="userAuth.user.email"
				:title="userAuth.user.first_name + ' ' + userAuth.user.last_name" v-else>
			</v-list-item>
		</v-list>
		<v-divider></v-divider>

		<div class="mx-1">
			<v-list density="compact" nav>
				<v-list-group value="Access Control" v-if="$can('user_create')">
					<template v-slot:activator="{ props }">
						<v-list-item v-bind="props" prepend-icon="mdi-account-circle" class="text-caption">
							Access Control
						</v-list-item>
					</template>
					<v-list-item prepend-icon="mdi-account-group" value="Users" router to="/user"  class="text-caption"
						:color="'/' == route.fullPath.slice(0) ? 'primary' : '#6D88B9'"
					>
						Users
					</v-list-item>
				</v-list-group>	
				
				<v-list-item prepend-icon="mdi-view-dashboard" class="text-caption" router to="/template"  v-if="$can('template_view')"
					:color="'/' == route.fullPath.slice(0) ? 'primary' : '#6D88B9'"
				>
					Templates
				</v-list-item>
			</v-list>



		


			<v-list-item class="text-red text-caption mt-1" prepend-icon="mdi-lock" value="sign out" @click="signOut">
				Sign Out
			</v-list-item>
		</div>
	</v-navigation-drawer>
	<v-app-bar app scroll-behavior="elevate">
  		<v-app-bar-nav-icon @click="adminDrawer = !adminDrawer"></v-app-bar-nav-icon>
		<v-row align="center">
			<v-img v-if="userAuth.user.ability.length == 0" class="ml-13 hidden-sm-and-down" max-width="80"
				src="images/logo.jpeg"></v-img>
			<v-img v-else class="ml-4 hidden-sm-and-down" max-width="80"
				src="images/logo.jpeg"></v-img>
			<v-img class="hidden-md-and-up ml-5" max-width="80" src="images/logo.jpeg"></v-img>
			<div class="text-primaryText ml-2 hidden-sm-and-down">
				<b>ResumeApp</b>
			</div>
			<v-spacer></v-spacer>
			<v-btn size="small" to="/" class="mr-2 text-none hidden-sm-and-down"
				:variant="'/' == route.fullPath.slice(0) ? 'flat' : 'text'"
				:color="'/' == route.fullPath.slice(0) ? 'primary' : '#6D88B9'"
				style="transition: color 0.3s ease; background-color: 0.3s ease;"
				v-if="userAuth.user.ability.length == 0"
			>
				Home
			</v-btn>
			<v-menu v-if="userAuth.isAuthenticated && userAuth.user.ability.length == 0">
				<template v-slot:activator="{ props }">
					<v-btn variant="flat" size="small"  color="secondary" v-bind="props" class="mr-13 hidden-sm-and-down">
						<v-avatar size="30" color="white"
							:image="'storage/profile_pics/' + userAuth.user.profile.profile_image"
							v-if="userAuth.user.profile != null && userAuth.user.profile.profile_image != null">
						</v-avatar>
						<v-avatar size="25" color="white" v-else>
							<v-icon >
								mdi-account
							</v-icon>
						</v-avatar>
						<div class="ml-2 text-none text-caption"> {{ userAuth.user.first_name }}
							{{ userAuth.user.last_name }}</div>
						<v-icon>mdi-chevron-down</v-icon>
					</v-btn>
				</template>
				<v-list>
					<v-list-item @click="signOut">
						<v-list-item-title class="text-caption">Sign Out</v-list-item-title>
					</v-list-item>
				</v-list>
			</v-menu>
			<v-menu v-if="userAuth.isAuthenticated">
				<template v-slot:activator="{ props }">
					<v-btn icon variant="flat" color="secondary" v-bind="props" class="mr-5 mb-2 hidden-md-and-up">
						<v-avatar size="45" color="white" image="images/resume_logo.jpeg" />
					</v-btn>
				</template>
				<v-list>
					<v-list-item @click="signOut">
						<v-list-item-title class="body-2">Sign Out</v-list-item-title>
					</v-list-item>
				</v-list>
			</v-menu>
		</v-row>
	</v-app-bar>
</template>
