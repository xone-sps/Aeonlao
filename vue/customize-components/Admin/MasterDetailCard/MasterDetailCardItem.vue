<template>
    <content>
        <slot></slot>
    </content>
</template>

<script>
    export default {
        props: {
            header: {
                type: Object,
                default: () => {
                    return {title: '', tooltipIcon: ''}
                }
            },
        },
        data: () => ({
            menuItems: [],
        }),
        created() {
            this.menuItems = this.$children;
        }
    }
</script>
