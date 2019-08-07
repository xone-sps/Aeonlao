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
                                                    @click="Route({name: 'create-assessment', query: {assessment_id: mAssessment.id }})"
                                                    class="v-md-button v-md-icon-button theme-blue"><i
                                                    class="material-icons">arrow_back</i>
                                                </button>
                                            </div>
                                            <div class="title-nav">Edit Assessment</div>
                                        </div>
                                        <div class="nav-right">
                                            <div class="actions">
                                                <div>
                                                    <button style="visibility: hidden"
                                                            class="v-md-button primary"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template slot="form-wrap">
                                        <div class="form-create-wrap">
                                            <div class="wrap">
                                                <div class="content-wrap">
                                                    <div class="FormviewerViewAccentBanner AccentBackground"></div>
                                                    <div class="item title main-form">
                                                        <div aria-disabled="true" class="li main-form">
                                                            <div class="assessment-form-title"> {{mAssessment.title}}
                                                            </div>
                                                        </div>
                                                        <div aria-disabled="true" class="li main-form">
                                                            <textarea placeholder="Assessment description (optional)"
                                                                      disabled
                                                                      v-model="mAssessment.description"
                                                                      @focus="$autoText($event)"
                                                                      class="form-desc assessment-form-description"
                                                                      @input="$autoText($event)"
                                                                      ref="assessment-desc-text-id"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </template>

                                </FormTop>
                                <!--Form Top-->

                                <!--Form Content-->

                                <div class="questions-section clearfix" style="top: auto;">
                                    <div class="questions">

                                        <div class="form-create-wrap add-after"
                                             v-for="(section, item_idx) in mSectionsStack" :key="item_idx">
                                            <div class="wrap">
                                                <div class="content-wrap">

                                                    <HeaderBanner :titleStyles="`font-size: 1.1rem;`" :title="section.title" color="#039be5"/>

                                                    <div class="item title main-form preview-item">
                                                        <div aria-disabled="true" class="li main-form">
                                                            <textarea placeholder="Assessment description (optional)"
                                                                      disabled
                                                                      v-model="section.desc"
                                                                      @focus="$autoText($event)"
                                                                      class="form-desc assessment-form-description medium-font-size"
                                                                      @input="$autoText($event)"
                                                                      :ref="`assessment-desc-text-id-${item_idx}`"></textarea>
                                                        </div>
                                                    </div>

                                                    <!--Questions Preview-->

                                                    <div class="q-wrap">
                                                            <ViewQuestionnaire :question="question" v-for="(question, q_idx) in section.questions" :question_index="q_idx" :key="q_idx"/>
                                                    </div>

                                                    <!--Questions Preview-->

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--Form Content-->
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
    import {mapActions, mapMutations, mapState} from 'vuex'
    import FormTop from '@com/Admin/Assessments/AssessmentForm.vue'
    import HeaderBanner from './Includes/HeaderBanner.vue'
    import ViewQuestionnaire from './Question/ViewQuestion/ViewQuestionnaire.vue'

    export default AdminBase.extend({
        name: "PreviewAssessment",
        components: {
            FormTop,
            HeaderBanner,
            ViewQuestionnaire
        },
        data() {
            return {
                title: 'Preview Assessment',
                type: 'none',
                watchers: true,
                tabs: [{name: 'Preview Assessment'}],
            }
        },
        computed: {
            ...mapState(['mEditAssessment', 'mAssessment', 'mSectionsStack']),
        },
        watch: {
            '$route.query': function (n) {
                this.initAssessmentData();
            }
        },
        methods: {
            ...mapMutations([]),
            ...mapActions(['fetchAssessment',]),
            getAssessment() {
                let id = this.$route.params.id;
                this.fetchAssessment({id})
                    .then(res => {
                        if (!res.success) {
                            this.Route({name: 'assessment'});
                        }
                    }).catch(err => {
                })
            },
            initAssessmentData() {
                this.getAssessment();
            }
        },
        beforeRouteLeave(to, from, next) {
            next();
        },
        created() {
            this.initAssessmentData();
        },
    });
</script>
<style lang="scss">
    @import "../Assets/reset.scss";
    @import url('../Assets/iconfont/iconfont.css');
    @import "../Assets/create-wrapper.scss";

    .md-single-grid.assessment-form {
        min-height: 799px;
    }
</style>
