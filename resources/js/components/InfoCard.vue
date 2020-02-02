<template>

    <section class="card">
        <div class="card-header">{{ title }}</div>

        <div class="card-body">
            <slot name="body"></slot>
        </div>

        <div class="card-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
        </div>
        <!--        <h4>Inactive Listings</h4>-->
        <!--        <div class="card-body">-->
        <!--            @foreach($inactiveListings as $listing)-->
        <!--            <a href="{{ route('user.listing.edit', ['listing' => $listing->id]) }}">-->
        <!--                <h3><i class="fad fa-warehouse-alt"></i> {{ $listing->title }}-->
        <!--                </h3>-->
        <!--            </a>-->
        <!--            @endforeach-->
        <!--        </div>-->
    </section>

</template>

<script>
    export default {
        name: 'InfoCard',
        props: {
            title: {
                type: String,
                required: false,
                default: ''
            },

        }
    };
</script>

<style scoped>

</style>
