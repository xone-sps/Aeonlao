<template>
    <div class="admin-writer editor">
        <editor-menu-bar :editor="editor">
            <div border-bottom class="menubar"
                 slot-scope="{ commands, isActive }">

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    @click="showImagePrompt(commands.image)"
                >
                    <i class="material-icons">
                        insert_photo
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.bold() }"
                    @click="commands.bold"
                >
                    <i class="material-icons">
                        format_bold
                    </i>
                </button>


                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.italic() }"
                    @click="commands.italic"
                >
                    <i class="material-icons">
                        format_italic
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.strike() }"
                    @click="commands.strike"
                >
                    <i class="material-icons">
                        strikethrough_s
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.underline() }"
                    @click="commands.underline"
                >
                    <i class="material-icons">
                        format_underlined
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.code() }"
                    @click="commands.code"
                >
                    <i class="material-icons">
                        functions
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.paragraph() }"
                    @click="commands.paragraph"
                >
                    <i class="material-icons">
                        format_paint
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                    @click="commands.heading({ level: 1 })"
                >
                    H1
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                    @click="commands.heading({ level: 2 })"
                >
                    H2
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                    @click="commands.heading({ level: 3 })"
                >
                    H3
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.bullet_list() }"
                    @click="commands.bullet_list"
                >
                    <i class="material-icons">
                        format_list_bulleted
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.ordered_list() }"
                    @click="commands.ordered_list"
                >
                    <i class="material-icons">
                        format_list_numbered
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.blockquote() }"
                    @click="commands.blockquote"
                >
                    <i class="material-icons">
                        format_quote
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    :class="{ 'is-active': isActive.code_block() }"
                    @click="commands.code_block"
                >
                    <i class="material-icons">
                        bubble_chart
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    @click="commands.horizontal_rule"
                >
                    <i class="material-icons">
                        space_bar
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    @click="commands.undo"
                >
                    <i class="material-icons">
                        undo
                    </i>
                </button>

                <button
                    class="menubar__button v-md-button v-md-icon-button"
                    @click="commands.redo"
                >
                    <i class="material-icons">
                        redo
                    </i>
                </button>

            </div>
        </editor-menu-bar>

        <editor-menu-bubble :editor="editor">
            <div
                slot-scope="{ commands, isActive, menu }"
                class="menububble"
                :class="{ 'is-active': menu.isActive }"
                :style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`"
            >


                <button
                    class="menububble__button"
                    @click="showImagePrompt(commands.image)"
                >
                    <i class="material-icons">
                        insert_photo
                    </i>
                </button>

                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.bold() }"
                    @click="commands.bold"
                >
                    <i class="material-icons">
                        format_bold
                    </i>
                </button>

                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.italic() }"
                    @click="commands.italic"
                >
                    <i class="material-icons">
                        format_italic
                    </i>
                </button>

                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.underline() }"
                    @click="commands.underline"
                >
                    <i class="material-icons">
                        format_underlined
                    </i>
                </button>
                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                    @click="commands.heading({ level: 2 })"
                >
                    H2
                </button>

                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.bullet_list() }"
                    @click="commands.bullet_list"
                >
                    <i class="material-icons">
                        format_list_bulleted
                    </i>
                </button>

                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.ordered_list() }"
                    @click="commands.ordered_list"
                >
                    <i class="material-icons">
                        format_list_numbered
                    </i>
                </button>

                <button
                    class="menububble__button"
                    :class="{ 'is-active': isActive.blockquote() }"
                    @click="commands.blockquote"
                >
                    <i class="material-icons">
                        format_quote
                    </i>
                </button>
            </div>
        </editor-menu-bubble>

        <editor-content class="editor__content"
                        :editor="editor"/>
    </div>

</template>

<script>
    import {Editor, EditorContent, EditorMenuBar, EditorMenuBubble} from 'tiptap'
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        Image,
        HorizontalRule,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
    } from 'tiptap-extensions'

    export default {
        name: "writer-editor",
        components: {
            Editor,
            EditorContent,
            EditorMenuBar,
            EditorMenuBubble
        },
        data: () => ({
            editor: null,
            content: `Type here...`,
        }),
        methods: {
            initEditor() {
                this.editor = new Editor({
                    extensions: [
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({levels: [1, 2, 3]}),
                        new Image(),
                        new HorizontalRule(),
                        new ListItem(),
                        new OrderedList(),
                        new TodoItem(),
                        new TodoList(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Link(),
                        new Strike(),
                        new Underline(),
                        new History(),
                    ],
                    content: this.content,
                })
            },
            showImagePrompt(command) {
                const src = prompt('Enter the url of your image here')
                if (src !== null) {
                    command({src})
                }
            },
        },
        mounted() {
            this.initEditor();
        },
        beforeDestroy() {
            if (!this.$utils.isEmptyVar(this.editor)) {
                this.editor.destroy();
            }
        }
    }
</script>

<style scoped>

    .menubar__button i, .menubar__button {
        color: #78909c;
    }

    .menubar {
        margin-bottom: 1rem;
        -webkit-transition: visibility .2s .4s, opacity .2s .4s;
        transition: visibility .2s .4s, opacity .2s .4s;
    }
    .editor__content * {
        caret-color: currentColor;
    }
</style>
