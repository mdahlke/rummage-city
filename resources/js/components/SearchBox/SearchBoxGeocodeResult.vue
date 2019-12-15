<template>
    <div class="search-result"
         @click="trigger"
    >
        <h4>{{ result.place_name }}</h4>
    </div>
</template>

<script>
    export default {
        name: 'SearchBoxGeocodeResult',
        props: {
            result: {
                type: Object,
                required: true,
            }
        },
        methods: {
            trigger: function () {
                this.$emit('triggerSearch', this.result);
            }
        }
    };
</script>

<style lang="scss">
    .search-result {

        h4 {
            font-size: 16px;
        }

        &:last-of-type h4 {
            margin-bottom: 0;
        }
    }
</style>
