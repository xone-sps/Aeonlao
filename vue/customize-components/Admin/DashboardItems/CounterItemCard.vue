<template>
    <div class="p-card">
        <div class="admin-mat-card">
            <div class="p-card-header">
                <i class="material-icons"> {{ icon }} </i>
                <h3 @click="emitCardClick" class="p-card-title"> {{ title }} </h3>
            </div>
            <SpinnerLoading v-if="isLoading"/>
            <div class="p-posts-card">
                <div class="p-columns">

                    <!-- Count-->
                    <div class="p-column" v-for="(item, idx) in multipleItems" :key="idx">
                        <div @click="emitMultipleCardClick(item)" style="cursor: pointer;"
                             class="items-counter align-horizontal-center target-host">
                            <div class="counter-header">
                                <div class="counter-title-label">
                                    <h4 class="counter-title"> {{ item.title }}</h4>
                                    <div class="p-label">(Count)</div>
                                </div>
                            </div>
                            <div class="value-delta">
                                <div class="value">
                                    <span class="value-container">{{ item.count.value }} {{item.count.text }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Count-->

                    <!-- Count-->
                    <div class="p-column" v-if="multipleItems.length <= 0">
                        <div @click="emitCardClick" style="cursor: pointer;"
                             class="items-counter align-horizontal-center target-host">
                            <div class="counter-header">
                                <div class="counter-title-label">
                                    <h4 class="counter-title"> {{ title }} Count</h4>
                                    <div class="p-label">(Current)</div>
                                </div>
                            </div>
                            <div class="value-delta">
                                <div class="value">
                                    <span class="value-container">{{ count.value }} {{count.text }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Count-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CounterItemCard",
        props: {
            multipleItems: {
                type: Array,
                default: function () {
                    return []
                }
            },
            title: {
                type: String,
                default: '',
            },
            icon: {
                type: String,
                default: '',
            },
            count: {
                type: Object,
                default: function () {
                    return {value: 0, text: ''}
                },
            },
            isLoading: {
                type: Boolean,
                default: false,
            }
        },
        methods: {
            emitCardClick() {
                this.$emit('onCardClick', this);
            },
            emitMultipleCardClick(item) {
                this.$emit('onMultipleCardClick', item);
            }
        }
    }
</script>

<style scoped>

</style>
