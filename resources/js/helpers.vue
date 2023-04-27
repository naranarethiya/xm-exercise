<script>
const baseUrl = window.base_url+'api/';

const apiEndpoints = {
    'getHistoricalData': 'historical-data',
    'sendHistoricalData': 'historical-data/send',
    'getCompanySymbols': 'company-symbols'
}

export default {
    async sendRequest(endpoint, data={}, method='get', header={}){
        if (!apiEndpoints.hasOwnProperty(endpoint)) {
            alert("No property exist with name: "+ endpoint+" in sendJsonRequest");
            return;
        }

        let headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrf_token,
            ...header
        }

        let url = baseUrl+apiEndpoints[endpoint];
        let options = {
            method: method,
            headers:headers
        }

        if (method.toLowerCase() === 'post') {
            options.body = JSON.stringify(data)
        }
        
        try {
            let response = await fetch(url, options);
            return await response.json();
        }
        catch (e) {
            console.log(e);
            return {'status': '0' , 'message': e.message};
        }
    },
    getTodayDate() {
        let today = new Date();
        return formatDate(today);
    },

    formatDate(date) {
        let dd = String(date.getDate()).padStart(2, '0');
        let mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy = date.getFullYear();
        return yyyy+'-'+mm+'-'+dd
    },

    isValidEmail(email) {
      return /^[^@]+@\w+(\.\w+)+\w$/.test(email);
    }
}
</script>
