<template>
    <v-app>
        <header-component></header-component>
        <v-main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
    export default {
        data: () => ({

        }),
        created(){
            axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('bearerToken')}`;
        }
    }
</script>
