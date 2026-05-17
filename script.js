const API_URL =
'http://localhost/monitoring_suhu/api.php';

const tempValue = document.getElementById('tempValue');
const humValue = document.getElementById('humValue');
const statusText = document.getElementById('statusText');
const updateTime = document.getElementById('updateTime');
const zoneTitle = document.getElementById('zoneTitle');

const params = new URLSearchParams(window.location.search);
const zone = params.get('zone');

if(zone === 'masuk'){
    zoneTitle.innerText = 'Monitoring Pintu Masuk';
}
else if(zone === 'penyimpanan'){
    zoneTitle.innerText = 'Monitoring Area Penyimpanan';
}

async function loadData(){

    try{

        const response = await fetch(API_URL);
        const data = await response.json();

        if(data.sensors.length > 0){

            const sensor = data.sensors[0];

            tempValue.innerText =
            sensor.temperature + '°C';

            humValue.innerText =
            sensor.humidity + '%';

            updateTime.innerText =
            sensor.created_at;

            if(sensor.temperature > 30){

                statusText.innerText =
                'Suhu terlalu tinggi';

            }
            else{

                statusText.innerText =
                'Kondisi normal';

            }

        }

    }
    catch(error){

        console.log(error);

    }

}

loadData();
setInterval(loadData,3000);