<template>
    <section id="listings__list" class="listings-list">
        <template v-if="$parent.visible_listings.length">

            <section v-for="(listing, index) in $parent.visible_listings"
                     :id="'listing-'+ listing.id"
                     class="listing"
                     :class="{ 'active': (listing.id === $parent.active_listing.id) }">
                <!--                     v-on:mouseenter="$emit('set_active_listing', listing)"-->
                <div class="listing-wrap">

                    <div class="listing__main-info"
                         :class="{ 'has-image': listing.image.length}"
                         @click="view(listing.id, slugify(listing.address))">
                        <div class="listing__featured-image" v-if="listing.image.length">
                            <div class="featured-image__blur"
                                 :style="'background-image: url('+listing.image[0].url+')'"></div>
                            <div class="featured-image__image"
                                 :style="'background-image: url('+listing.image[0].url+')'"></div>
                        </div>
                        <div class="listing__content">
                            <h1 class="listing__title">{{ listing.title }}</h1>
                            <h3 class="listing__address">{{ listing.address }}</h3>
                        </div>
                    </div>

                    <div class="listing__content">
                        <router-link
                                :to="{ name: 'listing.view', params: { id: listing.id, address: slugify(listing.address) }}">
                            View
                        </router-link>

                        <listing-dates :dates="listing.active_date"></listing-dates>

                        <div class="listing__actions">
                            <ul class="list-inline">
                                <li class="list-inline-item listing__action">
                                    <span class="cursor-pointer" v-if="listing.isSaved"
                                          @click="remove_saved(listing)">
                                            <i class="fas fa-heart"></i> Saved</span>
                                    <span class="cursor-pointer" v-else @click="save(listing)">
                                            <i class="far fa-heart"> Save</i></span>
                                </li>
                                <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="$parent.highlight_on_map(listing)">
                                            <i class="fas fa-map"></i> Show on Map</span>
                                </li>
                                <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="$parent.zoom_to_on_map(listing)">
                                            <i class="fas fa-crosshairs"></i> Zoom to</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </template>
        <template v-else>
            <section>
                There are no listings in this area.
            </section>
        </template>
    </section>
</template>

<script>
    import '../../../sass/component/listings-list.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
    import slugify from 'slugify';

    export default {
        name: 'ListingsList',
        created() {
            console.log(this.$parent.visible_listings);
        },
        mounted() {
        },
        methods: {
            view(id, address = null) {
                this.$router.push({name: 'listing.view', params: {id, address}})
            },
            save(listing) {
                if (!listing.isSaved) {
                    axios.post(listing.saveUrl).then(function (e) {
                        listing.isSaved = true;
                    });
                }
            },
            remove_saved(listing) {
                if (listing.isSaved) {
                    axios.post(listing.removeSavedUrl).then((e) => {
                        listing.isSaved = false;
                    });
                }
            },
            slugify(text) {
                return slugify(text);
            }
        }
    };

</script>
