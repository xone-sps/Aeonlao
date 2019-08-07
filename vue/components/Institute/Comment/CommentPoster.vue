<template>
    <div v-if="!isHidePoster">
        <slot name="poster-top"></slot>
        <div class="user-commenter-container displayFlex" :class="[containerClass]">
            <div class="image-container" v-if="!isHideUserImage">
                <img
                    :src="ImageUrl"
                    alt="comment image poster">
            </div>
            <div class="user-comment-input-container" :class="[{'is-invalid-data': isInValid}]">
                <div class="user-comment-input">
                    <div v-show="$utils.isEmptyVar(text)" class="place-holder">{{ placeholder }}</div>
                    <textarea @keydown="onKeySetData" @keyup="onKeySetData" :ref="`poster-${_uid}`"
                              @click="enterTextPoster"
                              @blur="leaveTextPoster" v-model="text" rows="3" class="textarea"
                              :class="poster.class"></textarea>
                </div>
            </div>
            <div class="user-comment-action-container" v-if="!isHideButton">
                <button @click="onPosterButtonClick" class="button is-default" :class="poster.button.class">{{
                    buttonText }}
                </button>
            </div>
        </div>
        <slot name="poster-bottom"></slot>
    </div>
</template>

<script>
    export default {
        name: "CommentPoster",
        props: {
            value: {},
            placeholder: {
                type: String,
                default: '',
            },
            ImageUrl: {
                type: String,
                default: '',
            },
            buttonText: {
                type: String,
                default: '',
            },
            isHideButton: {
                default: false,
                type: Boolean
            },
            isHideUserImage: {
                default: false,
                type: Boolean
            },
            isHidePoster: {
                default: false,
                type: Boolean
            },
            isInValid: {
                default: false,
            },
            containerClass: {
                default: '',
                type: String
            },
            state: {
                default: '',
            },
        },
        data: () => ({
            text: '',
            poster: {class: '', button: {text: '', class: ''}},
        }),
        watch: {
            'text': function (n, o) {
                this.setInputData(n, o);
            },
            'value': function (n, o) {
                this.setInputData(n, o);
            },
            state: function (n, o) {
                if (o !== '' || o !== null) {
                    this.setPosterClass();
                }
            }
        },
        methods: {
            onKeySetData() {
                this.setInputData(this.$refs[`poster-${this._uid}`].value, null);
            },
            setInputData(n, o) {
                this.poster.button.class = this.$utils.isEmptyVar(n) ? '' : 'is-active-comment';
                this.text = n;
                this.setPosterClass();
                this.emits();
            },
            enterTextPoster() {
                this.poster.class = 'is-commenting';
            },
            setFocus() {
                setTimeout(() => {
                    if (this.$refs[`poster-${this._uid}`]) {
                        this.$refs[`poster-${this._uid}`].focus();
                    }
                }, 80);
            },
            leaveTextPoster() {
                this.setPosterClass();
            },
            setPosterClass() {
                this.poster.class = this.$utils.isEmptyVar(this.text) ? '' : 'is-commenting';
            },
            emits() {
                this.$emit('send', this.text);
                this.$emit('input', this.text);
            },
            inits() {
                this.text = this.value;
                this.setPosterClass();
                this.emits();
            },
            onPosterButtonClick() {
                if (!this.$utils.isEmptyVar(this.text)) {
                    this.$emit('onPosterButtonClick', this.text);
                }
            }
        },
        created() {
            this.inits();
        }
    }
</script>

<style scoped>
    .user-commenter-container {
        padding-left: 8px;
        padding-right: 8px;
    }

    .user-commenter-container.in-side-comment {
        width: 100%;
        margin-top: 8px;
        padding-bottom: 24px;
        padding-right: 0;
        padding-left: 0;
    }


    .user-commenter-container.guest-user {
        justify-content: space-between;
        margin: 0 0 16px;
    }

    .user-commenter-container .image-container {
        align-items: flex-start;
    }

    .user-commenter-container .image-container img {
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        margin-right: 8px;
    }

    .user-commenter-container.in-side-comment img {
        width: 24px;
        height: 24px;
    }

    .user-comment-input-container {
        position: relative;
        -webkit-border-radius: 16px;
        -moz-border-radius: 16px;
        -ms-border-radius: 16px;
        border-radius: 16px;
        -webkit-flex: 1;
        flex: 1;
        min-width: 0;
        background: #fff;
        border: 1px solid #dddfe2;
        box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, .07);
        margin-left: 0;
        padding: 8px;
        width: 100%;
    }

    .user-commenter-container.guest-user.in-side-comment {
        margin: 0;
        padding: 0;
        padding-bottom: 8px;
    }

    .user-comment-input {
        display: block;
        position: relative;

    }

    .user-comment-input .place-holder {
        line-height: 22px;
        left: 5px;
        padding: 0;
        border-width: 0;
        bottom: 0;
        color: #90949c;
        pointer: none;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 3;
        text-indent: 0;
        white-space: pre-wrap;
        width: 100%;
        word-wrap: break-word;
        display: block;
        box-sizing: border-box;
        -webkit-line-break: after-white-space;
    }

    .user-comment-input textarea, .user-comment-input input {
        -webkit-appearance: none;
        background: transparent;
        border: 0;
        box-shadow: none;
        box-shadow: unset;
        outline: none;
        text-indent: 0;
        padding: 0;
        line-height: 16px;
        -webkit-nbsp-mode: space;
        letter-spacing: 0;
        -webkit-line-break: after-white-space;
        overflow: hidden;
        overflow-y: auto;
        resize: none;
        resize: unset;
        padding-top: 3px;
        height: 22px;
        z-index: 4;
        min-height: 0;
    }

    .user-comment-input input {
        margin: 0;
    }

    .user-comment-input textarea.is-commenting {
        height: auto;
    }

    .user-comment-action-container button.is-active-comment {
        background-color: #1a73e8;
        color: #fff;
        border-color: transparent;
    }

    .user-comment-action-container button {
        color: #bec3c9;
        height: 38px;
        margin-top: 1px;
        margin-left: 8px;
        font-size: 12px;
    }

    .user-comment-edit-action-container {
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .user-comment-edit-action-container button.is-active-comment {
        background-color: #1a73e8;
        color: #fff;
        border-color: transparent;
    }

    .user-comment-edit-action-container button {
        color: #bec3c9;
        height: 28px;
        margin-top: 1px;
        font-size: 12px;
    }

    .user-comment-edit-action-container .edit-action.is-left {
        padding-left: 30px;
        margin-right: auto;
    }

    .user-comment-edit-action-container .edit-action.is-right {
        margin-left: auto;
        padding-right: 4px;
    }

    .user-commenter-container.is-edit-comment {
        padding-bottom: 12px;
    }

    .user-comment-poster {
        border-bottom: 1px solid rgba(6, 8, 21, 0.12);
        padding: 0 0 11px 0;
        margin-bottom: 12px;
    }

    .user-comment-input-container.is-invalid-data {
        border: 2px solid #d93025;
    }

    img {
        vertical-align: middle;
    }
</style>
