<template>

    <article class="related-listing__item">
        <ListingListItemMainInfo :listing="listing"/>
    </article>

</template>

<script>
    import ListingListItemMainInfo from './listings/ListingListItemMainInfo';

    export default {
        name: 'RelatedListingsItem',
        components: {ListingListItemMainInfo},
        props: {
            listing: Object,
            required: true
        },
        methods: {
            excerpt(string, length = 20) {
                return string.substring(0, length);
            }
        }
    };
</script>

<style lang="scss">

    .related-listing__item {
        margin-bottom: 30px;
    }

</style>
