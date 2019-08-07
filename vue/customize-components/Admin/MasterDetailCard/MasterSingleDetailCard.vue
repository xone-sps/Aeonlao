<template>
    <div class="admin-master-detail-card">
        <div class="md-single-grid">
            <div :ad-cell="cell" class="admin-card theme-blue">
                <div class="admin-master-card-action-bar" ad-narrow-only>
                    <!--is-hidden add when click on back button in screen max with 633px-->
                    <div class="card-action-bar">
                        <div class="layout-align-start-center layout-row">
                            <button @click="setOnClickBackButton"
                                    class="v-md-button v-md-icon-button theme-blue"><i
                                class="material-icons">arrow_back</i></button>
                            <div class="emails-card-list-item layout-align-start-center layout-row">
                                <i class="material-icons" v-text="menuItem.icon"></i>
                                <span class="emails-card-list-item-id flex"
                                      v-text="menuItem.name"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="admin-master-detail-container admin-show-details">
                    <!--Main side content-->
                    <content class="admin-master-detail">
                        <!-- is-hidden when click the back button in screen max with 633px-->
                        <div>
                            <div class="admin-master-card-template admin-spinner-covered">
                                <!--Spinner Loading-->
                                <SpinnerLoading v-if="isLoading"/>
                                <!--Spinner Loading-->
                                <!-- Headers -->
                                <div class="header"><h5 v-text="header.title"></h5>
                                    <div class="layout-row flex" v-html="header.content"></div>
                                </div>
                                <!-- Headers -->
                                <!--Details-->
                                <slot></slot>
                                <!--Details-->
                            </div>
                        </div>
                    </content>
                    <!--Main side content-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            header: {
                type: Object,
                default: () => {
                    return {title: '', content: ''};
                }
            },
            isLoading: {
              type: Boolean,
              default: false,
            },
            menuItem: {
                type: Object,
                default: () => {
                    return {title: ''};
                }
            },
            cell: {
                type: String,
                default: '12'
            }
        },
        data: () => ({

        }),
        methods: {
            setOnClickBackButton() {
                this.$emit('onBackButtonClick');
            }
        },
        created() {

        }
    }
</script>

<style scoped>

</style>
