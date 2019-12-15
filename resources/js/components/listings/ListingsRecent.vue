<template>
    <section class="recent-listings">
        <div v-for="listing in listings"
             class="recent-listing"
        >
            <div class="pretty-blur">
                <template v-if="listing.image[0]">
                    <div class="pretty-blur__background"
                         :style="'background-image: url(' + listing.image[0].url + ')'"
                    ></div>
                    <div class="pretty-blur__image"
                         :style="'background-image: url(' + listing.image[0].url + ')'"
                    ></div>
                </template>
                <template v-else>
                    <div class="pretty-blur__placeholder"></div>
                </template>
            </div>
            <h2 class="title">{{ listing.title }}</h2>
        </div>
    </section>
</template>

<script>

    export default {
        name: 'ListingsRecent',
        props: {
            listings: {
                type: Array,
                required: false,
            }
        },
        created() {
            if (!this.listings) {

            }
            console.log(this.listings);
        },
        methods: {
            fetchListings() {
                // fetch most recent listings
            }
        }
    };
</script>

<style lang="scss">
    @import '../../../node_modules/bootstrap/scss/functions';
    @import '../../../node_modules/bootstrap/scss/variables';
    @import '../../../node_modules/bootstrap/scss/mixins/breakpoints';
    @import '../../sass/colors';

    .recent-listings {
        display: flex;
        flex-wrap: nowrap;
        margin: 60px 0;
    }

    .recent-listing {
        flex: 0 0 33.3333336%;
        position: relative;
        margin: 0 5px;
        height: 300px;
        overflow: hidden;

        @include media-breakpoint-down(md) {
            flex: 1 0 auto;
        }

        .title {
            position: absolute;
            bottom: 0;
            left: 0;
            margin: 0;
            padding: 8px 15px;
            background-color: transparentize($black, 0.3);
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
