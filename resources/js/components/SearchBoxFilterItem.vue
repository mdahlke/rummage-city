<template>
    <li @click="emitFilter(name)"
        class="list-inline-item"
        :class="{ 'active': active }"
    >
        <i :class="icon"></i> {{ text }}
    </li>
</template>

<script>
    export default {
        name: 'SearchBoxFilterItem',
        props: {
            name: {
                type: String,
                required: true,
            },
            text: {
                type: String,
                required: true,
            },
            icon: {
                type: String,
                required: true,
            }
        },
        computed: {
            active() {
                return this.$store.getters.searchFilters.indexOf(this.name) >= 0;
            }
        },
        methods: {
            emitFilter(val) {
                if (this.active) {
                    this.$emit('filterRemove', val);
                } else {
                    this.$emit('filterAdd', val);
                }
            }
        }
    }
</script>
