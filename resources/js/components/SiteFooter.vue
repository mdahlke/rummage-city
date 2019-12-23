<template>

    <footer id="footer">
        <div class="copyright">
            <span>Copyright  {{ year }} | Rummage City</span>
        </div>
    </footer>

</template>

<script>
    export default {
        name: 'SiteFooter',
        computed: {
            year() {
                return (new Date).getFullYear();
            }
        }
    };
</script>

<style lang="scss">
    @import '../../sass/colors';

    #footer {
        padding: 40px;
        background: $tertiary;
        color: $white;

        .copyright {
            padding: 40px 20px 20px;
            text-align: center;
        }
    }
</style>
