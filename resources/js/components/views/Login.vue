<template>
	<v-row>
		<div class="text-center">
			<v-snackbar :color="snackbarColor" v-model="snackbar" :timeout="timeout" top>{{ snackbarMessage }}
				<template v-slot:action="{ attrs }">
					<v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
				</template>
			</v-snackbar>
		</div>
		<v-col cols="12">
			<v-row justify="center">
				<v-card width="500px">
			        <v-card-title class="headline">Login</v-card-title>

			        <v-card-text>
			        	<v-form ref="loginForm">
			        		<v-text-field v-model="formData.email" placeholder="Email" id="email" type="text" :rules="emailRules"></v-text-field>
			        		<v-text-field v-model="formData.password" placeholder="Password" id="password" type="password" :rules="passwordRules"></v-text-field>
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
			emailRules: [
				v => v.length != 0 || 'This field is required',
				v => /.+@.+\..+/.test(v) || 'E-mail must be valid'
			],
			passwordRules: [
				v => v.length != 0 || 'This field is required'
			],
			snackbarMessage: '',
			snackbar: false,
			snackbarColor: 'success',
			timeout: 2000
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
					}).catch(error => {
						this.snackbarMessage = error.response.data.errors.message[0];
						this.snackbar = true;
						this.snackbarColor = 'error';
					});
				}
				
			}
		}
	}
</script>