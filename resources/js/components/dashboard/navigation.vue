<template>
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
                        <router-link :to="{ name: 'myListings' }">
                            <i class="far fa-list"></i> My Listings
                        </router-link>
                    </li>
                    <li class="list-group-item">
                        <router-link :to="{ name: 'savedListings' }">
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
                        <router-link :to="{ name: 'settings' }">
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
</template>

<script>
    import {mapActions, mapState} from 'vuex';

    export default {
        name: 'DashboardNavigation',
        computed: {
            ...mapState([
                'user',
            ])
        },
        methods: {
            ...mapActions(['logout']),
        }
    };
</script>

<style scoped lang="scss">
    @import '../../../sass/colors';

    .list-group-item {
        padding: 0;

        a {
            display: block;
            padding: 0.75rem 1.25rem;

            &:hover {
                background: transparentize($brand-primary, 0.8);
                text-decoration: none;
                cursor: pointer;
                color: $brand-primary;
            }
        }
    }

    .router-link-exact-active {
        background: $brand-primary;
        color: white;
        pointer-events: none;
    }
</style>
