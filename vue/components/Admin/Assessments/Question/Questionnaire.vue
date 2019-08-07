<template>
    <div>
        <draggable
            v-model="mQuestions"
            group="questions"
            :handle="'.drap-area'"
            :move="onMove"
            @start="onStart"
            @end="onEnd">

            <div v-for="(ques, idx) in mQuestions" class="q-li"
                 :class="{'q-li-focus': isItemFocus(idx)}"
                 :id="`items-sec-${sectionIndex}-ques-${idx}`"
                 :key="`items-sec-${sectionIndex}-ques-${idx}`"
                 :data-schema="`${JSON.stringify({sectionIndex, question_index: idx, hash_id: ques.hash_id})}`"
                 @click="focusItem($event, idx)">

                <div class="drap-area">
                    <i class="iconfont icon-menu-drag-head"></i>
                </div>
                <div class="q-item-wrap"
                     v-for="(content, idx1) in ques.content"
                     :key="`${idx +'-' + idx1}`">
                    <!--Select Question type-->
                    <div class="q-item q-title-wrap">
                        <div class="q-title">
                            <div class="li">
                                <textarea class="q-area" placeholder="Question"
                                          :data-schema="`${JSON.stringify({sectionIndex, question_index: idx, content_index: idx1})}`"
                                          :class="`sec-${sectionIndex}-ques-title--`"
                                          v-model="content.title"
                                          @focus="$autoText($event)"
                                          @input="$autoText($event)"></textarea>
                            </div>
                        </div>

                        <el-select class="q-select" v-if="isItemFocus(idx)"
                                   v-model="ques.types" filterable
                                   @change="registerOptionData($event, {sectionIndex, question_index: idx, key: 'types'})"
                                   placeholder="Types">
                            <el-option
                                v-for="item in selectOptions"
                                :key="item.value"
                                :label="item.name"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </div>
                    <!--Select Question type-->

                    <!--@OptionsQuestionQuestions-->

                    <OptionsQuestion
                        :type="ques.types"
                        :sectionIndex="sectionIndex"
                        :currentSectionIndex="currentFocusIndexes.sectionIndex"
                        :isFocus="isItemFocus(idx)"
                        :options="content.option_answers"
                        :question_idx="idx"
                        :answer_schema_index="idx1"
                        @onDeleteOptionAnswerClick="deleteOptionRadio"
                        @onAddOptionAnswerClick="addOptionRadio"/>

                    <!--@EndOptionsQuestionQuestions-->

                    <!--@TextQuestions-->

                    <div class="q-item text-wrap spaced-bottom non-options"
                         v-if="ques.types === 'short_answer'">Short answer text
                    </div>

                    <div class="q-item text-wrap spaced-bottom non-options"
                         v-if="ques.types === 'paragraph'">Long answer text
                    </div>

                    <!--@EndTextQuestions-->

                    <!--@LinearScaleQuestions|MatrixScaleQuestions-->
                    <ScaleOptionsQuestion
                        :type="ques.types"
                        :sectionIndex="sectionIndex"
                        :currentSectionIndex="currentFocusIndexes.sectionIndex"
                        :isFocus="isItemFocus(idx)"
                        :question_idx="idx"
                        :answer_schema_index="idx1"
                        @onDeleteOptionAnswerClick="deleteOptionRadio"
                        @onAddOptionAnswerClick="addOptionRadio"
                    />
                    <!--@EndLinearScaleQuestions|MatrixScaleQuestions-->

                    <!--@BottomActions-->
                    <div class="q-item option-wrap non-options" v-if="isItemFocus(idx)">
                        <ul class="option-list">
                            <li>
                                <i class="iconfont icon-copy"
                                   @click="copyListFn(idx)"></i>
                            </li>
                            <li>
                                <i class="material-icons"
                                   @click="deleteListFn($event, idx)">delete</i>
                            </li>
                            <li>
                                <div class="group">
                                    <span>Required</span>
                                    <el-switch
                                        @change="registerOptionData($event, {sectionIndex, question_index: idx, key: 'is_required'})"
                                        v-model="ques.is_required"
                                        active-color="rgb(3, 155, 229)"
                                        inactive-color="#b9b9b9">
                                    </el-switch>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--@EndBottomActions-->

                </div>
            </div>

        </draggable>

    </div>
</template>

<script>

    import draggable from 'vuedraggable';
    import OptionsQuestion from './OptionsQuestion.vue';
    import ScaleOptionsQuestion from './ScaleOptionsQuestion.vue';
    import {mapMutations, mapState} from 'vuex'

    export default {
        name: "Questionnaire",
        components: {
            draggable,
            OptionsQuestion,
            ScaleOptionsQuestion
        },
        props: {
            sectionIndex: {
                default: -1
            },
            focusIndex: {
                default: -1,
            }
        },
        computed: {
            ...mapState(['mSectionsStack', 'currentFocusIndexes']),
            mQuestions: {
                get() {
                    return this.mSectionsStack[this.sectionIndex].questions;
                },
                set(data) {
                    let time_group = new Date().getTime();
                    this.setMoveQuestionSectionDataStack({
                        type: 'questions',
                        component: this.$parent.$parent,
                        questions: data,
                        sectionIndex: this.sectionIndex,
                        time_group
                    });
                    if (this.dragDropContext.related && this.dragDropContext.dragged) {
                        let targetComp = this.dragDropContext.related.component.$parent;
                        let dragged = this.dragDropContext.dragged.element;
                        let placedIndex = targetComp.mQuestions.findIndex((item) => {
                            return item.hash_id === dragged.hash_id;
                        });
                        if (placedIndex !== -1) {
                            this.setMovementQuestionSectionDataStack({
                                sectionIndex: targetComp.sectionIndex,
                                questionIndex: placedIndex,
                                time_group
                            });
                        }
                    }
                }
            }
        },
        watch: {
            mQuestions: function (n, o) {
                this.$nextTick(() => {
                    this.registerTextData();
                })
            }
        },
        data: () => ({
            dragging: false,
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
            dragDropContext: {},
        }),
        methods: {
            ...mapMutations(['setMoveQuestionSectionDataStack', 'setCurrentFocusQuestionIndex', 'setMovementQuestionSectionDataStack']),
            onMove({relatedContext, draggedContext}) {
                this.dragDropContext = {related: relatedContext, dragged: draggedContext};
                this.setCurrentFocusQuestionIndex({
                    sectionIndex: this.sectionIndex,
                    questionIndex: relatedContext.index
                });
                return true;
            },
            onStart(e) {
                this.dragging = true;
            },
            onEnd({from, to}) {
                this.dragging = false;
            },
            focusItem(event, i) {
                let classList = event.target.classList;
                if (classList.contains('el-icon-delete') || classList.contains('icon-copy') ||
                    (this.focusIndex === i && this.currentFocusIndexes.questionIndexes.sectionIndex === this.sectionIndex)) return;
                this.setCurrentFocusQuestionIndex({
                    sectionIndex: this.sectionIndex,
                    questionIndex: i
                });
            },
            isItemFocus(idx) {
                return this.focusIndex === idx && this.currentFocusIndexes.questionIndexes.sectionIndex === this.sectionIndex;
            },
            addOptionRadio(data_schema_index) {
                this.$emit('onAddOptionItemClick', data_schema_index)
            },
            deleteOptionRadio(data_schema_index) {
                this.$emit('onDeleteOptionItemClick', data_schema_index)
            },
            copyListFn(question_idx) {
                this.$emit('onCopyQuestionClick', question_idx)
            },
            deleteListFn($event, question_idx) {
                $event.stopPropagation();
                this.$emit('onDeleteQuestionClick', question_idx)
            },
            setTextValueChanged(el, key, value, schema) {
                let parseSchema = this.$utils.parseJSON(schema);
                this.$store.commit('setTextValueChangeDataStack', {
                    sectionIndex: this.sectionIndex,
                    type: 'question_title',
                    value: value,
                    key: key,
                    el: el,
                    schema: parseSchema,
                })
            },
            assignSchemaTextData(o) {
                this.setTextValueChanged(o.target, 'title', o.target.value, o.target.getAttribute('data-schema'));
            },
            registerTextData() {
                let classSchema = `.sec-${this.sectionIndex}-ques-title--`,
                    els = this.jq(classSchema);
                els.each((idx, item) => {
                    item.removeEventListener('input', this.assignSchemaTextData, true);
                    item.addEventListener('input', this.assignSchemaTextData, true);
                });
            },
            registerOptionData(d, schema) {
                this.$store.commit('setOptionValueChangeDataStack', {value: d, schema})
            }
        },
        mounted() {
            this.registerTextData();
        },
        created() {
            this.setTextValueChanged = this.debounce(this.setTextValueChanged, 200);
        }
    }
</script>

<style scoped>

</style>
