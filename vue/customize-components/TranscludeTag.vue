<script>
    export default {
        data: () => ({
            mainTag: 'content',
            defaultClass: 'transclude',
        }),
        props: {
            tag: {
                type: String,
                required: true
            },
            childClass: {
                type: String,
                default: '',
            }
        },
        render(createElement) {
            return createElement(
                this.mainTag, // tag name
                [
                    createElement(
                        this.tag, // tag name
                        {
                            attrs: {
                                class: `${this.defaultClass} ${this.childClass}`,
                            }
                        },
                        this.$slots.default, // array of children,
                    ),
                ],
            );
        }
    }
</script>
