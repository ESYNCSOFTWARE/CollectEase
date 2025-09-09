<script setup>
import Chart from 'chart.js/auto';
import { onMounted, reactive, watch } from 'vue';

const props = defineProps({
    data: Object,
    chartType: {
        type: String,
        default: 'pie',
    },
    options: Object,
    chartId: String,
    titleText: String,
    height: String,
    titleDisplay: {
        type: Boolean,
        default: false,
    },
    dataLabelDisplay: {
        type: Boolean,
        default: true,
    },
    isRerender: [Number, Boolean],
});

const state = reactive({
    chartObject: null,
});

const generateChart = (chartElement, chartType, chartData, chartOption) => {
    state.chartObject = new Chart(chartElement, {
        type: chartType,
        data: chartData,
        options: chartOption,
    });
};

onMounted(() => {
    const chartElement = document.querySelector(`#${props.chartId} canvas`);
    generateChart(chartElement, props.chartType, props.data, props.options);
});

const rerenderChart = () => {
    if (state.chartObject) {
        state.chartObject.destroy();
    }

    const chartElement = document.querySelector(`#${props.chartId} canvas`);
    const chart = new Chart(chartElement, {
        type: props.chartType,
        data: props.data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: props.titleText,
                },
            },
        },
    });
    chart.options.plugins.title.text = props.titleText;
    state.chartObject = chart;
};

watch(() => props.isRerender, rerenderChart);
</script>
<template>
    <div :id="chartId" class="w-full" :style="{ height: height }">
        <canvas></canvas>
    </div>
</template>
