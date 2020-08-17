<template>
	<div>
		<v-app-bar app flat dense color="blue">
		    <v-toolbar-title>DR Social Spot</v-toolbar-title>
		    <v-spacer></v-spacer>
		    <template v-if="showMenu()">
		    	<v-btn v-for="(menu, index) in menus" color="white" :key="index" text :to="menu.path">
		        {{ menu.name }}
			    </v-btn>
			    <v-btn text color="white" @click="logout()">Log out</v-btn>
		    </template>
		</v-app-bar>
	</div>
</template>
<script>
	export default {
        data: () => ({
			menus: [
				{path: '/newsfeed', name: 'Newsfeed'},
				{path: '/profile/'+localStorage.getItem('userId'), name: 'My Profile'},
			],
        }),
        methods: {
        	logout(){
        		axios.put('/api/v1/logout').then(response => {
					localStorage.removeItem('bearerToken');
					localStorage.removeItem('userId');
					this.$router.push('/login');
				});
        	},
        	showMenu(){
        		return localStorage.getItem('bearerToken') != null;
        	}
        }
    }
</script>