<template>
    <div class="q-item" v-if="allows.includes(type)">
        <template v-if="type==='multiple_choice'">
            <div class="q-radio" v-for="(item, i) in options" :key="i">
                <div class="drap-area area-temp">
                    <i class="iconfont icon-menu-drag-head"></i>
                </div>
                <el-radio v-model="radio" :label="item.description">{{ item.description }}</el-radio>
            </div>
        </template>
        <template v-else-if="type==='checkboxes'">
            <el-checkbox-group v-model="checkboxes">
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
                           v-model="dropdown_list" filterable
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
                    <el-select class="q-select" v-model="priority[objectKey(q.description)]" filterable clearable
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
    export default {
        name: "ViewOptionsQuestion",
        props: {
            type: {
                default: ''
            },
            options: {
                default: function () {
                    return [];
                }
            },
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
            allowSelectPriorities() {
                let p = this.priority, options = Array.apply(null, Array(this.options.length)).map((it, i) => {
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
                return String(str).toLowerCase().replace(' ', '_');
            }
        }
    }
</script>

<style scoped>

</style>
