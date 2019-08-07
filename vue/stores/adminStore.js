import {axiosClient, createActions} from "./actions/adminActions";
import {createInit, defaultActions, defaultGetters, defaultMutations, defaultStates} from '../initial/initializer';
//@end set vue prototype
//@Vue UNDO REDO
import VuexUndoRedo from '../plugin/vuex-undo-redo-plugin.js';

/**
 * @initialize
 */
let {Vue, Vuex, $utils, initRouter} = createInit();
let addedActions = createActions($utils);
let {client, ajaxConfig, apiUrl} = axiosClient();
//@start set vue prototype
Vue.prototype.initRouter = initRouter;
Vue.prototype.apiUrl = apiUrl;
Vue.prototype.ajaxConfig = ajaxConfig;
Vue.prototype.client = client;

//declare default empty state
Vue.use(VuexUndoRedo, {
    watchOnly: true, watchMutations: [
        'setAssessmentTextValueChangeDataStack',
        'addSectionDataStack',
        'deleteSectionDataStack',
        'addSectionQuestionDataStack',
        'deleteSectionQuestionDataStack',
        'addRadioOptionQuestionDataStack',
        'deleteRadioOptionQuestionDataStack',
        'setTextValueChangeDataStack',
        'setOptionValueChangeDataStack',
        'setMoveQuestionSectionDataStack',
        'setMoveQuestionSectionOptionsDataStack',
        'setMovementQuestionSectionDataStack',
        'setOptionsScaleChangeDataStack',
    ],
    exceptNewMutations: [
        'setCurrentFocusQuestionIndex',
        'setCurrentFocusSectionIndex',
    ]
});
//@END VUE UNDO REDO
export default new Vuex.Store({
    state: {
        ...defaultStates,
        isSidebarCollapsed: '',
        isSidebarMobileOpen: '',
        userImage: 'user.png' + settings.fresh_version,
        authInfo: {img: 'admin.png' + settings.fresh_version},
        sidebarWidth: {normal: 256, collapsed: 68},
        isSidebar: '',
        selectedSidebarItem: {},
        menuContext: {menus: []},
        menuContextItemClicked: {},
        searchQuery: {text: '', filters: {}},
        dashboardData: {
            activities_count: 0,
            latest_members_count: 0,
            news_count: 0,
            scholarships_count: {active: 0, all: 0},
            assessment_count: {active: 0, all: 0, close: 0, success: 0},
            members: {institute_count: 0, field_inspector_count: 0, checker_count: 0},
        },
        searchesData: {
            institute_category: {},
            news: {},
            activity: {},
            scholarship: {},
            banner: {},
            file: {},
            users_checker: {},
            users_field_inspector: {},
            users_institute: {},
            assessments: {},
            check_assessments: {},
            check_assessments_field_inspector: {},
        },
        searchesAllowed: {
            institute_category: true,
            news: true,
            activity: true,
            scholarship: true,
            banner: true,
            file: true,
            users_checker: true,
            users_field_inspector: true,
            users_institute: true,
            assessments: true,
            check_assessments: true,
            check_assessments_field_inspector: true,
        },
        //@stack state
        mEditAssessment: false,
        mAssessmentEmptyState: {},
        mAssessment: {},
        mSectionsStackEmptyState: [],
        mSectionsStack: [],
        mInitEmptyStateCalled: false,
        currentFocusIndexes: {sectionIndex: 0, questionIndexes: {sectionIndex: 0, questionIndex: -1}},
        //@stack state
        mSectionsAssessmentAnswer: [],
        checkAssessmentComments: [],
    },
    getters: {
        ...defaultGetters,
        getSideBarWidthForTabs(s) {
            return s.isSidebarCollapsed !== '' ? s.sidebarWidth.collapsed : s.sidebarWidth.normal;
        }
    },
    mutations: {
        ...defaultMutations,
        setSidebar(s, p) {
            s.isSidebar = p.isSidebar;
        },
        setSidebarCollapsed(s, p) {
            s.isSidebarCollapsed = s.isSidebarCollapsed === 'app-sidebar-collapsed' ? '' : 'app-sidebar-collapsed'
        },
        setSelectedSidebarItem(s, p) {
            s.selectedSidebarItem = p;
            if (s.isMobile)
                s.isSidebarMobileOpen = '';
            $utils.setWindowTitle(`${p.name} | ${settings.site_name}`);
        },
        setSidebarMobileOpen(s, p) {
            s.isSidebarMobileOpen = p.isOpen ? 'mobile-nav-open' : ''
        },
        setMenuContext(s, p) {
            if (p.el)
                p.el.stopPropagation();
            s.menuContext = p;
        },
        setOnMenuContextItemClick(s, p) {
            s.menuContextItemClicked = {};
            s.menuContextItemClicked = p;
        },
        setSearchesData(s, p) {
            if (!!s.searchesAllowed[p.type]) {
                s.searchesData[p.type] = p.data;
                s.searchesData[p.type].items = [];
            }
        },
        setSearchQuery(s, p) {
            s.searchQuery.text = p.text;
            s.searchQuery.filters = p.filters;
        },
        setDashboardData(s, p) {
            s.dashboardData = p;
        },
        //@Stack
        addSectionDataStack(s, p) {
            let schema = Object.assign({}, p.schema);//to spilt up questions section
            schema.questions = Object.assign([], schema.questions);//we need to clone array object if not they will connect each other
            s.mSectionsStack.splice(p.index, 0, schema);
            s.currentFocusIndexes.sectionIndex = p.type && p.type === 'duplicate' ? p.index + 1 : p.index;
            if (!schema.questions.length) {
                s.currentFocusIndexes.questionIndexes.sectionIndex = s.currentFocusIndexes.sectionIndex;
                s.currentFocusIndexes.questionIndexes.questionIndex = -1;
            }
            if (p.type === 'duplicate') {
                s.currentFocusIndexes.questionIndexes.sectionIndex = s.currentFocusIndexes.sectionIndex;
            }
            //set vertical scroll
            p.component.setSectionFocusItemPositioner();
        },
        deleteSectionDataStack(s, p) {
            s.mSectionsStack.splice(p.index, 1);
            s.currentFocusIndexes.sectionIndex = p.index === 0 ? 0 : p.index - 1;
            //set vertical scroll
            p.component.setSectionFocusItemPositioner();
        },
        addSectionQuestionDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex],
                question = $utils.deepCopy(p.list);
            question.hash_id = $utils.hashCode(`sec-${p.sectionIndex}-q-${p.index}-time-${new Date().getMilliseconds()}`);
            mSection.questions.splice(p.index, 0, question);
            //set current section index
            s.currentFocusIndexes.sectionIndex = p.sectionIndex;
            //set focus index
            s.currentFocusIndexes.questionIndexes.sectionIndex = p.sectionIndex;
            s.currentFocusIndexes.questionIndexes.questionIndex = p.focusIndex;
            //set vertical scroll
            p.component.setSectionQuestionFocusItemPositioner();
            //set positioner position
            p.component.setPlacePositioner();
        },
        deleteSectionQuestionDataStack(s, p) {
            let section = s.mSectionsStack[p.sectionIndex];
            section.questions.splice(p.index, 1);
            //set current section index
            s.currentFocusIndexes.sectionIndex = p.sectionIndex;
            //@set focus index
            //set focus index
            s.currentFocusIndexes.questionIndexes.sectionIndex = p.sectionIndex;
            s.currentFocusIndexes.questionIndexes.questionIndex = p.focusIndex;
            //set vertical scroll
            p.component.setSectionQuestionFocusItemPositioner();
            //set positioner position
            p.component.setPlacePositioner();
        },
        addRadioOptionQuestionDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex],
                mQuestion = mSection.questions[p.question_index], list;
            if (p.type === 'row') {
                list = mQuestion.content[p.option_answer_index].row_option_answers;
            } else {
                list = mQuestion.content[p.option_answer_index].option_answers;
            }
            //add answer item
            let index = list.length ? parseInt((list[list.length - 1].description.match(/\d$/g) || []).join('')) + 1 : '1';
            let text = index ? 'Option ' + index : 'Option 1';
            let hash_id = $utils.hashCode(`sec-${p.sectionIndex}-q-${p.question_index}-content${p.option_answer_index}-options-${list.length + 1}-time-${new Date().getMilliseconds()}`);
            list.push({hash_id, description: text});
        },
        deleteRadioOptionQuestionDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex],
                mQuestion = mSection.questions[p.question_index], list;
            if (p.type === 'row') {
                list = mQuestion.content[p.option_answer_index].row_option_answers;
            } else {
                list = mQuestion.content[p.option_answer_index].option_answers;
            }
            //delete answer item
            list.splice(p.index, 1);
        },
        setTextValueChangeDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex];
            if (p.type === 'section') {
                mSection[p.key] = p.value;
            } else if (p.type === 'question_title') {
                mSection.questions[p.schema.question_index].content[p.schema.content_index].title = p.value;
            } else if (p.type === 'option_answers') {
                mSection.questions[p.schema.question_index].content[p.schema.content_index].option_answers[p.schema.option_index].description = p.value;
            } else if (p.type === 'option_answers_linear_scale') {
                let scale = mSection.questions[p.schema.question_index].content[p.schema.content_index][p.key];
                scale[p.schema.key] = p.value;
            } else if (p.type === 'row_option_answers') {
                let scale = mSection.questions[p.schema.question_index].content[p.schema.content_index].row_option_answers;
                scale[p.schema.option_index][p.key] = p.value;
            }
        },
        setOptionValueChangeDataStack(s, p) {
            let mSection = s.mSectionsStack[p.schema.sectionIndex];
            if (p.schema.key === 'types') {
                mSection.questions[p.schema.question_index].types = p.value;
            }
            if (p.schema.key === 'is_required') {
                mSection.questions[p.schema.question_index].is_required = p.value;
            }
        },
        setOptionsScaleChangeDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex];
            let scale = mSection.questions[p.question_index].content[p.answer_schema_index];
            scale.line_answer = $utils.deepCopy(p.line_answer);
        },
        setAssessmentTextValueChangeDataStack(s, p) {
            s.mAssessment[p.key] = p.value;
        },
        setMoveQuestionSectionDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex];
            mSection.questions = $utils.deepCopy(p.questions);
            p.component.setPlacePositioner();
        },
        setMovementQuestionSectionDataStack(s, p) {
            s.currentFocusIndexes.sectionIndex = p.sectionIndex;
            s.currentFocusIndexes.questionIndexes.sectionIndex = p.sectionIndex;
            s.currentFocusIndexes.questionIndexes.questionIndex = p.questionIndex;
        },
        setMoveQuestionSectionOptionsDataStack(s, p) {
            let mSection = s.mSectionsStack[p.sectionIndex],
                mContent = mSection.questions[p.question_index].content[p.answer_schema_index];
            if (p.type === 'row_options_scale') {
                mContent.row_option_answers = $utils.deepCopy(p.options);
            } else {
                mContent.option_answers = $utils.deepCopy(p.options);
            }
        },
        emptyState(s) {
            this.replaceState({
                ...s,
                mSectionsStack: $utils.deepCopy(s.mSectionsStackEmptyState),
                mAssessment: $utils.deepCopy(s.mAssessmentEmptyState),
            });
        },
        //@endStack
        setCurrentFocusQuestionIndex(s, p) {
            s.currentFocusIndexes.questionIndexes.sectionIndex = p.sectionIndex;
            s.currentFocusIndexes.questionIndexes.questionIndex = p.questionIndex;
        },
        setCurrentFocusSectionIndex(s, p) {
            s.currentFocusIndexes.sectionIndex = p;
            s.currentFocusIndexes.questionIndexes.sectionIndex = p;
        },
        setEditAssessmentStatus(s, p) {
            s.mEditAssessment = p;
        },
        setInitEmptyStateCalled(s, p) {
            s.mInitEmptyStateCalled = p;
        },
        setInitEditAssessmentEmptyState(s, p) {
            s.currentFocusIndexes.sectionIndex = 0;
            if (p.sections.length && p.sections[0].questions.length) {
                p.sections[0].focusIndex = 0;
                s.currentFocusIndexes.questionIndexes.sectionIndex = 0;
                s.currentFocusIndexes.questionIndexes.questionIndex = 0;
            }
            s.mAssessmentEmptyState = $utils.deepCopy(p.assessment);
            s.mAssessment = $utils.deepCopy(p.assessment);
            s.mSectionsStackEmptyState = $utils.deepCopy(p.sections);
            s.mSectionsStack = $utils.deepCopy(p.sections);
            s.mInitEmptyStateCalled = true;
            s.mEditAssessment = true;
        },
        setDefaultAssessmentEmptyState(s) {
            s.currentFocusIndexes.sectionIndex = 0;
            s.currentFocusIndexes.questionIndexes.sectionIndex = 0;
            s.currentFocusIndexes.questionIndexes.questionIndex = -1;
            s.mAssessmentEmptyState = {id: null, title: '', description: ''};
            s.mAssessment = {id: null, title: '', description: ''};
            s.mSectionsStackEmptyState = [
                {
                    id: null,
                    title: '',
                    desc: '',
                    focusIndex: -1,
                    questions: []
                }
            ];
            s.mSectionsStack = [
                {
                    id: null,
                    title: '',
                    desc: '',
                    focusIndex: -1,
                    questions: []
                }
            ];
            s.mInitEmptyStateCalled = true;
        },
        setSectionsAssessmentAnswer(s, p) {
            s.mSectionsAssessmentAnswer = p;
        },
        setCheckAssessmentComments(s, p) {
            if (p.position === 'top') {
                s.checkAssessmentComments.unshift(p.data);
                s.checkAssessmentComments.pop();
            } else if (p.position === 'reset') {
                s.checkAssessmentComments = p.data;
            } else {
                s.checkAssessmentComments = s.checkAssessmentComments.concat(p.data);
            }
        }
    },
    actions: {
        ...defaultActions(axiosClient()),
        ...addedActions,
        setPageTitle(c, n) {
            if (n !== c.state.selectedSidebarItem.name)
                c.commit('setSelectedSidebarItem', {name: n});
        },
        showErrorToast(c, data) {
            Vue.toasted.error(data.msg, {
                duration: data.dt,
                action: {
                    text: 'Close',
                    onClick: (e, t) => {
                        t.goAway(0);
                    }
                }
            });
        },
        showInfoToast(c, data) {
            Vue.toasted.show(data.msg, {
                duration: data.dt,
                action: {
                    text: 'Close',
                    onClick: (e, t) => {
                        t.goAway(0);
                    }
                }
            });
        },
    } //end actions
});
