<template>
    <section v-if="recentListings.length"
             class="recent-listings">
        <ListingsRecentListing v-for="listing in recentListings"
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
        data() {
            return {
                recentListings: [],
            };
        },
        props: {
            listings: {
                type: Array,
                required: false,
                default: () => []
            }
        },
        created() {
            if (!this.listings.length) {
                this.fetchListings();
            } else {
                this.recentListings = this.listings;
            }
            console.log(this.recentListings);
        },
        methods: {
            fetchListings() {
                // fetch most recent listings
                axios.get('/api/listings/recent')
                    .then(res => {
                        this.recentListings = res.data.listings;
                    });
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
