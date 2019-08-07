<template>
    <div class="p-question">
        <div class="q-li">
            <div class="q-item-wrap"
                 v-for="(content, idx) in question.content" :key="`${question_index +'-' + idx}`">

                <div class="q-item q-title-wrap">
                    <div class="q-title">
                        <div class="q-item-title">
                            {{ content.title }} <span v-if="question.is_required" class="RequiredAsterisk"
                                                      aria-label="Required question">*</span>
                            <span class="checking-status">
                                 <el-tooltip transition="scale-tran"
                                             :content="mSectionsAssessmentAnswer[section_index].answers[question_index].status_approved ? 'Make as Checking': 'Make as Success'"
                                             placement="right">
                                       <el-checkbox
                                           @change="$emit('onQuestionItemStatusChange', {section_index, question_index, status: mSectionsAssessmentAnswer[section_index].answers[question_index].status_approved})"
                                           v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].status_approved"
                                           class="q-radio">
                                        </el-checkbox>
                                 </el-tooltip>
                            </span>
                        </div>
                    </div>
                </div>

                <OptionsQuestion
                    :editable="editable"
                    :section_index="section_index"
                    :question_index="question_index"
                    :content="content"
                    :type="question.types"
                    :options="content.option_answers"
                    :key="idx"/>

                <div class="q-item text-wrap spaced-bottom non-options preview-item" :aria-disabled="!editable"
                     v-if="question.types === 'short_answer'">

                    <div class="short-answer-text">
                        <input :disabled="!editable"
                               v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language]"
                               type="text" placeholder="Your answer">
                    </div>
                </div>

                <div class="q-item"
                     v-if="question.types === 'paragraph'">
                    <div class="paragraph-answer-text">
                        <div :aria-disabled="!editable" class="li main-form">
                            <textarea placeholder="Your answer"
                                      :disabled="!editable"
                                      @focus="$autoText($event)"
                                      @input="$autoText($event)"
                                      v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language]"
                                      :data-schema="`${JSON.stringify({sectionIndex: section_index, questionIndex: question_index, language: content.language })}`"
                                      :ref="`assessment-desc-text-answer-${section_index}-${question_index}-${content.language}`"></textarea>
                        </div>
                    </div>
                </div>


                <div class="q-item"
                     v-if="question.types === 'linear_scale'">
                    <div class="linear-scale-answer">
                        <div class="columnRage">
                            <div class="ragePlaceholder"></div>
                            <div class="ragePlaceholder">
                                <div class="ScalecontentRangeLabel">
                                    {{content.line_answer.line_tag}}
                                </div>
                            </div>
                        </div>
                        <label class="ScalecontentColumn"
                               v-for="(item, idx) in line_answer_linear_scale(content.line_answer)" :key="idx">
                            <div class="ragePlaceholder ragePlaceholder-padding">{{ item }}</div>
                            <div class="ragePlaceholder ScalecontentInput">
                                <el-radio
                                    :disabled="!editable"
                                    v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language]"
                                    :label="item">&nbsp;
                                </el-radio>
                            </div>
                        </label>
                        <div class="columnRage">
                            <div class="ragePlaceholder"></div>
                            <div class="ragePlaceholder">
                                <div class="ScalecontentRangeLabel">
                                    {{content.line_answer.line_end_tag}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="q-item"
                     v-if="question.types === 'matrix_scale'">
                    <div class="matrix-scale-answer-container">
                        <div class="grid-container">
                            <div class="inner">
                                <div class="matrix-scale-answer">
                                    <div class="GridScrollingData">
                                        <div class="GridRow GridColumnHeader ViewItemsGridRow">
                                            <div class="ViewItemsGridRowHeader ViewItemsGridCell"></div>
                                            <div :key="`col-${cIdx}`" v-for="(col, cIdx) in content.option_answers"
                                                 class="ViewItemsGridCell">{{col.description}}
                                            </div>
                                        </div>
                                        <div class="ViewItemsGridRowGroup GridUngraded" :key="`row-${rIdx}`"
                                             v-for="(row, rIdx) in content.row_option_answers">
                                            <span row-radio>
                                                <div class="ViewItemsGridCell ViewItemsGridRowHeader">{{ row.description }}</div>
                                                <div class="ViewItemsGridCell" :key="`row-col-answer-${cIdx}`"
                                                     v-for="(col, cIdx) in content.option_answers">
                                                     <el-radio
                                                         :disabled="!editable"
                                                         v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language][objectKey(row.description)]"
                                                         :label="col.description">&nbsp;</el-radio>
                                                </div>
                                            </span>
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
    import OptionsQuestion from './ViewAnswerOptionsQuestion.vue'
    import {mapState} from 'vuex'

    export default {
        name: "ViewAnswerQuestionnaire",
        components: {
            OptionsQuestion
        },
        props: {
            question: {
                default: function () {
                    return {}
                }
            },
            section_index: {
                default: -1,
            },
            question_index: {
                default: -1,
            },
            editable: {
                default: true
            }
        },
        computed: {
            ...mapState(['mSectionsAssessmentAnswer']),
        },
        data: () => ({
            linear_scale_radio: '',
            matrix_scale: {}
        }),
        methods: {
            line_answer_linear_scale(line) {
                let a = [];
                for (let i = line.line_value; i <= line.line_end_value; i++) {
                    a.push(i);
                }
                return a;
            },
            objectKey(str) {
                return String(str).toLowerCase().replace(/\s/g, '_');
            },
            setTextValueChanged(e) {
                let parseSchema = this.$utils.parseJSON(e.target.getAttribute('data-schema'));
                this.mSectionsAssessmentAnswer[parseSchema.sectionIndex].answers[parseSchema.questionIndex].schema[parseSchema.language] = e.target.value;
            },
            registerTextareaWatcher() {
                for (let i in this.$refs) {
                    if (this.$refs.hasOwnProperty(i)) {
                        let textarea = this.$refs[i][0];
                        textarea.addEventListener('input', this.setTextValueChanged);
                    }
                }
            },
            unregisterTextareaWatcher() {
                for (let i in this.$refs) {
                    if (this.$refs.hasOwnProperty(i)) {
                        let textarea = this.$refs[i][0];
                        textarea.removeEventListener('input', this.setTextValueChanged);
                    }
                }
            }
        },
        beforeDestroy() {
            this.unregisterTextareaWatcher();
        },
        mounted() {
            this.registerTextareaWatcher();
        },
        created() {
            this.setTextValueChanged = this.debounce(this.setTextValueChanged, 200);
        }
    }
</script>

<style>
    .form-create-wrap .q-wrap .q-item .checking-status > .q-radio {
        display: inline;
    }
    .form-create-wrap .q-wrap .checking-status .el-checkbox__input.is-checked .el-checkbox__inner {
        border-color: #4ca2ae;
        background: #01c5df;
    }
</style>
