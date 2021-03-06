<template>
	<v-container fluid grid-list-md>
		<div class="text-center">
			<v-snackbar :color="snackbarColor" v-model="snackbar" :timeout="timeout" top>{{ snackbarMessage }}
				<template v-slot:action="{ attrs }">
					<v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
				</template>
			</v-snackbar>
		</div>
		<v-row v-if="showPostForm">
			<v-col>
				<v-layout row wrap align-center>
					<v-flex md12>
						<v-card>
							<v-card-title>What's on your mind?</v-card-title>
							<v-card-text>
					        	<v-form>
					        		<v-text-field v-model="postData" @input="btnPostDisabled = (postData == '')" placeholder="Type your post here" type="text"></v-text-field>
					        	</v-form>
					        </v-card-text>
							<v-card-actions>
								<v-btn color="primary" @click="post()" :disabled="btnPostDisabled">Post</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-layout>
			</v-col>
		</v-row>
		<v-row>
			<v-col>
				<v-layout row wrap align-center>
					<template v-if="postList.length != 0">
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
									<v-btn text @click="likePost(post.post_id)">
										<template v-if="post.is_liker">Unlike</template>
										<template v-else>Like</template>
									</v-btn>
									<v-btn text @click="sharePost(post.post_id)" v-if="post.sharedFrom == null">Share</v-btn>
									<v-btn text @click="sharePost(post.sharedFrom.post_id)" v-else>Share</v-btn>
									<v-btn text @click="showComments(post.comments, post.post_id)">Comments</v-btn>
								</v-card-actions>
							</v-card>
						</v-flex>
					</template>
					<template v-else>
						<v-flex>
							<div class="text-center">No Posts Yet</div>
						</v-flex>
					</template>
					<v-pagination :length="paginationLength" v-model="page" @input="getPosts" v-show="!(postTotal <= 10)"></v-pagination>
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
		props: ['isNewsfeed', 'showPostForm'],
		data: () => ({
			postList: [],
			paginationLength: 1,
			postTotal: 0,
			page: 1,
			postData: '',
			commentsData: {
				comments: [],
				postId: ''
			},
			commentDialog: false,
			snackbarMessage: '',
			snackbar: false,
			snackbarColor: 'success',
			timeout: 2000,
			btnPostDisabled: true
		}),
		methods: {
			getPosts(page){
				let url = this.isNewsfeed ? '/api/v1/newsfeed' : '/api/v1/post/'+this.$route.params.id;
				axios.get(url+'?page='+page).then(response => {
					this.postTotal = response.data.meta.total;
					this.postList = response.data.data;
					this.paginationLength = response.data.meta.last_page >= 10 ? 10 : response.data.meta.last_page;
					this.page = response.data.meta.current_page;
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			},
			post(){
				axios.post('/api/v1/post/create', {'post' : this.postData }).then(response => {
					this.postData = '';
					this.page = 1;
					this.getPosts(this.page);
					this.snackbarMessage = response.data.message;
					this.snackbar = true;
					this.snackbarColor = 'success';
					this.btnPostDisabled = true;
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			},
			likePost(postId){
				axios.put('/api/v1/post/like/'+postId).then(response => {
					this.getPosts(this.page);
					this.snackbarMessage = response.data.message;
					this.snackbar = true;
					this.snackbarColor = 'success';
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			},
			sharePost(postId){
				axios.post('/api/v1/post/share', { 'parent_id': postId}).then(response => {
					this.page = 1;
					this.getPosts(this.page);
					this.snackbarMessage = response.data.message;
					this.snackbar = true;
					this.snackbarColor = 'success';
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			},
			showComments(comments, postId){
				this.commentsData.comments = comments;
				this.commentsData.postId = postId;
				this.commentDialog = true;
			},
		},
		mounted(){
			this.getPosts(this.page);
		}
	}
</script>