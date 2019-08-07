<template>
    <div class="general-input-container">
        <div
            class="general-input-container-group" :class="[activeInputClass,
                 isNotEmptyClass, isErrorClass]">
            <!-- general-input-active is-error is-not-empty-->
            <div
                class="general-input-container-group-inner
                    general-input-container-group-default">
                <div class="general-input-element-container" :class="getSmallClass">
                    <div class="general-input-element-inner">
                        <div class="general-input-element">
                            <input @keyup.enter="emitEnter" @click="addActiveInput" @mousedown="addActiveInput"
                                   ref="general-input" :aria-label="labelText"
                                   :disabled="isDisabled"
                                   v-model="inputText"
                                   :type="isShownPassword?'text':inputType"
                                   :autocomplete="autoComplete"
                                   :badinput="isBadInput"
                                   class="general-input general-input-text-direction is-big"
                                   :class="[getSmallClass, {'is-disabled' : isDisabled}]"/>
                            <!---->
                            <div :class="[isFocusedClass, getSmallClass]"
                                 class="general-input-label is-white"
                                 aria-hidden="true">{{labelText}}
                            </div>

                        </div>
                        <content v-if="!$utils.isEmptyVar(inputAdviceText)"
                                 class="general-input-email-advice" :class="[getSmallClass]">
                            <span class="inner" aria-hidden="true">{{inputAdviceText}}</span>
                        </content>

                        <content v-else-if="showPasswordButton"
                                 class="general-input-show-password">
                            <div class="inner is-big" :class="[getSmallClass]">
                                <content class="general-input-show-password-button">
                                        <span id="identifierShowPassword"
                                              @click="showPassword"
                                              :aria-text="isShownPassword ? showPasswordText.hide : showPasswordText.show">
                                              <span class="show-password-eye"
                                                    :class="[isShownPassword?'hide': '']">
                                                  <i class="material-icons black-fill">visibility_off</i>
                                              </span>
                                              <span class="shown-password-eye"
                                                    :class="[isShownPassword? '': 'hide']">
                                                 <i class="material-icons black-fill">remove_red_eye</i>
                                              </span>
                                        </span>
                                </content>
                            </div>
                        </content>

                        <div
                            class="general-input-element-border
                                general-input-element-border-main"></div>
                        <div
                            class="general-input-element-border-active
                                general-input-element-border-active-color
                                general-input-element-border-active-tran"></div>
                    </div>
                </div>
                <!--validate show-->
                <div class="general-input-validate-container">
                    <div class="general-input-validate-top"></div>
                    <div
                        class="general-input-validate-text-container">
                        <div class="inner" v-if="validate.text">
                            <span class="span-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         viewBox="0 0 24 24" fill="rgb(229, 28, 35)"><path
                                d="M0 0h24v24H0z" fill="none"/><path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg></span>{{
                            validate.text }}
                        </div>
                        <div v-else-if="!$utils.isEmptyVar(adviceText)" class="is-advice">{{ adviceText }}</div>
                    </div>
                </div>
                <!--validate show-->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {},
            validate: {
                type: Object,
                default: () => {
                    return {};
                }
            },
            adviceText: {
                type: String,
                default: '',
            },
            inputAdviceText: {
                type: String,
                default: '',
            },
            inputType: {
                type: String,
                default: 'text'
            },
            autoComplete: {
                type: String,
                default: ''
            },
            labelText: {
                type: String,
                default: ''
            },
            isDisabled: {
                type: Boolean,
                default: false,
            },
            isSmall: {
                type: Boolean,
                default: false,
            },
            showPasswordButton: {
                type: Boolean,
                default: false,
            },
            showPasswordText: {
                type: Object,
                default: () => {
                    return {hide: 'Hide password', show: 'Show password'};
                }
            }
        },
        name: "general-input",
        data() {
            return {
                activeInputClass: '',// general-input-active,
                isFocusedClass: '', // is-focused
                isNotEmptyClass: '',// is-not-empty
                inputText: '',
                isShownPassword: false,
            }
        },
        watch: {
            '$store.state.WindowState': function (n, o) {
                if (n === 'visible' && o === 'hidden' && this.isInputActivated()) {
                    this.isFocusedClass = 'is-focused';
                    this.addActiveInput(null);
                } else {
                    this.isFocusedClass = '';
                }
            },
            'value': function (n, o) {
                this.inputText = n;
            },
            'inputText': function (n, o) {
                this.emit();
            }
        },
        computed: {
            isBadInput() {
                return this.$utils.isEmptyVar(this.validate.text) ? 'false' : 'true';
            },
            isErrorClass() {
                return this.$utils.isEmptyVar(this.validate.text) ? '' : 'is-error';
            },
            getSmallClass() {
                return this.isSmall ? 'is-small' : '';
            }
        },
        methods: {
            addActiveInput(e) {
                if (e)
                    e.stopPropagation();
                this.addActiveClasses();
                //check blurred
                if (this.$utils.isEmptyVar(this.$utils.getAttrb(this.$refs['general-input'], 'aria-focus-events'))) {
                    this.setBlur();
                }
            },
            setIsNotEmptyClass() {
                this.isNotEmptyClass = this.$utils.isEmptyVar(this.inputText) ? '' : 'is-not-empty';
            },
            addActiveClasses() {
                if (this.activeInputClass === '') {
                    this.activeInputClass = 'general-input-active';
                }
                this.setIsNotEmptyClass();
            },
            removeActiveClasses() {
                if (this.activeInputClass !== '') {
                    this.isFocusedClass = '';
                    this.activeInputClass = '';
                }
                this.setIsNotEmptyClass();
            },
            isInputActivated() {
                let ac = document.activeElement;
                return ac.tagName === 'INPUT' && ac.className.split(" ")[0] === 'general-input';
            },
            setFocus() {
                this.addAriaInput('aria-focus-events', 'called');
                this.addActiveClasses();
            },
            setBlur() {
                this.addAriaInput('aria-focus-events', '');
                this.removeActiveClasses();
            },
            addAriaInput(n, t) {
                this.$utils.addAttrb(this.$refs['general-input'], n, t);
            },
            showPassword() {
                this.isShownPassword = !this.isShownPassword;
                let el = this.$refs['general-input'];
                setTimeout(() => {
                    this.$utils.setCaretPosition(el, (el.value || []).length);
                }, 250);

            },
            emit() {
                this.$emit('send', this.inputText);
                this.$emit('input', this.inputText);
                this.setIsNotEmptyClass();
            },
            emitEnter() {
                this.$emit('inputEnter', this.inputText);
            },
        },
        created() {
            this.inputText = this.value;
            this.emit();
        },
        mounted() {
            this.$refs['general-input'].onfocus = (e) => {
                this.setFocus();
            };
            this.$refs['general-input'].onblur = (e) => {
                this.setBlur();
            };
        }
    }
</script>

<style scoped>

</style>
