<script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.js');?>"></script>
<script>
    function levelColor(level) {
        if(level >= 50) {
            return 'green';
        } else if(level >= 100) {
            return 'yellow';
        } else if(level >= 150) {
            return 'orange';
        } else if(level >= 200) {
            return 'red';
        } else if(level >= 300) {
            return 'purple';
        } else {
            return 'maroon';
        }
    }
    $(document).ready(function () {
       fetch('https://api.waqi.info/feed/@5773/?token=32befd08e264a299ffc5f9b6ccb792d75c8ad092',{
              method: 'GET',
              cache: 'no-cache',
            })
            .then(response => response.json())
            .then(data => {
                $('#ospLocation').html(data.data.city.name);
                $('#ospPM10').html(`PM10 : <span style="color:${levelColor(data.data.iaqi.pm10.v)}">${data.data.iaqi.pm10.v}</span>`);
                $('#ospPM25').html(`PM2.5 : <span style="color:${levelColor(data.data.iaqi.pm25.v)}">${data.data.iaqi.pm25.v}</span>`);
            });
    });
</script>
</div>
</body>
</html>