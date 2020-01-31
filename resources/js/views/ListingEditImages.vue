<template>

    <div class="container mt-5 mb-5">

        <div class="row">
            <div class="col-12">
                <router-link :to="{ name: 'listing.edit', params: $route.params.id }">&larr; Back to details
                </router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- we prevent the submit on the form because of DropzoneJS -->
                <form @submit.prevent
                      id="listing-edit"
                      class="form"
                      method="post"
                      :action="(isNew && listing ? '/api/listings' : '/api/listings/' + listing.id)"
                >
                    <input type="hidden" name="_method" :value="(isNew ? 'post' : 'patch')"/>


                    <template v-if="loaded">

                        <ListingImageInput :images="images"
                                           :isNew="isNew"
                                           :max_file_size="10"
                                           @update="updateImages"
                        />
                    </template>
                </form>


                <div class="listing__images">
                    <div v-for="(image, index) in images" class="image__wrap"
                         :data-remove="isRemoving(image) ? 'yes' : 'no'">
                            <span class="remove-item circle-icon circle-icon__hover" @click="remove(image)">
                                <i class="fas fa-times fa-circle fa-action"></i>
                            </span>
                        <span class="remove-item__undo circle-icon circle-icon__hover" @click="removeUndo(image)">
                                <i class="fas fa-trash fa-circle fa-action"></i>
                            </span>
                        <img :src="image.url"/>
                    </div>
                </div>

                <div class="text-right">
                    <button v-if="removeImages.length"
                            @click="doRemoveImages"
                            id="submit-listing"
                            type="button"
                            class="btn btn-primary">
                        Remove images
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button @click="finishListing"
                        id="listing-finish"
                        type="button">
                    Finish updates
                </button>
            </div>
        </div>
    </div>

</template>

<script>
    import {axiosOne, serializeArray} from '../helpers';

    const ListingImageInput = () => import('../components/listings/ListingImageInput.vue'/* webpackChunkName: "js/chunks/listing-images-input" */);

    export default {
        name: 'ListingEdit',
        components: {
            ListingImageInput
        },
        data() {
            return {
                loaded: false,
                listing: {},
                title: '',
                images: [],
                removeImages: []
            };
        },
        props: {
            isNew: false,
            userListing: {
                type: Object,
                required: false
            }
        },
        created() {
            console.log('this.$route.params.id', this.$route.params.id);

            if ((this.$route.params.id)) {
                this.loadListing(this.$route.params.id).then(res => {

                    if (typeof res.data.errors !== 'undefined') {
                        return;
                    }

                    this.listing = res.data.data.userListings;

                    console.log(this.listing);

                    this.title = this.listing.title;
                    this.images = this.listing.image;

                    this.loaded = true;
                });
            } else {
                this.listing = this.userListing;

                if (this.listing) {
                    this.images = this.listing.image;
                    this.loaded = true;
                }
            }
        },
        methods: {
            loadListing(id) {
                return axiosOne({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
							query FetchListing {
							  userListings(id: "` + id + `") {
							      id
							      title
							      image {
							        id
							        name
							        url
							      }
							    }
							  }
					`
                    }
                });
            },
            updateDates(dates) {
                this.dates = dates;
            },
            updateImages(images) {
                this.images = images;
            },
            doRemoveImages() {

                return axios({
                    method: 'post',
                    url: '/api/listing-images/remove',
                    params: {
                        'method': 'delete',
                        '_token': window.csrf_token,
                        'images': this.removeImages,
                    }
                }).then(res => {
                    if (res.data.status) {
                        this.$notification.success('Images removed.', {timer: 5});
                    } else {
                        this.$notification.error('Error saving images');
                    }
                    this.removeImages = [];
                });
            },
            finishListing() {
                if (this.removeImages.length) {
                    this.doRemoveImages().then(_ => {
                        this.$notification.success('Listing saved');
                        this.$router.push({name: 'dashboard'});
                    });
                } else {
                    this.$notification.success('Listing saved');
                    this.$router.push({name: 'dashboard'});
                }
            },
            geocode_result(address) {
                // this.address = address.address;
                // this.house_number = address.house_number;
                // this.street_name = address.street_name;
                // this.city = address.city;
                // this.postcode = address.postcode;
                // this.country = address.country;
                // this.latitude = address.latitude;
                // this.longitude = address.longitude;
            },
            remove(image) {
                console.log({image}, this.removeImages[image.id]);
                if (!this.removeImages.includes(image.id)) {
                    this.removeImages.push(image.id);

                }
            },
            removeUndo(image) {
                this.removeImages = _.without(this.removeImages, image.id);
            },
            isRemoving(image) {
                return this.removeImages.includes(image.id);
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import "../../sass/colors";

    .dropzone {
        border: none;
    }


    .listing__images {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 30px;

        > * {
            flex: 0 0 calc(16.66666667% - 16px);
        }
    }

    .image__wrap {
        position: relative;
        margin: 8px;
        border-radius: 20px;
        height: 150px;
        overflow: hidden;
        transition: 300ms ease-in-out background;

        img {
            object-position: center;
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        &::before {
            display: none;
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba($gray, .9);
            transition: 300ms ease-in-out all;
        }

        &:hover {
            img {
                filter: blur(8px);
                transform: scale(1.05, 1.05);
            }

            &::before {
                display: block;
            }

            .remove-item {
                display: block;
            }
        }

        .remove-item,
        .remove-item__undo {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
            z-index: 9;
        }

        &[data-remove=yes] {
            background: $red;

            &::before {
                display: none;
            }

            img {
                opacity: .3;
                filter: blur(2px);
                transform: scale(1.05, 1.05);
            }

            &:hover {
                img {
                    opacity: .2;
                }
            }

            .remove-item__undo {
                display: block;
            }
        }

        .remove-item__undo {
            background-color: $red;

            &:hover {
                i {
                    &::before {
                        content: "\f895";
                        cursor: pointer;
                    }
                }
            }
        }
    }

</style>
