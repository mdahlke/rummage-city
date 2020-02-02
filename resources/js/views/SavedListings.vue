<template>

    <LayoutDashboard>
        <template v-slot:main>
            <InfoCard title="Saved Listings">
                <template v-slot:body>
                    <UserListingsList v-if="savedListings.length"
                                      :listings="savedListings"/>
                </template>
            </InfoCard>
        </template>
    </LayoutDashboard>
</template>

<script>
    import {mapState, mapActions} from 'vuex';

    const InfoCard = () => import('../components/InfoCard' /* webpackChunkName: 'js/chunks/info-card' */);
    const UserListingsList = () => import('../components/UserListingsList' /* webpackChunkName: 'js/chunks/user-listings-list' */);
    const LayoutDashboard = () => import('./layouts/Dashboard' /* webpackChunkName: 'js/chunks/layout-dashboard' */);

    export default {
        name: 'SavedListings',
        components: {
            LayoutDashboard,
            InfoCard,
            UserListingsList
        },
        computed: {
            ...mapState([
                'user',
                'savedListings',
            ])
        },
        created() {
            this.loadData();
        },
        methods: {
            ...mapActions(['logout']),
            loadData() {
                this.$store.dispatch('getSavedListings');
            }
        }
    };
</script>

<style scoped>

</style>
