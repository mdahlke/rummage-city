<template>
    <section id="listing-popup-view" :key="'listing-view-' + sale.id ">
        <article class="listing-wrap">

            <a @click="$router.go(-1)" class="cursor-pointer"><i class="far fa-map"></i> Back to map</a>

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
                                            <a class="cursor-pointer" v-if="is_saved(sale.isSaved)"
                                               @click="remove_saved_listing(sale)">
                                                <i class="fas fa-heart"></i> Saved
                                            </a>
                                            <a class="cursor-pointer" v-else @click="save_listing(sale)">
                                                <i class="far fa-heart"> Save</i>
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
        </article>
    </section>

</template>

<script>
    import axios from 'axios';
    import ListingImages from '../components/listings/ListingImages';
    import '../../sass/component/listings-popup-view.scss';
    import {listing_mixin} from "../components/listings/shared";
    import {is_true} from '../helpers';

    export default {
        name: 'ListingView',
        components: {ListingImages},
        mixins: [listing_mixin],
        data() {
            return {
                loading: true,
                sale: false,
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
                this.fetch_data();
            } else {
                this.fetch_data();
            }

            this.$store.commit('set_listing', this.sale);
        },
        methods: {
            is_saved(val) {
                return is_true(val);
            },
            fetch_data() {
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
                    this.$store.commit('set_listing', this.sale);
                    this.loading = false;
                });
            },
        }
    };

</script>
