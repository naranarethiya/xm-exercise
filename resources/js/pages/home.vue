<template>
    <div class="row">
        <h2 class="mt-2 mb-2">PHP Exercise - v21.0.5</h2>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Company Symbol</label>
                                <Select2 v-model="searchForm.symbol"
                                    :options="companyList"
                                    :style="'width:100%'"
                                    :settings="{placeholder: 'Select Symbol'}"
                                />
                                <small class="text-danger"
                                    v-text="searchFormErrors.symbol"
                                ></small>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Start Date</label>
                                <VueDatePicker 
                                    v-model="searchForm.start_date"
                                    auto-apply 
                                    :enable-time-picker="false"
                                    :close-on-auto-apply="true"
                                    :max-date="new Date()"
                                />
                                <small class="text-danger"
                                    v-text="searchFormErrors.start_date"
                                ></small>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>End Date</label>
                                <VueDatePicker 
                                    v-model="searchForm.end_date"
                                    auto-apply 
                                    :enable-time-picker="false"
                                    :close-on-auto-apply="true"
                                    :min-date="searchForm.start_date"
                                    :max-date="new Date()"
                                />
                                <small class="text-danger"
                                    v-text="searchFormErrors.end_date"
                                ></small>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control"
                                    v-model="searchForm.email"
                                >
                                <small class="text-danger"
                                    v-text="searchFormErrors.email"
                                ></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer text-right">
                    
                    <button class="btn btn-default mr-3"
                        @click="sendEmail"
                        :disabled="submitting"
                        v-text="submitting ? 'Wait...' : 'Send Email'"
                    ></button>

                    <button class="btn btn-primary"
                        @click="submitForm"
                        :disabled="submitting"
                        v-text="submitting ? 'Wait...' : 'Submit'"
                    ></button>

                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <VueApexCharts 
                                width="100%" 
                                type="candlestick" 
                                :series="chartData"
                                :options="chartOptions"
                                v-if="historicalData.length > 0"
                            >
                            </VueApexCharts>
                            <div v-else>
                                <h4>No data available.</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <h2 class="mt-2 mb-2">Historical Data</h2>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive mb-5">
                            <table class="table table-bordered item-table">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>Date</th>
                                        <th>Open</th>
                                        <th>High</th>
                                        <th>Low</th>
                                        <th>Close</th>
                                        <th>Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in historicalData"
                                    >
                                        <td v-text="helpers.formatDate(new Date(parseFloat(item.date)*1000))"></td>
                                        <td v-text="item.open"></td>
                                        <td v-text="item.high"></td>
                                        <td v-text="item.low"></td>
                                        <td v-text="item.close"></td>
                                        <td v-text="item.volume"></td>
                                    </tr>
                                    <tr
                                        v-if="historicalData.length < 1"
                                    >   
                                        <td colspan="6">No Data available</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<script setup type="ts">
import {computed, onBeforeMount, onMounted, ref, watch} from 'vue'
import Select2 from "../shared/select2.vue";
import helpers from "../helpers.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import VueApexCharts from "vue3-apexcharts";


const companyList = ref([]);
const searchForm = ref({
    'symbol': '',
    'start_date': '',
    'end_date': '',
    'email': '',
});

const searchFormErrors = ref({});
const submitting = ref(false);
const historicalData = ref([]);

const chartOptions = ref({
    chart: {
        type: 'candlestick',
        height: 350
    },
    title: {
        text: 'CandleStick Chart',
        align: 'left'
    },
    xaxis: {
        type: 'datetime'
    },
    yaxis: {
        tooltip: {
            enabled: true
        }
    }
})

onMounted(() => {
    setCompanySymbols();
});

const chartData = computed(() => {
    let data = [];
    for(let i in historicalData.value) {
        let item = historicalData.value[i];

        if(!item.open ||
            !item.high ||
            !item.low ||
            !item.close 
        ) {
            continue;
        }

        data.push({
            x: new Date(item.date*1000),
            y: [
                item.open?.toFixed(2),
                item.high?.toFixed(2),
                item.low?.toFixed(2),
                item.close?.toFixed(2)
            ]
        });
    }

    return [{
        data: data
    }];
})

async function setCompanySymbols() {
    let symbols = await helpers.sendRequest('getCompanySymbols');
    let selectList = [];
    for(let i in symbols) {
        if (symbols[i].product_id == null) {
            selectList.push({text: symbols[i]["Company Name"]+'('+symbols[i].Symbol+')', id: symbols[i].Symbol})
        }
    }

    companyList.value = selectList;
}

async function submitForm() {

    if(!validateForm()) {
        return;
    }

    submitting.value = true;
    historicalData.value = [];

    let response = await helpers.sendRequest(
        'getHistoricalData', 
        searchForm.value,
        'post'
    );

    submitting.value = false;

    if(response.hasOwnProperty('errors')) {
        alert(response.message);
        return;
    }

    if(response.hasOwnProperty('status') && response.status == "0") {
        alert(response.message);
        return;
    }

    if(response.hasOwnProperty('prices')) {
        historicalData.value = response.prices;
        if(historicalData.value.length < 1) {
            alert("No data available.");
            return;
        }

        return;
    }
}

async function sendEmail() {
    if(!validateForm()) {
        return;
    }

    submitting.value = true;
    let response = await helpers.sendRequest(
        'sendHistoricalData', 
        searchForm.value,
        'post'
    );

    submitting.value = false;
    alert(response.message);
}

function validateForm() {
    searchFormErrors.value = {};
    let requireField = [
        'symbol',
        'start_date',
        'end_date',
        'email',
    ];

    let isValid = true;
    for(let i in requireField) {
        let field = requireField[i];

        if(searchForm.value[field] == '') {
            searchFormErrors.value[field] = 'This field is required';
            isValid = false;
        }
    }

    if(!isValid) return isValid;

    if(helpers.isValidEmail(searchForm.value.email) == false) {
        searchFormErrors.value.email = 'Invalid email address';
        isValid = false;
    }

    return isValid;
}
</script>
