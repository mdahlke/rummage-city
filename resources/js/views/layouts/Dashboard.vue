<template>
    <article class="user-dashboard">
        <div id="account-area" class="container-edge" v-if="user">
            <div class="row no-gutters">
                <div class="col-xs col-md-3 col-xl-2 justify-self-center">
                    <DashboardNavigation/>
                </div>

                <div class="col-xs col-sm-12 col-md">
                    <main class="main">
                        <slot name="main"></slot>
                    </main>
                </div>
            </div>
        </div>
    </article>
</template>

<script>
    import {mapState} from 'vuex';

    const DashboardNavigation = () => import('../../components/dashboard/navigation' /* webpackChunkName: 'js/chunks/dashboard-navigation' */);


    export default {
        name: 'Dashboard',
        components: {
            DashboardNavigation
        },
        computed: {
            ...mapState([
                'user',
            ])
        }
    };
</script>

<style scoped>

</style>
