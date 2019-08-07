import Dashboard from '@com/Admin/Dashboard.vue'


import Checker from '@com/Admin/Members/Checker.vue'
import FieldInspector from '@com/Admin/Members/FieldInspector.vue'

import Assessment from '@com/Admin/Assessments/All.vue'
import SendAssessment from '@com/Admin/Assessments/Send.vue'
import CreateAssessment from '@com/Admin/Assessments/Create.vue'
import PreviewAssessment from '@com/Admin/Assessments/Preview.vue'
import ReviewAssessment from '@com/Admin/Assessments/ReviewAssessment.vue'
import CheckAssessmentSingle from '@com/Admin/Assessments/CheckAssessment/CheckAssessmentSingle.vue'

import Institutes from '@com/Admin/Institutes/All.vue'
import InstituteCategory from '@com/Admin/Institutes/InstituteCategory.vue'

import SiteSetting from '../components/Admin/Default/SiteSetting.vue'
import ContactInfo from '@com/Admin/Posts/ContactInfo.vue'
import AboutUs from '@com/Admin/Posts/AboutUs.vue'
import News from '@com/Admin/Posts/News.vue'
import Activity from '@com/Admin/Posts/Activity.vue'
import Scholarship from '@com/Admin/Posts/Scholarship.vue'
import UploadFile from '@com/Admin/Posts/Uploadfile.vue'
import ReviewAssessmentFieldInspector from "@com/Admin/Assessments/ReviewAssessmentFieldInspector.vue";
import CheckAssessmentFieldInspectorSingle
    from "@com/Admin/Assessments/CheckAssessment/CheckAssessmentFieldInspectorSingle.vue";

const prefix = '/admin/me';
const metas = {
    authMeta: {
        requiresAuth: true,
        allows: ['admin', 'super_admin']
    },
    guestMeta: {
        requiresVisitor: true,
        except: ['admin', 'super_admin'],
        redirect: 'admin/me', //don't use any route name of requiresVisitor
        path: '/admin/me', //don't use any route path of requiresVisitor
    }
};

export default [
    {
        path: `${prefix}/sitesetting`,
        component: SiteSetting,
        name: 'sitesetting',
        meta: metas.authMeta,
    },
    {
        path: prefix,
        component: Dashboard,
        name: 'dashboard',
        meta: metas.authMeta,
    },
    {
        name: 'checker',
        path: `${prefix}/members/checker`,
        component: Checker,
        meta: metas.authMeta,
    },
    {
        name: 'field-inspector',
        path: `${prefix}/members/field-inspector`,
        component: FieldInspector,
        meta: metas.authMeta,
    },
    {
        path: `${prefix}/contactinfo`,
        name: 'contactinfo',
        component: ContactInfo,
        meta: metas.authMeta,
    }, {
        path: `${prefix}/about-us`,
        name: 'about-us',
        component: AboutUs,
        meta: metas.authMeta,
    },
    {
        path: `${prefix}/news`,
        name: 'news',
        component: News,
        meta: metas.authMeta,
    },
    {
        path: `${prefix}/activity`,
        name: 'activity',
        component: Activity,
        meta: metas.authMeta,
    },
    {
        path: `${prefix}/scholarship`,
        name: 'scholarship',
        component: Scholarship,
        meta: metas.authMeta,
    },

    {
        path: `${prefix}/upload-files`,
        name: 'uploadfile',
        component: UploadFile,
        meta: metas.authMeta,
    },
    {
        name: 'review-assessment',
        path: `${prefix}/review-assessment`,
        component: ReviewAssessment,
        meta: metas.authMeta,
    },
    {
        name: 'check-assessment-single',
        path: `${prefix}/review-assessment/:check_assessment_id`,
        component: CheckAssessmentSingle,
        meta: metas.authMeta,
    },
    {
        name: 'assessment',
        path: `${prefix}/assessment`,
        component: Assessment,
        meta: metas.authMeta,
    },
    {
        name: 'create-assessment',
        path: `${prefix}/assessment/start-assessment`,
        component: CreateAssessment,
        meta: metas.authMeta,
    },
    {
        name: 'preview-assessment',
        path: `${prefix}/assessment/preview-assessment/:id`,
        component: PreviewAssessment,
        meta: metas.authMeta,
    },
    {
        name: 'send-assessment',
        path: `${prefix}/assessment/send`,
        component: SendAssessment,
        meta: metas.authMeta,
    },

    {
        name: 'institute',
        path: `${prefix}/members/institute`,
        component: Institutes,
        meta: metas.authMeta,
    },
    {
        name: 'institute-category',
        path: `${prefix}/institute-categories`,
        component: InstituteCategory,
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
