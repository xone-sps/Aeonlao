<template>
    <!--Comment item -->
    <div class="comment-info" :class="[containerClass]">
        <div class="comment-user-img">
            <img
                :src="user.image"
                alt="user comment image">
        </div>
        <div class="border">
            <div class="row-text">{{user.name}}</div>
            <div class="row-text-comment"> {{user.comment.text}}
            </div>
        </div>
        <div class="row-text-comment-info">
            <span class="time">{{user.comment.created_ago }}</span>
            <a v-if="actions.reply" @click="onCommentReply" class="row-text-action reply">Reply</a>
            <a v-if="actions.edit || owner" @click="onCommentEdit" class="row-text-action reply">Edit</a>
            <a v-if="actions.delete || owner" @click="onCommentDelete" class="row-text-action reply">Delete</a>
        </div>
        <!--See more reply-->
        <div class="see-reply" v-if="replyCount > 0 && !user.comment.showReply">
            <div class="inner">
                <a @click="onViewReply" class="row-text-action see-reply">View {{ replyCount }} {{
                    replyCount > 1 ? 'Replies' : 'Reply'}}</a>
            </div>
        </div>
        <!--See more reply-->
        <!--Slots for comment level 1 and Poster Of a comment -->
        <slot></slot>
    </div>

</template>

<script>
    export default {
        name: "CommentItem",
        props: {
            containerClass: {
                default: '',
                type: String,
            },
            user: {
                default: function () {
                    return {comment: {showReply: false}}
                },
                type: Object,
            },
            actions: {
                default: function () {
                    return {reply: true, edit: true, delete: true}
                }
            }
        },
        data: () => ({
            childCommentItems: [],
            owner: false,
        }),
        watch: {
            'user.replies': function (n, o) {
                if (n.length <= 0) {
                    this.$children = [];
                }
                this.setChildItems();
            },
        },
        computed: {
            replyCount() {
                return this.childCommentItems.length | (this.user.replies || []).length;
            }
        },
        methods: {
            onCommentReply() {
                this.$emit('onCommentReply', this.user);
            },
            onCommentEdit() {
                this.$emit('onCommentEdit', this.user);
            },
            onCommentDelete() {
                this.$emit('onCommentDelete', this.user);
            },
            onViewReply() {
                this.$emit('onViewReplyShow', this.childCommentItems);
            },
            setChildItems() {
                this.childCommentItems = this.$children.filter((d) => {
                    return String(d.$options._componentTag).toLowerCase() === 'commentitem';
                });
            },
        },
        mounted() {
            this.setChildItems();
        },
    }
</script>

<style scoped>
    .comment-info {
        margin-bottom: 8px;
        margin-left: 48px;
        position: relative;
    }

    .comment-info.lv-1 {
        margin-left: 28px;
        margin-top: 8px;
    }

    .comment-info .border {
        background-color: #f2f3f5;
        -webkit-border-radius: 18px;
        -moz-border-radius: 18px;
        -ms-border-radius: 18px;
        border-radius: 18px;
        box-sizing: border-box;
        max-width: 100%;
        word-wrap: break-word;
        position: relative;
        white-space: normal;
        word-break: break-word;
        padding: 8px 10px;
        overflow: hidden;
        display: inline-block;
    }

    .comment-info .row-text {
        color: #222;
        unicode-bidi: inherit;
        font-size: 15px;
        font-weight: 600;
    }

    .comment-info .row-text-comment {
        margin-top: 4px;
        font-size: 16px;
        color: #222;
    }

    .comment-info .row-text-comment-info {
        color: #8d949e;
        font-size: 12px;
        line-height: 16px;
        padding: 0 0 0 12px;
        position: relative;
    }

    .comment-info .row-text-comment-info .time {
        margin-right: 8px;
        display: inline-block;
        padding: 3px 0 10px 0;
    }

    .comment-info .row-text-comment-info a.row-text-action {
        color: #8d949e;
        padding: 3px 8px 10px 8px;
        display: inline-block;
        margin-right: 0;
        font-weight: 600;
        text-decoration: none;
    }

    /*user comment info */
    .comment-user-img {
        position: absolute;
        left: -40px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        line-height: 18px;
        text-align: center;
    }

    .comment-user-img img {
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        border-radius: 50%;
    }

    .comment-info.lv-1 .comment-user-img {
        left: -28px;
        width: 24px;
        height: 24px;
    }

    .see-reply {
        padding: 0 0 0 6px;
    }

    .see-reply .inner {
        margin: 0;
        display: flex;
        flex: 1 1 auto;
        padding-bottom: 8px;
        padding-top: 4px;
    }

    a.see-reply {
        font-size: 13px;
        color: #365898;
        flex: 1 1 auto;
    }

    /*user comment info */

    /*@mediaScreen*/
    @media screen and (max-width: 719px) {

        /*Comments @media 719px*/
        .comment-info .border {
            padding: 6px 12px 7px 12px;
            line-height: 16px;
        }

        .comment-info .row-text {
            font-size: 13px;
        }

        .comment-info .row-text-comment {
            font-size: 15px;
        }

        /*Comments @media 719px*/
    }

    /*@mediaScreen*/

</style>
