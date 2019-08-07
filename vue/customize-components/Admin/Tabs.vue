<template>
    <div class="admin-tabs">
        <div class="layout-column flex">
            <div class="md-tabs admin-fb-featurebar-tabs " :style="`background: ${bgColor};`">
                <div class="admin-md-tabs-wrapper">
                    <div ref="refPreTab" @click="prevTab" class="admin-tab-prev-button"
                         :class="[paginateCount===0 ? 'is-disabled' : '']"
                         v-if="isPaginated">
                        <div class="md-icon">
                            <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                                 focusable="false">
                                <g>
                                    <polygon points="15.4,7.4 14,6 8,12 14,18 15.4,16.6 10.8,12 "></polygon>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div ref="refNextTab" @click="nextTab" class="admin-tab-next-button"
                         :class="[paginateCount===paginateTotal ? 'is-disabled' : '']"
                         v-if="isPaginated">
                        <div class="md-icon">
                            <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                                 focusable="false">
                                <g>
                                    <polygon points="15.4,7.4 14,6 8,12 14,18 15.4,16.6 10.8,12 "></polygon>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-tabs-canvas" :class="[isPaginated?'md-paginated' :'' ]">
                        <div class="admin-pagination-wrapper" ref="tabs-wrapper" :style="transMove">
                            <div :style="(isPaginated && i===0) ? 'margin-left: 0;': ''" :ref="`${i}-tab`"
                                 @click="tab(t, i)" v-for="(t, i) in tabs"
                                 class="admin-tab-item admin-tab"
                                 :class="[activeClass(i)]">{{t.name}}
                            </div>
                            <div :class="[transClass]" class="admin-tab-ink-bar"
                                 :style="`left: ${inkBar.l}px; right: ${inkBar.r}px;`"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            bgColor: {
                required: false
            },
            tabs: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            offsetLeft: {
                type: Number,
                default: 0,
            },
            selectedTab: {
                type: Object,
                default: () => {
                    return {};
                }
            }
        },
        data() {
            return {
                tabIndex: 0,
                tabsWidth: 0,// total tabs width
                tabsDiff: 0,
                itemCanDisplay: 0,
                paginateCount: 0,
                paginateTotal: 0,
                navControlWidth: 32,
                tabsPaginateWidth: 999999,
                inkBar: {l: 0, r: 0},
                transClass: '',
                transMove: '',
                isPaginated: false,
                timeout: 0,
            }
        },
        name: "tabs",
        watch: {
            tabIndex: function (n, o) {//set transition class for ink bar
                if (n > o) {// right tran
                    this.transClass = 'admin-tab-ink-bar-right';
                } else {// left tran
                    this.transClass = 'admin-tab-ink-bar-left';
                }
            },
            '$store.state.windowWidth': function (n, o) {//check on window resize
                this.shouldPaginate();
            },
            selectedTab: function (n, o) {
                this.tabIndex = this.tabs.findIndex(x => x.id == n.id);
                this.tab(this.tabs[this.tabIndex], this.tabIndex);
            }
        },
        methods: {
            activeClass(i) {//set tab active class
                return this.tabIndex === i ? ' tab-active' : '';
            },
            tab(t, i) {//set tab index, emit item and ink bar transition
                this.tabIndex = i;
                this.setInkBar();
                this.emit();
            },
            getDoubleNavWidth() {// get next and prev navigation width
                return (this.navControlWidth * 2);
            },
            setItemsCanDisplay() {// set items that can display per window width
                let inc = 0, w = 0;
                for (let el in this.$refs) {
                    if (this.$utils.isEmptyVar(this.$refs[el]))
                        continue;
                    if (this.$utils.containsClassName(this.$refs[el][0], "admin-tab-item")) {
                        let pos = this.$utils.getElBouningClientRect(this.$refs[el][0]);
                        if (pos)
                            w += pos.width;
                        if (this.$utils.MathAbsRound(w, 0.1) > (this.$store.state.windowWidth - this.getDoubleNavWidth())) {
                            break;
                        }
                        inc++;
                    }
                }
                this.itemCanDisplay = inc;
                this.setPaginateTotal();
            },
            setPaginateTotal() {//set total move next and prev
                if (this.itemCanDisplay === 0) {// fix bug infinity number
                    this.paginateTotal = 1;
                    return;
                }
                let t = this.$utils.MathAbsRound(this.tabs.length / this.itemCanDisplay),
                    ct = 0, inc = 0;
                for (let i = t; i > 0; i--) {// get actually total paginate size
                    ct = this.$utils.MathAbsRound((this.tabsDiff / this.itemCanDisplay) * i);
                    if (ct <= this.tabsDiff) {
                        inc = i - 1;
                        break;
                    }
                }
                this.paginateTotal = t - inc;
            },
            setTabDiff() {// diff between tabs width and window width with calculated navigation width
                //important this offset left can be change for another usage
                //that does't have the side bar this is working correct when using for the site that have the sidebar we made.
                this.tabsDiff = Math.abs(((this.$store.state.windowWidth
                    - (!this.$store.state.isMobile ? this.offsetLeft : 0))
                    - this.tabsWidth) - this.getDoubleNavWidth());
            },
            setTransMove() {//set transition style to tabs wrapper
                this.transMove = `transform: translate(-${this.getXAxis()}px, 0px); width: ${this.tabsPaginateWidth}px;`;
                if (!this.isPaginated)
                    this.transMove = '';
            },
            getXAxis() {//get translate x for motion moving tabs left and right
                let x = this.$utils.MathAbsRound((this.tabsDiff / this.itemCanDisplay) * this.paginateCount), a = 0;
                if (this.paginateCount === this.paginateTotal && x < this.tabsDiff) {//check if the x ais not equals to tabs total movement (tabDiff)
                    a = this.tabsDiff - x;
                }
                return x + a;
            },
            prevTab() {// move tab back
                this.paginateCount--;
                if (this.paginateCount >= 0) {
                    this.setTransMove();
                } else {
                    this.paginateCount = 0;
                }
            },
            nextTab() { // move tab next
                this.paginateCount++;
                if (this.paginateCount <= this.paginateTotal) {
                    this.setTransMove();
                } else {
                    this.paginateCount = this.paginateTotal;
                }
            },
            clearTimeout() {//clear timeout id
                window.clearTimeout(this.timeout);
            },
            shouldPaginate() {// check if window should add navigation
                let el = this.$refs['0-tab'],//get all tabs width except 0
                    pos = this.$utils.getElBouningClientRect(el[0]); //get tab index 0 width
                this.isPaginated = this.$store.state.windowWidth < (this.tabsWidth = Math.ceil(this.getNextWidths(el[0]) + pos.width));
                //call first
                this.setInitializeOrders();
                //re-call for windows resize waiting  for elements shaped
                this.clearTimeout();
                this.timeout = setTimeout(() => {
                    this.setInitializeOrders();
                }, 560);
            },
            getNextWidths(el) {// get next sibling tab items width from current el
                var Inc = 0, w = 0, sb = el.nextSibling;
                while (this.$utils.containsClassName(sb, "admin-tab-item") && Inc <= this.tabs.length) {
                    let rectSb = this.$utils.getElBouningClientRect(sb);
                    if (rectSb) {
                        w += rectSb.width;
                    }
                    sb = sb.nextSibling;
                    Inc++;
                }
                return this.$utils.MathAbsRound(w, 0.1);
            },
            getPrevWidths(el) {// get prev sibling tab items width from current el
                var Inc = 0, w = 0, sb = el.previousSibling;
                while (this.$utils.containsClassName(sb, "admin-tab-item") && Inc <= this.tabs.length) {
                    let rectSb = this.$utils.getElBouningClientRect(sb);
                    if (rectSb) {
                        w += rectSb.width;
                    }
                    sb = sb.previousSibling;
                    Inc++;
                }
                return this.$utils.MathAbsRound(w, 0.1);
            },
            getLeft(pos) {// get left position for the ink bar, important this offset left can be change for another usage
                //that does't have the side bar this is working correct when using for the site that have the sidebar we made.
                return pos.left === 0 ? 0 : Math.ceil(pos.left - (this.$store.state.isMobile ?
                        (this.isPaginated ? this.navControlWidth : 0) : (this.offsetLeft +
                            (this.isPaginated ? this.navControlWidth : 0))
                ));
            },
            getRight(el, pos) {// get right position for the ink bar
                return this.$utils.MathAbsRound((this.isPaginated ?
                    (this.tabsPaginateWidth - (this.getPrevWidths(el) + pos.width)) : this.getNextWidths(el)), 0.1);
            },
            setInkBar() {//set ink bar position
                let el = this.$refs[`${this.tabIndex}-tab`];
                let pos = this.$utils.getElBouningClientRect(el[0]);
                if (pos) {
                    this.inkBar = {
                        l: this.getLeft(pos) + (this.isPaginated ? this.getXAxis() : 0),
                        r: this.getRight(el[0], pos),
                    };
                }
            },
            setInitializeOrders() {// set position for all tab elements
                this.setInkBar();
                this.setTabDiff();
                this.setItemsCanDisplay();
                this.setTransMove();
            },
            emit() {// emit tab to event
                this.$emit('ItemClick', this.tabs[this.tabIndex]);
            }
        },
        mounted() {
            this.shouldPaginate();// initialize elements information
        },
        created() {
            this.emit(); //emit data on created life cycle
        }
    }
</script>
