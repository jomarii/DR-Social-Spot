<template>
	<div>
		<v-app-bar app flat dense color="blue">
		    <v-toolbar-title>DR Social Spot</v-toolbar-title>
		    <v-spacer></v-spacer>
	    	<v-btn v-for="(menu, index) in menus" color="white" :key="index" text :to="menu.path">
		        {{ menu.name }}
		    </v-btn>
		    <v-btn text color="white" @click="logout()">Log out</v-btn>
		</v-app-bar>
	</div>
</template>
<script>
	export default {
        data: () => ({
			menus: [
				{path: '/newsfeed', name: 'Newsfeed'},
				{path: '/profile/', name: 'My Profile'},
			],
        }),
        methods: {
        	logout(){
        		axios.put('/api/v1/logout').then(response => {
					localStorage.removeItem('bearerToken');
					this.$router.push('/login');
				});
        	}
        }
    }
</script>