<template>
    <section class="recent-listings">
        <ListingsRecentListing v-for="listing in listings"
                               :key="'listings-recent-' + listing.id"
                               :listing="listing"
        />
    </section>
</template>

<script>
    const ListingsRecentListing = () => import('./ListingsRecentListing.vue'/* webpackChunkName: "js/chunks/listings-recent-listing" */);

    export default {
        name: 'ListingsRecent',
        components: {
            ListingsRecentListing
        },
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

<style lang="scss" scoped>

    .recent-listings {
        display: flex;
        flex-wrap: wrap;
        margin: 60px 0;
    }

</style>
