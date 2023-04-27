<div class="card mt-5">
    <div class="card-header">
        <div class="d-flex align-items-center pt-2 pb-2">
            <div class="d-flex align-items-center">
                <?= $lang['tHeader_title'] ?>
            </div>
            <div class="ms-auto">
                <a href="<?= base_url('products/create'); ?>" class="btn btn-primary" role="button"><?= $lang['tAdd'] ?></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row d-flex align-items-center justify-content-center">
                <div class="col-4">
                    <input type="text" name="oetSearch" id="oetSearch" class="form-control" placeholder="<?= $lang['tSearch_product'] ?>">
                </div>
                <div class="col-2">
                    <select name="ocmCategorySearch" id="ocmCategorySearch" class="form-select">
                    </select>
                </div>
                <div class="col-3">
                    <input type="text" name="odpDatePick" id="odpDatePick" class="form-control" autocomplete="off" placeholder="<?= $lang['tSearch_date'] ?>">
                    <input type="date" class="form-control" name="odpStart" id="odpStart" hidden>
                    <input type="date" class="form-control" name="odpEnd" id="odpEnd" hidden>
                </div>
                <div class="col-1">
                    <button class="btn btn-primary" type="button" id="obtSearch"><?= $lang['tSearch'] ?></button>
                </div>
        </div>
    </div>
        <div class="row">
            <div class="col-12 pt-2 table-margin">
                <table id="otbProducts" width="100%">
                    <thead class="text-center">
                    <th><?= $lang['tProduct_code'] ?></th>
                    <th><?= $lang['tProduct_name'] ?></th>
                    <th><?= $lang['tProduct_price'] ?></th>
                    <th><?= $lang['tProduct_description'] ?></th>
                    <th><?= $lang['tProduct_image'] ?></th>
                    <th><?= $lang['tProduct_category'] ?></th>
                    <th><?= $lang['tProduct_update'] ?></th>
                    <th><?= $lang['tProduct_action'] ?></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>