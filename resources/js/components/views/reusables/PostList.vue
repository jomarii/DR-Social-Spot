<template>
	<v-container fluid grid-list-md>
		<v-row>
			<v-col>
				<v-layout row wrap align-center>
					<v-flex md12>
						<v-card>
							<v-card-title>What's on your mind?</v-card-title>
							<v-card-text>
					        	<v-form>
					        		<v-text-field v-model="form.post" placeholder="Type your post here" type="text"></v-text-field>
					        	</v-form>
					        </v-card-text>
							<v-card-actions>
								<v-btn color="primary" @click="post()">Post</v-btn>
								<v-btn text>Cancel</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-layout>
			</v-col>
		</v-row>
		<v-row>
			<v-col>
				<v-layout row wrap align-center>
					<v-flex md12 v-for="(post, index) in postList" :key="index">
						<v-card>
							<v-card-title>{{ post.post }}</v-card-title>
							<v-card-subtitle>{{ post.user }} • {{ post.created_at }}</v-card-subtitle>
							<v-card-text>Likes: {{ post.likes }}</v-card-text>
							<v-card-actions>
								<v-btn text @click="likePost(post.post_id)">Like</v-btn>
								<v-btn text>Show comments</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-layout>
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
	export default{
		props: ['isNewsfeed'],
		data: () => ({
			postList: [],
			form: {
				post: ''
			}
		}),
		methods: {
			getPosts(){
				let url = this.isNewsfeed ? '/api/v1/newsfeed' : '/api/v1/post/'+this.$route.params.id;
				axios.get(url).then(response => {
					this.postList = response.data.data;
				});
			},
			post(){
				axios.post('/api/v1/post/create', this.form).then(response => {
					this.form.post = '';
					this.getNewsfeed();
				});
			},
			likePost(postId){
				axios.put('/api/v1/post/like/'+postId).then(response => {
					this.getPosts();
				});
			}
		},
		mounted(){
			this.getPosts();
		}
	}
</script>