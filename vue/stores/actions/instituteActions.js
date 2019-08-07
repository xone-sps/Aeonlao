import {createAxiosClient} from "../../initial/api/axios-client";

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
        fetchSearches(c, i) {
            let request = `limit=${i.limit}&page=${i.page}&q=${i.q}`;
            c.commit('setValidated', {errors: {loading_searches: true}});
            client.get(`${apiUrl}/users/searches/${i.type}?${request}`, ajaxToken(c))
                .then(res => {
                    c.commit('setSearchesData', {type: i.type, data: res.data.data});
                    c.commit('setClearMsg');
                })
                .catch(err => {
                    c.dispatch('HandleError', err.response);
                });
        },
        /*** @UserProfileOptions **/
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
        /*** @UserProfileOptions **/
        fetchOptionProfileData(c, data) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/institute/profile-options`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err);
                    });
            });
        },
        /*** @UserProfileOptions **/
        /*** @UserProfile **/
        postManageUserProfile(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    'institute_name': ['required', {max: 191}],
                    'short_institute_name': ['required', {max: 191}],
                    'phone_number': ['phone number', {max: 191}],
                    'profile_image': [{mimes: 'jpeg,jpg,png,gif,svg'}, {max: 3000}],
                    'public_email': ['email', {max: 191}],
                    'institute_category': ['required'],
                    'parent_institute_category': [{required: {when: 'institute_category.have_parent', equals: 'yes'}}],
                }).then(v => {
                    let info = utils.clone(data);
                    for (let i in info) {
                        if (info.hasOwnProperty(i)) {
                            if (utils.isEmptyVar(info[i])) {
                                info[i] = '';
                            }
                            if (i === 'parent_institute_category' && info[i]) {
                                info[i] = info[i].id;
                            }
                            if (i === 'institute_category' && info[i]) {
                                info[i] = info[i].id;
                            }
                        }
                    }
                    let formData = new FormData();
                    utils.addDataForm(['institute_name', 'short_institute_name', 'phone_number', 'parent_institute_category',
                        'public_email', 'address', 'facebook', 'googlemap', 'website', 'about', 'founded', 'institute_category'], formData, info);
                    if (info.profile_image) {//check if user change image
                        formData.append('profile_image', info.profile_image.file);
                    }
                    client.post(`${apiUrl}/institute/profile-manage`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err);
                        });

                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        /*** @UserProfile **/

        /*** @UserCredentials **/
        postChangeCredentialsUser(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    'new_email': ['email', {max: 191}],
                    'new_password': [{min: 6}, {max: 191}],
                    'new_password_confirmation': [{min: 6}, {max: 191}],
                    'current_password': ['required', {max: 191}],
                }).then(v => {
                    client.post(`${apiUrl}/users/credentials-manage`, data, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err);
                        });

                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        /*** @UserCredentials **/
        /***  @UsersSettings Profile **/

        /*** @DashboardData **/
        fetchDashboardData(c, d) {
            c.commit('setValidated', {errors: {loading_dashboard_data: true}});
            client.get(`${apiUrl}/institute/dashboard-data`, ajaxToken(c))
                .then(res => {
                    c.commit('setClearMsg');
                    c.commit('setDashboardData', res.data.data);
                })
                .catch(err => {
                    c.dispatch('HandleError', err.response);
                });
        },
        /*** @DashboardData **/
        /***@GET_ASSESSMENT**/
        fetchAssessment(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/users/assessment/fetch/${i.id}`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    }).catch(err => {
                    c.dispatch('HandleError', err.response);
                    n(err);
                });
            });
        },
        /***@GET_ASSESSMENT**/
        /***@SAVE_CHECK_ASSESSMENT*/
        postSaveCheckAssessmentAnswer(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    'check_assessment_sections': ['required'],
                }).then(v => {
                    client.post(`${apiUrl}/users/assessment/check-assessment/save-answer/${data.id}`, data, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data);
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err);
                        });

                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        /***@SAVE_CHECK_ASSESSMENT*/
        /***@manageComments */
        postMangeComment(c, data) {
            return new Promise((r, n) => {
                let i, actions = {edit: true, reply: true, 'edit-reply': true};
                if (data.comment.action === 'edit-reply') {
                    data.child.data.type = data.type;
                    i = utils.clone(data.child.data);
                } else {
                    i = utils.clone(data);
                }
                if (actions[i.comment.action]) {
                    i.text = data.comment.poster.text;
                }
                utils.Validate(i, {
                    'text': ['required'],
                }).then((v) => {
                    //clear old invalid validate data
                    data.validated = {};
                    client.post(`${apiUrl}/users/check-assessment-comments-manage`, i, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data);
                            if (res.data.success && data.comment.poster) {
                                data.comment.poster.text = '';
                            }
                            if (i.comment.action === 'post') {
                                c.commit('setCheckAssessmentComments', {data: res.data.comment, position: 'top'});
                            }
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err.response);
                        });
                }).catch(e => {
                    if (actions[i.comment.action]) {
                        data.validated = e.errors;
                    } else {
                        c.commit('setValidated', {errors: e.errors});
                    }
                    n(e);
                });
            })
        },
        /***@manageComments */
        /***@GetComments */
        fetchComments(c, i) {
            return new Promise((r, n) => {
                let request = `&limit=${i.limit}&page=${i.page}&type=${i.type}`;
                client.get(`${apiUrl}/users/check-assessment-comments?check_assessment_id=${i.id}${request}`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        if(!res.data.success){
                            return;
                        }
                        if (i.firstLoad) {
                            c.commit('setCheckAssessmentComments', {data: [], position: 'reset'});
                        }
                        c.commit('setCheckAssessmentComments', {data: res.data.data.data, position: 'bottom'});
                        r(res.data)
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err)
                    });
            });
        },
        /***@GetComments */
        /***@deleteComments */
        deleteComment(c, data) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/users/check-assessment-comments-delete?id=${data.id}&check_assessment_id=${data.check_assessment_id}&isReplyComment=${data.isReplyComment}&type=${data.type}`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        console.log(err);
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    });
            });
        },
        /***@deleteComments */
        /***@AutoUserLogin */
        postAutoUserLogin(c, i) {
            return new Promise((r, n) => {
                client.post(`${apiUrl}/users/auto-login`, {}, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data)
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err)
                    })
            });
        },
        /***@AutoUserLogin */
    }
};
