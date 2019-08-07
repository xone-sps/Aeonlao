<template>
    <div class="q-item" v-if="allows.includes(type)">
        <template v-if="type==='multiple_choice'">
            <div class="q-radio" v-for="(item, i) in options" :key="i">
                <div class="drap-area area-temp">
                    <i class="iconfont icon-menu-drag-head"></i>
                </div>
                <el-radio
                    v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language]"
                    :label="item.description">{{ item.description }}
                </el-radio>
            </div>
        </template>
        <template v-else-if="type==='checkboxes'">
            <el-checkbox-group
                v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language]">
                <el-checkbox :key="i" class="q-radio" v-for="(item, i) in options" :label="item.description">{{
                    item.description }}
                </el-checkbox>
            </el-checkbox-group>
        </template>

        <template v-else-if="type==='dropdown_list'">
            <div class="q-radio">
                <div class="drap-area area-temp">
                    <i class="iconfont icon-menu-drag-head"></i>
                </div>
                <el-select class="q-select"
                           v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language]"
                           filterable
                           placeholder="Choose">
                    <el-option
                        v-for="(o, j) in options"
                        :key="j"
                        :label="o.description"
                        :value="o.description">
                    </el-option>
                </el-select>
            </div>
        </template>
        <template v-else-if="type==='priority'">
            <div class="q-radio" v-for="(q, h) in options" :key="h" style="padding-left: 2px;">
                <div class="drap-area area-temp">
                    <i class="iconfont icon-menu-drag-head"></i>
                </div>
                <div class="icon-radio">{{h + 1}}.</div>
                <div class="priority-container">
                    <div class="radio-input text-data">{{ q.description }}</div>
                    <el-select class="q-select" v-model="mSectionsAssessmentAnswer[section_index].answers[question_index].schema[content.language][objectKey(q.description)]" filterable clearable
                               placeholder="Choose">
                        <el-option
                            v-for="(o, j) in allowSelectPriorities"
                            :key="j"
                            :label="o"
                            :value="o">
                        </el-option>
                    </el-select>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "ViewAnswerOptionsQuestion",
        props: {
            type: {
                default: ''
            },
            content: {
                default: function () {
                    return {};
                }
            },
            options: {
                default: function () {
                    return [];
                }
            },
            section_index: {
                default: -1,
            },
            question_index: {
                default: -1,
            }
        },
        data: () => ({
            allows: [
                'multiple_choice',
                'checkboxes',
                'dropdown_list',
                'priority'
            ],
            radio: '',
            checkboxes: [],
            dropdown_list: '',
            priority: {},
        }),
        computed: {
            ...mapState(['mSectionsAssessmentAnswer']),
            allowSelectPriorities() {
                let p = this.mSectionsAssessmentAnswer[this.section_index].answers[this.question_index].schema[this.content.language];
                let options = Array.apply(null, Array(this.options.length)).map((it, i) => {
                    return i + 1;
                });
                for (let i in p) {
                    if (p.hasOwnProperty(i)) {
                        let selected = p[i];
                        options = options.filter((item) => {
                            return item !== selected;
                        });
                    }
                }
                return options;
            }
        },
        methods: {
            objectKey(str) {
                return String(str).toLowerCase().replace(/\s/g, '_');
            }
        }
    }
</script>

<style scoped>

</style>
