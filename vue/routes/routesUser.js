import Dashboard from '@com/User/Dashboard.vue'
import UserProfileSettings from '@com/User/Member/ProfileSettings.vue'
import MembersProfile from '@com/User/Member/MembersProfile.vue'
import SingleMemberProfile from '@com/User/Member/SingleMemberProfile.vue'
import News from '@com/User/Posts/News.vue'
import Activity from '@com/User/Posts/Activity.vue'
import Event from '@com/User/Posts/Event.vue'
import DownloadFile from '@com/User/Posts/DownloadFile.vue'

const prefix = '/users/me';
const metas = {
    authMeta: {
        requiresAuth: true
    },
    guestMeta: {
        requiresVisitor: true,
        except: ['admin', 'super_admin'],
        redirect: 'user/me', //don't use any route name of requiresVisitor
        path: '/user/me', //don't use any route path of requiresVisitor
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
        name: 'user-profile-settings',
        path: `${prefix}/profile-settings`,
        component: UserProfileSettings,
        meta: metas.authMeta,
    },
    {
        name: 'members-profile',
        path: `${prefix}/members-profile`,
        component: MembersProfile,
        meta: metas.authMeta,
    },
    {
        name: 'member-profile',
        path: `${prefix}/members-profile/:user_id`,
        component: SingleMemberProfile,
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
        path: `${prefix}/event`,
        name: 'event',
        component: Event,
        meta: metas.authMeta,
    },
    {
        path: `${prefix}/download-files`,
        name: 'download-file',
        component: DownloadFile,
        meta: metas.authMeta,
    },
];
