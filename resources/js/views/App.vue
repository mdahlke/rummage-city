<template>
    <div>
        <HeaderNavigation/>

        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>

        <SiteFooter/>
    </div>
</template>
<script>
    import HeaderNavigation from '../components/HeaderNavigation';
    import SiteFooter from '../components/SiteFooter';

    export default {
        components: {
            HeaderNavigation,
            SiteFooter
        }
    };
</script>
