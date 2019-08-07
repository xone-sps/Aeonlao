<template>
    <div class="form-input-container flex" :class="[containerClass, hasError() !=='' ? 'remove-dense' : '']">
        <label> {{ label }} </label>
        <slot></slot>
        <template v-if="inputType==='textarea'">
            <textarea :id="id" full :rows="rows"
                      ref="main-input" @keydown.enter="emitInputEnter"
                      v-model="inputText" :autocomplete="autoCompleteText"
                      :disabled="isDisabled"
                      :type="[inputType]"
                      class="admin-input"
                      :placeholder="placeholder"
                      :class="[customClass, hasError()]"
            >{{inputText}}</textarea>
        </template>
        <template v-else-if="inputType==='select'">
            <select :id="id" full
                    class="admin-select admin-select-rows" v-model="inputText"
                    ref="main-input"
                    :disabled="isDisabled"
                    :class="[customClass, hasError()]">
                <option :value="o.value" v-for="(o, i) in options">{{o.text}}</option>
            </select>
        </template>
        <template v-else-if="inputType==='file'">
            <input
                @click="triggerInputClick"
                readonly
                v-model="inputText"
                full
                :disabled="isDisabled"
                type="text"
                class="admin-input"
                :placeholder="placeholder"
                :class="[customClass, hasError()]"/>
            <input :id="id" @change="onFileSelected" ref="main-input" type="file" v-show="false"/>
        </template>
        <template v-else-if="inputType==='checkbox'">
            <div class="checklist-items-list">
                <div class="checklist-item">
                    <div class="checklist-item-checkbox" :class="[{'is-disabled': isDisabled}]">
                        <label class="icon-check" :for="'main-input-' + _uid"> </label>
                        <input :id="'main-input-' + _uid"  v-model="inputText" ref="main-input" type="checkbox"/>
                        <i class="material-icons icon-check">done</i>
                    </div>
                    <div class="checklist-item-details">
                        <div class="checklist-item-row">
                            <span @click="triggerInputClick"
                                  class="checklist-item-details-text markeddown">{{ label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <input :id="id" ref="main-input" @keydown.enter="emitInputEnter"
                   v-model="inputText" :autocomplete="autoCompleteText"
                   full
                   :disabled="isDisabled"
                   :type="[inputType]"
                   class="admin-input"
                   :placeholder="placeholder"
                   :class="[customClass, hasError()]">
        </template>
        <template v-if="hasSlot('message-error')">
            <div admin-messages>
                <div admin-message class="message-required ">
                    <slot name="message-error"></slot>
                </div>
            </div>
        </template>
        <template v-else>
            <div admin-messages>
                <div v-if="validateText!==''" admin-message class="message-required ">
                    <!--describe-->
                    {{ validateText }}
                </div>
            </div>
        </template>

    </div>
</template>

<script>
    export default {
        props: {
            value: {},
            focus: {
                type: Boolean,
                default: false,
            },
            originData: {
                required: false
            },
            id: {
                type: String,
                default: ''
            },
            customClass: {
                type: String,
                default: ''
            },
            containerClass: {
                type: String,
                default: ''
            },
            inputType: {
                type: String,
                default: 'text'
            },
            placeholder: {
                required: false,
            },
            isDisabled: {
                type: Boolean,
                default: false
            },
            isInputEnterOnce: {
                type: Boolean,
                default: true,
            },
            label: {
                type: String,
                default: 'Label'
            },
            autoCompleteText: {
                type: String,
                default: 'none'
            },
            rows: {
                default: 10,
                required: false,
            },
            options: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            validateText: {
                type: String,
                default: ''
            }
        },
        data: () => ({
            inputText: '',
            originalInputText: '',
            oldInput: '',
        }),
        watch: {
            'value': function (n, o) {
                this.inputText = n;
            },
            inputText: function (n) {
                this.emits();
            }
        },
        methods: {
            hasError() {
                return (this.validateText !== '' || this.hasSlot('message-error')) ? 'invalid' : ''
            },
            emitInputEnter(e) {
                e.preventDefault();
                if (this.inputText === this.oldInput && this.isInputEnterOnce) return;
                this.$emit('onInputEnter', this.inputText);
                this.oldInput = this.inputText;
            },
            emits() {
                this.$emit('send', this.inputText);
                this.$emit('input', this.inputText);
            },
            inits() {
                this.inputText = !this.$utils.isEmptyVar(this.originData) ? this.originData : this.value;
                this.emits();
            },
            triggerInputClick() {
                this.$refs['main-input'].click();
            },
            onFileSelected() {
                let inputFile = this.$refs['main-input'], file, fileSize, fileName, fileBase64;
                if (inputFile.files.length <= 0) return;
                file = inputFile.files[0];
                fileSize = this.$utils.getFileSize(file);
                if (this.$utils.IsImageFileExtensions(file)) {
                    this.$utils.createFileBase64(file, base64 => {
                        fileBase64 = base64;
                        this.$emit('inputImageBase64', fileBase64);
                    });
                }
                fileName = this.$utils.getFileNameFromFile(file);
                this.$emit('inputFile', {file, fileSize, fileName});
                this.inputText = fileName;
            }
        },
        mounted() {
            //emit if view created
            this.$emit('inputMounted');
            //set focus input
            if (this.focus && !this.$utils.isEmptyVar(this.$refs['main-input']))
                this.$refs['main-input'].focus();
        },
        created() {
            this.inits();
        }
    };
</script>
<style scoped>
    ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: rgba(0, 0, 0, 0.4);
    }

    ::-moz-placeholder { /* Firefox 19+ */
        color: rgba(0, 0, 0, 0.4);
    }

    :-ms-input-placeholder { /* IE 10+ */
        color: rgba(0, 0, 0, 0.4);
    }

    :-moz-placeholder { /* Firefox 18- */
        color: rgba(0, 0, 0, 0.4);
    }
</style>
