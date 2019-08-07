// vue.prototype
let plugins = {};
plugins.install = (Vue, options) => {
    // textarea
    Vue.prototype.$autoText = (event) => {
        let elem = event.target
        let height, minHeight, mMinHeight
        let padding
        let style = elem.style
        let getStyle = (name) => {
            return getComputedStyle(elem, null)[name]
        }
        if (elem.value.length === 0) {
            style.height = parseFloat(getStyle('min-height')) + 'px';
            return;
        }

        if (elem._length === elem.value.length) return


        if (elem._length > elem.value.length) {
            minHeight = parseFloat(getStyle('height'));
            mMinHeight = parseFloat(getStyle('min-height'));
            let calculateHeight = minHeight / (elem.value.length + 1);
            if (mMinHeight > calculateHeight) {
                style.height = mMinHeight + 'px';
            } else {
                style.height = calculateHeight + 'px';
            }
        }

        elem._length = elem.value.length
        padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'))
        minHeight = parseFloat(getStyle('height'))
        elem.style.height = minHeight + 'px'
        if (elem.scrollHeight > minHeight) {
            height = elem.scrollHeight - padding
            style.overflowY = 'hidden';
            style.height = height + 'px'
        }
    }
    // textarea disable new line
    Vue.prototype.$disableEnterNewLine = (e) => {
        e.preventDefault();
    }
};

export default plugins
