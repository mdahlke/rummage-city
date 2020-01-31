<template>
    <section id="listing-edit-dropzone" class="listing__uploaded-images dropzone">

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
    </section>

</template>

<script>
    import {serializeArray} from '../../helpers';
    import store from '../../config/store/store';

    const Dropzone = require('dropzone');

    Dropzone.autoDiscover = false;

    export default {
        data() {
            return {
                pendingUpload: [],
            };
        },
        props: {
            isNew: {
                type: Boolean,
                default: false,
            },
            images: Array,
            remove_route: String,
            max_file_size: {
                type: Number,
                default: 10
            }
        },
        computed: {
            removing() {
                return JSON.stringify(this.removeImages);
            }
        },
        mounted() {
            const _component = this;

            console.log({_component});

            const listingDropzone = new Dropzone('#listing-edit', {
                method: 'post',
                autoProcessQueue: true,
                uploadMultiple: true,
                createImageThumbnails: true,
                addRemoveLinks: true,
                parallelUploads: 100,
                maxFiles: 100,
                previewsContainer: '#dropzone-previews',
                clickable: '#old-school-upload-trigger',
                headers: {
                    'X-CSRF-TOKEN': window.csrf_token,
                    'Authorization': 'Bearer ' + store.state.accessToken
                },

                // The setting up of the dropzone
                init: function () {
                    const myDropzone = this;

                    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                    // of the sending event because uploadMultiple is set to true.
                    this.on('sendingmultiple', function (data, xhr, formData) {
                        // Gets triggered when the form is actually being sent.
                        const form = document.getElementById('listing-edit');
                        const extraFormData = serializeArray(form);

                        console.log({data, formData, extraFormData});

                        extraFormData.forEach((item) => {
                            console.log({item}, item.name, item.value);
                            formData.append(item.name, item.value);
                        });

                        formData.append('_token', window.csrf_token);
                        formData.append('_method', 'patch');

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
            });

            listingDropzone.on('addedfile', (file) => {
                this.pendingUpload.push(file);
            });
            listingDropzone.on('removedfile', (file) => {
                _.remove(this.pendingUpload, img => img.upload.uuid === file.upload.uuid);
            });

        }
    };
</script>

<style lang="scss">

    .dropzone {
        border: none;
    }


    .dz-default {
        display: none;
    }

</style>
