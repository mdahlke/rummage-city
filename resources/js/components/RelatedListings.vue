<template>

    <section class="related-listings">
        <h2>More Listings for you</h2>
        <section
                v-if="related"
                class="listings"
        >
            <div class="row">
                <div class="col-xs-12 col-sm-6"
                     v-for="(listing) in related"
                >
                    <RelatedListingsItem
                            :key="listing.id"
                            :listing="listing"
                    />
                </div>
            </div>
        </section>
    </section>

</template>

<script>
    import RelatedListingsItem from './RelatedListingsItem';
    import axios from 'axios';

    export default {
        name: 'RelatedListings',
        components: {RelatedListingsItem},
        data() {
            return {
                related: false
            };
        },
        props: {
            listing: {
                type: Object,
                required: true
            }
        },
        created() {
            this.loadData().then(results => {
                console.log({results});
                this.related = results.data.data.relatedListings.data || false;
                console.log(this.related);
            });
        },
        methods: {
            loadData() {
                return axios({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
							query RelatedListings {
							  relatedListings(limit: 4) {
							    data {
							      id
							      title
							      description
							      address
							      latitude
							      longitude
							      isSaved
							      saveUrl
							      removeSavedUrl
							      active_date {
							        start
							        end
							      }
							      image {
							        name
							        url
							      }
							    }
							  }
							}
					`
                    },
                }, 'listings').catch(function (thrown) {
                    if (axios.isCancel(thrown)) {
                        console.log('Request canceled', thrown.message);
                    } else {
                        // handle error
                    }
                });
            }
        }
    };
</script>

<style lang="scss">
    .related-listings {
        margin-top: 60px;
    }

</style>
