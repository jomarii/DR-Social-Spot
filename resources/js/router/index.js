import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Login from '../components/views/Login'
import Register from '../components/views/Register'
import Newsfeed from '../components/views/Newsfeed'
import Profile from '../components/views/Profile'
import NotFound from '../components/views/NotFound'
import Forbidden from '../components/views/Forbidden'

const routes = [
	{
		path: '/', redirect: '/newsfeed'
	},
	{
		component: Login,
		name: 'login',
		path: '/login'
	},
	{
		component: Register,
		name: 'register',
		path: '/register'
	},
	{
		component: Newsfeed,
		name: 'newsfeed',
		path: '/newsfeed',
		meta: {
			requiresAuth: true
		}
	},
	{
		component: Profile,
		name: 'profile',
		path: '/profile/:id',
		meta: {
			requiresAuth: true
		}
	},
	{
		component: NotFound,
		name: 'notfound',
		path: '/404'
	},
	{
		component: Forbidden,
		name: 'forbidden',
		path: '/403'
	},
	{
		path: '*', redirect: '/404'
	}
];

let router = new VueRouter({
	mode: 'history',
	routes
})

//check if route requires authentication
router.beforeEach((to, from, next) => {
	if(to.matched.some(record => record.meta.requiresAuth)){
		if(localStorage.getItem('bearerToken') == null){
			next({
				path: '/login'
			})
		}
		else {
			next()
		}
	}
	else{
		next()
	}
})


export default router