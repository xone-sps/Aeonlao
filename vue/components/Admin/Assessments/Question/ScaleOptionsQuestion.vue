<template>

    <div>
        <div class="q-item line-wrap non-options"
             v-if="type === 'linear_scale'">
            <div class="q-item-line">
                <el-select v-model="mContent.line_answer.line_value"
                           @change="optionsSelectScaleChanged(mContent.line_answer)">
                    <el-option
                        v-for="i in lineOptions"
                        :key="i"
                        :value="i">
                    </el-option>
                </el-select>
                <span class="line-tip">To</span>
                <el-select
                    v-model="mContent.line_answer.line_end_value"
                    @change="optionsSelectScaleChanged(mContent.line_answer)">
                    <el-option
                        v-for="i in lineEndOptions"
                        :key="i"
                        :value="i">
                    </el-option>
                </el-select>
            </div>
            <div class="q-radio">
                <div class="icon-radio">1.</div>
                <input class="radio-input text-data scale"
                       :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-linear_scale`]"
                       v-model="mContent.line_answer.line_tag"
                       :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index , key: 'line_tag', content: mContent.line_answer})}`"
                       placeholder="Label (optional)">
            </div>
            <div class="q-radio">
                <div class="icon-radio">2.</div>
                <input class="radio-input text-data scale"
                       :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-linear_scale`]"
                       :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index , key: 'line_end_tag', content: mContent.line_answer})}`"
                       v-model="mContent.line_answer.line_end_tag"
                       placeholder="Label (optional)">
            </div>
        </div>

        <div class="q-item line-wrap square-wrap"
             v-if="type === 'matrix_scale'">
            <div class="square-li square-percent-13">
                <h4 class="square-margin-42">Rows</h4>
                <!--<div class="q-item-line">
                    <el-select
                        v-model="mContent.line_answer.line_value"
                        @change="optionsSelectScaleChanged(mContent.line_answer)">
                        <el-option
                            v-for="i in lineOptions"
                            :key="i"
                            :value="i">
                        </el-option>
                    </el-select>
                    <span class="line-tip">To</span>
                    <el-select
                        v-model="mContent.line_answer.line_end_value"
                        @change="optionsSelectScaleChanged(mContent.line_answer)">
                        <el-option
                            v-for="i in lineEndOptions"
                            :key="i"
                            :value="i">
                        </el-option>
                    </el-select>
                </div>
                <div class="q-radio">
                    <div class="icon-radio">1.</div>
                    <input class="radio-input text-data scale"
                           :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-matrix_scale`]"
                           :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index , key: 'line_tag', content: mContent.line_answer})}`"
                           v-model="mContent.line_answer.line_tag"
                           placeholder="Label (optional)">
                </div>
                <div class="q-radio">
                    <div class="icon-radio">2.</div>
                    <input class="radio-input text-data scale"
                           :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-matrix_scale`]"
                           v-model="mContent.line_answer.line_end_tag"
                           :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index , key: 'line_end_tag', content: mContent.line_answer})}`"
                           placeholder="Label (optional)">
                </div> -->
                <draggable
                    v-model="mRowOptions"
                    group="options"
                    :handle="'.drap-area'"
                    :move="onMove"
                    @start="dragging=true"
                    @end="dragging=false">

                    <div class="q-radio"
                         v-for="(item, i) in mRowOptions" :key="i">

                        <div class="drap-area">
                            <i class="iconfont icon-menu-drag-head"></i>
                        </div>

                        <div class="icon-radio">{{i + 1}}.</div>
                        <input class="radio-input text-data"
                               :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-matrix_scale-row-options`]"
                               :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index, option_index: i})}`"
                               :id="IdSchema(i, 'row')"
                               v-model="item.description">
                        <i class="el-icon-close"
                           v-if="isFocus"
                           @click="deleteRowRadioFn(i)"></i>
                    </div>

                </draggable>

                <div class="q-radio" v-if="isFocus">
                    <div class="drap-area area-temp">
                        <i class="iconfont icon-menu-drag-head"></i>
                    </div>
                    <div class="icon-radio">{{mRowOptions.length + 1}}.
                    </div>
                    <input class="radio-add" v-model="addRadio"
                           @focus="addRowRadioFn()">
                </div>

            </div>

            <div class="square-li square-percent-13">
                <h4 class="drag-area">Columns</h4>

                <draggable

                    v-model="mOptions"
                    group="options"
                    :handle="'.drap-area'"
                    :move="onMove"
                    @start="dragging=true"
                    @end="dragging=false">


                    <div class="q-radio"
                         v-for="(item, i) in mOptions" :key="i">

                        <div class="drap-area">
                            <i class="iconfont icon-menu-drag-head"></i>
                        </div>

                        <div class="icon-radio icon-cirle"></div>
                        <input class="radio-input text-data"
                               :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-matrix_scale-options`]"
                               :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index, option_index: i})}`"
                               :id="IdSchema(i, 'column')"
                               v-model="item.description">
                        <i class="el-icon-close"
                           v-if="isFocus"
                           @click="deleteRadioFn(i)"></i>
                    </div>

                </draggable>

                <div class="q-radio" v-if="isFocus">
                    <div class="drap-area area-temp">
                        <i class="iconfont icon-menu-drag-head"></i>
                    </div>
                    <div class="icon-radio icon-cirle"></div>
                    <input class="radio-add" v-model="addRadio"
                           @focus="addRadioFn()">
                </div>
            </div>

        </div>
    </div>


</template>

<script>
    let lineEndOptions = Array.apply(null, Array(9)).map((item, i) => {
        return i + 2
    });

    import draggable from 'vuedraggable';
    import {mapMutations, mapState} from 'vuex'

    export default {
        name: "ScaleOptionQuestion",
        components: {
            draggable,
        },
        props: {
            type: {
                default: 'multiple_choice',
            },
            sectionIndex: {
                default: 0
            },
            currentSectionIndex: {
                default: 0
            },
            isFocus: {
                default: false
            },
            question_idx: {
                default: 0
            },
            answer_schema_index: {
                default: 0
            }
        },
        computed: {
            ...mapState(['mSectionsStack']),
            mRowOptions: {
                get() {
                    return this.mSectionsStack[this.sectionIndex].questions[this.question_idx].content[this.answer_schema_index].row_option_answers;
                },
                set(data) {
                    let time_group = new Date().getTime();
                    this.setMoveQuestionSectionOptionsDataStack({
                        type: 'row_options_scale',
                        options: data, sectionIndex: this.sectionIndex,
                        question_index: this.question_idx, answer_schema_index: this.answer_schema_index,
                        time_group
                    });

                    if (this.dragDropContext.related && this.dragDropContext.dragged) {
                        let targetComp = this.dragDropContext.related.component.$parent;
                        let dragged = this.dragDropContext.dragged.element;
                        let placedIndex = targetComp.mOptions.findIndex((item) => {
                            return item.hash_id === dragged.hash_id;
                        });
                        if (placedIndex !== -1) {
                            this.setMovementQuestionSectionDataStack({
                                sectionIndex: targetComp.sectionIndex,
                                questionIndex: targetComp.question_idx,
                                time_group
                            });
                        }
                    }
                }
            },
            mContent() {
                return this.mSectionsStack[this.sectionIndex].questions[this.question_idx].content[this.answer_schema_index];
            },
            mOptions: {
                get() {
                    return this.mSectionsStack[this.sectionIndex].questions[this.question_idx].content[this.answer_schema_index].option_answers;
                },
                set(data) {
                    let time_group = new Date().getTime();
                    this.setMoveQuestionSectionOptionsDataStack({
                        type: 'options_scale',
                        options: data, sectionIndex: this.sectionIndex,
                        question_index: this.question_idx, answer_schema_index: this.answer_schema_index,
                        time_group
                    });

                    if (this.dragDropContext.related && this.dragDropContext.dragged) {
                        let targetComp = this.dragDropContext.related.component.$parent;
                        let dragged = this.dragDropContext.dragged.element;
                        let placedIndex = targetComp.mOptions.findIndex((item) => {
                            return item.hash_id === dragged.hash_id;
                        });
                        if (placedIndex !== -1) {
                            this.setMovementQuestionSectionDataStack({
                                sectionIndex: targetComp.sectionIndex,
                                questionIndex: targetComp.question_idx,
                                time_group
                            });
                        }
                    }
                }
            }
        },
        data: () => ({
            dragging: false,
            addRadio: 'Add option',
            lineOptions: [0, 1],
            lineEndOptions: lineEndOptions,
            dragDropContext: {},
        }),
        watch: {
            'mContent.option_answers': function () {
                this.$nextTick(() => {
                    this.registerOptionsTextData();
                })
            },
            'mContent.row_option_answers': function () {
                this.$nextTick(() => {
                    this.registerRowOptionsTextData();
                })
            },
            type: function () {
                this.$nextTick(() => {
                    this.initTextData();
                })
            }
        },
        methods: {
            ...mapMutations(['setMoveQuestionSectionOptionsDataStack', 'setMovementQuestionSectionDataStack', 'setOptionsScaleChangeDataStack']),
            preIdSchema(t) {
                return `section-${this.sectionIndex}-question-${this.question_idx}-aws-${this.answer_schema_index}-matrix-${t}-`
            },
            /**
             * @return {string}
             */
            IdSchema(i, t) {
                return `section-${this.sectionIndex}-question-${this.question_idx}-aws-${this.answer_schema_index}-matrix-${i}-${t}`;
            },
            optionsSelectScaleChanged(n) {
                this.setOptionsScaleChangeDataStack({
                    sectionIndex: this.sectionIndex,
                    question_index: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    line_answer: n,
                });
            },
            deleteRadioFn(i) {
                this.$emit('onDeleteOptionAnswerClick', {
                    question_idx: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    index: i,
                    id_schema: this.preIdSchema('column'),
                    length: this.mOptions.length,
                    type: 'column',
                })
            },
            addRadioFn() {
                this.$emit('onAddOptionAnswerClick', {
                    question_idx: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    new_id_idx: this.IdSchema(this.mOptions.length, 'column'),
                    id_schema: this.preIdSchema('column'),
                    length: this.mOptions.length,
                    type: 'column',
                })
            },
            deleteRowRadioFn(i){
                this.$emit('onDeleteOptionAnswerClick', {
                    question_idx: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    index: i,
                    id_schema: this.preIdSchema('row'),
                    length: this.mRowOptions.length,
                    type: 'row',
                })
            },
            addRowRadioFn(){
                this.$emit('onAddOptionAnswerClick', {
                    question_idx: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    new_id_idx: this.IdSchema(this.mRowOptions.length, 'row'),
                    id_schema: this.preIdSchema('row'),
                    length: this.mRowOptions.length,
                    type: 'row',
                })
            },
            onMove({relatedContext, draggedContext}) {
                this.dragDropContext = {related: relatedContext, dragged: draggedContext};
                return true;
            },
            registerLinearScaleTextData() {
                if (!(this.type === 'linear_scale')) {
                    return;
                }
                let classSchema = `.radio-input.text-data.scale.SectionIndex-${this.sectionIndex}-QuestionIndex-${this.question_idx}-type-linear_scale`,
                    els = this.jq(classSchema);
                els.each((idx, item) => {
                    item.removeEventListener('input', this.assignSchemaTextData, true);
                    item.addEventListener('input', this.assignSchemaTextData, true);
                });
            },
            registerOptionsTextData() {
                if (!(this.type === 'matrix_scale')) {
                    return;
                }
                let classSchema = `.radio-input.text-data.SectionIndex-${this.sectionIndex}-QuestionIndex-${this.question_idx}-type-matrix_scale-options`,
                    els = this.jq(classSchema);
                els.each((idx, item) => {
                    item.removeEventListener('input', this.assignSchemaOptionTextData, true);
                    item.addEventListener('input', this.assignSchemaOptionTextData, true);
                });
            },
            registerRowOptionsTextData() {
                if (!(this.type === 'matrix_scale')) {
                    return;
                }
                let classSchema = `.radio-input.text-data.SectionIndex-${this.sectionIndex}-QuestionIndex-${this.question_idx}-type-matrix_scale-row-options`,
                    els = this.jq(classSchema);
                els.each((idx, item) => {
                    item.removeEventListener('input', this.assignSchemaRowOptionTextData, true);
                    item.addEventListener('input', this.assignSchemaRowOptionTextData, true);
                });
            },
            assignSchemaOptionTextData(o) {
                this.setOptionTextValueChanged(o.target, 'description', o.target.value, o.target.getAttribute('data-schema'));
            },
            assignSchemaRowOptionTextData(o) {
                this.setRowOptionTextValueChanged(o.target, 'description', o.target.value, o.target.getAttribute('data-schema'));
            },
            assignSchemaTextData(o) {
                this.setTextValueChanged(o.target, 'line_answer', o.target.value, o.target.getAttribute('data-schema'));
            },
            setTextValueChanged(el, key, value, schema) {
                let parseSchema = this.$utils.parseJSON(schema);
                this.$store.commit('setTextValueChangeDataStack', {
                    sectionIndex: this.sectionIndex,
                    type: 'option_answers_linear_scale',
                    value: value,
                    key: key,
                    el: el,
                    schema: parseSchema,
                })
            },
            setOptionTextValueChanged(el, key, value, schema) {
                let parseSchema = this.$utils.parseJSON(schema);
                this.$store.commit('setTextValueChangeDataStack', {
                    sectionIndex: this.sectionIndex,
                    type: 'option_answers',
                    value: value,
                    key: key,
                    el: el,
                    schema: parseSchema,
                })
            },
            setRowOptionTextValueChanged(el, key, value, schema) {
                let parseSchema = this.$utils.parseJSON(schema);
                this.$store.commit('setTextValueChangeDataStack', {
                    sectionIndex: this.sectionIndex,
                    type: 'row_option_answers',
                    value: value,
                    key: key,
                    el: el,
                    schema: parseSchema,
                })
            },
            initTextData() {
                this.registerOptionsTextData();
                this.registerRowOptionsTextData();
                this.registerLinearScaleTextData();
            }
        },
        mounted() {
            this.initTextData();
        },
        created() {
            this.setTextValueChanged = this.debounce(this.setTextValueChanged, 200);
            this.setOptionTextValueChanged = this.debounce(this.setOptionTextValueChanged, 200);
            this.setRowOptionTextValueChanged = this.debounce(this.setRowOptionTextValueChanged, 200);
        }
    }
</script>

<style scoped>

</style>
