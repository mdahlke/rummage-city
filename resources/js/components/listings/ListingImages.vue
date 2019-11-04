<template>
    <section id="listing__images">
        <slick ref="slick"
                :options="slickOptions"
                @afterChange="handleAfterChange"
                @beforeChange="handleBeforeChange"
                @breakpoint="handleBreakpoint"
                @destroy="handleDestroy"
                @edge="handleEdge"
                @init="handleInit"
                @reInit="handleReInit"
                @setPosition="handleSetPosition"
                @swipe="handleSwipe"
                @lazyLoaded="handleLazyLoaded"
                @lazyLoadError="handleLazeLoadError">
            <div class="listing__image-wrap" v-for="(image) in images">
                <picture class="listing__image-blur" :style="'background-image: url('+ image.url +');'"></picture>
                <picture class="listing__image" :style="'background-image: url('+ image.url +');'"></picture>
            </div>
        </slick>
    </section>
</template>

<script>
    import Slick from 'vue-slick';
    import '../../../../node_modules/slick-carousel/slick/slick.scss';
    import '../../../../node_modules/slick-carousel/slick/slick-theme.scss';

    import '../../../sass/component/listing-images.scss';

    export default {
        name: 'ListingImages',
        components: {Slick},
        props: {
            images: Array,
        },
        data() {
            return {
                slickOptions: {
                    slidesToShow: 1,
                    dots: true,
                    // Any other options that can be got from plugin documentation
                },
            };
        },
        created() {
        },
        mounted() {
            console.log(this.images);
        },
        computed: {},
        methods: {
            next() {
                this.$refs.slick.next();
            },

            prev() {
                this.$refs.slick.prev();
            },

            reInit() {
                // Helpful if you have to deal with v-for to update dynamic lists
                this.$nextTick(() => {
                    this.$refs.slick.reSlick();
                });
            },

            // Events listeners
            handleAfterChange(event, slick, currentSlide) {
                console.log('handleAfterChange', event, slick, currentSlide);
            },
            handleBeforeChange(event, slick, currentSlide, nextSlide) {
                console.log('handleBeforeChange', event, slick, currentSlide, nextSlide);
            },
            handleBreakpoint(event, slick, breakpoint) {
                console.log('handleBreakpoint', event, slick, breakpoint);
            },
            handleDestroy(event, slick) {
                console.log('handleDestroy', event, slick);
            },
            handleEdge(event, slick, direction) {
                console.log('handleEdge', event, slick, direction);
            },
            handleInit(event, slick) {
                console.log('handleInit', event, slick);
            },
            handleReInit(event, slick) {
                console.log('handleReInit', event, slick);
            },
            handleSetPosition(event, slick) {
                console.log('handleSetPosition', event, slick);
            },
            handleSwipe(event, slick, direction) {
                console.log('handleSwipe', event, slick, direction);
            },
            handleLazyLoaded(event, slick, image, imageSource) {
                console.log('handleLazyLoaded', event, slick, image, imageSource);
            },
            handleLazeLoadError(event, slick, image, imageSource) {
                console.log('handleLazeLoadError', event, slick, image, imageSource);
            },
        }
    }
</script>
