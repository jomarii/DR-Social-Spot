<template>
	<post-list :isNewsfeed="true" :showPostForm="true"></post-list>
</template>
<script>
	export default{
		created(){
			axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('bearerToken')}`;
		}
	}
</script>