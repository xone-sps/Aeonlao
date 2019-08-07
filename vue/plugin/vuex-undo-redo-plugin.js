const EMPTY_STATE = 'emptyState', plugin = {}, SET_EMPTY_STATE_CALLED = 'setInitEmptyStateCalled';
const TIME_GROUP = 'time_group', GROUP_TIME_OFFSET = 10, GROUP_OBJECT_KEY = '__MERGE_KEY_TIME_GROUP__';
const isArray = (obj) => {
    return Object.prototype.toString.call(obj) === '[object Array]';
};
const isObject = (obj) => {
    return Object.prototype.toString.call(obj) === '[object Object]';
};
const isEmpty = (exp) => {
    return exp === '' || exp === null || typeof exp === 'undefined';
};

plugin.install = (Vue, options = {}) => {
    if (!Vue._installedPlugins.find(plugin => plugin.Store)) {
        throw new Error("VuexUndoRedo plugin must be installed after the Vuex plugin.")
    }
    Vue.mixin({
        data() {
            return {
                done: [],
                undone: [],
                newMutation: true,
                watchOnly: options.watchOnly || false,
                watchMutations: options.watchMutations || [],
                ignoreMutations: options.ignoreMutations || [],
                exceptNewMutations: options.ignoreMutations || [],
            };
        },
        created() {
            if (this.$store) {
                this.$store.subscribe(mutation => {
                    if (this.watchOnly) {
                        if (mutation.type !== EMPTY_STATE && this.watchMutations.indexOf(mutation.type) !== -1) {
                            let time_group = (mutation.payload || {})[TIME_GROUP] || false;
                            if (time_group !== false) {
                                mutation[GROUP_OBJECT_KEY] = this._uid + time_group;
                            }
                            this.done.push(mutation);
                            if (this.$store.state.mInitEmptyStateCalled) {
                                this.$store.commit(SET_EMPTY_STATE_CALLED, false);
                            }
                        }
                    } else if (mutation.type !== EMPTY_STATE && this.ignoreMutations.indexOf(mutation.type) === -1) {
                        this.done.push(mutation);
                    }
                    if (this.newMutation && !this.exceptNewMutations.indexOf(mutation.type)) {
                        this.undone = [];
                    }
                });
            }
        },
        computed: {
            canRedo() {
                return this.undone.length;
            },
            canUndo() {
                return this.done.length;
            }
        },
        methods: {
            redo() {
                let commit = this.undone.pop();
                this.newMutation = false;
                this.commitMutation(commit);
                this.newMutation = true;
                this.$emit('onRedo', {done: this.done, undone: this.undone})
            },
            undo() {
                let lastDo = this.done.pop();
                let lastDoGroup = this.done.filter(item => {
                    if (!isEmpty(lastDo[GROUP_OBJECT_KEY]) && !isEmpty(item[GROUP_OBJECT_KEY])) {
                        let offset = lastDo[GROUP_OBJECT_KEY] - item[GROUP_OBJECT_KEY];
                        return Math.abs(offset) <= GROUP_TIME_OFFSET;
                    }
                    return false;
                });//return related other grouped commit
                if (lastDoGroup.length) {//check if have grouped
                    lastDoGroup.forEach(f => this.done.splice(this.done.findIndex(e => e[GROUP_OBJECT_KEY] === f[GROUP_OBJECT_KEY]), 1));//delete grouped commit and push to undone commit
                    let merge = {};
                    merge[GROUP_OBJECT_KEY] = lastDo[GROUP_OBJECT_KEY];//set merged grouped commit
                    merge.items = lastDoGroup.concat(lastDo);//merge arrays of all items committed
                    this.undone.push(merge);//push to undone commit.
                } else {
                    this.undone.push(lastDo);
                }
                this.newMutation = false;
                this.$store.commit(EMPTY_STATE);
                this.done.forEach(mutation => {
                    this.commitMutation(mutation);
                    this.done.pop();
                });
                this.newMutation = true;
                this.$emit('onUndo', {done: this.done, undone: this.undone});
            },
            commitMutation(mutation) {
                if (mutation[GROUP_OBJECT_KEY] && (mutation.items || []).length) {
                    mutation.items.forEach(commit => {
                        this.setMutation(commit);
                    });
                } else {
                    this.setMutation(mutation);
                }
            },
            setMutation(mutation) {
                if (isObject(mutation.payload)) {
                    this.$store.commit(`${mutation.type}`, Object.assign({}, mutation.payload));
                } else if (isArray(mutation.payload)) {
                    this.$store.commit(`${mutation.type}`, Object.assign([], mutation.payload));
                } else {
                    this.$store.commit(`${mutation.type}`, mutation.payload);
                }
            }
        }
    });
};
export default plugin;
