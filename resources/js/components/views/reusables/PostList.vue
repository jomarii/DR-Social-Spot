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
					        		<v-text-field v-model="postData" placeholder="Type your post here" type="text"></v-text-field>
					        	</v-form>
					        </v-card-text>
							<v-card-actions>
								<v-btn color="primary" @click="post()">Post</v-btn>
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
							<v-card-title v-if="post.sharedFrom == null">{{ post.post }}</v-card-title>
							<v-card-title v-else>{{ post.sharedFrom.sharedPost }}</v-card-title>
							<v-card-subtitle>{{ post.user }} <span v-if="post.sharedFrom != null">shared from {{ post.sharedFrom.user }}</span> • {{ post.created_at }} • 
								<v-tooltip top>
									<template v-slot:activator="{ on, attrs }">
										<span v-bind="attrs" v-on="on">Likes: {{ post.likes }}</span>
									</template>
									<template v-for="(liker, index) in post.likers">
										<span>{{ liker.full_name }}</span><br/>
									</template>
								</v-tooltip>
							</v-card-subtitle>
							<v-card-actions>
								<v-btn text @click="likePost(post.post_id)">Like</v-btn>
								<v-btn text @click="sharePost(post.post_id)">Share</v-btn>
								<v-btn text @click="showComments(post.comments, post.post_id)">Show comments</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-layout>
			</v-col>
		</v-row>
		<v-row>
			<comment-dialog v-show="commentDialog" :commentsData="commentsData"></comment-dialog>
		</v-row>
	</v-container>
</template>
<script>
	export default{
		props: ['isNewsfeed'],
		data: () => ({
			postList: [],
			postData: '',
			commentsData: {
				comments: [],
				postId: ''
			},
			commentDialog: false
		}),
		methods: {
			getPosts(){
				let url = this.isNewsfeed ? '/api/v1/newsfeed' : '/api/v1/post/'+this.$route.params.id;
				axios.get(url).then(response => {
					this.postList = response.data.data;
				});
			},
			post(){
				axios.post('/api/v1/post/create', {'post' : this.postData }).then(response => {
					this.postData = '';
					this.getPosts();
				});
			},
			likePost(postId){
				axios.put('/api/v1/post/like/'+postId).then(response => {
					this.getPosts();
				});
			},
			sharePost(postId){
				axios.post('/api/v1/post/share', { 'parent_id': postId}).then(response => {
					this.getPosts();
				});
			},
			showComments(comments, postId){
				this.commentsData.comments = comments;
				this.commentsData.postId = postId;
				this.commentDialog = true;
			}
		},
		mounted(){
			this.getPosts();
		}
	}
</script>