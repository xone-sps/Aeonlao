<template>
    <div class="single-comments-container">
        <div class="fire-spinner" v-if="isLoading"></div>
        <div class="user-comment-poster">
            <Poster ref="main-poster" :isHideUserImage="!LoggedIn()" placeholder="Write a comment..." buttonText="Post"
                    :ImageUrl="`${baseUrl}${authUserInfo.thumb_image}`"
                    v-model="user.text"
                    @onPosterButtonClick="postComment(user)">
            </Poster>
        </div>
        <div class="header-container">
            <div class="header-text-container">
                <span>Comments</span> ({{ comments.length }})
            </div>
            <div class="content-float-right"></div>
        </div>
        <div class="comments-user-container">
            <div class="comments-list">
                <!--Comment item -->
                <CommentItem v-for="(item, idx) in comments"
                             :user="item"
                             :actions="item.comment_actions"
                             @onViewReplyShow="()=> item.comment.showReply = true"
                             @onCommentReply="commentReplyAction(item, idx)"
                             @onCommentEdit="commentEditAction(item, null, {row: idx, column: null})"
                             @onCommentDelete="commentDeleteAction(item, idx)"
                             :key="idx">
                    <div v-show="item.comment.showReply">
                        <!--reply comments -->
                        <CommentItem v-for="(jItem, jIdx) in item.replies" container-class="lv-1"
                                     :user="jItem"
                                     :actions="jItem.comment_actions"
                                     @onCommentReply="commentReplyAction(item, idx)"
                                     @onCommentEdit="commentEditAction(item, jItem, {row: idx, column: jIdx})"
                                     @onCommentDelete="commentDeleteAction(jItem, idx, jIdx)"
                                     :key="`child-${jIdx}`"/>
                        <!--reply comments -->
                    </div>
                    <Poster
                        :ref="`poster-${idx}`"
                        :isInValid="item.validated.text"
                        :isHidePoster="!item.comment.poster.active"
                        :state="item.comment.action"
                        :placeholder="item.comment.poster.placeholder"
                        :buttonText="item.comment.poster.buttonText"
                        :isHideButton="item.comment.poster.hideButton"
                        :ImageUrl="`${baseUrl}${authUserInfo.thumb_image}`"
                        :container-class="(item.comment.action==='edit' || item.comment.action ==='edit-reply') ? 'in-side-comment is-edit-comment': 'in-side-comment'"
                        @onPosterButtonClick="postComment(item, idx)"
                        v-model="item.comment.poster.text"
                        :key="`poster-${idx}`">

                        <template slot="poster-bottom"
                                  v-if="item.comment.action==='edit' || item.comment.action ==='edit-reply'">
                            <div class="user-comment-edit-action-container displayFlex">
                                <div class="edit-action displayFlex is-left">
                                    <button class="button is-default" @click="commentActionCancel(item)">Cancel</button>
                                </div>
                                <div class="edit-action displayFlex is-right">
                                    <button @click="updateComment(item)" class="button is-default is-active-comment">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </template>
                    </Poster>
                </CommentItem>

            </div>

            <!--See more comments -->
            <div class="see-more-comments flex">
                <div class="inner flex">
                    <a v-if="paginate.current_page * paginate.per_page < paginate.total"
                       class="see-more-comments-action" @click="getComments(paginate.current_page+1)"><span>View more comments</span></a>
                </div>
                <span
                    class="comment-counter flex">{{ comments.length }} of {{ paginate.total + insertedData.comment }}</span>
            </div>
            <!--Write a comment-->
            <div class="see-more-comments flex">
                <div class="inner flex">
                    <a class="see-more-comments-action" @click="focusCommentPoster"><span>Write a comment...</span></a>
                </div>
            </div>
            <!--See more comments -->
        </div>
    </div>
</template>

<script>
    import Poster from './Comment/CommentPoster.vue'
    import CommentItem from './Comment/CommentItem.vue'

    import {mapState, mapGetters, mapActions} from 'vuex'

    export default {
        name: "CheckAssessmentComments",
        props: {
            check_assessment_id: {
                required: true
            },
            type: {
                default: 'institute'
            }
        },
        components: {
            Poster,
            CommentItem
        },
        data: () => ({
            ...mapGetters(['LoggedIn', 'validated']),
            user: {},
            firstLoad: true,
            isLoading: false,
            insertedData: {comment: 0, reply_comment: 0},
            paginate: {per_page: 12, data: [], current_page: 1, last_page: 0, total: 0},
            isChild: false,
            comments: [],
        }),
        computed: {
            ...mapState(['isMobile', 'authUserInfo', 'checkAssessmentComments']),
        },
        watch: {},
        methods: {
            ...mapActions(['deleteComment', 'postMangeComment', 'fetchComments', 'showErrorToast', 'showInfoToast']),
            setPosterUser(item, idx) {
                let poster = this.$refs['poster-' + idx];
                if (poster && poster.length > 0) {
                    poster[0].setFocus();
                }
            },
            commentReplyAction(item, idx) {
                this.setPosterUser(item, idx);
                item.comment.action = 'reply';
                item.comment.poster.text = '';
                item.comment.poster.active = true;
                item.comment.showReply = true;
                item.comment.poster.placeholder = 'Write a reply...';
                item.comment.poster.buttonText = 'Reply';
                item.comment.poster.hideButton = false;
            },
            commentEditAction(item, child, pos) {
                if (child && pos) {
                    item.comment.action = 'edit-reply';
                    child.comment.action = 'edit-reply';
                    child.check_assessment_id = this.check_assessment_id;
                    item.child = {data: child, pos};
                    item.comment.poster.text = child.text;
                } else {
                    item.child = {};
                    item.comment.action = 'edit';
                    item.comment.poster.text = item.text;
                }
                this.setPosterUser(item, pos.row);
                item.comment.poster.active = true;
                item.comment.poster.placeholder = 'Update your comment...';
                item.comment.poster.hideButton = true;
            },
            commentActionCancel(item) {
                item.comment.action = '';
                item.comment.poster.text = '';
                item.comment.poster.active = false;
                item.comment.poster.placeholder = 'Write a comment...';
                item.comment.poster.hideButton = false;
            },
            commentDeleteAction(item, idx, jIdx = 0) {
                let c = confirm('Are you sure you want to delete this comment?');
                if (c) {
                    if (item.isReplyComment) {
                        item.check_assessment_id = this.check_assessment_id;
                    }
                    item.type = this.type;
                    this.isLoading = true;
                    this.deleteComment(item)
                        .then(res => {
                            this.isLoading = false;
                            if (res.success) {
                                if (item.isReplyComment) {
                                    this.comments[idx].replies.splice(jIdx, 1);
                                    this.insertedData.reply_comment--;
                                    let cancel = this.comments[idx];
                                    this.commentActionCancel(cancel);
                                } else {
                                    this.comments.splice(idx, 1);
                                    this.insertedData.comment--;
                                }
                            } else {
                                let text = item.isReplyComment ? 'reply comment' : 'comment';
                                this.showErrorToast({msg: `Failed to delete this ${text}.`, dt: 3500});
                            }
                        })
                        .catch(err => {
                            this.isLoading = false;
                            console.log(err);
                        })
                }
            },
            focusCommentPoster() {
                let obj = this.$refs['main-poster'].$refs;
                obj[Object.keys(obj)[0]].focus();
            },
            postComment(data, idx) {
                let actions = {reply: true};
                data.loggedIn = this.LoggedIn();
                data.check_assessment_id = this.check_assessment_id;
                data.type = this.type;
                this.isLoading = true;
                if (actions[data.comment.action]) {
                    this.setPosterUser(data);
                }
                this.postMangeComment(data)
                    .then(res => {
                        this.isLoading = false;
                        if (res.success) {
                            this.setInsertedItem(data, res, idx);
                            if (data.comment.action === 'post') {
                                this.insertedData.comment++;
                                this.user.text = '';
                            }
                        } else {
                            let text = data.comment.action === 'reply' ? 'reply comment' : 'comment';
                            this.showErrorToast({msg: `Failed to post the ${text}.`, dt: 3500});
                        }
                    })
                    .catch(err => {
                        this.isLoading = false;
                    })
            },
            setInsertedItem(data, res, idx, saved = true) {
                res.comment.comment_actions = {reply: true, edit: saved, delete: saved};
                if (data.comment.action === 'reply') {
                    this.insertedData.reply_comment++;
                    this.comments[idx].replies.push(res.comment);
                } else {
                    this.comments.unshift(res.comment);
                }
            },
            updateComment(data) {
                data.loggedIn = this.LoggedIn();
                this.isLoading = true;
                data.type = this.type;
                this.postMangeComment(data)
                    .then(res => {
                        this.isLoading = false;
                        if (res.success) {
                            if (data.comment.action === 'edit') {
                                data.text = res.comment.text;
                                data.comment.text = data.text;
                                data.comment.created_ago = res.comment.comment.created_ago;
                            } else {
                                let pos = data.child.pos, reply = this.comments[pos.row].replies[pos.column],
                                    comment = res.comment;
                                reply.text = comment.text;
                                reply.comment.text = reply.text;
                                reply.comment.created_ago = comment.comment.created_ago;
                            }
                            this.commentActionCancel(data);
                        } else {
                            let text = data.comment.action === 'edit' ? 'comment' : 'reply comment';
                            this.showErrorToast({msg: `Failed to update the ${text}.`, dt: 3500});
                        }
                    })
                    .catch(err => {
                        this.isLoading = false;
                    })
            },
            getComments(page = 1) {
                if (this.paginate.current_page === this.paginate.last_page && page !== 1) return;
                this.paginate.current_page = page;
                this.isLoading = true;
                this.fetchComments({
                    firstLoad: this.firstLoad,
                    id: this.check_assessment_id,
                    limit: this.paginate.per_page, page: this.paginate.current_page,
                    type: this.type
                }).then(res => {
                    this.isLoading = false;
                    this.paginate = res.data;
                    if (this.firstLoad || page === 1) {
                        this.comments = res.data.data;
                        this.firstLoad = false;
                    } else {
                        this.comments = this.comments.concat(res.data.data);
                    }
                }).catch(err => {
                    this.isLoading = false;
                })
            },
            setUserPostComment() {
                //@for post comment
                this.user.id = '';
                this.user.comment = {};
                this.user.comment.action = 'post';
                //@for post comment
            },
        },
        created() {
            this.comments = this.checkAssessmentComments;
            this.getComments();
            this.setUserPostComment();
        }
    }
</script>

<style scoped>
    /*comments section */

    .header-text-container {
        padding: 0 8px 11px;
        color: #777;
        display: inline-block;
        font-family: 'Google Sans', sans-serif;
        font-size: 16px;
    }

    .header-text-container span {
        color: #222;
        unicode-bidi: embed;
        direction: ltr;
    }

    .content-float-right {
        float: right;
    }

    .single-comments-container {
        background-color: #fff;
        padding-top: 16px;
        outline-width: 0;
        position: relative;
        margin-bottom: 8px;
    }

    .comments-user-container, .header-container {
        padding: 0 8px 8px;
    }

    .comments-list {
        unicode-bidi: embed;
    }

    .see-more-comments {
        justify-content: space-between;
        margin: 0 12px;
    }

    .see-more-comments .inner {
        padding-bottom: 8px;
        padding-top: 9px;
    }

    a.see-more-comments-action {
        font-size: 14px;
        color: #365898;
        flex: 1 1 auto;
    }

    .comment-counter {
        color: #606770;
        padding-bottom: 8px;
        padding-top: 9px;
    }

    .user-comment-poster {
        border-bottom: 1px solid rgba(6, 8, 21, 0.12);
        padding: 0 0 11px 0;
        margin-bottom: 12px;
    }

    /*comments section */
    /*Validate color */
    .user-comment-input-container.is-invalid-data {
        border: 2px solid #d93025;
    }

    .add-margin-right {
        margin-right: 10px;
    }

    /*Validate color*/
    /*@mediaScreen*/
    @media screen and (max-width: 719px) {
        .single-comments-container {
            margin: 4px 4px;
            margin-bottom: 8px;
        }
    }

    /*@mediaScreen*/
    .fire-spinner {
        align-items: center;
        background: rgba(255, 255, 255, .64);
        display: flex;
        display: -webkit-box;
        display: -moz-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        flex-direction: column;
        height: 100%;
        -webkit-justify-content: center;
        justify-content: center;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 6;
    }
</style>
