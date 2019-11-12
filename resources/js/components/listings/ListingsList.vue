<template>
    <section id="listings__list" class="listings-list">
        <template v-if="listings || $parent.fetching">

            <div class="listings__fetching" v-if="$parent.fetching"><i class="fas fa-spinner fa-spin"></i></div>

            <listings-list-item v-for="(listing, index) in listings"
                                :id="'listing-'+ listing.id"
                                :key="'listing-' + listing.id"
                                :listing="listing"
                                class="listing"
                                :class="{ 'active': (listing.id === $parent.active_listing.id) }">
            </listings-list-item>
        </template>
        <template v-else-if="$parent.fetching">
            <div class="fetching"><i class="fas fa-spinner fa-spin"></i></div>
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
    import '../../../sass/component/listings-list.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';

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
