<template>
    <div class="header">
        <div class="labelContainer">
            <div class="SolidBackground" :style="`background-color: ${color};`">
                <div class="headerBannerSectionText" :style="titleStyles">
                    {{ title }}
                </div>
            </div>
            <div class="SectionTriangleContainer">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 10 10" preserveAspectRatio="none"
                     class="SectionTriangle">
                    <polygon class="SolidFill"
                             :style="`fill: ${color}; stroke: ${color};`"
                             points="0,0 10,0 0,10"></polygon>
                </svg>
            </div>
        </div>
        <slot name="action"></slot>
    </div>
</template>

<script>
    export default {
        name: "HeaderBanner",
        props: {
            title: {
                default: 'Section 1/1'
            },
            color: {
                default: '#323232'
            },
            titleStyles: {
                default: ''
            }
        }
    }
</script>

<style scoped>

</style>
