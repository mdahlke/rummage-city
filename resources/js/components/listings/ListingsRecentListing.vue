<template>
    <div class="recent-listing">
        <!--        <router-link :to="{ name: 'listing.view', params: { address: '123', id: listing.id } }">-->
        <a :href="listing.viewUrl">
            <div class="pretty-blur">
                <template v-if="image">
                    <div class="pretty-blur__background"
                         :style="'background-image: url(' + image.url + ')'"
                    ></div>
                    <div class="pretty-blur__image"
                         :style="'background-image: url(' + image.url + ')'"
                    ></div>
                </template>
                <template v-else>
                    <div class="pretty-blur__placeholder"></div>
                </template>
            </div>
            <h2 class="listing__title">{{ listing.title }}</h2>
            <!--        </router-link>-->
        </a>
    </div>

</template>

<script>
    export default {
        name: 'ListingsRecentListing',
        props: {
            listing: {
                type: Object,
                required: true
            }
        },
        mounted() {
            console.log(this.listing);
        },
        computed: {
            image() {
                return this.listing.image[0] || false;
            }
        }
    };
</script>

<style lang="scss">
    @import '../../../../node_modules/bootstrap/scss/functions';
    @import '../../../../node_modules/bootstrap/scss/variables';
    @import '../../../../node_modules/bootstrap/scss/mixins/breakpoints';
    @import '../../../sass/colors';

    .recent-listing {
        flex: 0 0 calc(100% - 10px);
        position: relative;
        margin-bottom: 10px;
        height: 300px;
        overflow: hidden;

        @include media-breakpoint-up(md) {
            flex: 0 0 calc(50% - 10px);
            margin: 0 5px 5px;
        }


        @include media-breakpoint-up(xl) {
            flex: 0 0 calc(25% - 10px);
            margin: 0 5px;
        }

        .listing__title {
            position: absolute;
            bottom: 0;
            left: 0;
            margin: 0;
            padding: 8px 15px;
            background-color: transparentize($black, 0.3);
            color: $white;
            font-size: 20px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            z-index: 1;
        }
    }

    .pretty-blur {
        overflow: hidden;

        &,
        .pretty-blur__image,
        .pretty-blur__background,
        .pretty-blur__placeholder {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .pretty-blur__background {
            background-size: 150%;
            filter: blur(35px);
        }

        .pretty-blur__image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .pretty-blur__placeholder {
            background-color: #666;
        }
    }
</style>
