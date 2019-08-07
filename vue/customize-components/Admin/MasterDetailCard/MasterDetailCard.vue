<template>
    <div class="admin-master-detail-card">
        <div class="md-single-grid">
            <div ad-cell="12" class="admin-card theme-blue">
                <div class="admin-master-card-action-bar" ad-narrow-only :class="[getHideClassOnActionBar()]">
                    <!--is-hidden add when click on back button in screen max with 633px-->
                    <div class="card-action-bar">
                        <div class="layout-align-start-center layout-row">
                            <button @click="removeActiveShowDetailsClass"
                                    class="v-md-button v-md-icon-button theme-blue"><i
                                class="material-icons">arrow_back</i></button>
                            <div class="emails-card-list-item layout-align-start-center layout-row">
                                <i class="material-icons" v-text="currentActiveItem.menuItem.icon"></i>
                                <span class="emails-card-list-item-id flex"
                                      v-text="currentActiveItem.menuItem.name"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="admin-master-detail-container" :class="[showDetailsClass]">
                    <!-- admin-show-details remove when click on back button in screen max with 633px-->
                    <!--Left side items-->
                    <content class="admin-master-card" ad-cell="4" ad-cell-tablet="3">
                        <!--Top left side -->
                        <div class="emails-card-lists">
                            <template v-for="(cardItem, i) in detailCardItems">
                                <header>
                                    <h4> {{ cardItem.header.title }} </h4>
                                </header>
                                <div v-for="(menu, j) in cardItem.menuItems"
                                     class="emails-card-list-item layout-align-start-center layout-row"
                                     :class="[{ 'is-selected': menu.isActive }]"
                                     @click="setCurrentMenuItemContent(menu, {card: i, menu: j})">
                                    <!-- is-selected add to the selected left side item in screen min-width 664px or
                                    hide when click the back button in screen 633px-->
                                    <i class="material-icons">{{ menu.menuItem.icon }}</i>
                                    <span class="emails-card-list-item-id flex"> {{ menu.menuItem.name }} </span>
                                </div>
                            </template>
                            <!--<header>-->
                            <!--<h4> {{ header.title }} </h4>-->
                            <!--</header>-->
                            <!--<div v-for="(cardItem, i) in detailCardItems"-->
                            <!--class="emails-card-list-item layout-align-start-center layout-row"-->
                            <!--:class="[{ 'is-selected': currentActiveItem.selected }]"-->
                            <!--@click="setCurrentMenuItem(cardItem.menuItem)">-->
                            <!--&lt;!&ndash; is-selected add to the selected left side item in screen min-width 664px or-->
                            <!--hide when click the back button in screen 633px&ndash;&gt;-->
                            <!--<i class="material-icons">{{ cardItem.menuItem.icon }}</i>-->
                            <!--<span class="emails-card-list-item-id flex"> {{ cardItem.menuItem.name }} </span>-->
                            <!--</div>-->
                            <!--<div class="emails-card-list-item layout-align-start-center layout-row">-->
                            <!--<i class="material-icons">email</i>-->
                            <!--<span class="emails-card-list-item-id flex"> รีเซ็ตรหัสผ่าน </span>-->
                            <!--</div>-->

                            <!--<header>-->
                            <!--<h4>SMS</h4>-->
                            <!--</header>-->
                            <!--<div class="emails-card-list-item layout-align-start-center layout-row">-->
                            <!--<i class="material-icons">email</i>-->
                            <!--<span class="emails-card-list-item-id flex">  การยืนยันทาง SMS  </span>-->
                            <!--</div>-->

                        </div>
                        <!--Top left side-->
                        <!--Bottom left side-->
                        <div class="admin-template-footer" v-if="hasSlot('template-footer')">
                            <slot name="template-footer"></slot>
                        </div>
                        <!--Bottom left side-->
                    </content>
                    <!--Left side items-->

                    <!--Right side content-->

                    <content class="admin-master-detail" :class="[getHideClassOnActionBar()]">
                        <!-- is-hidden when click the back button in screen max with 633px-->
                        <div>
                            <div class="admin-master-card-template admin-spinner-covered">
                                <!--Spinner Loading-->
                                <SpinnerLoading v-if="isLoading"/>
                                <!--Spinner Loading-->
                                <!-- Headers -->
                                <div class="header"><h5 v-text="currentActiveItem.header.title"></h5>
                                    <div class="layout-row flex" v-html="currentActiveItem.header.content"></div>
                                </div>
                                <!-- Headers -->
                                <!--Details-->
                                <slot name="details"></slot>
                                <!--Details-->
                            </div>
                        </div>
                    </content>
                    <!--Right side content-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            isLoading: {
                type: Boolean,
                default: false,
            },
        },
        data: () => ({
            hideClass: {actionBar: 'is-hidden'},
            showDetailsClass: 'admin-show-details',
            ItemState: {card: -1, menu: -1},
            currentActiveItem: {header: {}, menuItem: {}},
            shouldTrigger: {atWidth: 664, need: true},
            detailCardItems: [],
        }),
        watch: {
            '$store.state.windowWidth': function (n) {
                if (this.shouldTrigger.atWidth < n && this.shouldTrigger.need) {
                    this.addActiveShowDetailsClass();
                    this.shouldTrigger.need = false;
                }
                if (this.shouldTrigger.atWidth > n) {
                    this.shouldTrigger.need = true;
                }
            }
        },
        methods: {
            getHideClassOnActionBar() {
                return this.showDetailsClass === '' ? this.hideClass.actionBar : '';
            },
            addActiveShowDetailsClass() {
                this.showDetailsClass = 'admin-show-details';
                this.currentActiveItem.isActive = true;
            },
            emitStateChanged() {
                this.$emit('onStateChanged', this.ItemState);
            },
            removeActiveShowDetailsClass() {
                this.showDetailsClass = '';
                this.currentActiveItem.isActive = false;
            },
            setPreviousActiveItemContentState(state, newState) {
                if (state.card === -1 && state.menu === -1) {
                    this.detailCardItems[0].menuItems[0].isActive = false
                } else {
                    this.detailCardItems[state.card].menuItems[state.menu].isActive = false
                }
                this.ItemState = newState;
                this.emitStateChanged();
            },
            setCurrentMenuItemContent(menu, state) {
                this.setPreviousActiveItemContentState(this.ItemState, state);
                menu.isActive = true;
                this.currentActiveItem = menu;
                this.addActiveShowDetailsClass();
            },
            setMasterCardItem() {
                this.detailCardItems = this.$children.filter((d) => {
                    return String(d.$options._componentTag).toLowerCase() === 'masterdetailcarditem';
                });
            }
        },
        mounted() {
            this.setMasterCardItem();
        },
        created() {
            this.setMasterCardItem();
            this.Event.offListen('master-detail-card-menu-active');
            this.Event.listen('master-detail-card-menu-active', (menu) => {
                if (!this.currentActiveItem.isActive) {
                    this.currentActiveItem = menu;
                } else {
                    menu.isActive = false;
                }
            });
        }
    }
</script>

<style scoped>

</style>
