<template>
    <section id="main-navigation">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <router-link :to="{ name: 'home' }" class="navbar-brand">Rummage City</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">

                            <router-link :to="{ name: 'listings' }"
                                         class="nav-link"
                                         role="button"
                                         aria-expanded="false">
                                Browse Sales
                            </router-link>
                        </li>

                    </ul>

                    <ul class="navbar-nav flex-row mr-lg-0">
                        <template v-if="!accessToken">
                            <li class="nav-item">
                                <router-link :to="{ name: 'login' }"
                                             class="nav-link">
                                    Login
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link :to="{ name: 'register' }"
                                             class="nav-link">
                                    Register
                                </router-link>
                            </li>
                        </template>

                        <template v-else>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle mr-3 mr-lg-0" id="navbarDropdownMenuLink"
                                   data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <img class="rounded-circle"
                                         :src="'https://www.gravatar.com/avatar/' + user.gravatar + '?size=40'"
                                         :alt="user.email">
                                    <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <router-link :to="{ name: 'dashboard' }"
                                                 class="dropdown-item">
                                        <i class="far fa-tachometer"></i>
                                        Dashboard
                                    </router-link>

                                    <router-link :to="{ name: 'listing.new' } "
                                                 class="dropdown-item">
                                        <i class="far fa-plus-circle"></i> Add Listing
                                    </router-link>


                                    <router-link :to="{ name: 'account' }"
                                                 class="dropdown-item">
                                        <i class="far fa-tachometer"></i>
                                        Account
                                    </router-link>

                                    <div class="dropdown-divider"></div>

                                    <a @click=logout
                                       class="dropdown-item">Logout</a>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </nav>
        </div>
    </section>


</template>

<script>
    import {mapState, mapActions} from 'vuex';

    export default {
        name: 'HeaderNavigation',
        computed: {
            ...mapState(['accessToken', 'user'])
        },
        methods: {
            ...mapActions(['logout'])
        }
    };
</script>

<style scoped>

</style>
