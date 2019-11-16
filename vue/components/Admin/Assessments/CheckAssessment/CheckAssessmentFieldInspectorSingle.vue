<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <div class="admin-master-detail-card">
                        <div class="md-single-grid assessment-form">
                            <div ad-cell="12" class="theme-blue absolute-parent clearfix">
                                <!--Form Top-->
                                <FormTop ref="AssessmentForm">

                                    <template slot="navigator">
                                        <div class="nav-left">
                                            <div>
                                                <button
                                                    @click="Route({name: 'review-assessments-field-inspector', query: {check_assessment_id: $route.query.check_assessment_id, field_inspector_id: $route.query.user_id }})"
                                                    class="v-md-button v-md-icon-button theme-blue"><i
                                                    class="material-icons">arrow_back</i>
                                                </button>
                                            </div>
                                            <div class="title-nav">Field Inspector Assessments</div>
                                        </div>
                                        <div class="nav-right">
                                            <div class="actions">
                                                <div>
                                                    <button @click="goToComments"
                                                            class="v-md-button v-md-icon-button theme-blue"><i
                                                        class="material-icons">
                                                        comment
                                                    </i></button>
                                                </div>
                                                <div>
                                                    <a v-if="mRelatedAssessment" target="_blank"
                                                       :href="`/admin/me/review-assessment/${mRelatedAssessment.id}?user_id=${mRelatedAssessment.user_id}?type=institute`"
                                                       class="v-md-button v-md-icon-button theme-blue"><i
                                                        class="material-icons">
                                                        import_contacts
                                                    </i></a>
                                                </div>
                                                <div>
                                                    <button @click="SaveCheckAssessment" class="v-md-button primary">{{
                                                        SaveText }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template slot="form-wrap">
                                        <div class="form-create-wrap">
                                            <div class="wrap">
                                                <div class="content-wrap">
                                                    <HeaderBanner :titleStyles="`font-size: 1.1rem;`"
                                                                  :title="user_name" color="#039be5"/>
                                                    <div class="item title main-form">
                                                        <div aria-disabled="true" class="li main-form">
                                                            <div class="assessment-form-title">{{mAssessment.title}}
                                                            </div>
                                                        </div>
                                                        <div aria-disabled="true" class="li main-form">
                                                            <textarea placeholder="Assessment description (optional)"
                                                                      disabled
                                                                      v-model="mAssessment.description"
                                                                      @focus="$autoText($event)"
                                                                      class="form-desc assessment-form-description"
                                                                      @input="$autoText($event)"
                                                                      ref="assessment-desc-text-id">Def</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </template>

                                </FormTop>

                                <!--Questions Content-->

                                <div class="questions-section clearfix answer">
                                    <div class="questions">


                                        <div class="form-create-wrap add-after"
                                             v-for="(section, item_idx) in mSections" :key="item_idx">
                                            <div class="wrap">
                                                <div class="content-wrap">

                                                    <HeaderBanner :titleStyles="`font-size: 1.1rem;`"
                                                                  :title="section.title" color="#039be5"/>

                                                    <div class="item title main-form preview-item">
                                                        <div aria-disabled="true" class="li main-form">
                                                            <textarea placeholder="Assessment description (optional)"
                                                                      disabled
                                                                      v-model="section.desc"
                                                                      @focus="$autoText($event)"
                                                                      class="form-desc assessment-form-description medium-font-size"
                                                                      @input="$autoText($event)"></textarea>
                                                        </div>
                                                    </div>

                                                    <!--Questions Preview-->

                                                    <div class="q-wrap" v-if="!isLoading">
                                                        <ViewAnswerQuestionnaire
                                                            @onQuestionItemStatusChange="autoSaveCheckAssessment"
                                                            :editable="false"
                                                            :section_index="item_idx"
                                                            :question_index="q_idx"
                                                            :question="question"
                                                            v-for="(question, q_idx) in section.questions"
                                                            :key="q_idx"/>
                                                    </div>

                                                    <!--Questions Preview-->

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-create-wrap add-after">
                                            <div class="wrap">
                                                <div class="content-wrap">
                                                    <HeaderBanner :titleStyles="`font-size: 1.1rem;`"
                                                                  title="Summary Assessment Scores" color="#039be5"/>

                                                    <div class="item title main-form preview-item">
                                                        <div aria-disabled="true" class="li main-form">
                                                            <textarea placeholder="Assessment description (optional)"
                                                                      disabled
                                                                      @focus="$autoText($event)"
                                                                      class="form-desc assessment-form-description medium-font-size"
                                                                      @input="$autoText($event)">ຄະແນນ ການສັງລວມ ຜົນການປະເມີນ ຈາກບົດປະເມີນນີ້</textarea>
                                                        </div>
                                                    </div>
                                                    <!--Summary Scores-->
                                                    <div class="q-wrap summary-score">
                                                        <div class="p-question">
                                                            <div class="q-li">
                                                                <div class="q-item-wrap">
                                                                    <div class="q-item">
                                                                        <div class="matrix-scale-answer-container">
                                                                            <div class="grid-container">
                                                                                <div class="inner">
                                                                                    <div class="matrix-scale-answer">
                                                                                        <div class="GridScrollingData">
                                                                                            <div
                                                                                                class="GridRow GridColumnHeader ViewItemsGridRow">
                                                                                                <div
                                                                                                    class="ViewItemsGridRowHeader ViewItemsGridCell"></div>
                                                                                                <div
                                                                                                    :key="`col-${sIdx}`"
                                                                                                    v-for="(score, sIdx) in summaryScores"
                                                                                                    class="ViewItemsGridCell">
                                                                                                    {{score}}
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="ViewItemsGridRowGroup GridUngraded"
                                                                                                :key="`row-${rIdx}`"
                                                                                                v-for="(row, rIdx) in mSections">
                                                                                                <span row-radio>
                                                                                                    <div
                                                                                                        class="ViewItemsGridCell ViewItemsGridRowHeader">{{ row.title }}</div>
                                                                                                    <div
                                                                                                        class="ViewItemsGridCell"
                                                                                                        :key="`row-col-answer-${sIdx}`"
                                                                                                        v-for="(score, sIdx) in summaryScores">
                                                                                                         <el-radio
                                                                                                             @change="autoSaveCheckAssessment"
                                                                                                             v-model="mSectionsAssessmentAnswer[rIdx].score"
                                                                                                             :label="score">&nbsp;</el-radio>
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
                                                    </div>
                                                    <!--Summary Scores-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--End Scores-->

                                        <!--Comments-->
                                        <div class="form-create-wrap add-after" id="comments-sections">
                                            <div class="wrap">
                                                <div class="content-wrap comments">
                                                    <HeaderBanner :titleStyles="`font-size: 1.1rem;`"
                                                                  title="Assessment Comments" color="#039be5">
                                                        <template slot="action">
                                                            <button @click="refreshComments"
                                                                    class="v-md-button v-md-icon-button theme-blue">
                                                                <i class="material-icons">
                                                                    refresh
                                                                </i></button>
                                                        </template>
                                                    </HeaderBanner>
                                                    <div class="q-wrap">
                                                        <div class="p-question">
                                                            <Comments ref="ref-comments"
                                                                      :type="'field_inspector'"
                                                                      :check_assessment_id="$route.params.check_assessment_id"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Comments-->

                                    </div>
                                </div>
                                <!--End Questions Content-->
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
    import {mapActions, mapState, mapMutations} from 'vuex'
    import FormTop from '@com/Admin/Assessments/AssessmentForm.vue'
    import HeaderBanner from '@com/Admin/Assessments/Includes/HeaderBanner.vue'

    export default AdminBase.extend({
        name: "CheckAssessmentFieldInspectorSingle",
        components: {
            FormTop,
            HeaderBanner,
        },
        data() {
            return {
                title: 'Checking an Field Inspector Assessment',
                type: 'none',
                watchers: true,
                tabs: [{name: 'Field Inspector Assessment'}],
                mAssessment: {},
                mSections: [],
                SaveText: 'Save',
                isSaving: false,
                summaryScores: [0, 1, 2, 3, 4, 5],
                isLoading: true,
                user_name: '...',
                mRelatedAssessment: {},
            }
        },
        computed: {
            ...mapState(['mSectionsAssessmentAnswer']),
        },
        methods: {
            ...mapMutations(['setSectionsAssessmentAnswer']),
            ...mapActions(['fetchCheckAssessmentFieldInspector', 'showInfoToast', 'postSaveCheckAssessmentAnswer']),
            setSaveTextState(state) {
                this.isSaving = false;
                if (state.restore) {
                    this.SaveText = 'Save';
                    return;
                }
                if (state.first_auto) {
                    this.SaveText = 'Auto Saving...';
                    this.isSaving = false;
                    return;
                }
                if (state.first) {
                    this.SaveText = 'Saving...';
                    this.isSaving = true;
                    return;
                }
                this.SaveText = 'Saved';
                setTimeout(() => {
                    this.SaveText = 'Save';
                }, 1000);
            },
            getAssessment() {
                let id = this.$route.params.check_assessment_id;
                let user_id = this.$route.query.user_id;
                let institute_id = this.$route.query.institute_id;
                this.fetchCheckAssessmentFieldInspector({id, user_id, institute_id})
                    .then(res => {
                        if (!res.success) {
                            this.setSectionsAssessmentAnswer([]);
                            this.Route({
                                name: 'review-assessments-field-inspector',
                                query: {
                                    field_inspector_id: this.$route.query.user_id,
                                    check_assessment_id: this.$route.query.check_assessment_id
                                }
                            });
                        } else {
                            this.mRelatedAssessment = res.data.related_institute_assessment;
                            this.user_name = res.data.user_name + "'s assessment for " + res.data.related_user_name;
                            this.mAssessment = res.data.assessment;
                            this.mSections = res.data.sections;
                            this.setSectionsAssessmentAnswer(res.data.check_sections);
                            this.isLoading = false;
                        }
                    }).catch(err => {
                })
            },
            initAssessmentData() {
                this.getAssessment();
            },
            SaveCheckAssessment() {
                if (this.isSaving) {
                    return;
                }
                this.setSaveTextState({first: true});
                this.postSaveCheckAssessmentAnswer({
                    id: this.$route.params.check_assessment_id,
                    user_id: this.$route.query.user_id,
                    check_assessment_sections: this.mSectionsAssessmentAnswer,
                    type: 'field_inspector',
                }).then(res => {
                    if (!res.success) {
                        this.showInfoToast({
                            msg: 'Cannot save maybe the checking assessment was closed!.',
                            dt: 4500
                        })
                    }
                    this.setSaveTextState({});
                }).catch(err => {
                    console.log(err);
                    this.setSaveTextState({restore: true});
                })
            },
            autoSaveCheckAssessment() {
                this.SaveCheckAssessment();
            },
            goToComments() {
                let comment = this.jq('#comments-sections');
                this.$utils.scrollToY('main-container', this.$utils.findPos(comment.get(0)).y - 200);
            },
            refreshComments() {
                let comment = this.$refs['ref-comments'];
                comment.getComments();
            }
        },
        beforeRouteLeave(to, from, next) {
            this.setSectionsAssessmentAnswer([]);
            next();
        },
        created() {
            this.initAssessmentData();
            this.autoSaveCheckAssessment = this.debounce(this.autoSaveCheckAssessment, 200);
        }
    });
</script>
<style lang="scss">
    @import "../../../Admin/Assets/reset.scss";
    @import url('../../../Admin/Assets/iconfont/iconfont.css');
    @import "../../../Admin/Assets/create-wrapper.scss";

    .md-single-grid.assessment-form {
        min-height: 799px;
    }

    .questions-section.answer {
        top: auto;
    }

    [aria-disabled="true"] {
        .el-select.q-select {
            width: 100%;
        }

        .el-radio__input.is-disabled .el-radio__inner, .el-input.is-disabled .el-input__inner,
        .el-checkbox__input.is-disabled .el-checkbox__inner {
            background-color: #ffffff;
            border-color: #bfd3d9;
            cursor: not-allowed;
        }

        .el-select .el-input.is-disabled .el-input__inner:hover {
            border-color: transparent;
        }

        .el-checkbox__input.is-disabled + .el-checkbox__label, .el-radio__input.is-disabled + .el-radio__label,
        .el-input.is-disabled .el-input__inner {
            color: #1f363d;
        }
    }

    .comments .theme-blue i {
        color: rgba(0, 0, 0, 0.54);
    }

    .summary-score.q-wrap {
        .el-radio__label {
            padding-left: 0;
        }

        .q-item-wrap .el-radio__input {
            margin-right: 0;
        }

        .el-radio__input.is-disabled .el-radio__inner {
            background-color: #ffffff;
            border-color: #bfd3d9;
            cursor: not-allowed;
        }
    }
</style>
