import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Login from '../components/views/Login'
import Register from '../components/views/Register'
import Newsfeed from '../components/views/Newsfeed'
import Profile from '../components/views/Profile'

const routes = [
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
		path: '/newsfeed'
	},
	{
		component: Profile,
		name: 'profile',
		path: '/profile/:id'
	},
];

export default new VueRouter({
	mode: 'history',
	routes
});