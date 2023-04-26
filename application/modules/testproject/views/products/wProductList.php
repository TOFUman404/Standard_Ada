<div class="card mt-5">
    <div class="card-header">
        <div class="d-flex align-items-center pt-2 pb-2">
            <div class="d-flex align-items-center">
                <?= $title ?>
            </div>
            <div class="ms-auto">
                <a href="<?= base_url('products/create'); ?>" class="btn btn-primary" role="button">เพิ่มสินค้า</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row d-flex align-items-center justify-content-center">
                <div class="col-4">
                    <input type="text" name="oetSearch" id="oetSearch" class="form-control" placeholder="ค้นหาชื่อสินค้า">
                </div>
                <div class="col-2">
                    <select name="ocmCategorySearch" id="ocmCategorySearch" class="form-select">
                    </select>
                </div>
                <div class="col-3">
                    <input type="text" name="odpDatePick" id="odpDatePick" class="form-control" autocomplete="off" placeholder="ค้นหาตามช่วงวันที่">
                    <input type="date" class="form-control" name="odpStart" id="odpStart" placeholder="วันที่เริ่มต้น" aria-label="วันที่เริ่มต้น" hidden>
                    <input type="date" class="form-control" name="odpEnd" id="odpEnd" placeholder="วันที่สิ้นสุด" aria-label="วันที่สิ้นสุด" hidden>
                </div>
                <div class="col-1">
                    <button class="btn btn-primary" type="button" id="obtSearch">ค้นหา</button>
                </div>
        </div>
    </div>
        <div class="row">
            <div class="col-12 pt-2 table-margin">
                <table id="otbProducts" width="100%">
                    <thead class="text-center">
                    <th>Code</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Update</th>
                    <th>Action</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>