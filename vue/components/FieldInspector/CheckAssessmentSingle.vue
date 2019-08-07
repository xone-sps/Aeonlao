<template>
    <div>
        <Tabs :bgColor="theme.bgColor" :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
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
                                                    @click="Route({name: 'check-assessments'})"
                                                    class="v-md-button v-md-icon-button theme-blue"><i
                                                    class="material-icons">arrow_back</i>
                                                </button>
                                            </div>
                                            <div class="title-nav">My Assessments</div>
                                        </div>
                                        <div class="nav-right">
                                            <div class="actions">
                                                <div>
                                                    <button @click="goToComments"
                                                            class="v-md-button v-md-icon-button theme-blue"><i
                                                        class="material-icons">
                                                        comment
                                                    </i></button>
                                                    <button
                                                        v-if="!$utils.isEmptyVar(check_assessment_field_inspector.id)"
                                                        @click="downloadExportFile(check_assessment_field_inspector.id)"
                                                        class="v-md-button v-md-icon-button theme-blue">
                                                        <i class="material-icons v-icon">save_alt</i>
                                                    </button>
                                                </div>
                                                <div v-if="!$utils.isEmptyVar(institute)">
                                                    <button @click="SaveCheckAssessment" class="v-md-button primary">{{
                                                        SaveText }}
                                                    </button>
                                                </div>
                                                <div v-else>
                                                    <button style="visibility: hidden"
                                                            class="v-md-button primary"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template slot="form-wrap">
                                        <!--Select institute-->
                                        <div class="form-create-wrap">
                                            <div class="wrap">
                                                <div class="content-wrap">
                                                    <div class="FormviewerViewAccentBanner AccentBackground"></div>

                                                    <div class="admin-settings-cameo template-brand-settings">
                                                        <div
                                                            style="background-color: #ffffff; min-height: 180px;"
                                                            class="settings-container no-margin-top no-border-left add-padding"
                                                            border-bottom>
                                                            <div class="cameo-header">
                                                                <i class="material-icons cameo-header-icon">filter_list</i>
                                                                <span> Filters</span>
                                                            </div>
                                                            <div class="cameo-content">
                                                                <div class="layout-align-space-around-start layout-row">
                                                                    <!-- Select Assessment -->
                                                                    <div class="form-multi-select-container flex dense"
                                                                         full>
                                                                        <label>Select an Institute</label>
                                                                        <multiselect class="select-multiple"
                                                                                     v-model="institute"
                                                                                     label="name" track-by="id"
                                                                                     placeholder="Select institute"
                                                                                     open-direction="bottom"
                                                                                     :options="institutes"
                                                                                     :show-no-results="false"
                                                                                     :preserve-search="true"
                                                                                     :hide-selected="false"
                                                                                     @input="getAssessment()">
                                                                        </multiselect>
                                                                        <template
                                                                            v-if="validated().institute">
                                                                            <div class="form-input-container"
                                                                                 style="padding-bottom: 10px;">
                                                                                <input v-show="false"/>
                                                                                <div admin-messages>
                                                                                    <div admin-message
                                                                                         class="message-required ">
                                                                                        Please choose an institute
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                    <!-- Select Field Inspector -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Select institute-->
                                        <div class="form-create-wrap">
                                            <div class="wrap">
                                                <div class="content-wrap">
                                                    <div class="FormviewerViewAccentBanner AccentBackground"></div>
                                                    <div class="item title main-form">
                                                        <div aria-disabled="true" class="li main-form">
                                                            <div class="assessment-form-title">{{ mAssessment.title ||
                                                                'Assessment title' }} <small>{{ checkStatus }}</small>
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

                                <div class="questions-section clearfix answer" v-if="mSectionsAssessmentAnswer.length">
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

                                                    <div class="q-wrap">
                                                        <ViewAnswerQuestionnaire
                                                            :editable="check_assessment_field_inspector.status==='checking'"
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
                                                                                                             :disabled="true"
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
                                                                      :check_assessment_id="check_assessment_field_inspector.id"/>
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
    import FieldInspectorBase from '@bases/FieldInspectorBase.js'
    import {mapActions, mapState, mapMutations} from 'vuex'
    import FormTop from '@com/Admin/Assessments/AssessmentForm.vue'
    import HeaderBanner from '@com/Admin/Assessments/Includes/HeaderBanner.vue'
    import ViewAnswerQuestionnaire from './ViewAnswerQuestion/ViewAnswerQuestionnaire.vue'
    import Comments from '@com/Institute/CheckAssessmentComments.vue'

    export default FieldInspectorBase.extend({
        name: "CheckAssessmentSingle",
        components: {
            FormTop,
            HeaderBanner,
            ViewAnswerQuestionnaire,
            Comments
        },
        data() {
            return {
                title: 'Checking an Assessment ',
                type: 'none',
                watchers: true,
                tabs: [{name: 'My Assessment'}],
                mAssessment: {},
                mSections: [],
                SaveText: 'Save',
                isSaving: false,
                summaryScores: [1, 2, 3, 4, 5],
                check_assessment_field_inspector: {status: ''},
                institute: null,
                institutes: [],
            }
        },
        computed: {
            ...mapState(['mSectionsAssessmentAnswer']),
            checkStatus() {
                let status = { close: ' (Closed)', success: ' (Success)'};
                return status[this.check_assessment_field_inspector.status] || '';
            }
        },
        watch: {
            mSectionsAssessmentAnswer: {
                deep: true,
                handler: function (n, o) {
                    if (o.length && n.length) {
                        this.autoSaveCheckAssessment();
                    }
                }
            }
        },
        methods: {
            ...mapMutations(['setSectionsAssessmentAnswer']),
            ...mapActions(['fetchAssessment', 'fetchInstitutes', 'postSaveCheckAssessmentAnswer']),
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
            getInstitutes() {
                this.fetchInstitutes()
                    .then(res => {
                        this.institutes = res.data.institutes;
                        if (this.$route.query.institute_id) {
                            this.institute = this.institutes.filter((item) => {
                                return String(item.id) === this.$route.query.institute_id;
                            })[0];
                        }
                        if (!this.institute) {
                            this.institute = this.institutes[0];
                        }
                        this.getAssessment();
                    }).catch(err => {
                })
            },
            getAssessment() {
                if (this.$utils.isEmptyVar(this.institute)) {
                    return;
                }
                this.setSectionsAssessmentAnswer([]);
                let id = this.$route.params.check_assessment_id;
                this.fetchAssessment({id, institute_id: this.institute.id})
                    .then(res => {
                        if (!res.success) {
                            this.setSectionsAssessmentAnswer([]);
                            this.Route({name: 'check-assessments'});
                        } else {
                            this.mAssessment = res.data.assessment;
                            this.mSections = res.data.sections;
                            this.check_assessment_field_inspector = res.data.check_assessment_field_inspector;
                            this.setSectionsAssessmentAnswer(res.data.check_sections);
                            this.$router.replace({
                                name: 'check-assessment-single',
                                params: {check_assessment_id: id},
                                query: {institute_id: this.institute.id}
                            });
                        }
                    }).catch(err => {
                    console.log(err);
                })
            },
            initAssessmentData() {
                this.getInstitutes();
            },
            SaveCheckAssessment() {
                if (this.isSaving) {
                    return;
                }
                if (this.$utils.isEmptyVar(this.institute)) {
                    return;
                }
                this.setSaveTextState({first: true});
                this.postSaveCheckAssessmentAnswer({
                    id: this.check_assessment_field_inspector.id,
                    check_assessment_id: this.$route.params.check_assessment_id,
                    institute_id: this.institute.id,
                    check_assessment_sections: this.mSectionsAssessmentAnswer
                }).then(res => {
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
    @import "../Admin/Assets/reset.scss";
    @import url('../Admin/Assets/iconfont/iconfont.css');
    @import "../Admin/Assets/create-wrapper.scss";

    .md-single-grid.assessment-form {
        min-height: 799px;
    }

    .questions-section.answer {
        top: auto;
        @media screen and (max-width: 692px) {
            top: auto;
        }
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
