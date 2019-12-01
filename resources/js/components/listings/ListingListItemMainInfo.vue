<template>

    <div class="listing__main-info"
         :class="{ 'has-image': listing.image.length}"
         @click="view_listing( listing)"
    >
        <div v-if="listing.image.length"
             class="listing__featured-image"
        >
            <div class="featured-image__blur lazy-background"
                 v-lazy:background-image="listing.image[0].url"
            ></div>
            <div class="featured-image__image lazy-background"
                 v-lazy:background-image="listing.image[0].url"
            ></div>
        </div>
        <div class="listing__content">
            <h1 class="listing__title">{{ listing.title }}</h1>
            <h3 class="listing__address">{{ listing.address }}</h3>
        </div>
    </div>

</template>

<script>
    import {listing_mixin} from './shared';

    export default {
        name: 'ListingListItemMainInfo',
        mixins: [listing_mixin],
        props: {
            listing: {
                type: Object,
                required: true,
            }
        }
    };
</script>

<style lang="scss">
    @import './../../../sass/colors';

    .listing__main-info {
        position: relative;
        padding: 30px 0;
        max-height: 180px;
        cursor: pointer;

        &.has-image {
            padding: 60px 0 0;
        }

        .listing__featured-image {

            .featured-image__blur,
            .featured-image__image {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-size: contain;
                background-position: center;
            }

            .featured-image__blur {
                filter: blur(10px);
                background-size: cover;
            }

            .featured-image__image {
                background-repeat: no-repeat;
            }

            &::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: transparentize(#000, .6);
            }
        }

        .listing__title {
            font-size: 24px;
            font-weight: bold;
            color: $secondary;
        }

        .listing__address {
            font-size: 14px;
        }

        &.has-image {
            overflow: hidden;

            .listing__address {
                color: white;
            }
        }
    }

    .listing__content {
        position: relative;
        padding: 10px 20px;
    }
</style>
