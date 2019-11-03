<template>
    <section id="listing-popup-view">
        <div class="listing-wrap">
            <section class="listing">
                <a @click="$router.go(-1)"><i class="far fa-map"></i> Back to map</a>
                <h4>{{ l.title }}</h4>
                <!--        <p>Hosted by: {{ listing.user }}</p>-->
                <p>{{ l.address }}</p>

                <h4>Dates:</h4>
                <ul>
                    <!--            @foreach($listing->date as $date)-->
                    <!--            <li>{{ $date->start }} - {{ $date->end }}</li>-->
                    <!--            @endforeach-->
                </ul>
                <div class="listing__actions">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="ajax-link" :href="l.saveUrl"
                               data-method="post"><i class="far fa-heart"></i> Save</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </section>

</template>

<script>
    import '../../sass/component/listings-popup-view.scss';

    export default {
        name: 'ListingView',
        components: {},
        data() {
            return {
                l: {}
            };
        },
        props: {
            listing: Object,
        },
        created() {
            console.log('hhere');
            this.fetchData();
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchData'
        },
        mounted() {
            console.log(this.$store);
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
                    this.l = results.data.data.listings.data[0]
                    this.$store.commit('listing', this.l);
                });
            },
        }
    };

</script>
