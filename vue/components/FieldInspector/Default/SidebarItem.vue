<template>
    <div>
        <div class="app-sidebar-content-container-root show-tree-item"
             :class="[{'is-expanded': contentHeader.expanded}] ">
            <div class="app-sidebar-content-container-header" @click="toggleHeader()">
                <span>{{contentHeader.name}}</span>
                <p>{{contentHeader.description}}</p>
                <i class="material-icons collapse-icon" aria-label="">{{contentHeader.icon}}</i>
            </div>
            <div class="app-sidebar-content-container-item">
                <div v-for="(i, index) in items" :key="index">
                    <a @click="action(i)" class="app-sidebar-item is-small-side-bar-item"
                       :class="[{'selected-entry': i.params.name === $route.name}]">
                        <div class="app-sidebar-item-lockup  is-no-after">
                            <i class="material-icons selected-icon">{{i.icon}}</i>
                            <span class="app-sidebar-entry-displayname">{{i.name}}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            contentHeader: {
                type: Object, default: function () {
                    return {
                        expanded: true,
                        name: '',
                        description: '',
                        icon: ''
                    }
                }
            },
            items: {
                type: Array, default: function () {
                    return [
                        {
                            name: '',
                            icon: '',
                        },
                    ]
                }
            },
        },
        name: "sidebaritem",
        methods: {
            action(i) {
                this.emitItem(i);
            },
            emitItem(i) {
                if (i.action) {
                    if (this.$utils.isNativeCode(i.action)) {
                        i.action = i.action.bind(window);
                        this.$emit('onItemClick', i);
                    } else {
                        this.$emit('onItemClick', i);
                    }
                }
                this.$emit('send', i);
                this.$emit('input', i);
                this.$store.commit('setSelectedSidebarItem', i);
            },
            toggleHeader() {
                this.contentHeader.expanded = !this.contentHeader.expanded;
            }
        },

    }
</script>
