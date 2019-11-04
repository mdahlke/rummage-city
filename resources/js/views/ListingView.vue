<template>
    <section id="listing-popup-view">
        <div class="listing-wrap">
            <div class="container">

                <section class="listing">
                    <div class="row">
                        <div class="col-12 col-md-7 col-lg-8">

                            <div class="listing__main-content">
                                <listing-images :images="sale.image"></listing-images>

                                <a @click="$router.go(-1)" class="cursor-pointer"><i class="far fa-map"></i> Back to map</a>

                                <h4>{{ sale.title }}</h4>

                                <div class="listing__actions">
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <span class="cursor-pointer" v-if="sale.isSaved"
                                                  @click="remove_saved(sale)">
                                                <i class="fas fa-heart"></i> Saved
                                            </span>
                                            <span class="cursor-pointer" v-else @click="save(sale)">
                                                <i class="far fa-heart"> Save</i>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-5 col-lg-4">
                            <p>{{ sale.address }}</p>
                            <listing-dates :dates="sale.active_date"></listing-dates>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

</template>

<script>
    import ListingImages from '../components/listings/ListingImages';
    import '../../sass/component/listings-popup-view.scss';

    export default {
        name: 'ListingView',
        components: {ListingImages},
        data() {
            return {
                l: {}
            };
        },
        props: {
            listing: Object,
        },
        created() {
            this.fetchData();
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchData'
        },
        mounted() {
            console.log(this.$store);
        },
        computed: {
            sale: function () {
                console.log(this.$store);
                return this.$store.state.listing;
            }
        },
        methods: {
            fetchData() {
                const id = this.$route.params.id;
                console.log({id});
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
                          user {
                            name
                            savedListing{
                              listing {
                                title
                              }
                            }
                          }
                          active_date {
                            start
                            end
                          }
                          date {
                            start
                            end
                          }
                        }
                        total
                        per_page
                        current_page
                      }
                    }
                `
                    }
                }).then((results) => {
                    console.log(results.data.data.listings);
                    this.$store.commit('listing', results.data.data.listings.data[0]);
                });
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
            }
        }
    };

</script>
