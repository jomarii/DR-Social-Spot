<template>
	<v-dialog v-model="$parent.commentDialog" scrollable max-width="500px" transition="dialog-transition" class="mx-auto">
		<v-card max-width="500" class="mx-auto">
   			<v-toolbar color="blue" dense>
				<v-app-bar-nav-icon></v-app-bar-nav-icon>
				<v-toolbar-title>Comments</v-toolbar-title>
				<v-spacer></v-spacer>
    		</v-toolbar>

		    <v-list three-line>
		        <template v-for="(comment, index) in commentsData.comments">
		        	<v-list-item>
		          		<v-list-item-content>
			                <v-list-item-title v-text="comment.user"></v-list-item-title>
			                <v-list-item-subtitle style="-webkit-line-clamp: unset !important;">{{ comment.comment }}</v-list-item-subtitle>
		          		</v-list-item-content>
		            	<v-list-item-action>
		                	<v-list-item-action-text v-text="comment.created_at"></v-list-item-action-text>
		            	</v-list-item-action>
		          	</v-list-item>
		          	<template v-for="(reply, idx) in comment.replies">
		          		<v-list-item class="ml-16">
			          		<v-list-item-content>
				                <v-list-item-title v-text="reply.user"></v-list-item-title>
				                <v-list-item-subtitle style="-webkit-line-clamp: unset !important;">{{ reply.comment }}</v-list-item-subtitle>
			          		</v-list-item-content>
			            	<v-list-item-action>
			                	<v-list-item-action-text v-text="reply.created_at"></v-list-item-action-text>
			            	</v-list-item-action>
			          	</v-list-item>
			          	<v-divider v-if="idx + 1 <= comment.replies.length"></v-divider>
		          	</template>
		          	<v-list-item class="ml-16">
			        	<v-list-item-content>
			        		<v-text-field v-model="replyForm[comment.comment_id]" placeholder="Type your reply here">
			        			<template slot="append">
			        				<v-btn color="primary" small @click="reply(comment.post_id, comment.comment_id)">Reply</v-btn>
			        			</template>
			        		</v-text-field>
			        	</v-list-item-content>
		        	</v-list-item>

		          	<v-divider v-if="index + 1 <= commentsData.comments.length" :key="index"></v-divider>
		        </template>
		        <v-list-item>
		        	<v-list-item-content>
		        		<v-text-field v-model="commentForm.comment" placeholder="Type your comment here">
		        			<template slot="append">
		        				<v-btn color="primary" small @click="comment(commentsData.postId)">Comment</v-btn>
		        			</template>
		        		</v-text-field>
		        	</v-list-item-content>
		        </v-list-item>
		    </v-list>
  		</v-card>
	</v-dialog>
</template>
<script>
	export default{
		props: ['commentsData'],
		data: () => ({
			commentForm: {
				comment: ''
			},
			replyForm: []
		}),
		methods: {
			comment(postId){
				axios.post('/api/v1/post/comment/'+postId, this.commentForm).then(response => {
					this.commentForm.comment = '';
					this.$parent.getPosts();
					this.getComments();
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			},
			reply(postId, commentId){
				axios.post('/api/v1/post/comment-reply/'+postId+'/'+commentId, {'comment': this.replyForm[commentId]}).then(response => {
					this.replyForm[commentId] = '';
					this.$parent.getPosts();
					this.getComments();
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			},
			getComments(){
				axios.get('/api/v1/post/comments/'+this.commentsData.postId).then(response => {
					this.commentsData.comments = response.data.data;
				}).catch(error => {
					if(error.response.status == 401){
						this.redirectToLogin();
					}
				});
			}
		}
	}
</script>