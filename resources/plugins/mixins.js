import Vue from 'vue'
import router from '../js/router'

Vue.mixin({
	data: () => ({
		
	}),
	methods: {
		redirectToLogin(){
			localStorage.removeItem('bearerToken');
			localStorage.removeItem('userId');
			router.push('/login');
		}
	}
});

export default {

}