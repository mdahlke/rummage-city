<template>
    <LayoutDashboard>
        <template v-slot:main>
            <h1>Settings</h1>
        </template>
    </LayoutDashboard>
</template>

<script>
    const LayoutDashboard = () => import('./layouts/Dashboard' /* webpackChunkName: 'js/chunks/layout-dashboard' */);

    export default {
        name: 'Settings',
        components: {
            LayoutDashboard
        }
    };
</script>

<style scoped>

</style>
