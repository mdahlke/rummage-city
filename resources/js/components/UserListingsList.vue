<template>
    <ul class="list-group list-group-flush">
        <li v-for="listing in listings"
            :key="'user-listing-' +listing.id"
            class="list-group-item"
        >
            <router-link :to="{ name: route(listing),
                                                      params: {
                                                        id: listing.id,
                                                        address: address(listing),
                                                        listing: listing
                                                      },
                                        }">
                <i class="fad fa-warehouse-alt"></i> {{ listing.title }}
            </router-link>
        </li>
    </ul>
</template>

<script>
    import slugify from 'slugify';

    export default {
        name: 'UserListingsList',
        props: {
            listings: {
                type: Array,
                required: true,
            },
            editable: {
                type: Boolean,
                default: false,
            }
        },
        methods: {
            address(listing) {
                return slugify(listing.address);
            },
            route() {
                return this.editable ? 'listing.edit' : 'listing.view';
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../sass/colors';

    .list-group-item {

        a {
            text-decoration: none;

            &:hover {
                color: $brand-primary;
            }

            i {
                padding-right: 10px;
            }
        }
    }
</style>
