<template>
    <div :style="`display: ${dropBg.show?'':'none'};`">
        <div v-if="dropBg.show" class="admin-back-drop is-fixed background"
             :style="`background-color: ${dropBg.color} !important;${dropBg.tran}`"></div>
        <div v-if="dropBg.show" class="admin-scroll-mask">
            <div class="admin-scroll-mask-bar"></div>
        </div>
        <div class="admin-modal-container"
             :style="`top:${top}px; height: ${ modalHeight > 0 ? modalHeight: $store.state.windowHeight}px;`">
            <div class="admin-modal user admin-modal-fb theme-blue" :class="[transClass]">
                <button @click="closeModal" class="v-md-button v-md-icon-button close-button"><i class="material-icons">close</i>
                </button>
                <div class="admin-modal-fb-header">
                    <div class="content-title">
                        <slot name="title"></slot>
                    </div>
                </div>
                <div class="admin-modal-fb-body">
                    <slot></slot>
                    <!--<p> ส่งอีเมลรีเซ็ตรหัสผ่าน <a class="c5e-simple-link md-gmp-blue-theme" href="#" ng-click="controller.goToEmailTemplate()">ตรวจทานเทมเพลตอีเมล</a> </p>-->
                    <!--<div>-->
                    <!--<div class="body-message-container has-icon is-warning">-->
                    <!--<div class="inner">-->
                    <!--<i class="material-icons m-icon">warning</i>-->
                    <!--<div class="admin-modal-message"> ผู้ใช้ที่มีบัญชีที่ปิดใช้ไม่สามารถลงชื่อเข้าใช้ได้</div>-->
                    <!--</div>-->
                    <!--</div>-->
                    <!--</div>-->
                    <!--<div class="form-label"> บัญชีผู้ใช้</div>-->
                    <!--<div class="form-input-static-value"> bee@gmail.com</div>-->
                </div>
                <div class="admin-modal-actions">
                    <button @click="closeModal" class="v-md-button secondary"> Cancel</button>
                    <slot name="actions"></slot>
                    <!--<button class="v-md-button primary"> ปิดใช้</button>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "adminmodal",
        props: {
            isActive: {
                type: Boolean,
                default: false,
            },
            parentHeight: {
                default: null,
            },
            scrollContainer: {
                default: null,
            }
        },
        data: () => ({
            dropBg: {},
            transClass: '',
            t: 0,
            modalHeight: 0,
            top: 0,
        }),
        watch: {
            isActive: function (n) {
                this.setTransInClass(n);
            }
        },
        methods: {
            closeModal() {
                this.setTransInClass(false);
            },
            clearTimeout() {
                window.clearTimeout(this.t);
            },
            setPosition() {
                //reset position
                this.top = 0;
                this.modalHeight = 0;
                //reset position
                if (this.parentHeight) {//check height
                    this.modalHeight = this.parentHeight.clientHeight;
                }
                if (this.scrollContainer && this.modalHeight > 0) { //check scroll
                    let haftPage = this.modalHeight / 2, scrollTop = this.scrollContainer.scrollTop();
                    if (scrollTop > haftPage) {
                        this.top = Math.abs(scrollTop - haftPage);
                    }
                }
            },
            setShowDropBg() {
                this.setPosition();
                this.dropBg.show = true;
                this.dropBg.color = 'transparent';
                this.dropBg.tran = `-webkit-transition: background-color .2s ease; transition: background-color .2s ease;`;
            },
            setTransInClass(n) {
                this.clearTimeout();
                if (n) {
                    this.transClass = 'trans-out';
                    this.setShowDropBg();
                    this.t = setTimeout(() => {
                        this.transClass = 'trans-in';
                        this.dropBg.color = '#19212b';
                    }, 100);
                } else {
                    this.transClass = 'trans-out-leave';
                    this.dropBg.color = 'transparent';
                    this.t = setTimeout(() => {
                        this.transClass = '';
                        this.dropBg = {};
                        this.$emit('close');
                    }, 100);
                }
            }
        }
    }
</script>
<style scoped>
    .trans-out-leave {
        opacity: 0.5;
        -webkit-transition: all .4s cubic-bezier(0.25, 0.8, 0.25, 1);
        transition: all .4s cubic-bezier(0.25, 0.8, 0.25, 1);
        -webkit-transform: translate(0, 0) scale(0);
        transform: translate(0, 0) scale(0);
    }

    .trans-out {
        -webkit-transform: translate(0, 0) scale(0.5);
        transform: translate(0, 0) scale(0.5);
    }
</style>
