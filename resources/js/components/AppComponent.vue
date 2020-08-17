<template>
    <v-app>
        <header-component :key="$route.path"></header-component>
        <v-main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
    export default {
        data(){
            return {
                
            }
        },
        created(){
            axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('bearerToken')}`;
        }
    }
</script>
