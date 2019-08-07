<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <div class="admin-master-detail-card">
                        <div class="md-single-grid assessment-form">
                            <div ad-cell="12" class="theme-blue absolute-parent clearfix">
                                <FormTop ref="AssessmentForm" @SavedEditAssessment="()=> { this.formChanged = false}"/>
                                <div class="ViewPagePageBreakGap">
                                    <label class="ViewPageGoToPageSelectLabel">
                                        Continue to question sections
                                    </label>
                                </div>

                                <div class="questions-section clearfix">
                                    <div class="questions">

                                        <div class="FormeditorViewFatDesktop">
                                            <div class="ViewFatPositioner" ref="ActionPositioner">
                                                <div class="ViewFatCard">
                                                    <ActionFormeditor
                                                        :disabled="disableTooltip"
                                                        @OnAddQuestion="addSectionQuestion()"
                                                        @OnAddSection="AddSection()"/>
                                                </div>
                                            </div>
                                        </div>

                                        <QuestionsSection
                                            class="clearfix"
                                            v-for="(sItem, sIdx ) in mSectionsStack"
                                            :key="sIdx"
                                            :section="sItem"
                                            :sectionIndex="sIdx"
                                            :total="mSectionsStack.length"
                                            @onSectionClick="setCurrentFocusSectionIndex"
                                            @onDropdownClick="DropdownOptionClick"
                                            :ref="`section-${sIdx}`">

                                            <template slot="questions">
                                                <Questionnaire
                                                    :key="`s-${sIdx}q-`"
                                                    :sectionIndex="sIdx"
                                                    :focusIndex="sItem.focusIndex"
                                                    @onFocusQuestionItemClick="()=>{setPlacePositioner();isSectionQuestionClick=true}"
                                                    @onAddOptionItemClick="addRadioFn(sItem, $event)"
                                                    @onDeleteOptionItemClick="deleteRadioFn(sItem, $event)"
                                                    @onCopyQuestionClick="copyListFn(sItem, $event)"
                                                    @onDeleteQuestionClick="deleteListFn(sItem, $event)">
                                                </Questionnaire>
                                            </template>

                                        </QuestionsSection>

                                    </div>


                                    <div class="ViewFatMobile">
                                        <div class="FormeditorViewFatPositioner">
                                            <div class="FormeditorViewFatCard">
                                                <ActionFormeditor
                                                    :disabled="disableTooltip"
                                                    @OnAddQuestion="addSectionQuestion()"
                                                    @OnAddSection="AddSection()"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AdminBase from '@bases/AdminBase.js'

    import FormTop from '@com/Admin/Assessments/AssessmentForm.vue'
    import QuestionsSection from './Question/Section.vue'
    import Questionnaire from './Question/Questionnaire.vue'
    import ActionFormeditor from './Includes/ActionFormeditor.vue'

    import {mapActions, mapMutations, mapState} from 'vuex'
    import {
        setPlacePositioner,
        getSectionsScrollHeight,
        getSectionFocusQuestionScrollHeight
    } from '../Assets/PlaceElements.js'

    let lineEndOptions = Array.apply(null, Array(9)).map((item, i) => {
        return i + 2
    });

    export default AdminBase.extend({
        name: "CreateAssessment",
        components: {
            FormTop,
            QuestionsSection,
            Questionnaire,
            ActionFormeditor
        },
        data() {
            return {
                title: 'ສ້າງບົດປະເມີນ',
                type: 'none',
                watchers: true,
                tabs: [{name: 'Create Assessment'}],
                formChanged: false,
                isSectionQuestionClick: false,
                disableTooltip: false,
                positioner: null,
                targetViewPort: null,
                //@form
                selectOptions: [
                    {name: 'Short answer', value: 'short_answer'},
                    {name: 'Paragraph', value: 'paragraph'},
                    {name: 'Multiple choice', value: 'multiple_choice'},
                    {name: 'Checkboxes', value: 'checkboxes'},
                    {name: 'Dropdown', value: 'dropdown_list'},
                    {name: 'Linear scale', value: 'linear_scale'},
                    {name: 'Matrix scale', value: 'matrix_scale'},
                    {name: 'Priority', value: 'priority'}
                ],
                defaultOption: 'multiple_choice',
                addRadio: 'Add option',
                lineOptions: [0, 1],
                lineEndOptions: lineEndOptions,
                undoRedo: {undo: false, redo: false},
                //@end-form
            }
        },
        computed: {
            ...mapState(['mEditAssessment', 'mAssessment', 'mSectionsStack', 'mInitEmptyStateCalled', 'currentFocusIndexes']),
        },
        watch: {
            'currentFocusIndexes.questionIndexes': {
                deep: true,
                handler: function (n) {
                    (this.mSectionsStack[n.sectionIndex] || {}).focusIndex = n.questionIndex;
                    this.setPlacePositioner();
                }
            },
            mAssessment: {
                deep: true,
                handler: function (n) {
                    if (this.mEditAssessment) {
                        this.$refs['AssessmentForm'].AutoSaveEditAssessmentHandle();
                    }
                    if (!this.mInitEmptyStateCalled) {
                        this.formChanged = true;
                    }
                }
            },
            mSectionsStack: {
                deep: true,
                handler: function (n) {

                    if (this.mEditAssessment && !this.isSectionQuestionClick) {
                        this.$refs['AssessmentForm'].AutoSaveEditAssessmentHandle();
                    }
                    if (!this.mInitEmptyStateCalled) {
                        this.formChanged = true;
                    }
                    if (this.isSectionQuestionClick) {
                        this.isSectionQuestionClick = false;
                    }
                }
            },
            '$route.query': function (n) {
                this.initAssessmentData();
            }
        },
        methods: {
            ...mapMutations([
                'setUndoRedoHistory',
                'addSectionDataStack', 'deleteSectionDataStack',
                'addSectionQuestionDataStack', 'deleteSectionQuestionDataStack',
                'addRadioOptionQuestionDataStack', 'deleteRadioOptionQuestionDataStack',
                'setEditAssessmentStatus', 'setDefaultAssessmentEmptyState',
                'setCurrentFocusSectionIndex', 'setCurrentFocusQuestionIndex',
            ]),
            ...mapActions(['fetchAssessment',]),
            /**@ON_REDO_UNDO*/
            onUndo({undone}) {
                this.undoRedo.undo = true;
                this.undoRedo.redo = false;
                if (undone.length) {
                    let lastCheckPoint = undone[undone.length - 1],
                        payload = lastCheckPoint.payload;
                    if (lastCheckPoint.type === 'addSectionDataStack') {
                        this.setCurrentFocusSectionIndex(payload.index - 1)
                    } else if (lastCheckPoint.type === 'addSectionQuestionDataStack') {
                        this.setCurrentFocusQuestionIndex({
                            sectionIndex: payload.sectionIndex,
                            questionIndex: payload.focusIndex - 1
                        })
                    } else if (lastCheckPoint.type === "deleteSectionQuestionDataStack") {
                        this.setCurrentFocusQuestionIndex({
                            sectionIndex: payload.sectionIndex,
                            questionIndex: payload.focusIndex + 1
                        })
                    } else if (lastCheckPoint.type === "setAssessmentTextValueChangeDataStack") {
                        payload.el.focus();
                        this.$utils.scrollToY('main-container', payload.el.offsetTop);
                    } else if (lastCheckPoint.type === "setTextValueChangeDataStack") {
                        payload.el.focus();
                        let className = String(payload.el.className).replace(' ', '.');
                        if (this.$utils.isEmptyVar(className)) {
                            return;
                        }
                        let focusEls = this.jq(`.${className}`);
                        if (focusEls.length) {
                            let focusEl = focusEls[(payload.schema || {}).question_index];
                            if (focusEl) {
                                focusEl.focus();
                            }
                        }
                    } else if (lastCheckPoint.type === "addRadioOptionQuestionDataStack") {
                        this.$nextTick(() => {
                            let input = document.getElementById(payload.id_schema + (payload.length - 1));
                            if (input)
                                input.focus();
                        });
                    } else if (lastCheckPoint.type === "deleteRadioOptionQuestionDataStack") {
                        this.$nextTick(() => {
                            let input = document.getElementById(payload.id_schema + (payload.index));
                            if (input)
                                input.focus();
                        });
                    }
                }
            },
            onRedo({done}) {
                this.undoRedo.undo = false;
                this.undoRedo.redo = true;
                if (done.length) {
                    let lastCheckPoint = done[done.length - 1],
                        payload = lastCheckPoint.payload;
                    if (lastCheckPoint.type === 'addSectionDataStack') {
                        this.setCurrentFocusSectionIndex(payload.index)
                    } else if (lastCheckPoint.type === 'addSectionQuestionDataStack') {
                        this.setCurrentFocusQuestionIndex({
                            sectionIndex: payload.sectionIndex,
                            questionIndex: payload.focusIndex
                        })
                    } else if (lastCheckPoint.type === "deleteSectionQuestionDataStack") {
                        this.setCurrentFocusQuestionIndex({
                            sectionIndex: payload.sectionIndex,
                            questionIndex: payload.focusIndex
                        })
                    } else if (lastCheckPoint.type === "setAssessmentTextValueChangeDataStack") {
                        payload.el.focus();
                        this.$utils.scrollToY('main-container', payload.el.offsetTop);
                    } else if (lastCheckPoint.type === "setTextValueChangeDataStack") {
                        payload.el.focus();
                        let className = String(payload.el.className).replace(' ', '.');
                        if (this.$utils.isEmptyVar(className)) {
                            return;
                        }
                        let focusEls = this.jq(`.${className}`);
                        if (focusEls.length) {
                            let focusEl = focusEls[(payload.schema || {}).question_index];
                            if (focusEl) {
                                focusEl.focus();
                            }
                        }
                    } else if (lastCheckPoint.type === "addRadioOptionQuestionDataStack") {
                        this.$nextTick(() => {
                            let input = document.getElementById(payload.id_schema + (payload.length - 1));
                            if (input)
                                input.focus();
                        });
                    } else if (lastCheckPoint.type === "deleteRadioOptionQuestionDataStack") {
                        this.$nextTick(() => {
                            let input = document.getElementById(payload.id_schema + (payload.index - 1));
                            if (input)
                                input.focus();
                        });
                    }
                }
            },
            /**@END_ON_REDO_UNDO*/
            /**@END_SECTION QUESTIONS*/
            AddSection() {
                let index = this.currentFocusIndexes.sectionIndex,
                    schema = {
                        id: null,
                        title: '',
                        desc: '',
                        focusIndex: -1,
                        questions: []
                    };
                this.addSectionDataStack({component: this, schema, index: index + 1});
            },
            DropdownOptionClick(schema) {
                let index = schema.sectionIndex;
                if (schema.action === 'create') {
                    this.AddSection();
                }
                if (schema.action === 'duplicate') {
                    let data = JSON.parse(JSON.stringify(this.mSectionsStack[index]));
                    //for editing data
                    data.id = null;
                    data.questions.map((item) => {
                        item.id = null;
                    });
                    //for editing data
                    //set section stacking data
                    this.addSectionDataStack({component: this, schema: data, index, type: 'duplicate'});
                }
                if (schema.action === 'delete') {
                    this.deleteSectionDataStack({component: this, index});
                    this.unDoToast();
                }
            },
            /**@END_SECTION QUESTIONS*/
            addSectionQuestion() {
                let section = this.mSectionsStack[this.currentFocusIndexes.sectionIndex];
                //@disable tooltip when add question positioner click
                this.disableTooltip = true;

                if (!section) {
                    return;
                }
                //content list purpose for multiple language
                let contentList = [
                    {
                        language: 'en',
                        title: '',
                        option_answers: [{
                            description: 'Option 1'
                        }],
                        row_option_answers: [{
                            description: 'Option 1'
                        }],
                        line_answer: {
                            line_value: 1,
                            line_end_value: 5,
                            line_tag: '',
                            line_end_tag: ''
                        },
                        text_answer: ''
                    }
                ];

                let index = section.focusIndex,
                    list = {
                        id: null,
                        types: 'multiple_choice',
                        is_required: false,
                        content: contentList
                    };
                //set section stacking data
                this.addSectionQuestionDataStack({
                    component: this,
                    sectionIndex: this.currentFocusIndexes.sectionIndex,
                    index: index + 1,
                    focusIndex: index + 1,
                    list
                });
            },
            copyListFn(section, question_idx) {
                let data = JSON.parse(JSON.stringify(section.questions[question_idx]));
                data.id = null;//for editing data
                this.addSectionQuestionDataStack({
                    component: this,
                    sectionIndex: this.currentFocusIndexes.sectionIndex,
                    index: question_idx,
                    focusIndex: question_idx + 1,
                    list: data
                });
            },
            deleteListFn(section, question_idx) {
                let focusIndex = section.questions.length - 1 <= 0 ? -1 : question_idx === 0 ? 0 : question_idx - 1;
                this.deleteSectionQuestionDataStack({
                    component: this,
                    sectionIndex: this.currentFocusIndexes.sectionIndex,
                    index: question_idx,
                    focusIndex
                });
                setTimeout(() => {
                    //set positioner position
                    this.setPlacePositioner();
                    this.unDoToast();
                }, 20);
            },
            /**@RADIO_QUESTION */
            addRadioFn(section, option_schema) {
                this.addRadioOptionQuestionDataStack({
                    sectionIndex: this.currentFocusIndexes.sectionIndex,
                    question_index: option_schema.question_idx,
                    option_answer_index: option_schema.answer_schema_index,
                    full_id_schema: option_schema.new_id_idx,
                    id_schema: option_schema.id_schema,
                    length: option_schema.length,
                    type: option_schema.type
                });
                this.$nextTick(() => {
                    let input = document.getElementById(option_schema.new_id_idx);
                    if (input)
                        input.focus();
                });
            },
            deleteRadioFn(section, option_schema) {
                this.deleteRadioOptionQuestionDataStack({
                    sectionIndex: this.currentFocusIndexes.sectionIndex,
                    question_index: option_schema.question_idx,
                    option_answer_index: option_schema.answer_schema_index,
                    index: option_schema.index,
                    id_schema: option_schema.id_schema,
                    length: option_schema.length,
                    type: option_schema.type
                });
                let focusOptionIndex = option_schema.index === 0 ? 0 : option_schema.index - 1;
                this.$nextTick(() => {
                    let input = document.getElementById(option_schema.id_schema + focusOptionIndex);
                    if (input)
                        input.focus();
                });
            },
            /**@END_RADIO_QUESTION */
            setPlacePositioner() {
                this.$nextTick(() => {
                    setPlacePositioner(this);
                    //@enable tooltip
                    this.disableTooltip = false;
                });
            },
            setSectionFocusItemPositioner() {
                this.$nextTick(() => {
                    if (this.undoRedo.undo) {
                        this.undoRedo.undo = false;
                        return;
                    }
                    this.$utils.scrollToY('main-container', getSectionsScrollHeight(this));
                });
            },
            setSectionQuestionFocusItemPositioner() {
                this.$nextTick(() => {
                    this.$utils.scrollToY('main-container', getSectionFocusQuestionScrollHeight(this));
                });
            },
            scrollHandler() {
                this.setPlacePositioner();
            },
            keyHandler(e) {
                const key = e.which || e.keyCode;
                let ac = document.activeElement;
                if (ac.className.indexOf('el-input__inner') !== -1) {//except for select options
                    return;
                }
                if (e.ctrlKey && key === 90) {//ctrl + z undo
                    e.preventDefault();
                    if (this.canUndo) {
                        this.undo();
                    }
                } else if (e.ctrlKey && key === 89) {//ctrl + y redo
                    e.preventDefault();
                    if (this.canRedo) {
                        this.redo();
                    }
                }
            },
            registerHandler() {
                this.$on('onRedo', this.onRedo);
                this.$on('onUndo', this.onUndo);
                this.Event.listen('scrolling', this.scrollHandler);
                window.addEventListener('keydown', this.keyHandler)
            },
            unRegisterHandler() {
                this.$off('onRedo', this.onRedo);
                this.$off('onUndo', this.onUndo);
                this.Event.offListen('scrolling', this.scrollHandler);
                window.removeEventListener('keydown', this.keyHandler);
            },
            unDoToast() {
                this.$toasted.show('Item deleted', {
                    duration: 4500,
                    action: {
                        text: 'Undo',
                        onClick: (e, t) => {
                            t.goAway(0);
                            if (this.canUndo) {
                                this.undo();
                            }
                        }
                    }
                });
            },
            beforeUnload() {
                window.addEventListener("beforeunload", (event) => {
                    if (this.formChanged) {
                        (event || window.event).returnValue = "Are you sure you want to leave?";
                    }
                });
            },
            getAssessment() {
                let id = this.$route.query.assessment_id;
                this.title = 'Edit Assessment';
                this.tabs[0].name = 'Assessment';
                this.setPageTitle(this.title);

                this.fetchAssessment({id})
                    .then(res => {
                        this.formChanged = false;
                        if (!res.success) {
                            this.Route({name: 'assessment'});
                        }
                    }).catch(err => {
                })
            },
            initAssessmentData() {
                this.done = [];
                this.undone = [];
                if (this.$route.query.assessment_id) {
                    this.getAssessment();
                } else {
                    this.setEditAssessmentStatus(false);
                    this.setDefaultAssessmentEmptyState();
                    this.setCurrentFocusSectionIndex(0);
                }
            }
        },
        mounted() {
            this.positioner = this.$refs['ActionPositioner'];
            this.targetViewPort = this.jq('#main-container');
            this.registerHandler();
            this.beforeUnload();
        },
        beforeRouteLeave(to, from, next) {
            if (this.formChanged) {
                let a = confirm('Are you sure you want to leave?');
                if (a) {
                    this.formChanged = false;
                    next();
                } else {
                    return;
                }
            }
            if (this.mEditAssessment) {
                this.initAssessmentData();
            }
            next();
        },
        beforeDestroy() {
            this.unRegisterHandler();
        },
        created() {
            this.setPlacePositioner = this.debounce(this.setPlacePositioner, 100);
            this.setSectionFocusItemPositioner = this.debounce(this.setSectionFocusItemPositioner, 100);
            this.setSectionQuestionFocusItemPositioner = this.debounce(this.setSectionQuestionFocusItemPositioner, 100);
            this.initAssessmentData();
        },
    });
</script>
<style lang="scss">
    @import "../Assets/reset.scss";
    @import url('../Assets/iconfont/iconfont.css');
    @import "../Assets/create-wrapper.scss";

    .md-single-grid.assessment-form {
        min-height: 799px;
    }
    .questions-section.clearfix {
        top: auto;
    }
</style>
