</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="<?php echo base_url('application/modules/testproject/assets/js/bootstrap/bootstrap.bundle.js');?>"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url('application/modules/testproject/assets/js/daterangepicker.js');?>"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    function JSxPDTLevelColor(level) {
        if(level <= 50) {
            return 'green';
        } else if(level > 50 && level <= 100) {
            return 'yellow';
        } else if(level > 100 && level <= 150) {
            return 'orange';
        } else if(level > 150 && level <= 200) {
            return 'red';
        } else if(level > 200 && level <= 300) {
            return 'purple';
        } else {
            return 'maroon';
        }
    }
    $(document).ready(async function () {
        const oData = await JSxPDTLoadQI();
        if ( oData.status ) {
            $('#ospLocation').html(oData.data.city.name);
            $('#ospPM10').html(`PM10 : <span style="color:${JSxPDTLevelColor(oData.data.iaqi.pm10.v)}">${oData.data.iaqi.pm10.v}</span>`);
            $('#ospPM25').html(`PM2.5 : <span style="color:${JSxPDTLevelColor(oData.data.iaqi.pm25.v)}">${oData.data.iaqi.pm25.v}</span>`);
        }
    });

    async function JSxPDTLoadQI() {
        const oResponse = await fetch('https://api.waqi.info/feed/@5773/?token=32befd08e264a299ffc5f9b6ccb792d75c8ad092',{
            method: 'GET',
            cache: 'no-cache',
        })
            .then(response => response.json());
        return oResponse;
    }
</script>
</body>
</html>