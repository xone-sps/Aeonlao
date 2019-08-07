<template>
    <content>
        <el-tooltip :disabled="disabled" class="item" transition="scale-tran"
                    content="Add question"
                    placement="right">
            <div class="sidebar-li" @click="AddQuestion">
                <div class="item">
                    <i class="material-icons">
                        add_circle
                    </i>
                </div>
            </div>
        </el-tooltip>
        <el-tooltip :disabled="disabled" class="item" transition="scale-tran"
                    content="Add section"
                    placement="right">
            <div class="sidebar-li" @click="AddSection">
                <div class="item">
                    <i class="material-icons">
                        view_agenda
                    </i>
                </div>
            </div>
        </el-tooltip>
    </content>
</template>

<script>
    export default {
        name: "ActionFormeditor",
        props: {
            disabled: {
                default: false,
            }
        },
        methods: {
            AddQuestion() {
                this.$emit('OnAddQuestion')
            },
            AddSection() {
                this.$emit('OnAddSection')
            },
        },
    }
</script>

<style scoped>

</style>
