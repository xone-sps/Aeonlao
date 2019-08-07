import Dashboard from '@com/Institute/Dashboard.vue'
import UserProfileSettings from '@com/Institute/Detail/ProfileSettings.vue'
import CheckAssessments from '@com/Institute/CheckAssessments.vue'
import CheckAssessmentSingle from '@com/Institute/CheckAssessmentSingle.vue'

const prefix = '/institute/me';
let adminTypes = ['admin', 'super_admin'];
const metas = {
    authMeta: {
        requiresAuth: true,
        allows: adminTypes.concat(['institute']),
    },
    guestMeta: {
        requiresVisitor: true,
        except: adminTypes,
        redirect: 'institute/me', //don't use any route name of requiresVisitor
        path: '/institute/me', //don't use any route path of requiresVisitor
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
