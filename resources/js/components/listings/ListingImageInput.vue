<template>
    <section class="listing__uploaded-images">

        <div class="listing__add-image">

            <div class="image-upload__dropzone">
                <div class="image-upload__helper">
                    <div class="dz-dropzone-helper">
                        <div class="media-hints-wrap">
                            <p><strong>Accepted File Types:</strong>
                                <br>Image (PNG, JPG, JPEG, GIF) - {{ max_file_size }} MB
                                <br>Video (MP4) - MB
                            </p>
                        </div>
                    </div>
                    <input id="old-school-upload" class="d-none" type="file" name="file">

                    <div class="mediaBox">
                        <i class="fa fa-cloud-download mediaBox__icon" aria-hidden="true"></i>
                        <p class="mediaBox__text">
                            <span id="old-school-upload-trigger" type="button">Choose a file</span> or drag it here.</p>
                    </div>
                </div>

            </div>
        </div>

        <div id="dropzone-previews"></div>

        <div class="listing__images">
            <div v-for="(image, index) in listing_images" class="image__wrap"
                 :data-remove="is_removing(image) ? 'yes' : 'no'">
				<span class="remove-item circle-icon circle-icon__hover" @click="remove(image)">
					<i class="fas fa-times fa-circle fa-action"></i>
				</span>
                <span class="remove-item__undo circle-icon circle-icon__hover" @click="remove_undo(image)">
					<i class="fas fa-trash fa-circle fa-action"></i>
				</span>
                <img :src="image.url"/>
            </div>
        </div>

        <input type="hidden" class="d-none" :value="removing" name="remove_images"/>
    </section>

</template>

<script>
    const Dropzone = require('dropzone');

    export default {
        data() {
            return {
                listing_images: [],
                remove_images: []
            };
        },
        props: {
            images: Array,
            remove_route: String,
            max_file_size: {
                type: Number,
                default: 10
            }
        },
        computed: {
            removing() {
                return JSON.stringify(this.remove_images);
            }
        },
        mounted() {
            if (this.images) {
                this.listing_images = this.images;
            }

            Dropzone.options.listingEditDropzone = { // The camelized version of the ID of the form element
                autoProcessQueue: false,
                uploadMultiple: true,
                createImageThumbnails: true,
                addRemoveLinks: true,
                parallelUploads: 100,
                maxFiles: 100,
                previewsContainer: '#dropzone-previews',
                clickable: '#old-school-upload-trigger',

                // The setting up of the dropzone
                init: function () {
                    const myDropzone = this;

                    $('#submit-listing').click(function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        myDropzone.processQueue();

                        $('#listing-edit-dropzone').submit();
                    });

                    // First change the button to actually tell Dropzone to process the queue.
                    // this.element.querySelector('button[type=submit]').addEventListener('click', function (e) {
                    // 	// Make sure that the form isn't actually being sent.
                    // 	e.preventDefault();
                    // 	e.stopPropagation();
                    // 	myDropzone.processQueue();
                    // });

                    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                    // of the sending event because uploadMultiple is set to true.
                    this.on('sendingmultiple', function () {
                        // Gets triggered when the form is actually being sent.
                        // Hide the success button or the complete form.
                    });
                    this.on('successmultiple', function (files, response) {
                        // Gets triggered when the files have successfully been sent.
                        // Redirect user or notify of success.

                    });
                    this.on('errormultiple', function (files, response) {
                        // Gets triggered when there was an error sending the files.
                        // Maybe show form again, and notify user of error
                    });
                }
            };
        },
        methods: {
            remove(image) {
                console.log({image}, this.remove_images[image.id]);
                if (!this.remove_images.includes(image.id)) {
                    this.remove_images.push(image.id);

                }
            },
            remove_undo(image) {
                this.remove_images = _.without(this.remove_images, image.id);
            },
            is_removing(image) {
                return this.remove_images.includes(image.id);
            }
        }
    };
</script>

<style lang="scss">
    @import "../../../sass/colors";

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

    .dz-default {
        display: none;
    }

</style>
