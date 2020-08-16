<template>
	<div>
		<template v-if="!this.notFound">{{ user.first_name }} {{ user.last_name }}</template>
		<template v-else>Profile Not Found</template>
		<post-list :isNewsfeed="false"></post-list>
	</div>
</template>
<script>
	export default{
		data: () => ({
			user: [],
			notFound: false,
		}),
		methods: {
			getProfile(){
				axios.get('/api/v1/profile/'+this.$route.params.id).then(response => {
					this.user = response.data.data;
				}).catch(error => {
					this.notFound = true;
				});
			}
		},
		created(){
			this.getProfile();
		}
	}
</script>