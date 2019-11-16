import Login from '@com/General/Login.vue'
import Logout from '@com/General/Logout.vue'

import ResetPassword from '@com/General/ResetPassword.vue'
import RegisterInstitute from '@com/General/RegisterInstitute.vue'
import RegisterCheckerFieldInspector from '@com/General/RegisterCheckerFieldInspector.vue'
import Registered from '@com/General/Registered.vue'
import FinishedResetPassword from '@com/General/FinishedResetPassword.vue'
import Home from '@com/General/Default/Home.vue'
import About from '@com/General/Default/about.vue'
import News from '@com/General/Default/News.vue'
import Contact from '@com/General/Default/contact.vue'
import Activity from '@com/General/Default/activity.vue'
import Service from '@com/General/Default/Service.vue'
import NewsSingle from '@com/General/Default/Single/NewsSingle.vue'
import ActivitySingle from '@com/General/Default/Single/ActivitySingle.vue'
import ServiceSingle from '@com/General/Default/Single/ServiceSingle.vue'
import Institute from '@com/General/Default/Institute.vue'
import InstituteSingle from '@com/General/Default/Single/InstituteSingle.vue'
import Document from '@com/General/Default/Document.vue'


import Home2 from '@com/General/Default/Home2.vue'
import Promotion from '@com/General/Default/Promotion.vue'
import Shop from '@com/General/Default/Shop.vue'


const jv0ABI4_ch = 'jv0ABI4k2qmWQfLwSapBKfIQe7Lw0xTTVpa0xGG6';
const FWSfbih_in = 'FWSfbih3KioEQAAOTinfTMME4HT5l8faZ9easpl7';


const metas = {
    guestMeta: {
        requiresVisitor: true,
        except: ['admin', 'super_admin'],
        redirect: 'home', //don't use any route name of requiresVisitor
        path: '/', //don't use any route path of requiresVisitor
    },
    defaultMeta: {
        navFixed: true,
        hideNavFooter: true,
    },
    authMeta: {
        requiresAuth: true
    },
    df(prm) {
        let r = Object.assign({}, this.defaultMeta);
        for (let p in prm) {
            if (prm.hasOwnProperty(p))
                r[p] = prm[p];
        }
        return r;
    }
};

export default [
    {
        path: "/login",
        component: Login,
        name: 'login',
        meta: {
            ...metas.guestMeta, ...metas.df({
                navFixed: false,
            })
        },
    },
    {
        path: '/users-logout',
        name: 'users-logout',
        component: Logout, meta: {
            ...metas.df({
                navFixed: false,
            })
        },
    },
    {
        path: `/${jv0ABI4_ch}`,
        component: RegisterCheckerFieldInspector,
        name: 'checker-filed-in-register',
        meta: {
            ...metas.guestMeta, ...metas.df({
                navFixed: false,
            })
        },
    },
    {
        path: `/${FWSfbih_in}`,
        component: RegisterInstitute,
        name: 'institute-in-register',
        meta: {
            ...metas.guestMeta, ...metas.df({
                navFixed: false,
            })
        },
    },


    {
        path: "/register-successfully",
        component: Registered,
        name: 'registered',
        meta: {
            ...metas.guestMeta, ...metas.df({
                navFixed: false,
            })
        },
    },
    {
        path: "/password/reset/:token",
        component: ResetPassword,
        name: 'reset-password',
        meta: {
            ...metas.guestMeta, ...metas.df({
                navFixed: false,
            })
        },
    },
    {
        path: "/reset-password-successfully",
        component: FinishedResetPassword,
        name: 'finished-reset-password',
        meta: {
            ...metas.guestMeta, ...metas.df({
                navFixed: false,
            })
        },
    },


    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },

    //     {
    //     path: '/',
    //     name: 'home',
    //     component: Home2,
    //     meta: {
    //         ...metas.df({
    //             hideNavFooter: false,
    //         })
    //     },
    // },
      {
        path: '/promotion',
        name: 'promotion',
        component: Promotion,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
          {
        path: '/shop',
        name: 'shop',
        component: Shop,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },

    {
        path: '*', component: Home, meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/about',
        name: 'about',
        component: About,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/news',
        name: 'news',
        component: News,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/news/single/:id',
        name: 'news-single',
        component: NewsSingle,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/activities',
        name: 'activities',
        component: Activity,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/activities/single/:id',
        name: 'activity-single',
        component: ActivitySingle,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/service',
        name: 'service',
        component: Service,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/Service/single/:id',
        name: 'service-single',
        component: ServiceSingle,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/institutes',
        name: 'institute',
        component: Institute,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/posts/institutes/single/:id',
        name: 'institute-single',
        component: InstituteSingle,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
        {
        path: '/documents',
        name: 'document',
        component: Document,
        meta: {
            ...metas.df({
                hideNavFooter: false,
            })
        },
    },
];
