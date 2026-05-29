const API_URL =
'http://localhost/monitoring_suhu/api.php';

const tempValue = document.getElementById('tempValue');
const humValue = document.getElementById('humValue');
const statusText = document.getElementById('statusText');
const updateTime = document.getElementById('updateTime');
const zoneTitle = document.getElementById('zoneTitle');

// Ambil parameter zona dari URL
const params = new URLSearchParams(window.location.search);
const zone = params.get('zone');

// Judul halaman
if(zone === 'masuk'){

    zoneTitle.innerText =
    'Monitoring Area 1';

}
else if(zone === 'penyimpanan'){

    zoneTitle.innerText =
    'Monitoring Area 2';

}

async function loadData(){

    try{

        const response =
        await fetch(API_URL);

        const data =
        await response.json();

        // Cari sensor sesuai zona
        const sensor =
        data.sensors.find(
            item => item.zone === zone
        );

        // Kalau sensor ditemukan
        if(sensor){

            const suhu =
            sensor.temp;

            const kelembaban =
            sensor.humidity;

            const waktu =
            sensor.time;

            // Tampilkan data
            tempValue.innerText =
            suhu + '°C';

            humValue.innerText =
            kelembaban + '%';

            updateTime.innerText =
            waktu;

            // Status otomatis
            if(suhu > 30){

                statusText.innerText =
                '⚠ Suhu terlalu tinggi';

            }
            else{

                statusText.innerText =
                '✅ Kondisi normal';

            }

        }
        else{

            statusText.innerText =
            '❌ Sensor tidak ditemukan';

        }

    }
    catch(error){

        console.log(error);

        statusText.innerText =
        '❌ Gagal mengambil data';

    }

}

// Pertama kali load
loadData();

// Refresh tiap 3 detik
setInterval(loadData,3000);