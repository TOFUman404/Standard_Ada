<script>
    var default_options_tempus = {
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
    function renderDatatable(data) {
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
                        return dayjs(data).format('DD/MM/YYYY');
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

    let searchParams = {
        "searchInput": null,
        "searchCategory": null,
        "searchDateStart": null,
        "searchDateEnd": null,
    };

    $('#ocmCategorySearch').on('change', function() {
        searchParams.searchCategory = +$(this).val();
        renderDatatable(searchParams);
    })

    $('#obtSearch').on('click', function() {
        searchParams.searchInput = $('#oetSearch').val();
        searchParams.searchDateStart = $('#odpStart').val();
        searchParams.searchCategory = +$('#ocmCategorySearch').val();
        searchParams.searchDateEnd = $('#odpEnd').val();
        renderDatatable(searchParams);
    })

    $(document).ready(function() {
        fetch('<?= base_url('api/categories') ?>')
            .then(response => response.json())
            .then(data => {
                $('#ocmCategorySearch').append(`<option value="" selected>หมวดหมู่ทั้งหมด</option>`)
                data.forEach(category => {
                    $('#ocmCategorySearch').append(`<option value="${category.id}">${category.name}</option>`)
                })
            })
            .catch(error => console.error(error))
        renderDatatable({});
    });
</script>