<template>

    <article class="user-dashboard">
        <div id="account-area" class="container-edge" v-if="user">
            <div class="row no-gutters">
                <div class="col-xs col-md-3 col-lg-2 justify-self-center">
                    <aside id="dashboard-navigation">
                        <section class="profile-info text-center">
                            <img class="rounded-circle"
                                 :src="'https://www.gravatar.com/avatar/' + user.gravatar"
                                 :alt="user.email">
                            <h6>{{ user.name }}</h6>
                            <div class="nav-links">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <router-link :to="{ name: 'dashboard' }">
                                            <i class="far fa-tachometer"></i>
                                            Dashboard
                                        </router-link>
                                    </li>
                                    <li class="list-group-item">
                                        <router-link :to="{ name: 'listings' }">
                                            <i class="far fa-list"></i> My Listings
                                        </router-link>
                                    </li>
                                    <li class="list-group-item">
                                        <router-link :to="{ name: 'listings' }">
                                            <i class="far fa-heart"></i>
                                            Saved Listings
                                        </router-link>
                                    </li>
                                    <li class="list-group-item">
                                        <router-link :to="{ name: 'listings' }">
                                            <i class="far fa-location"></i>
                                            Sales Near Me
                                        </router-link>
                                    </li>
                                    <li class="list-group-item">
                                        <router-link :to="{ name: 'dashboard' }">
                                            <i class="far fa-cogs"></i> Settings
                                        </router-link>
                                    </li>
                                    <li class="list-group-item">
                                        <a @click="logout">
                                            <i class="far fa-power-off"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </aside>
                </div>

                <div class="col-xs col-sm-12 col-md">
                    <main class="main">
                        <InfoCard title="Your Listings">
                            <template v-slot:body>
                                <ul v-if="userListings.length"
                                    class="list-group list-group-flush">
                                    <li v-for="listing in userListings"
                                        :key="'user-listing-' +listing.id">
                                        <router-link :to="{ name: 'listing.edit', params: { id: listing.id }}">
                                            <i class="fad fa-warehouse-alt"></i> {{ listing.title }}
                                        </router-link>
                                    </li>
                                </ul>
                            </template>
                        </InfoCard>

                        <InfoCard title="Saved Listings">
                            <template v-slot:body>
                                <ul v-if="savedListings.length"
                                    class="list-group list-group-flush">
                                    <li v-for="listing in savedListings"
                                        :key="'user-listing-' +listing.id">
                                        <router-link :to="{ name: 'listing.view',
                                                      params: {
                                                        id: listing.id,
                                                        address: address(listing),
                                                        listing: listing
                                                      },
                                        }">
                                            <i class="fad fa-warehouse-alt"></i> {{ listing.title }}
                                        </router-link>
                                    </li>
                                </ul>
                            </template>
                        </InfoCard>
                    </main>
                </div>
            </div>
        </div>
    </article>
</template>

<script>
    import {mapState, mapActions} from 'vuex';
    import slugify from 'slugify';
    import {USER_LISTINGS} from '../config/store/mutations';

    const InfoCard = () => import('../components/InfoCard' /* webpackChunkName: 'js/chunks/info-card' */);

    export default {
        name: 'UserDashboard',
        components: {InfoCard},
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
            address(listing) {
                return slugify(listing.address);
            }
        }
    };
</script>

<style scoped>

</style>
