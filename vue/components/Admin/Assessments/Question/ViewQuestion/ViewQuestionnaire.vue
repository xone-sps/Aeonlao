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
                        </div>
                    </div>
                </div>

                <OptionsQuestion :type="question.types" :options="content.option_answers" :key="idx"/>

                <div class="q-item text-wrap spaced-bottom non-options preview-item"
                     v-if="question.types === 'short_answer'">
                    <div class="short-answer-text">
                        <input type="text" placeholder="Your answer">
                    </div>
                </div>

                <div class="q-item"
                     v-if="question.types === 'paragraph'">
                    <div class="paragraph-answer-text">
                        <div class="li main-form">
                            <textarea placeholder="Your answer"
                                      @focus="$autoText($event)"
                                      @input="$autoText($event)"
                                      ref="assessment-desc-text-id"></textarea>
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
                                <el-radio v-model="linear_scale_radio" :label="item">&nbsp;</el-radio>
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
                                                         v-model="matrix_scale[`row-answer-${objectKey(row.description)}`]"
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
    import OptionsQuestion from './ViewOptionsQuestion.vue'

    export default {
        name: "ViewQuestionnaire",
        components: {
            OptionsQuestion
        },
        props: {
            question: {
                default: function () {
                    return {}
                }
            },
            question_index: {
                default: -1,
            }
        },
        watch: {},
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
                return String(str).toLowerCase().replace(' ', '_');
            }
        },
    }
</script>

<style scoped>

</style>
