<template>
    <content >
        <slot v-if="isActive"></slot>
    </content>
</template>

<script>
    export default {
        props: {
            selected: {
                type: Boolean,
                default: false,
            },
            header: {
                type: Object,
                default: () => {
                    return {}
                }
            },
            menuItem: {
                type: Object,
                default: () => {
                    return {}
                }
            }
        },
        data: () => ({
            isActive: false,
            content: {},
        }),
        created() {
            this.isActive = this.selected;
            this.content = this.$children;
            if(this.isActive){
                this.Event.fire('master-detail-card-menu-active', this);
            }
        }
    }
</script>

<style scoped>

</style>
