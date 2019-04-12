import { Line } from 'vue-chartjs';

Vue.component('sessionchart',{
    extends: Line,
    mounted: function() {
        let uri = '/api/session/sessionchart';
        let Months = new Array();
        let Labels = new Array();
        let Counts = new Array();
        this.axios.get(uri).then((response) => {
            let data = response.data;
            if(data) {
                this.renderChart(data, {responsive: true, maintainAspectRatio: false})
            } else {
                console.log('No chart data received');
            }
        });
    },  
});

Vue.component('userchart',{
    extends: Line,
    mounted: function() {
        let uri = '/api/session/userchart';
        let Months = new Array();
        let Labels = new Array();
        let Counts = new Array();
        this.axios.get(uri).then((response) => {
            let data = response.data;
            console.log(data.datasets);
            if(data) {
                this.renderChart(data, {responsive: true, maintainAspectRatio: false})
            } else {
                console.log('No chart data received');
            }
        });
    },  
});
