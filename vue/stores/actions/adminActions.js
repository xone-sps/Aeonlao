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
            let options_request = '';
            for (let o in i.options) {
                if (i.options.hasOwnProperty(o)) {
                    options_request += `&${o}=${i.options[o] | ''}`;
                }
            }
            let request = `limit=${i.limit}&page=${i.page}&q=${i.q}${options_request}`;
            c.commit('setValidated', {errors: {loading_searches: true}});
            client.get(`${apiUrl}/admin/searches/${i.type}?${request}`, ajaxToken(c))
                .then(res => {
                    c.commit('setSearchesData', {type: i.type, data: res.data.data});
                    c.commit('setClearMsg');
                })
                .catch(err => {
                    c.commit('setClearMsg');
                    c.dispatch('HandleError', err.response);
                });
        },
        /***  @Users  **/
        postChangeUserStatus(c, i) {
            return new Promise((r, n) => {
                client.post(`${apiUrl}/admin/users-change-status/${i.id}`, i.data, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.commit('setClearMsg');
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    })
            });
        },
        postDeleteUser(c, i) {
            return new Promise((r, n) => {
                client.post(`${apiUrl}/admin/users-delete/${i.id}`, i.data, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    })
            });
        },
        postRegisterUser(c, user) {
            return new Promise((r, n) => {
                utils.Validate(user, {
                    'first_name': ['required'],
                    'last_name': ['required'],
                    'email': ['email', 'required'],
                    'password': ['required', 'confirm', {min: 6}]
                }).then((v) => {
                    //directly add password confirmation  for admin
                    user.password_confirmation = user.password;
                    client.post(`${apiUrl}/admin/users-register`, user, ajaxConfig)
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
        /***  @Users  **/
        /*** @InstituteCategories **/
        fetchInstituteCategories(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/admin/institute/category`, ajaxToken(c))
                    .then(res => {
                        r(res.data)
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err)
                    });
            });

        },
        postCreateInstituteCategory(c, organize) {
            return new Promise((r, n) => {
                utils.Validate(organize, {
                    'name': ['required', {max: 191}],
                    'have_parent': ['required', {max: 10}],
                    'parent_categories': [{required: {when: 'have_parent', equals: true}}],
                }).then(v => {
                    client.post(`${apiUrl}/admin/institute/category/create`, organize, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postUpdateInstituteCategory(c, organize) {
            return new Promise((r, n) => {
                utils.Validate(organize, {
                    'category_name': ['required', {max: 191}],
                    'have_parent': ['required', {max: 10}],
                    'parent_categories': [{required: {when: 'have_parent', equals: true}}],
                }).then(v => {
                    client.post(`${apiUrl}/admin/institute/category/update/${organize.id}`, organize, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postDeleteInstituteCategory(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/institute/category/delete/${i.id}`, ajaxToken(c))
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
        /*** @EndInstituteCategories **/
        /*** @ContactInfo **/
        fetchContactInfo(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/admin/contactinfo`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.commit('setClearMsg');
                        c.dispatch('HandleError', err.response);
                        n(err);
                    });
            });
        },
        postMangeContactInfo(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    phone: ["required", 'phone number'],
                    email: ["required", 'email'],
                    address: ["required"]
                }).then(v => {
                    client.post(`${apiUrl}/admin/contactinfo/manage`, data, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        /*** @ContactInfo **/
        /*** @AboutInfo **/
        fetchAboutInfo(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/admin/aboutinfo`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.commit('setClearMsg');
                        c.dispatch('HandleError', err.response);
                        n(err);
                    });
            });
        },
        postMangeAboutInfo(c, data) {
            return new Promise((r, n) => {
                client.post(`${apiUrl}/admin/aboutinfo/manage`, data, ajaxToken(c))
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
        /*** @AboutInfo **/
        /*** @News **/
        postCreateNews(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    title: ["required"],
                    image: ["required", {mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                    description: ["required"]
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['title', 'description'], formData, data);
                    formData.append('image', data.image.file);
                    client.post(`${apiUrl}/admin/news/create`, formData, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postUpdateNews(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    title: ["required"],
                    image: [{mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                    description: ["required"]
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['title', 'description'], formData, data);
                    if (data.image && data.image.file) {//check if user change image
                        formData.append('image', data.image.file);
                    }
                    client.post(`${apiUrl}/admin/news/update/${data.id}`, formData, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postDeleteNews(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/news/delete/${i.id}`, ajaxToken(c))
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
        /*** @News **/
        /*** @Activity **/
        postCreateActivity(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    title: ["required"],
                    place: ["required"],
                    activity_date: ["required"],
                    image: ["required", {mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                    description: ["required"]
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['title', 'place', 'description', 'activity_date'], formData, data);
                    formData.append('image', data.image.file);
                    client.post(`${apiUrl}/admin/activity/create`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postUpdateActivity(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    title: ["required"],
                    place: ["required"],
                    image: [{mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                    activity_date: ["required"],
                    description: ["required"]
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['title', 'place', 'description', 'activity_date'], formData, data);
                    if (data.image && data.image.file) {//check if user change image
                        formData.append('image', data.image.file);
                    }
                    client.post(`${apiUrl}/admin/activity/update/${data.id}`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postDeleteActivity(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/activity/delete/${i.id}`, ajaxToken(c))
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
        /*** @Activity **/
        /*** @Scholarship **/
        postCreateScholarship(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    title: ["required"],
                    place: ["required"],
                    deadline: ["required"],
                    scholarship_type: ["required"],
                    image: ["required", {mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                    description: ["required"]
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['title', 'place', 'description', 'deadline', 'scholarship_type'], formData, data);
                    formData.append('image', data.image.file);
                    client.post(`${apiUrl}/admin/scholarship/create`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postUpdateScholarship(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    title: ["required"],
                    image: [{mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                    scholarship_deadline: ["required"],
                    place: ["required"],
                    scholarship_type: ["required"],
                    description: ["required"]
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['title', 'place', 'description', 'scholarship_deadline', 'scholarship_type'], formData, data);
                    if (data.image && data.image.file) {//check if user change image
                        formData.append('image', data.image.file);
                    }
                    client.post(`${apiUrl}/admin/scholarship/update/${data.id}`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postDeleteScholarship(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/scholarship/delete/${i.id}`, ajaxToken(c))
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
        /*** @Scholarship **/
        /*** @SiteInfo **/
        postManageSiteInfo(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    'site_name': ['required', {max: 191}],
                    'website_logo': [{max: 2000}, {mimes: 'png,jpg,jpeg,svg'}],
                    'favorite_icon': [{max: 2000}, {mimes: 'png,jpg,jpeg,svg'}],
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['site_name'], formData, data);
                    if (data.website_logo) {
                        formData.append('website_logo', data.website_logo.file);
                    }
                    if (data.favorite_icon) {
                        formData.append('favorite_icon', data.favorite_icon.file);
                    }
                    client.post(`${apiUrl}/admin/site-info/manage`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        getSiteInfo(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/admin/site-info`, ajaxToken(c))
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
        /*** @SiteInfo **/
        /*** @SingleUserProfile **/
        fetchMemberProfile(c, user_id) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/users/profile-single/${user_id}`, ajaxToken(c))
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
        /*** @SingleUserProfile **/
        /*** @Banner **/
        postCreateBanner(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    image: ["required", {mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['name', 'description', 'order', 'link'], formData, data);
                    formData.append('image', data.image.file);
                    client.post(`${apiUrl}/admin/banner/create`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postUpdateBanner(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    image: [{mimes: 'jpeg,jpg,png,gif'}, {max: 3000}],
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['name', 'description', 'order', 'link'], formData, data);
                    if (data.image && data.image.file) {//check if user change image
                        formData.append('image', data.image.file);
                    }
                    client.post(`${apiUrl}/admin/banner/update/${data.id}`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postDeleteBanner(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/banner/delete/${i.id}`, ajaxToken(c))
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
        /*** @Banner **/
        /*** @File **/
        postCreateFile(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    fileName: ["required"],
                    file: ["required", {mimes: 'pdf,doc,docx,xlsx,pptx'}, {max: 10000}],
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['fileName'], formData, data);
                    formData.append('file', data.file.file);
                    client.post(`${apiUrl}/admin/file/create`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postUpdateFile(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    fileName: ["required"],
                    file: [{mimes: 'pdf,doc,docx,xlsx,pptx'}, {max: 10000}],
                }).then(v => {
                    let formData = new FormData();
                    utils.addDataForm(['fileName'], formData, data);
                    if (data.file && data.file.file) {//check if user change file
                        formData.append('file', data.file.file);
                    }
                    client.post(`${apiUrl}/admin/file/update/${data.id}`, formData, ajaxToken(c, true))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        postDeleteFile(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/file/delete/${i.id}`, ajaxToken(c))
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
        /*** @File **/
        /*** @SingleUserProfile **/
        /*** @OptionDataUserProfile **/
        fetchOptionProfileData(c, data) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/users/profile-options`, ajaxToken(c))
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
        /*** @OptionDataUserProfile **/
        /*** @DashboardData **/
        fetchDashboardData(c, d) {
            c.commit('setValidated', {errors: {loading_dashboard_data: true}});
            client.get(`${apiUrl}/users/dashboard-data`, ajaxToken(c))
                .then(res => {
                    c.commit('setClearMsg');
                    c.commit('setDashboardData', res.data.data);
                })
                .catch(err => {
                    c.dispatch('HandleError', err.response);
                });
        },
        /*** @DashboardData **/
        /*** @postSendUserResetPasswordLink **/
        postSendUserResetPasswordLink(c, i) {
            return new Promise((r, n) => {
                c.commit('setValidated', {errors: {loading_searches: true}});
                client.post(`${apiUrl}/admin/users-send-reset-password-link/${i.id}`, i.data, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    })
            });
        },
        /*** @postSendUserResetPasswordLink **/
        /*** @postManagePostsStatus **/
        postManagePostsStatus(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    id: ["required", {max: 191}],
                    changeStatusTo: ["required", {max: 191}],
                }).then(v => {
                    client.post(`${apiUrl}/admin/posts-status/manage`, data, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        /*** @postManagePostsStatus **/
        /*** @postManageAssessmentStatus **/
        postUpdateStatusAssessment(c, i) {
            return new Promise((r, n) => {
                utils.Validate(i.data, {
                    id: ["required", {max: 191}],
                    status: ["required", {max: 191}],
                }).then(v => {
                    client.post(`${apiUrl}/admin/assessment/update-status/${i.data.id}`, i.data, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                });
            });
        },
        /*** @postManageAssessmentStatus **/
        /*** @postDeleteAssessment **/
        postDeleteAssessment(c, i) {
            return new Promise((r, n) => {
                client.delete(`${apiUrl}/admin/assessment/delete/${i.id}`, ajaxToken(c))
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
        /*** @postDeleteAssessment **/
        /*** @fetchSendUsersAssessment **/
        fetchOptionSendAssessmentData(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/admin/assessment/send-users`, ajaxToken(c))
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
        /*** @fetchSendUsersAssessment **/
        /*** @postSendAssessmentForUsers **/
        postSendAssessmentForUsers(c, i) {
            let field_inspector_validator = {
                field_inspector_assessments: ["required"],
                field_inspectors: ["required"]
            };
            let institute_validator = {
                institute_assessment: ["required"],
                institutes: ["required"]
            };
            let validator = {};
            if (i.type === 'field_inspector') {
                validator = field_inspector_validator;
            } else {
                validator = institute_validator;
            }
            return new Promise((r, n) => {
                utils.Validate(i, {
                    type: ["required"],
                    ...validator
                }).then(v => {
                    client.post(`${apiUrl}/admin/assessment/send/${i.type}`, i, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                })
            });
        },
        /*** @postSendAssessmentForUsers **/
        /*** @postUpdateStatusCheckAssessment **/
        postUpdateStatusCheckAssessment(c, i) {
            return new Promise((r, n) => {
                utils.Validate(i.data, {
                    id: ['required'],//check assessment id
                    status: ["required"],
                }).then(v => {
                    client.post(`${apiUrl}/users/assessment/check-assessment/change-status/${i.data.id}`, i.data, ajaxToken(c))
                        .then(res => {
                            c.commit('setClearMsg');
                            r(res.data)
                        })
                        .catch(err => {
                            c.dispatch('HandleError', err.response);
                            n(err)
                        })
                }).catch(e => {
                    c.commit('setValidated', {errors: e.errors});
                    n(e);
                })
            });
        },
        /*** @postUpdateStatusCheckAssessment **/
        /***@GET_ASSESSMENT**/
        fetchCheckAssessment(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/users/assessment/fetch/${i.id}?user_id=${i.user_id}`, ajaxToken(c))
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
        /***@SAVE_CHECK_ASSESSMENT_SCORE_STATUS*/
        postSaveCheckAssessmentAnswer(c, data) {
            return new Promise((r, n) => {
                utils.Validate(data, {
                    'check_assessment_sections': ['required'],
                }).then(v => {
                    client.post(`${apiUrl}/users/assessment/check-assessment/save-answer-status-score/${data.id}?user_id=${data.user_id}&type=${data.type}`, data, ajaxToken(c))
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
        /***@SAVE_CHECK_ASSESSMENT_SCORE_STATUS*/
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
                        if (!res.data.success) {
                            return;
                        }
                        if (i.firstLoad) {
                            c.commit('setCheckAssessmentComments', {data: [], position: 'reset'});
                        }
                        c.commit('setCheckAssessmentComments', {data: res.data.data.data, position: 'bottom'});
                        r(res.data)
                    })
                    .catch(err => {
                        console.log(err);
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
        /***@GET_ASSESSMENT_FIELD_INSPECTOR**/
        fetchCheckAssessmentFieldInspector(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/users/assessment-field-inspector/fetch/${i.id}?user_id=${i.user_id}&institute_id=${i.institute_id}`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    }).catch(err => {
                    c.dispatch('HandleError', err.response);
                    n(err);
                });
            });
        },
        /***@GET_ASSESSMENT_FIELD_INSPECTOR**/
        /***@SAVE_ASSESSMENT**/
        saveAssessment(c) {
            return new Promise((r, n) => {
                client.post(`${apiUrl}/admin/assessment-post/create`, {
                    assessment: c.state.mAssessment,
                    sections: c.state.mSectionsStack
                }, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        r(res.data);
                    })
                    .catch(err => {
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    })
            });
        },
        /***@END_SAVE_ASSESSMENT**/
        /***@GET_ASSESSMENT**/
        fetchAssessment(c, i) {
            return new Promise((r, n) => {
                client.get(`${apiUrl}/admin/assessment/fetch/${i.id}`, ajaxToken(c))
                    .then(res => {
                        c.commit('setClearMsg');
                        let data = res.data;
                        r(data);
                        if (data.success) {
                            data.data.sections.forEach((section, s_idx) => {
                                section.questions.forEach((q, q_idx) => {
                                    q.hash_id = utils.hashCode(`sec-${s_idx}-q-${q_idx}-time-${new Date().getMilliseconds()}`);
                                    q.content.forEach((c, c_idx) => {
                                        c.option_answers.forEach((o, o_idx) => {
                                            o.hash_id = utils.hashCode(`sec-${s_idx}-q-${q_idx}-content${c_idx}-options-${o_idx + 1}-time-${new Date().getMilliseconds()}`);
                                        });
                                        if (!c.row_option_answers) return;
                                        c.row_option_answers.forEach((o, o_idx) => {
                                            o.hash_id = utils.hashCode(`sec-${s_idx}-q-${q_idx}-content${c_idx}-row-options-${o_idx + 1}-time-${new Date().getMilliseconds()}`);
                                        })
                                    })
                                });
                            });
                            c.commit('setInitEditAssessmentEmptyState', {
                                assessment: data.data.assessment,
                                sections: data.data.sections
                            });
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    });
            });

        },
        /***@END_GET_ASSESSMENT**/
        /***@Update_ASSESSMENT**/
        updateAssessment(c, i) {
            return new Promise((r, n) => {
                client.post(`${apiUrl}/admin/assessment/update/${i.id}`, {
                    assessment: c.state.mAssessment,
                    sections: c.state.mSectionsStack
                }, ajaxToken(c))
                    .then(res => {
                        let data = res.data;
                        r(res.data);
                        if (data.success) {
                            //set id for every new data
                            let sections = data.data.sections;
                            sections.map((section, idx) => {
                                let editSection = c.state.mSectionsStack[idx];
                                if (editSection) {
                                    editSection.id = section.id;
                                    let editQuestions = editSection.questions;
                                    editQuestions.map((question, q_idx) => {
                                        question.id = (section.questions[q_idx] || {}).id;
                                    });
                                }
                            })
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        c.dispatch('HandleError', err.response);
                        n(err.response);
                    })
            });
        },
        /***@Update_ASSESSMENT**/
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
