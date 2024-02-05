<template>
    <div class="commenter__comment py-4 border-t border-40">
        <div class="font-light text-80 text-sm">
            <template v-if="hasCommenter">
                <a class="link-default" :href="commenterUrl" v-text="commenter"></a>

                said
            </template>

            <template v-else>
                Written
            </template>

            {{ date }}
        </div>

        <div class="mt-2" v-html="commentString"></div>

        <template v-if="canViewPrivateComment">
            <input type="checkbox" class="mt-2" :checked="isPublic" :data-id="getId" @change="changeCommentVisibility" />
            <div class="inline-block ml-1">Public comment</div>
        </template>

    </div>
</template>

<script>
    require('lodash');
    import moment from 'moment-timezone';

    export default {
        props: {
            comment: {
                type: Object,
                required: true
            },
            canViewPrivateComment: {
                type: Boolean,
                required: true
            }
        },

        computed: {
            commentString() {
                return _.find(this.comment.fields, { attribute: 'comment' }).value;
            },

            commenter() {
                return _.find(this.comment.fields, { attribute: 'commenter' }).value;
            },

            commenterUrl() {
                let commenterId = _.find(this.comment.fields, { attribute: 'commenter' }).belongsToId;

                return `${Nova.config("base")}/resources/users/${commenterId}`;
            },

            date() {
                let now = moment();
                let date = moment.utc(_.find(this.comment.fields, { attribute: 'created_at' }).value)
                    .tz(moment.tz.guess());

                if (date.isSame(now, 'minute')) {
                    return 'just now';
                }

                if (date.isSame(now, 'day')) {
                    return `at ${date.format('LT')}`;
                }

                if (date.isSame(now, 'year')) {
                    return `on ${date.format('MMM D')}`;
                }

                return `on ${date.format('ll')}`;
            },

            hasCommenter() {
                return Boolean(this.commenter);
            },

            isPublic() {
                let isPublic = false;
                this.comment.fields.forEach(function(item, index) {
                    if(item.attribute === 'is_public') {
                        isPublic = Boolean(item.value);
                    }
                });

                return isPublic;
            },

            getId() {
                return this.comment.id.value;
            }
        },

        methods: {
            changeCommentVisibility(event) {
                try {
                    const commentId = event.target.attributes['data-id'].nodeValue;
                    Nova.request().post(`/nova-api/comments/${commentId}`, {
                        is_public: Boolean(event.target.checked),
                        _method: 'PUT'
                    });
                } catch (error) {
                    console.error(error);
                }
            }
        }
    }
</script>
