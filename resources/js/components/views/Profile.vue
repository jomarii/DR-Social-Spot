<template>
	<div>
		<v-container fluid grid-list-md>
			<v-row>
				<v-col>
					<v-layout>
						<v-flex md12>
							<v-card>
								<v-card-title>Profile</v-card-title>
								<v-card-text v-text="user.full_name"></v-card-text>
								<v-card-actions>
									<v-btn color="primary" @click="updateDialog = true">Edit</v-btn>
								</v-card-actions>
							</v-card>
							<v-dialog v-model="updateDialog" max-width="500px" transition="dialog-transition">
								<v-card>
									<v-card-title primary-title>Update Profile</v-card-title>
									<v-card-text>
										<v-text-field v-model="form.first_name" placeholder="First Name"></v-text-field>
										<v-text-field v-model="form.last_name" placeholder="Last Name"></v-text-field>
									</v-card-text>
									<v-card-actions>
										<v-btn color="primary" @click="updateProfile()">Update</v-btn>
										<v-btn @click="updateDialog = false">Cancel</v-btn>
									</v-card-actions>
								</v-card>
							</v-dialog>
						</v-flex>
					</v-layout>
				</v-col>
			</v-row>
		</v-container>
		<post-list :isNewsfeed="false" :key="postListKey"></post-list>
	</div>
</template>
<script>
	export default{
		data: () => ({
			user: [],
			updateDialog: false,
			form: {
				first_name: '',
				last_name: ''
			},
			postListKey: 0
		}),
		methods: {
			getProfile(){
				axios.get('/api/v1/profile/'+this.$route.params.id).then(response => {
					this.user = response.data.data;
					this.form.first_name = this.user.first_name;
					this.form.last_name = this.user.last_name;
				}).catch(error => {
					this.$router.push('/notfound');
				});
			},
			updateProfile(){
				axios.patch('/api/v1/profile/update', this.form).then(response => {
					this.user.full_name = response.data.data.full_name;
					this.postListKey = this.postListKey+1;
					this.updateDialog = false;
				});
			}
		},
		created(){
			this.getProfile();
		}
	}
</script>