<template>
    <section class="home">
        <section class="search-banner">

            <div class="background-helper"></div>

            <div class="container h-100">
                <div class="row align-items-center h-100">
                    <div class="col-12">
                        <div class="content text-center">
                            <h1 class="title">
                                Find your next sale!
                            </h1>

                            <SearchBox :show-filters="false"/>

                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div class="container">
            <ListingsRecent/>
        </div>
    </section>
</template>

<script>
    const SearchBox = () => import('../components/SearchBox/SearchBox.vue'/* webpackChunkName: "js/chunks/search-box" */);
    const ListingsRecent = () => import('../components/listings/ListingsRecent.vue'/* webpackChunkName: "js/chunks/listings-map" */);

    export default {
        name: 'Listings',
        components: {
            SearchBox,
            ListingsRecent,
        },
    };

</script>

<style lang="scss">
    @import '../../../node_modules/bootstrap/scss/mixins/breakpoints';
    @import '../../sass/variables';


    .search-banner {
        position: relative;
        height: 50vh;
    }

    .background-helper {
        background-image: url(/images/clothes-in-street-optimized.jpg);
        background-size: cover;
        background-position: center;

        &,
        &::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        &::before {
            background-color: transparentize($black, .4);
        }
    }

    .title {
        margin-bottom: 20px;
        color: $white;
    }

    .search-box {
        [name="q"] {
            padding: 25px 30px;
            font-size: 20px;
            width: 500px;
        }
    }
</style>
