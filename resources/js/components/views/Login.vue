<template>
	<v-row>
		<v-col cols="12">
			<v-row justify="center">
				<v-card width="500px">
			        <v-card-title class="headline">Login</v-card-title>

			        <v-card-text>
			        	<v-form ref="loginForm">
			        		<v-text-field v-model="formData.email" placeholder="Email" id="email" type="text" :rules="inputRules"></v-text-field>
			        		<v-text-field v-model="formData.password" placeholder="Password" id="password" type="password" :rules="inputRules"></v-text-field>
			        	</v-form>
			        </v-card-text>

			        <v-card-actions>
						<v-spacer></v-spacer>
						<v-btn tile color="blue" @click="login()">Login</v-btn>
						<v-btn text to="/register">Register</v-btn>
			        </v-card-actions>
			    </v-card>
			</v-row>
		</v-col>
	</v-row>
</template>
<script>
	export default{
		data: () => ({
			formData: {
				email: '',
				password: ''
			},
			inputRules: [
				v => v.length != 0 || 'This field is required'
			]
		}),
		methods: {
			login(){
				if(this.$refs.loginForm.validate()){
					axios.post('/api/v1/login', this.formData).then(response => {
						if(response.data.token){
							localStorage.setItem('bearerToken', response.data.token);
							localStorage.setItem('userId', response.data.user_id);
							this.$router.push('/newsfeed');
						}
					});
				}
				
			}
		}
	}
</script>