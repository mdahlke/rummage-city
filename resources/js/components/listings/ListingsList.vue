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
                listings: 'getListings',
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
    @import "../../../sass/fonts";
    @import "../../../sass/colors";

    #listings__list {
        position: relative;
        height: 100%;


        .listings__fetching {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: transparentize($white, .2);
            z-index: 1;
            color: $black;

            i {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        }

        .listing {
            border-bottom: 2px solid $primary;
            box-shadow: 0 0 13px -6px;

            &.active {
                box-shadow: inset 10px 0px 0px -3px $secondary
            }

            .listing-wrap {
                margin-bottom: 10px;
            }
        }


        .listing__actions {
            margin: 20px 0;

            ul {
                margin: 0;
            }
        }

        .listing__content {
            position: relative;
            padding: 10px 20px;
        }


    }

</style>
