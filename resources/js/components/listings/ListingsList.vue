<template>
    <section id="listings__list"
             class="listings-list"
    >
        <template v-if="listings || $parent.fetching">

            <div v-if="$parent.fetching"
                 class="listings__fetching"
            >
                <i class="fas fa-spinner fa-spin"></i>
            </div>

            <ListingsListItem v-for="(listing, index) in listings"
                              :key="'listing-' + listing.id"
                              v-on="$listeners"
                              :listing="listing"
            />
        </template>
        <template v-else-if="$parent.fetching">
            <div class="fetching">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </template>
        <template v-else>
            <section class="p-2">
                There are no listings in this area.
            </section>
        </template>
    </section>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex';
    import ListingsListItem from './ListingsListItem';
    import {listing_mixin} from "./shared";

    export default {
        name: 'ListingsList',
        mixins: [listing_mixin],
        components: {ListingsListItem},
        computed: {
            ...mapGetters({
                listings: 'get_listings',
            })
        },
        methods: {
            ...mapMutations([
                'save_listing',
                'remove_saved_listing'
            ])
        }
    };

</script>

<style lang="scss">
    @import '../../../sass/component/listings-list.scss';
    @import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
</style>
