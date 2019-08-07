import Dashboard from '@com/FieldInspector/Dashboard.vue'
import CheckAssessments from '@com/FieldInspector/CheckAssessments.vue'
import CheckAssessmentSingle from '@com/FieldInspector/CheckAssessmentSingle.vue'
import UserProfileSettings from '@com/FieldInspector/Detail/ProfileSettings.vue'

const prefix = '/field-inspector/me';
let adminTypes = ['admin', 'super_admin'];
const metas = {
    authMeta: {
        requiresAuth: true,
      allows: adminTypes.concat(['field_inspector']),
    },
    guestMeta: {
        requiresVisitor: true,
        except: adminTypes,
        redirect: 'field-inspector/me', //don't use any route name of requiresVisitor
        path: '/field-inspector/me', //don't use any route path of requiresVisitor
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
];
