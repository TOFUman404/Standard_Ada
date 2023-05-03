<script>
    let default_options_tempus = {
        localization: {
            dayViewHeaderFormat: {
                month: 'long',
                year: 'numeric'
            },
            locale: 'th',
        },
        display: {
            viewMode: 'calendar',
            theme: 'light',

            buttons: {
                close: true,
                today: true,
            },
            components: {
                useTwentyfourHour: true,
                decades: true,
                year: true,
                month: true,
                date: true,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
    }
    function JSPDTRenderDatatable(data) {
        $('#otbProducts').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            bDestroy: true,
            ajax: {
                url: "<?= base_url('api/products') ?>",
                dataType: "json",
                type: "POST",
                data: data,
            },
            columns: [
                { data: "FTPrdCode", name: "code"},
                { data: "FTPrdName" , nane: "name" },
                { data: "FCPrdPrice", name: "price" },
                { data: "FTPrdDescription", name: "description" },
                { data: "FTPrdImage", name: "image" },
                { data: "FNCatName", name: "category" },
                { data: "FDPrdUpdated_at", name: "updated_at" },
                { data: "actions" , name: "actions" }
            ],
            columnDefs: [
                {
                    targets: [0],
                    orderable: false,
                },
                {
                    targets: [1],
                    orderable: false,
                },
                {
                    targets: [2],
                    orderable: true,
                },
                {
                    targets: [3],
                    orderable: false,
                },
                {
                    targets: [4],
                    orderable: false,
                },
                {
                    targets: [5],
                    orderable: false,
                },
                {
                    targets: [6],
                    searchable: false,
                    type: "date",
                    render: function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    targets: [7],
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [6, 'desc']
            ],
            // lengthMenu: [1, 5, 10, 20, 50, 100, 200, 500],
        });
    }

    function JSPDTRenderChart(data) {
        let options = {
            chart: {
                type: 'line',
                height: '100%',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Created',
                data: data.count_product
            }],
            xaxis: {
                categories: data.date_series
            }
        }

        let chart = new ApexCharts(document.querySelector("#odvChart"), options);
        chart.render();
    }



    let searchParams = {
        "searchInput": null,
        "searchCategory": null,
        "searchDateStart": null,
        "searchDateEnd": null,
    };

    $('#ocmCategorySearch').on('change', function() {
        searchParams.searchCategory = +$(this).val();
        JSPDTRenderDatatable(searchParams);
    })

    $('#obtSearch').on('click', function() {
        searchParams.searchInput = $('#oetSearch').val();
        searchParams.searchCategory = +$('#ocmCategorySearch').val();
        searchParams.searchDateStart = $('#odpStart').val();
        searchParams.searchDateEnd = $('#odpEnd').val();
        JSPDTRenderDatatable(searchParams);
    })

    $(document).ready(function() {
        fetch('<?= base_url('api/categories') ?>')
            .then(response => response.json())
            .then(data => {
                $('#ocmCategorySearch').append(`<option value="" selected><?= $lang['tSearch_category'] ?></option>`)
                data.forEach(category => {
                    $('#ocmCategorySearch').append(`<option value="${category.id}">${category.name}</option>`)
                })
            })
            .catch(error => console.error(error))
        JSPDTRenderDatatable({});

        fetch('<?= base_url('api/productsHistory') ?>')
            .then(response => response.json())
            .then(data => {
                let newData = {
                    "date_series": [],
                    "count_product": []
                };
                data.forEach(item => {
                    newData.date_series.push(item.date_series);
                    newData.count_product.push(item.count_product);
                })
                // console.log(newData);
                JSPDTRenderChart(newData);
            })
            .catch(error => console.error(error))

        $('#odpDatePick').daterangepicker({
            "locale": {
                "format": "DD/MM/YYYY",
                "applyLabel": "ตกลง",
                "cancelLabel": "ล้าง",
                "daysOfWeek": [
                    "อา",
                    "จ",
                    "อ",
                    "พ",
                    "พฤ",
                    "ศ",
                    "ส"
                ],
                "monthNames": [
                    "มกราคม",
                    "กุมภาพันธ์",
                    "มีนาคม",
                    "เมษายน",
                    "พฤษภาคม",
                    "มิถุนายน",
                    "กรกฎาคม",
                    "สิงหาคม",
                    "กันยายน",
                    "ตุลาคม",
                    "พฤศจิกายน",
                    "ธันวาคม"
                ],
            }
        });
        $('#odpDatePick').val('');
        $('#odpDatePick').on('apply.daterangepicker', function(ev, picker) {
            $('#odpStart').val(picker.startDate.format('YYYY-MM-DD'));
            $('#odpEnd').val(picker.endDate.format('YYYY-MM-DD'));
        });
        $('#odpDatePick').on('cancel.daterangepicker', function(ev, picker) {
            picker.setStartDate({});
            picker.setEndDate({});
            $(this).val('');
            $('#odpStart').val('');
            $('#odpStart').val('');
        });
    });
</script>