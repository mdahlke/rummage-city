<template>
    <section id="listing-popup-view" :key="'listing-view-' + sale.id ">
        <article class="listing-wrap">

            <router-link :to="{ name: 'listings'}" class="close-listing"><i class="fas fa-times"></i></router-link>
            <!--            <a @click="backToMap" class="close-listing"><i class="fas fa-times"></i></a>-->

            <div class="single-listing__content">
                <section v-if="loading" class="listing__loading">
                    Loading...
                </section>

                <div v-if="sale" class="container">
                    <section class="listing">
                        <div class="row">
                            <section class="col-12 col-md-7 col-lg-8">
                                <div class="listing__main-content">
                                    <listing-images :images="sale.image"></listing-images>

                                    <header>
                                        <h4>{{ sale.title }}</h4>
                                    </header>

                                    <section class="listing__actions">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <a class="cursor-pointer" v-if="isSaved(sale.isSaved)"
                                                   @click="remove_saved_listing(sale)">
                                                    <i class="fas fa-star"></i> Saved
                                                </a>
                                                <a class="cursor-pointer" v-else @click="save_listing(sale)">
                                                    <i class="far fa-star"> Save</i>
                                                </a>
                                            </li>
                                        </ul>
                                    </section>

                                    <section class="listing__description">
                                        {{ sale.description }}
                                    </section>
                                </div>
                            </section>

                            <aside class="col-12 col-md-5 col-lg-4">
                                <p>{{ sale.address }}</p>
                                <listing-dates :dates="sale.active_date"></listing-dates>
                            </aside>
                        </div>
                    </section>
                </div>

                <RelatedListings :listing="sale"/>

            </div>
        </article>
    </section>
</template>

<script>
    import axios from 'axios';
    import ListingImages from '../components/listings/ListingImages';
    import ListingDates from '../components/listings/ListingDates';
    import RelatedListings from '../components/RelatedListings';
    import {listing_mixin} from '../components/listings/shared';
    import {isTrue} from '../helpers';
    import {SET_LISTING} from '../config/store/mutations';

    export default {
        name: 'ListingView',
        components: {
            ListingImages,
            ListingDates,
            RelatedListings
        },
        mixins: [listing_mixin],
        data() {
            return {
                loading: true,
                sale: false,
                transitionName: 'slide-left',
            };
        },
        props: {
            listing: Object,
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchData'
        },
        created() {
            const preloaded = this.$store.getters.get_listing_by_id(this.$route.params.id);

            if (this.listing) {
                this.sale = this.listing;
            } else if (preloaded) {
                this.loading = false;
                this.sale = preloaded;
                this.fetchData();
            } else {
                this.fetchData();
            }

            this.$store.commit(SET_LISTING, this.sale);
        },
        methods: {
            backToMap() {
                $router.go(-1);
            },
            isSaved(val) {
                return isTrue(val);
            },
            fetchData() {
                const id = this.$route.params.id;

                axios({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
                            query FetchListings {
                              listings(id: "` + id + `") {
                                data {
                                  id
                                  title
                                  description
                                  address
                                  latitude
                                  longitude
                                  saveUrl
                                  removeSavedUrl
                                  isSaved
                                  image {
                                      path
                                      url
                                  }
                                  active_date {
                                    start
                                    end
                                  }
                                }
                              }
                            }
                        `
                    },
                }).then((results) => {
                    this.sale = results.data.data.listings.data[0];
                    this.$store.commit(SET_LISTING, this.sale);
                    this.loading = false;
                });
            },
        }
    };

</script>

<style lang="scss">
    @import 'bootstrap/scss/_functions';
    @import 'bootstrap/scss/_variables';
    @import 'bootstrap/scss/mixins/_breakpoints';
    @import '../../sass/colors';

    .close-listing {
        position: absolute;
        left: 0;
        bottom: 0;
        padding: 10px 10px;
        background: $tertiary;
        color: $white;
        font-size: 14px;
        text-align: center;
        width: 100%;
        transform: translateY(100%);

        &::after {
            content: "Close";
            padding-left: 10px;
        }

        @include media-breakpoint-up(xl) {
            top: 0;
            right: 0;
            bottom: unset;
            left: unset;
            padding: 20px 20px;
            background-color: transparentize($tertiary, .2);
            font-size: 28px;
            width: auto;
            transform: translate(100%, 0);

            &:after {
                display: none;
            }
        }
    }

    #listing-popup-view {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        z-index: 9;

        &::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            background-color: #000;
            opacity: 0.5;
            height: 100vh;
            width: 100vw;
        }

        .listing-wrap {
            position: absolute;
            top: 80px;
            left: 50%;
            background-color: white;
            box-shadow: 0 0 13px -6px;
            width: 90vw;
            height: 80vh;
            max-width: 950px;
            transform: translateX(-50%);
        }

        .single-listing__content {
            padding: 30px 0;
            width: 100%;
            height: 100%;
            overflow: auto;

            .related-listings {
                padding: 0 30px;
            }

            @include media-breakpoint-up(md) {
                padding: 30px;

                .related-listings {
                    padding: 0;
                }
            }
        }

        .listing__main-content {
            overflow: auto;
        }

        .listing {
        }

        .listing__dates {
            max-width: 500px;
        }

    }

</style>
