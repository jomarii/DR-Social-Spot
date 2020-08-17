<template>
	<v-row>
		<v-col cols="12">
			<v-row justify="center">
				<v-card width="500px">
			        <v-card-title class="headline">Register</v-card-title>

			        <v-card-text>
			        	<v-form>
			        		<v-text-field v-model="formData.first_name" placeholder="First Name" type="text"></v-text-field>
			        		<v-text-field v-model="formData.last_name" placeholder="Last Name" type="text"></v-text-field>
			        		<v-text-field v-model="formData.email" placeholder="Email" id="email" type="text"></v-text-field>
			        		<v-text-field v-model="formData.password" placeholder="Password" type="password"></v-text-field>
			        		<v-text-field v-model="formData.reTypePasword" placeholder="Re-Type Password" type="password"></v-text-field>
			        	</v-form>
			        </v-card-text>

			        <v-card-actions>
						<v-spacer></v-spacer>
						<small>Already have an account?</small>
						<v-btn text to="/login" small>Login</v-btn>
						<v-btn tile color="blue" @click="register()">Register</v-btn>
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
				first_name: '',
				last_name: '',
				email: '',
				password: '',
				reTypePasword: ''
			}
		}),
		methods: {
			register(){
				axios.post('/api/v1/register', this.formData).then(response =>{
					this.$router.push('/login');
				});
			}
		}
	}
</script>