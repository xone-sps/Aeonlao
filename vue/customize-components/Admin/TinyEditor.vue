<template>
    <div :class="[containerClass]" class="form-input-container flex editor">
        <AdminInput
            v-model="tinyMCE.content"
            @inputMounted="registerTinyMCE"
            :id="id"
            :label="label"
            containerClass=""
            customClass="editor"
            :inputType="'textarea'"/>

        <div class="form-input-container" v-if="validateText!==''">
            <div class="input-alias-error"></div>
            <div admin-messages>
                <div admin-message class="message-required ">
                    <!--describe-->
                    {{ validateText }}
                </div>
            </div>
        </div>

        <template v-if="modal.show">
            <div class="my-modal s" id="modal-image">
                <div class="columns is-multiline">
                    <div class="column is-12-desktop is-12-tablet is-12-mobile">
                        <div class="modal-c">
                            <div class="head">
                                <label class="label my-label-ac">Image Manager {{ modal.msg==='' ? '' : ' | ' +
                                    modal.msg
                                    }}</label>
                                <hr>
                                <div class="md-close" @click="closeModal">
                                    <a class="modal-image-close v-md-button v-md-icon-button" title="Close this">
                                        <i class="material-icons">close</i>
                                    </a>
                                </div>
                            </div>
                            <div class="control-file">
                                <div class="form-group l">
                                    <!--Spinner Loading-->
                                    <SpinnerLoading v-if="isLoading"/>
                                    <!--Spinner Loading-->
                                    <a @click='triggerClick()'
                                       class="v-md-button v-md-icon-button"><i
                                        class="material-icons">cloud_upload</i></a>
                                    <a @click="getImages"
                                       class="v-md-button v-md-icon-button"><i class="material-icons">refresh</i></a>
                                    <a class="v-md-button v-md-icon-button"
                                       @click="deleteImages"><i class="material-icons">
                                        delete
                                    </i></a>
                                    <input multiple="multiple" type="file" accept="image/*" ref="uploadImages"
                                           class="is-not-show" @change="saveImages">
                                </div>
                                <div class="inps">
                                    <input v-if="modal.openFileDialog" type="text" class="input" id="i-search"
                                           placeholder="Search Image name, date, time" v-model="modal.search">
                                    <i class="material-icons f-ico">search</i>
                                </div>
                            </div>
                        </div>
                        <div class="content-imgs">
                            <ul class="assets">
                                <li class="asset-file" v-for="(c, index) in inputFilter" :key="index">
                                    <div class="asset-file-content" @click="setImage(`${baseUrl}${imageUrl}${c.src}`)">
                                        <img :src="`${baseUrl}${imageUrl}${c.src}`" alt="">
                                    </div>
                                    <label class="container-checkbox img" v-if="modal.checkboxes.length>0">
                                        <input type="checkbox" v-model="c.status">
                                        <span class="my-chkd img"></span>
                                        <p> {{ c.real_name }} </p>
                                    </label>
                                </li>
                            </ul>
                            <!--<div class="columns is-multiline">-->
                            <!--<div v-for="(c, index) in inputFilter" :key="index"-->
                            <!--class="column is-2-desktop is-3-tablet is-12-mobile-->
                            <!--no-padding-left no-padding-right"-->
                            <!--@click="setImage(`${baseUrl}${imageUrl}${c.src}`)">-->
                            <!--<div class="thum-container">-->
                            <!--<div class="thum">-->
                            <!--<img :src="`${baseUrl}${imageUrl}${c.src}`" alt="">-->
                            <!--</div>-->
                            <!--</div>-->
                            <!--<label class="container-checkbox img" v-if="modal.checkboxes.length>0">-->
                            <!--<input type="checkbox" v-model="c.status">-->
                            <!--<span class="my-chkd img"></span>-->
                            <!--<p> {{ c.real_name }} </p>-->
                            <!--</label>-->
                            <!--</div>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="s" id="modal-mask" @click="maskClick()"></div>
        </template>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapMutations, mapState} from 'vuex'

    export default {
        props: {
            value: {},
            id: {
                type: String,
                default: 'editor_tiny',
            },
            label: {
                type: String,
                default: 'Description'
            },
            containerClass: {
                type: String,
                default: ''
            },
            validateText: {
                type: String,
                default: ''
            }
        },
        data: () => ({
            ...mapGetters(['getToken']),
            tinyMCE: {editor: tinyMCE, options: tinyMceOptions, content: ``, ready: false},
            imageUrl: '/assets/images/posts/',
            isLoading: false,
            timer: 0,
            modal: {
                msg: '',
                overlayClass: 'image-picker-overlay',
                openFileDialog: false,
                show: false,
                search: '',
                data: [],
                files: [],
                checkboxes: []
            },
        }),
        watch: {
            'value': function (n, o) {
                this.tinyMCE.content = n;
            },
            'tinyMCE.ready': function (n, o) {
                if (n) {
                    this.setEditorContent(this.value);
                }
            }
        },
        computed: {
            ...mapState(['isMobile']),
            inputFilter() {
                let filters = this.modal.data, c = this.$utils.escapeRegExp(this.modal.search);
                const pattern = new RegExp(c, 'i');
                return filters.filter(c => {
                    return pattern.test(c.name) | pattern.test(c.real_name) | pattern.test(c.in_uses) | pattern.test(c.id) | pattern.test(c.created_at) | pattern.test(c.updated_at)
                })
            }
        },
        methods: {
            ...mapMutations(['setClearMsg']),
            ...mapActions(['HandleError']),
            setEditorContent(content) {
                let editor = this.tinyMCE.editor, currentEditor = editor.get(this.id);
                if (!this.$utils.isEmptyVar(editor)) {
                    if (!this.$utils.isEmptyVar(currentEditor)) {
                        content = content || "";
                        currentEditor.setContent(content);
                        this.tinyMCE.content = content;
                        this.$emit('postSetContent');
                        this.emitContent();
                    }
                    editor.triggerSave();
                }
            },
            setTinyMceContent() {
                let editor = this.tinyMCE.editor, currentEditor = editor.get(this.id);
                if (!this.$utils.isEmptyVar(editor)) {
                    if (!this.$utils.isEmptyVar(currentEditor)) {
                        this.tinyMCE.content = currentEditor.getContent();
                        this.emitContent();
                    }
                    editor.triggerSave();
                }
            },
            watchers() {
                this.setTinyMceContent();
            },
            registerTinyMCE() {
                let editor = this.tinyMCE.editor, currentEditor;
                if (!this.$utils.isEmptyVar(editor)) {
                    currentEditor = editor.get(this.id);
                    if (!this.$utils.isEmptyVar(currentEditor)) {
                        currentEditor.remove();
                        this.tinyMCE.editor.execCommand("mceRemoveEditor", false, this.id);
                    }
                    this.tinyMCE.options.setup = (ed) => {
                        ed.on('keyup', (e) => {
                            this.watchers();
                        });
                        ed.on('change', (e) => {
                            this.watchers();
                        });
                        this.uploadImage(ed);
                        this.callback();
                    };
                    editor.init(this.tinyMCE.options);
                }
            },
            setDefaultValue() {
                this.tinyMCE.content = this.value;
            },
            emitContent() {
                this.$emit('send', this.tinyMCE.content);
                this.$emit('input', this.tinyMCE.content);
            },
            /** Plugins **/
            getEditor() {
                let editor = this.tinyMCE.editor;
                return editor.get(this.id);
            },
            callback() {
                let currentEditor = this.getEditor();
                this.$utils.clearTOut(this.timer);
                this.timer = setTimeout(() => {
                    if (this.$utils.isEmptyVar(currentEditor) || this.$utils.isEmptyVar(currentEditor.dom)) {
                        this.callback();
                    } else {
                        currentEditor.dom.addClass(currentEditor.dom.select('p'), 'content_texts');
                        this.jq(".mce-branding.mce-widget.mce-label.mce-flow-layout-item.mce-last").remove();
                        this.$emit('editorMounted', this);
                        this.tinyMCE.ready = true;
                    }
                }, 100);
            },
            // end callback set tiny
            //upload image
            uploadImage(ed) {
                ed.addButton('myimageupload', {
                    text: "",
                    icon: "browse",
                    onclick: (e) => {
                        this.showModal();
                    }
                });
            },
            //upload image
            //show modal
            addBodyClass() {
                this.jq('html').addClass(this.modal.overlayClass);
                this.jq('body').addClass(this.modal.overlayClass);
            },
            removeBodyClass() {
                this.jq('html').removeClass(this.modal.overlayClass);
                this.jq('body').removeClass(this.modal.overlayClass);
            },
            showModal() {
                this.modal.openFileDialog = true;
                this.modal.show = true;
                this.addBodyClass();
            },
            closeModal() {
                this.modal.show = false;
                this.modal.msg = '';
                this.modal.openFileDialog = false;
                this.removeBodyClass();
            },
            triggerClick() {
                const trigger = this.$refs.uploadImages;
                trigger.click()
            },
            maskClick() {
                this.closeModal()
            },
            deleteImages() {
                let all_selected = this.modal.checkboxes.filter(c => {
                    return c.status === true
                }), formData;
                if (all_selected.length <= 0) return;
                formData = new FormData();
                for (let j = 0; j < all_selected.length; j++) {
                    formData.append('imgs[]', all_selected[j].id);
                }
                this.postDeleteImages(formData)
                    .then(res => {
                        this.modal.msg = res.msg;
                        this.getImages();
                    })
                    .catch(err => {
                        this.showErrorToast({msg: 'Cannot delete image!', dt: 3500})
                    });

            },
            saveImages() {
                if (this.$refs.uploadImages.files.length <= 0) return;
                this.modal.files = this.$refs.uploadImages.files;
                let formData = new FormData();
                for (let i = 0; i < this.modal.files.length; i++) {
                    let file = this.modal.files[i], fileSize;
                    fileSize = this.$utils.getFileSize(file);
                    if (this.$utils.IsImageFileExtensions(file)
                        && this.$utils.formatBytesTo(fileSize, "MB", 2) <= 5.00) {
                        formData.append('imgs[]', file, this.$utils.getFileNameFromFile(file));
                    }
                }
                this.postUploadImages(formData)
                    .then(res => {
                        this.modal.msg = res.msg;
                        this.getImages();
                    })
                    .catch(err => {
                        this.showErrorToast({msg: 'Cannot upload image!', dt: 3500})
                    });
            },
            getImages() {
                this.isLoading = true;
                this.fetchPostImages()
                    .then(res => {
                        this.modal.data = res;
                        this.modal.checkboxes = res;
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.showErrorToast({msg: 'Cannot get image!', dt: 3500});
                        this.isLoading = false;
                    });
            },
            setImage(url) {
                let currentEditor = this.getEditor(), $editor = currentEditor.dom.doc, length, id;
                length = currentEditor.dom.select('img').length;
                id = `img-${length}`;
                currentEditor.insertContent(`<img id="${id}" alt="my-image-description" class="my-img-posts" src="${url}" data-mce-src="${url}" data-mce-selected="1" />`);
                currentEditor.selection.select($editor.getElementById(id));
                this.closeModal();
            },
            //show modal
            /** Plugins **/
            /*** @PostImages **/
            ajaxToken() {
                return this.ajaxConfig.addHeader('CL-Token', this.getToken());
            },
            fetchPostImages() {
                return new Promise((r, n) => {
                    this.client.get(`${this.apiUrl}/admin/posts/get-images`, this.ajaxToken())
                        .then(res => {
                            this.setClearMsg();
                            r(res.data)
                        })
                        .catch(err => {
                            this.HandleError(err.response);
                            n(err)
                        });
                });
            },
            postUploadImages(data) {
                this.isLoading = true;
                return new Promise((r, n) => {
                    this.client.post(`${this.apiUrl}/admin/posts/upload-images`, data, this.ajaxToken())
                        .then(res => {
                            this.setClearMsg();
                            r(res.data)
                        })
                        .catch(err => {
                            this.HandleError(err.response);
                            n(err)
                        })
                });
            },
            postDeleteImages(data) {
                return new Promise((r, n) => {
                    this.client.post(`${this.apiUrl}/admin/posts/delete-images`, data, this.ajaxToken())
                        .then(res => {
                            this.setClearMsg();
                            r(res.data)
                        })
                        .catch(err => {
                            this.HandleError(err.response);
                            n(err)
                        });
                });
            },
            /*** @PostImages **/
            showErrorToast(data) {
                this.$toasted.error(data.msg, {
                    duration: data.dt,
                    action: {
                        text: 'Close',
                        onClick: (e, t) => {
                            t.goAway(0);
                        }
                    }
                });
            },
            destroyAllTinyMce() {
                //remove Elements
                this.jq('.mce-container.mce-panel').remove();
                this.jq('.mce-reset.mce-fade.mce-in').remove();
                this.jq('.mce-widget').remove();
                //removeClasses
                this.removeBodyClass();
                this.jq('html').removeClass('mce-fullscreen');
                this.jq('body').removeClass('mce-fullscreen');
                //destroy tiny
                this.tinyMCE.editor.execCommand("mceRemoveEditor", false, this.id);
            }
        },
        created() {
            this.setDefaultValue();
            this.emitContent();
            this.getImages();
        },
        beforeDestroy() {
            this.destroyAllTinyMce();
        }
    }
</script>

<style scoped>
    .dense {
        padding-bottom: 24px;
    }

    .form-input-container.flex.editor {
        padding-bottom: 4px;
        padding-top: 0;
    }

    .form-input-container {
        position: relative;
    }

    .input-alias-error {
        display: -webkit-box;
        display: -moz-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        flex: 1;
        width: 100%;
        height: 16px;
        -webkit-box-shadow: 0 -2px 0 #ff5252 inset, 0 0 0 0px #e0e0e0 inset !important;
        box-shadow: 0 -2px 0 #ff5252 inset, 0 0 0 0px #e0e0e0 inset !important;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        position: absolute;
        top: -18px;
    }

</style>
