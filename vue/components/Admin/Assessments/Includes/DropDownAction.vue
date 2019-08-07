<template>
    <div>
        <el-dropdown trigger="click" ref="dropdown"
                     @command="handleDropdownCommand"
                     @visible-change="handleDropdownPosition">
                        <span class="el-dropdown-link">
                             <button class="v-md-button v-md-icon-button theme-blue">
                                 <i class="material-icons">more_vert</i></button>
                        </span>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item v-for="(item, idx) in items" :key="idx" :command="item.command">{{ item.text }}
                </el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
    </div>
</template>

<script>
    export default {
        name: "DropDownAction",
        props: {
            items: {
                default: function () {
                    return []
                }
            }
        },
        methods: {
            handleDropdownPosition(v) {
                if (v) {
                    let dropDown = this.$refs['dropdown'],
                        popperElm = dropDown.popperElm, mLeft = 0, minWidth = 157,
                        bounce = this.$utils.getElBouningClientRect(dropDown.$el),
                        windowWidth = this.$store.state.windowWidth;
                    this.$nextTick(() => {
                        popperElm.style.visibility = 'hidden';
                        setTimeout(() => {
                            popperElm.style.visibility = 'visible';
                            mLeft = popperElm.style.left;
                            if (bounce.left + minWidth > windowWidth) {
                                popperElm.style.left = (mLeft.replace('px', '') - 130) + 'px';
                            }
                        }, 100);
                    })
                }
            },
            handleDropdownCommand(command) {
                this.$emit('onMenuItemClick', command);
            }
        }
    }
</script>

<style scoped>

</style>
