<script>
const baseUrl = window.base_url+'api/';

const apiEndpoints = {
    'productListAndTree': 'product/tree-and-list',
    'getHistoricalData': 'historical-data'
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
            console.log(response)
            console.log(e);
            return {'status':0, 'message': e.message};
        }
    },
    async getCompanySymbols() {
        let url = 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';

        let response = await fetch(url);

        try {
            return await response.json();
        }
        catch (e) {
            console.log(response)
            console.log(e);
            return {};
        }
    },
    showError(text, title='Oops...') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: text,
        });
    },
    showSuccess(text, title='Success') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: text,
        });
    },
    async showConfirm(text, title, successCallback, confirmButton='Yes', cancelButton='No, Cancel', cancelCallback) {
        return await Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            showCancelButton: true,
            confirmButtonText: confirmButton,
            cancelButtonText: cancelButton,
        });
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
