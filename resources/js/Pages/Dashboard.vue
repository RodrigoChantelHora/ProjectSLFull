<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import DashboardStatus from '../Components/Charts/Dashboard/DashboardStatus.vue';

const props = defineProps({
    contacts: Array,
    contactActiveCount: Number,
    contactInactiveCount: Number,
    groups: Array,
    groupActiveCount: Number,
    groupInactiveCount: Number,
    reports: Array,
    reportActiveCount: Number,
    reportInactiveCount: Number,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="">
            <div class="grid grid-cols-1 gap-4 bg-white border border-gray-300 shadow-lg p-4">
                <div class="w-1/2 mx-auto">
                    <DashboardStatus
                        class="w-full"
                        :contacts="contacts"
                        :contactActiveCount="contactActiveCount"
                        :contactInactiveCount="contactInactiveCount"
                        :groups="groups"
                        :groupActiveCount="groupActiveCount"
                        :groupInactiveCount="groupInactiveCount"
                        :reports="reports"
                        :reportActiveCount="reportActiveCount"
                        :reportInactiveCount="reportInactiveCount"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
