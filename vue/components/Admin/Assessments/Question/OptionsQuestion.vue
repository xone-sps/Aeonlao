<template>

    <div class="q-item" v-if="allows.includes(type)">

        <draggable
            v-model="mOptions"
            group="options"
            :handle="'.drap-area'"
            :move="onMove"
            @start="dragging=true"
            @end="dragging=false">

            <div class="q-radio" v-for="(item, i) in mOptions" :key="i">

                <div class="drap-area">
                    <i class="iconfont icon-menu-drag-head"></i>
                </div>

                <div class="icon-radio"
                     v-if="type === 'dropdown_list' || type === 'priority'">
                    {{i + 1}}.
                </div>
                <div v-else class="icon-radio"
                     :class="{'icon-cirle': type === 'multiple_choice', 'icon-square': type === 'checkboxes'}"></div>
                <input class="radio-input text-data"
                       :class="[`SectionIndex-${sectionIndex}-QuestionIndex-${question_idx}-type-${type}`]"
                       :data-schema="`${JSON.stringify({sectionIndex, question_index: question_idx, content_index: answer_schema_index, option_index: i})}`"
                       :id="IdSchema(i)"
                       v-model="item.description">
                <i class="el-icon-close" v-if="isFocus"
                   @click="deleteRadioFn(i)"></i>
            </div>

        </draggable>


        <div class="q-radio" v-if="isFocus">
            <div class="drap-area area-temp">
                <i class="iconfont icon-menu-drag-head"></i>
            </div>
            <div class="icon-radio" v-if="type === 'dropdown_list' || type === 'priority'">
                {{mOptions.length + 1}}.
            </div>
            <div v-else class="icon-radio"
                 :class="{'icon-cirle': type === 'multiple_choice', 'icon-square': type === 'checkboxes'}"></div>
            <input class="radio-add" v-model="addRadio"
                   @focus="addRadioFn()">
        </div>
    </div>

</template>

<script>
    import draggable from 'vuedraggable';
    import {mapMutations, mapState} from 'vuex'

    export default {
        name: "OptionsQuestion",
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
            mOptions: {
                get() {
                    return this.mSectionsStack[this.sectionIndex].questions[this.question_idx].content[this.answer_schema_index].option_answers;
                },
                set(data) {
                    let time_group = new Date().getTime();
                    this.setMoveQuestionSectionOptionsDataStack({
                        type: 'options',
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
            allows: [
                'multiple_choice',
                'checkboxes',
                'dropdown_list',
                'priority'
            ],
            addRadio: 'Add option',
            dragDropContext: {},
        }),
        watch: {
            mOptions: function (n) {
                this.$nextTick(() => {
                    this.registerTextData();
                })
            },
            type: function (n) {
                this.$nextTick(() => {
                    this.registerTextData();
                })
            }
        },
        methods: {
            ...mapMutations(['setMoveQuestionSectionOptionsDataStack', 'setMovementQuestionSectionDataStack']),
            preIdSchema() {
                return `section-${this.sectionIndex}-question-${this.question_idx}-aws-${this.answer_schema_index}-op-`;
            },
            /**
             * @return {string}
             */
            IdSchema(i) {
                return `section-${this.sectionIndex}-question-${this.question_idx}-aws-${this.answer_schema_index}-op-${i}`;
            },
            deleteRadioFn(i) {
                this.$emit('onDeleteOptionAnswerClick', {
                    question_idx: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    index: i,
                    id_schema: this.preIdSchema(),
                    length: this.mOptions.length
                })
            },
            addRadioFn() {
                this.$emit('onAddOptionAnswerClick', {
                    question_idx: this.question_idx,
                    answer_schema_index: this.answer_schema_index,
                    new_id_idx: this.IdSchema(this.mOptions.length),
                    id_schema: this.preIdSchema(),
                    length: this.mOptions.length
                })
            },
            onMove({relatedContext, draggedContext}) {
                this.dragDropContext = {related: relatedContext, dragged: draggedContext};
                return true;
            },
            registerTextData() {
                if (!this.allows.includes(this.type)) {
                    return;
                }
                let classSchema = `.radio-input.text-data.SectionIndex-${this.sectionIndex}-QuestionIndex-${this.question_idx}-type-${this.type}`,
                    els = this.jq(classSchema);
                els.each((idx, item) => {
                    item.removeEventListener('input', this.assignSchemaTextData, true);
                    item.addEventListener('input', this.assignSchemaTextData, true);
                });
            },
            assignSchemaTextData(o) {
                this.setTextValueChanged(o.target, 'description', o.target.value, o.target.getAttribute('data-schema'));
            },
            setTextValueChanged(el, key, value, schema) {
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
