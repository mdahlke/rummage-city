<template>

    <LayoutDashboard>
        <template v-slot:main>
            <InfoCard title="Notifications">
                <template v-slot:body>
                    You have no notifications.
                </template>
            </InfoCard>

            <InfoCard title="Saved Listings">
                <template v-slot:body>
                    <p>You have
                        <router-link :to="{name: 'savedListings'}">
                            {{ savedListings.length }} saved listings.
                        </router-link>
                    </p>
                </template>
            </InfoCard>
        </template>
    </LayoutDashboard>
    
</template>

<script>
    import {mapState, mapActions} from 'vuex';

    const LayoutDashboard = () => import('./layouts/Dashboard' /* webpackChunkName: 'js/chunks/layout-dashboard' */);
    const InfoCard = () => import('../components/InfoCard' /* webpackChunkName: 'js/chunks/info-card' */);
    const UserListingsList = () => import('../components/UserListingsList' /* webpackChunkName: 'js/chunks/user-listings-list' */);

    export default {
        name: 'UserDashboard',
        components: {
            LayoutDashboard,
            InfoCard,
            UserListingsList
        },
        computed: {
            ...mapState([
                'user',
                'userListings',
                'savedListings'
            ])
        },
        created() {
            this.loadData();
        },
        methods: {
            ...mapActions(['logout']),
            loadData() {
                this.$store.dispatch('getUserListings');
                this.$store.dispatch('getSavedListings');
            },
        }
    };
</script>

<style scoped>

</style>
