import Dashboard from '@com/Checker/Dashboard.vue'
import CheckAssessments from '@com/Checker/CheckAssessments.vue'
import CheckAssessmentSingle from '@com/Checker/CheckAssessment/CheckAssessmentSingle.vue'
import UserProfileSettings from '@com/Checker/Detail/ProfileSettings.vue'
import ReviewAssessmentFieldInspector from "@com/Checker/ReviewAssessmentFieldInspector.vue";
import CheckAssessmentFieldInspectorSingle
    from "@com/Checker/CheckAssessment/CheckAssessmentFieldInspectorSingle.vue";

const prefix = '/checker/me';
let adminTypes = ['admin', 'super_admin'];
const metas = {
    authMeta: {
        requiresAuth: true,
      allows: adminTypes.concat(['checker']),
    },
    guestMeta: {
        requiresVisitor: true,
        except: adminTypes,
        redirect: 'checker/me', //don't use any route name of requiresVisitor
        path: '/checker/me', //don't use any route path of requiresVisitor
    }
};

export default [

    {
        path: prefix,
        component: Dashboard,
        name: 'dashboard',
        meta: metas.authMeta,
    },
    {
        name: 'profile-settings',
        path: `${prefix}/profile-settings`,
        component: UserProfileSettings,
        meta: metas.authMeta,
    },
    {
        name: 'check-assessments',
        path: `${prefix}/check-assessments`,
        component: CheckAssessments,
        meta: metas.authMeta,
    },
    {
        name: 'check-assessment-single',
        path: `${prefix}/check-assessments/:check_assessment_id`,
        component: CheckAssessmentSingle,
        meta: metas.authMeta,
    },
    {
        name: 'review-assessments-field-inspector',
        path: `${prefix}/review-assessments-field-inspector`,
        component: ReviewAssessmentFieldInspector,
        meta: metas.authMeta,
    },
    {
        name: 'review-assessments-field-inspector-single',
        path: `${prefix}/review-assessments-field-inspector/:check_assessment_id`,
        component: CheckAssessmentFieldInspectorSingle,
        meta: metas.authMeta,
    },
];
