<template>
    <span class="data-tooltip"
          :style="`
                transform: translate(0px, -8px) scale(0.75) rotate(0deg);
                visibility: ${position.visibility};
                -ms-transform-origin: 50% 100% 0px;
                -webkit-transform-origin: 50% 100% 0px;
                transform-origin: 50% 100% 0px;${position.tran};${stylePosition}` "><span
        class="data-tooltip-top is-show"></span><span class="data-tooltip-text is-show">{{ text }}</span></span>
</template>

<script>
    export default {
        name: "tooltip",
        props: {
            id: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                el: null,
                position: {bottom: 0, right: 0, visibility: 'hidden', tran: ''},
                elPosition: {},
                offset: {bottom: 4, right: 1.2},
                timeout: 0,
                text: '',
                delay: 600,
                stylePosition: ''
            }
        },
        watch: {
            '$route.name': function (n, o) {
                this.registerEvents();
            }
        },
        methods: {
            resetPosition() {
                this.position = {
                    tran: 'transform: translate(0px, -8px) scale(0.75)',
                    visibility: 'hidden'
                };
                this.setText();
            },
            setStylePosition() {
                this.stylePosition = `bottom: ${this.position.bottom}px;right:${this.position.right}px`;
            },
            setText() {
                this.text = this.$utils.getAttrb(this.el, 'aria-text');
            },
            clearTimeout() {
                window.clearTimeout(this.timeout);
            },
            setElPosition() {
                if(this.$utils.isEmptyVar(this.el)) return;
                this.elPosition = this.$utils.getElBouningClientRectOffset(this.el);
                this.position.bottom = this.elPosition.verticalBottom - this.offset.bottom;
                this.position.right = this.elPosition.horizontalRight - this.offset.right;
                this.setStylePosition();
            },
            setPosition() {
                this.setElPosition();
                this.position.visibility = 'visible';
                this.position.tran = 'transition: all .2s ease;transform: translate(0px, 0px) scale(1)';
                this.setText();
            },
            mClick(e) {
                this.clearTimeout();
                this.resetPosition();
            },
            mMouseover(e) {
                this.clearTimeout();
                this.timeout = setTimeout((et) => {
                    this.setPosition();
                }, this.delay);
            },
            mMousemove(e) {
                this.clearTimeout();
                this.timeout = setTimeout((et) => {
                    this.setPosition();
                }, this.delay);
            },
            mMouseleave(e) {
                this.clearTimeout();
                this.resetPosition();
            },
            mTouchStart(e) {
                this.clearTimeout();
                this.timeout = setTimeout((et) => {
                    this.setPosition();
                }, this.delay);
            },
            mTouchEnd(e) {
                this.clearTimeout();
                this.resetPosition();
                e.preventDefault();
            },
            registerEvents() {
                //register events
                this.el = document.getElementById(this.id);
                if (this.el) {
                    this.$utils.addElementEvent(this.el, 'mouseover', this.mMouseover, false)
                        .addElementEvent(this.el, 'mousemove', this.mMousemove, false)
                        .addElementEvent(this.el, 'mouseleave', this.mMouseleave, false)
                        .addElementEvent(this.el, 'touchstart', this.mTouchStart, false)
                        .addElementEvent(this.el, 'touchend', this.mTouchEnd, false)
                        .addElementEvent(this.el, 'click', this.mClick, false);
                    //initialize position
                    this.setElPosition();
                    //initialize position on window resize
                    window.addEventListener('resize', this.debounce(this.setElPosition, 150));
                }
            },
        },
        mounted() {
            this.registerEvents();
        },
        beforeDestroy() {
            //remove events
            if (this.el) {
                this.$utils.removeElementEvent(this.el, 'mouseover', this.mMouseover, false)
                    .removeElementEvent(this.el, 'mousemove', this.mMousemove, false)
                    .removeElementEvent(this.el, 'mouseleave', this.mMouseleave, false)
                    .removeElementEvent(this.el, 'touchstart', this.mTouchStart, false)
                    .removeElementEvent(this.el, 'touchend', this.mTouchEnd, false)
                    .removeElementEvent(this.el, 'click', this.mClick, false);

                if (window.removeEventListener) {
                    window.removeEventListener('resize', this.setElPosition)
                }
            }
        }
    }
    // if (!e) e = window.event;
    // let relTarg = e.relatedTarget || e.fromElement;
</script>
<style scoped>
</style>
