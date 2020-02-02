<template>

    <LayoutDashboard>
        <template v-slot:main>
            <InfoCard title="Your Listings">
                <template v-slot:body>
                    <UserListingsList v-if="userListings.length"
                                      :listings="userListings"
                                      :editable="true"/>
                </template>
            </InfoCard>
        </template>
    </LayoutDashboard>
</template>

<script>
    import {mapState, mapActions} from 'vuex';
    import DashboardNavigation from '../components/dashboard/navigation';

    const LayoutDashboard = () => import('./layouts/Dashboard' /* webpackChunkName: 'js/chunks/layout-dashboard' */);
    const InfoCard = () => import('../components/InfoCard' /* webpackChunkName: 'js/chunks/info-card' */);
    const UserListingsList = () => import('../components/UserListingsList' /* webpackChunkName: 'js/chunks/user-listings-list' */);

    export default {
        name: 'MyListings',
        components: {
            LayoutDashboard,
            InfoCard,
            UserListingsList
        },
        computed: {
            ...mapState([
                'user',
                'userListings',
            ])
        },
        created() {
            this.loadData();
        },
        methods: {
            ...mapActions(['logout']),
            loadData() {
                this.$store.dispatch('getUserListings');
            }
        }
    };
</script>

<style scoped>

</style>
