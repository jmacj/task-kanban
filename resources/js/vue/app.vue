<template>
    <VApp>
        <VAppBar app>
            <VBtn icon @click="toggleDrawer">
                <VIcon>mdi-menu</VIcon>
            </VBtn>
            <VToolbarTitle>Task Management System</VToolbarTitle>
            <VSpacer/>
            <VBtn icon @click="logout">
                <VIcon>mdi-logout</VIcon>
            </VBtn>
        </VAppBar>

        <VNavigationDrawer app v-model="drawerOpen">
            <VList>
                <VListItem v-for="item in items" :key="item.text" @click="navigateTo(item.route)">
                    <VListItemIcon>
                        <VIcon>{{ item.icon }}</VIcon>
                    </VListItemIcon>
                    <VListItemContent>
                        <VListItemTitle>{{ item.text }}</VListItemTitle>
                    </VListItemContent>
                </VListItem>
            </VList>
        </VNavigationDrawer>

        <VMain>
            <RouterView/>
        </VMain>
    </VApp>
</template>
<script>
export default {
    data() {
        return {
            drawerOpen: false,
            items: [
                { text: 'Dashboard', route: '', icon: 'mdi-view-dashboard' },
            ],
            activeItem: 'dashboard'
        }
    },
    methods: {
        /**
         * toggles drawer
         */
        toggleDrawer() {
            this.drawerOpen = !this.drawerOpen;
        },

        /**
         * navigate to specified route
         * @param route
         */
        navigateTo(route) {
            this.$router.push({ path: `/${route}` });
            this.activeItem = route;
        },

        /**
         * logout
         */
        logout() {
            window.location.href = '/logout';
        }
    }
}
</script>
