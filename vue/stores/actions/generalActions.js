import {createAxiosClient} from "@vue/initial/api/axios-client";

let {client, ajaxConfig, apiUrl} = createAxiosClient();
const ajaxToken = (c, formData = false) => {
    if (formData) {
        ajaxConfig.addHeader('Content-Type', 'multipart/form-data');
    } else {
        ajaxConfig.addHeader('Content-Type', 'application/json');
    }
    return ajaxConfig.addHeader('CL-Token', c.getters.getToken);
};
export const axiosClient = () => createAxiosClient();
export const createActions = (utils) => {
    return {
        /**
         * @REGISTER_CHECKER_FIELD_INSPECTOR
         */
        register(c, user) {
            return new Promise((r, n) => {
                utils.Validate(user, {
                    'first_name': ['required'],
                    'last_name': ['required'],
                    'type': ['required'],
                    'email': ['email', 'required'],
                    'password': ['required', 'confirm', {min: 6}],
                    'password_confirmation': ['required', {min: 6}],
                }).then((v) => {
                    c.commit('setValidated', {errors: {loading: 'yes'}});
                    client.post(`${apiUrl}/guest/${user.url}-post`, user, ajaxConfig)
                        .then(res => {
                            c.commit('setClearMsg', {delay: 1000});
                            utils.setSession('registered', true);
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err.response);
                        });
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });

            })
        },
        /**
         * @END_REGISTER_CHECKER_FIELD_INSPECTOR
         */
        /**
         * @REGISTER_INSTITUTE
         */
        fetchInstituteParentCategories(c, id) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/guest/institute/category/list-parents/${id}`, ajaxConfig.getHeaders())
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data.data);
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    });
            });

        },
        registerInstitute(c, user) {
            return new Promise((r, n) => {
                utils.Validate(user, {
                    'institute_name': ['required', {max: 191}],
                    'short_name': ['required', {max: 90}],
                    'email': ['email', 'required'],
                    'password': ['required', 'confirm', {min: 6}],
                    'password_confirmation': ['required', {min: 6}],
                    'institute_category': ['required'],
                    'parent_institute_category': [{required: {when: 'institute_category.have_parent', equals: 'yes'}}],
                }).then((v) => {
                    c.commit('setValidated', {errors: {loading: 'yes'}});
                    client.post(`${apiUrl}/guest/${user.url}-post`, user, ajaxConfig)
                        .then(res => {
                            c.commit('setClearMsg', {delay: 1000});
                            utils.setSession('registered', true);
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err.response);
                        });
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });

            })
        },
        /**
         * @END_REGISTER_INSTITUTE
         */
        /*** @InstituteCategoriesData **/
        /*** @HomeData **/
        fetchHomeData(c, i) {
            client.get(`${apiUrl}/home/index`, ajaxConfig.getHeaders())
                .then(res => {
                    c.commit('setClearMsg');
                    c.commit('setHomeData', res.data.data)
                })
                .catch(err => {
                    c.dispatch('HandleError', err.response);
                });
        },
        /*** @HomeData **/
        /*** @ResetPasswordActionsAndData **/
        initResetForm(c, token) {
            return new Promise((r, n) => {
                c.commit('setValidated', {errors: {loading_reset: true}});
                client.post(`${apiUrl}/guest/forgot-password/email/${token}`, {}, ajaxConfig)
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    });
            })
        },
        resetPassword(c, user) {
            return new Promise((r, n) => {
                utils.Validate(user, {
                    'email': ['email', 'required'],
                    'password': ['required', 'confirm', {min: 6}],
                    'password_confirmation': ['required', {min: 6}],
                }).then((v) => {
                    c.commit('setValidated', {errors: {loading_reset: true}});
                    client.post(`${apiUrl}/guest/password/reset`, user, ajaxConfig)
                        .then(res => {
                            c.commit('setClearMsg', {delay: 1000});
                            utils.setSession('finished-reset-password', true);
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err.response);
                        });
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            })
        },
        /*** @ResetPasswordActionsAndData **/
        /*** @PostsData **/
        fetchPostsData(c, i) {
            let options_request = '';
            for (let o in i.options) {
                if (i.options.hasOwnProperty(o)) {
                    options_request += `&${o}=${i.options[o] | ''}`;
                }
            }
            let request = `limit=${i.limit}&page=${i.page}&q=${i.q}${options_request}`;
            c.commit('setValidated', {errors: {loading_search_posts: true}});
            client.get(`${apiUrl}/home/posts/${i.type}?${request}`, ajaxConfig.getHeaders())
                .then(res => {
                    c.commit('setSearchQuery', {text: i.q, filters: i.filters});
                    c.commit('setClearMsg', {delay: 300});
                    c.commit('setPostsData', {data: res.data, type: i.type})
                })
                .catch(err => {
                    c.dispatch('HandleError', err.response);
                });
        },
        /*** @PostsData **/
        /*** @PostsData **/
        fetchSinglePostsData(c, i) {
            c.commit('setValidated', {errors: {loading_single_posts: true}});
            client.get(`${apiUrl}/home/posts/${i.type}/single/${i.id}`, ajaxConfig.getHeaders())
                .then(res => {
                    if (res.data.success !== false) {
                        c.commit('setClearMsg', {delay: 300});
                        c.commit('setSinglePostsData', {data: res.data, type: i.type})
                    } else {
                        utils.Location('/');
                    }
                })
                .catch(err => {
                    c.dispatch('HandleError', err.response);
                });
        },
        /*** @PostsData **/
        /***@SendContactInfo */
        postSendContact(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    'email': ['email', 'required'],
                    'subject': ['required', {max: 191}],
                    'name': ['required', {max: 191}],
                    'message': ['required'],
                }).then((v) => {
                    c.commit('setValidated', {errors: {loading_send_contact: true}});
                    client.post(`${apiUrl}/home/contact-info`, data, ajaxConfig)
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err.response);
                        });
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            })
        },
        /***@SendContactInfo */
        /***@MembersChartRange */
        fetchMembersChartRange(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/home/chart-ranges/${i.id}?q=`, ajaxConfig)
                    .then(res => {
                        c.commit('setClearMsg');
                        c.commit('setChartData', res.data.data);
                        r(res.data)
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err)
                    });
            });
        },
        /***@MembersChartRange */
    }
};
