<template>
    <section id="listing-popup-view">
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
                                            <a class="cursor-pointer" v-if="is_saved"
                                                  @click="remove_saved(sale)">
                                                <i class="fas fa-heart"></i> Saved
                                            </a>
                                            <a class="cursor-pointer" v-else @click="save(sale)">
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

    export default {
        name: 'ListingView',
        components: {ListingImages},
        data() {
            return {
                loading: true,
                l: false
            };
        },
        props: {
            listing: Object,
        },
        created() {
            console.log('the listing', this.listing, this.$store);
            const preloaded = this.$store.getters.getListingById(this.$route.params.id);

            if (this.listing) {
                this.l = this.listing;
            } else if (preloaded) {
                this.loading = false;
                this.l = preloaded;
                this.fetch_data();
            } else {
                this.fetch_data();
            }
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchData'
        },
        mounted() {
        },
        computed: {
            sale: function () {
                return this.l;
            },
            is_saved() {
                const saved = this.l.isSaved;
                return saved == "false" ? false : true;
            }
        },
        methods: {
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
                    this.l = results.data.data.listings.data[0];
                    this.$store.commit('listing', this.l);
                    this.loading = false;
                });
            },
            save(listing) {
                if (!this.is_saved) {
                    axios.post(listing.saveUrl).then(function (e) {
                        listing.isSaved = true;
                    });
                }
            },
            remove_saved(listing) {
                if (this.is_saved) {
                    axios.post(listing.removeSavedUrl).then((e) => {
                        listing.isSaved = false;
                    });
                }
            }
        }
    };

</script>
