export const getSectionsScrollHeight = ($vue) => {
    let sections = $vue.jq('.content-wrap'), scrollClientHeight = 0, marginSectionDiff = 30, bottomSectionDiff = 30;
    sections.each((idx, section) => {
        let $el = $vue.jq(section).find('.item.title');
        if ($el.length && $el.attr('class').indexOf('main-form') === -1) {
            if ($el.attr('class').indexOf('title-focus') === -1) {
                scrollClientHeight += section.clientHeight;
            } else {
                return false;
            }
        }
    });
    return scrollClientHeight + (marginSectionDiff * $vue.currentFocusIndexes.sectionIndex) + bottomSectionDiff;
};

export const getSectionFocusQuestionScrollHeight = ($vue) => {
    let questions = $vue.jq('.q-li'), offsetBtwSection = 30 + 40,
        offsetSectionHeader = 159, sectionsQuestionsHeight = 254;
    questions.each((idx, el) => {
        let $el = $vue.jq(el);
        if ($el.attr('class').indexOf('q-li-focus') === -1) {
            sectionsQuestionsHeight += el.clientHeight;
        } else {
            return false;
        }
    });
    return sectionsQuestionsHeight +
        (offsetBtwSection * $vue.currentFocusIndexes.sectionIndex) + (offsetSectionHeader * $vue.currentFocusIndexes.sectionIndex);
};

export function setPlacePositioner($vue) {
    let focusItems = $vue.jq('.q-li-focus'),
        focusSections = $vue.jq('.title-focus'),
        positionerBounce = $vue.$utils.getElBouningClientRect($vue.positioner),
        viewPortScrollTop = $vue.targetViewPort.scrollTop(),
        targetTopDiff = 380, viewPortOptionalDiff = 70, // 380 is scroll diff | 70  is optional view port diff
        viewPortWithTargetDiff = 110, //110 is diff for object top btw view port and object top
        focusItemDiff = 28, // 28 is the margin top diff for focus item
        targetTop = positionerBounce.top + viewPortScrollTop - viewPortWithTargetDiff,
        viewPortScrollHeightPosition = viewPortScrollTop + $vue.targetViewPort.height() - viewPortOptionalDiff;

    if (viewPortScrollTop > targetTop) {
        $vue.positioner.style.top = (viewPortScrollTop - targetTopDiff) + 'px';
    } else if (viewPortScrollHeightPosition < targetTop + positionerBounce.height) {
        $vue.positioner.style.top = viewPortScrollHeightPosition - (targetTopDiff + positionerBounce.height) + 'px';
    }

    /**@ForFocusItem */
    if (focusItems.length > 0) {
        let focusItemScrollHeight = getSectionFocusQuestionScrollHeight($vue) - focusItemDiff,
            focusItemScrollHeightPosition = focusItemScrollHeight + targetTopDiff;
        if (focusItemScrollHeightPosition > viewPortScrollTop &&
            viewPortScrollHeightPosition > focusItemScrollHeightPosition + positionerBounce.height) {
            $vue.positioner.style.top = focusItemScrollHeight + 'px';
        }
    } else if (focusSections.length > 0) {
        let focusSectionScrollHeight = getSectionsScrollHeight($vue),
            focusSectionScrollHeightPosition = focusSectionScrollHeight + targetTopDiff;
        if (focusSectionScrollHeightPosition > viewPortScrollTop &&
            viewPortScrollHeightPosition > focusSectionScrollHeightPosition + positionerBounce.height) {
            $vue.positioner.style.top = focusSectionScrollHeight + 'px';
        }
    }
    /**@ForFocusItem */
}

