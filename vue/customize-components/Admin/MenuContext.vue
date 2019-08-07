<template>
    <div>
        <div v-if="isActive" class="admin-scroll-mask">
            <div class="admin-scroll-mask-bar"></div>
        </div>
        <div
            :style="`${position}display:${display};`"
            class="open-menu-context-container panel" :class="[isActive ? 'is-active': '', transClass]">
            <div class="menu-context-content theme-blue">
                <div class="menu-context-item" v-for="(t, i) in menuContext.menus">
                    <button @click="setActionMenuContext(t)" class="v-md-button">
                        {{ t.name}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState, mapMutations} from 'vuex'

    export default {
        name: "menu-context",
        data() {
            return {
                didScroll: false,
                delta: 5,
                display: '',
                isActive: false,
                transClass: '',
                timer: 0,
                t: 30,
                position: '',
                offset: {x: 77}
            }
        },
        computed: {
            ...mapState(['menuContext']), //post: left, top, name, action
        },
        watch: {
            menuContext: function (n, o) {
                this.getPosition();
            }
        },
        methods: {
            ...mapMutations(['setMenuContext', 'setOnMenuContextItemClick']),
            getScrollTop() {
                let el = this.jq('#main-container');
                if (el)
                    return el.scrollTop();
                return 0;
            },
            isEleHidden(el) {
                return el.offsetWidth === 0 && el.offsetHeight === 0;
            },
            getPosition() {
                this.menuContext.pos = {pos: {left: 0, top: 0}};
                if (
                    this.menuContext.el
                    && this.menuContext.el.target
                    && this.menuContext.el.target.parentElement
                ) {
                    if (this.isEleHidden(this.menuContext.el.target.parentElement)) {
                        this.outSideRemoveMenuContext();
                        return;
                    }
                    if (!this.$utils.isEmptyVar(this.menuContext.offsetX)) {
                        this.offset.x = this.menuContext.offsetX;
                    }
                    let pos = this.$utils.findPos(this.menuContext.el.target.parentElement);
                    this.position = `top: ${pos.y - this.getScrollTop()}px;
                    left: ${pos.x - this.offset.x}px;
                    transform-origin: right top 0px;`;
                    this.fade(true);
                }
            },
            fade(a) {
                this.clearTimeout();
                if (a) {
                    //fade in
                    this.transClass = 'trans-in-enter';
                    this.display = '';
                    this.timer = setTimeout(() => {
                        this.isActive = a;
                        this.transClass = 'trans-in-active';
                    }, this.t);
                    //fade in
                } else {
                    //fade out
                    this.isActive = a;
                    this.timer = setTimeout(() => {
                        this.display = 'none';
                        this.transClass = '';
                    }, this.t);
                    //fade out
                }
            },
            outSideRemoveMenuContext() {
                if (this.isActive) {
                    this.fade(false);
                    this.setMenuContext({pos: {left: 0, top: 0}, name: ''});
                }
            },
            setActionMenuContext(t) {
                t.from = this.menuContext.from;
                this.setOnMenuContextItemClick(t);
                this.outSideRemoveMenuContext();
            },
            clearTimeout() {
                window.clearTimeout(this.timeout);
            },
            registerEvents() {
                //initialize position on outside click
                this.$utils.addDocEvent('click', this.outSideRemoveMenuContext, false);
                //initialize position on window resize
                window.addEventListener('resize', this.debounce(this.getPosition, 150));
            },
        },
        mounted() {
            this.registerEvents();
        },
        beforeDestroy() {
            this.$utils.removeDocEvent('click', this.outSideRemoveMenuContext, false);
            if (window.removeEventListener) {
                window.removeEventListener('resize', this.getPosition)
            }
        }
    }
</script>

<style scoped>
    .trans-in-enter {
        -webkit-transform: translate(0, 0) scale(0.5);
        transform: translate(0, 0) scale(0.5);
    }

    .trans-in-active {
        -webkit-transform: translate(0, 0) scale(1);
        transform: translate(0, 0) scale(1);
    }
</style>
