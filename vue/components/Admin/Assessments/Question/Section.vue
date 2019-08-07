<template>

    <div class="form-create-wrap add-after">

        <div class="wrap">
            <div class="content-wrap" @click="onSectionClick">
                <HeaderBanner :title="`Section ${sectionIndex+1}/${total}`" color="#039be5">
                    <template slot="action" v-if="total > 1">
                        <DropDownAction :items="dropDownActionItems"
                                        @onMenuItemClick="handleDropdownCommand"/>
                    </template>
                    <template slot="action" v-else>
                        <DropDownAction :items="firstDropDownActionItems"
                                        @onMenuItemClick="handleDropdownCommand"/>
                    </template>
                </HeaderBanner>
                <div class="item title" :class="{'title-focus': sectionIndex === $store.state.currentFocusIndexes.sectionIndex}">
                    <div class="li">
                        <textarea class="form-title no-resize" placeholder="Section title"
                                  :ref="`${sectionIndex}-title-text-id`"
                                  v-model="section.title"
                                  @focus="$autoText($event)"
                                  @input="$autoText($event)"
                                  @keypress.enter="$disableEnterNewLine"></textarea>
                    </div>
                    <div class="li">
                        <textarea placeholder="Section description (optional)"
                                  :ref="`${sectionIndex}-desc-text-id`"
                                  v-model="section.desc"
                                  @focus="$autoText($event)"
                                  @input="$autoText($event)"></textarea>
                    </div>
                    <!--<div class="add-list" @click="addListQuestion" v-if="!section.questions.length">-->
                    <!--<i class="el-icon-plus"></i>-->
                    <!--</div>-->
                </div>
                <div class="q-wrap">
                    <slot name="questions"></slot>
                </div>
            </div>

        </div>

    </div>

</template>

<script>
    import HeaderBanner from '../Includes/HeaderBanner.vue'
    import DropDownAction from '../Includes/DropDownAction.vue'

    export default {
        name: "Section",
        props: {
            section: {
                default: function () {
                    return {};
                }
            },
            sectionIndex: {
                default: -1,
            },
            total: {
                default: 1,
            },
        },
        components: {
            HeaderBanner,
            DropDownAction
        },
        data: () => ({
            dropDownActionItems: [
                {text: 'New Section', command: 'create'},
                {text: 'Duplicate Section', command: 'duplicate'},
                {text: 'Delete Section', command: 'delete'},
            ],
            firstDropDownActionItems: [
                {text: 'New Section', command: 'create'},
                {text: 'Duplicate Section', command: 'duplicate'},
            ],
        }),
        methods: {
            handleDropdownCommand(c) {
                this.$emit('onDropdownClick', {sectionIndex: this.sectionIndex, action: c})
            },
            onSectionClick() {
                this.$emit('onSectionClick', this.sectionIndex);
            },
            setTitleTextValueChanged(el, key, value) {
                this.$store.commit('setTextValueChangeDataStack', {
                    sectionIndex: this.sectionIndex,
                    type: 'section',
                    value,
                    key,
                    el
                });
            },
            setDescTextValueChanged(el, key, value) {
                this.$store.commit('setTextValueChangeDataStack', {
                    sectionIndex: this.sectionIndex,
                    type: 'section',
                    value,
                    key,
                    el
                });
            },
            registerTextData() {
                let elTitle = this.$refs[`${this.sectionIndex}-title-text-id`],
                    elDesc = this.$refs[`${this.sectionIndex}-desc-text-id`];
                elTitle.addEventListener('input', (e) => {
                    this.setTitleTextValueChanged(e.target, 'title', e.target.value);
                });
                elDesc.addEventListener('input', (e) => {
                    this.setDescTextValueChanged(e.target, 'desc', e.target.value);
                });
            },

        },
        mounted() {
            this.registerTextData();
        },
        created() {
            this.setDescTextValueChanged = this.debounce(this.setDescTextValueChanged, 200);
            this.setTitleTextValueChanged = this.debounce(this.setTitleTextValueChanged, 200);
        }
    }
</script>

<style scoped>

</style>
