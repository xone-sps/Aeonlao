<template>
    <div>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="admin-dashboard-page">
                    <div class="p-header" p-header-host :style="`background: ${theme.bgColor};`">
                        <div>
                            <!--Make app-nav-bar transparent works -->
                            <div class="fire-feature-bar-image" p-header-host-1
                                 :style="`background: ${theme.bgColor};`"></div>
                            <!--Make app-nav-bar transparent works -->
                            <div class="feature-bar-content" p-content>
                                <div class="feature-bar-core" p-content>
                                    <div class="title-lockup stretch-across" p-content></div>
                                    <div class="feature-app-selector" p-content></div>
                                </div>
                                <div class="admin-dashboard-page-inner">
                                    <div class="page-project">
                                        <div class="page-project-name">
                                            <div class="fire-feature-bar-title" p-header-host-2>
                                                <h1 class="fire-featurebar-title" p-content-1>{{s.site_name}}</h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div @click="$utils.Location('/posts/institute')" style="cursor: pointer;" class="count-items">
                                        <div>Latest Institutes Count</div>
                                        <div class="value">{{ dashboardData.latest_institutes_count }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overlaying page-content p-main top-less" p-header-host-3>
                        <div class="page-inner-content">
                            <!--Overview Items-->
                            <div class="items items-stability">
                                <section>
                                    <h2 class="items-title">Overview Assessments</h2>
                                    <div class="items-row sidekick items-hero">
                                        <div class="p-card">
                                            <div class="admin-mat-card">
                                                <div class="p-card-header">
                                                    <i class="material-icons"> chrome_reader_mode </i>
                                                    <h3 @click="goTo('event')" class="p-card-title"> Total
                                                        Assessments. </h3>
                                                </div>
                                                <SpinnerLoading v-if="validated().loading_dashboard_data"/>
                                                <div class="p-posts-card">
                                                    <div class="p-columns">
                                                        <!--Events Count-->
                                                        <div class="p-column">
                                                            <div @click="goTo('assessments')" style="cursor: pointer;"
                                                                 class="items-counter align-horizontal-center target-host">
                                                                <div class="counter-header">
                                                                    <div class="counter-title-label">
                                                                        <h4 class="counter-title">Checking
                                                                            Assessments</h4>
                                                                        <div class="p-label">(Current)</div>
                                                                    </div>
                                                                </div>
                                                                <div class="value-delta">
                                                                    <div class="value"><span class="value-container">{{ dashboardData.assessments.checking }} Assessment(s)</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--End Events Count-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!--End Overview Items-->
                            <!--<hr class="splitter-items">-->
                            <!--Posts Items-->
                            <div class="items">
                                <!-- items-stability -->
                                <section>
                                    <h2 class="items-title">All Assessments Status</h2>
                                    <div class="items-row sidekick">
                                    </div>
                                    <div class="items-row sidekick">
                                        <!-- items-hero  -->
                                        <!--News Card-->
                                        <CounterCard :isLoading="validated().loading_dashboard_data"
                                                     @onCardClick="goTo('assessments')" title="Current Checking Assessments" icon="list_alt"
                                                     :count="{text: 'Assessment(s)', value: dashboardData.assessments.checking}"/>
                                        <!--News Card-->
                                        <!--Activities Card-->
                                        <CounterCard :isLoading="validated().loading_dashboard_data"
                                                     @onCardClick="goTo('assessments')" title="All Assessments"
                                                     icon="list_alt"
                                                     :count="{text: 'Assessment(s)', value: dashboardData.assessments.all }"/>
                                        <!--Activities Card-->
                                    </div>
                                    <div class="items-row sidekick">
                                        <!--Scholarships Card-->
                                        <CounterCard :isLoading="validated().loading_dashboard_data"
                                                     @onCardClick="goTo('assessments')" title="Success Assessments"
                                                     icon="list_alt"
                                                     :count="{text: 'Assessment(s)', value: dashboardData.assessments.success }"/>
                                        <!--Scholarships Card-->
                                    </div>
                                </section>
                            </div>
                            <!--End Posts Items-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import FieldInspectorBase from '@bases/FieldInspectorBase.js'
    import CounterCard from '@cus-com/Admin/DashboardItems/CounterItemCard.vue';

    export default FieldInspectorBase.extend({
        components: {
            CounterCard
        },
        data() {
            return {
                title: 'Dashboard'
            }
        },
        computed: {},
        watch: {},
        methods: {
            setEnterParentData() {
                let data = this.$parent.$data;
                data.showFeatureBar = false;
                data.addTranscludeClass = true;
                data.hasHeaderTransparent = true;
            },
            setOutParentData() {
                let data = this.$parent.$data;
                data.showFeatureBar = true;
                data.addTranscludeClass = false;
                data.hasHeaderTransparent = false;
            },
            goTo(routeName) {
                this.Route({name: routeName});
            }
        },
        mounted() {
            this.setPageTitle('Dashboard');
        },
        beforeRouteLeave: function (to, from, next) {
            this.setOutParentData();
            next();
        },
        created() {
            this.setEnterParentData();
            this.fetchDashboardData();
        }
    });
</script>

<style scoped>

</style>
