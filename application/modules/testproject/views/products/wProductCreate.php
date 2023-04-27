<div class="card mt-5">
    <div class="card-header">
        <div class="d-flex align-items-center pt-2 pb-2">
            <div class="d-flex align-items-center">
                <?= $lang['tHeader_title_add'] ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('products/create'); ?>" method="post" enctype="multipart/form-data">
            <label for="name"><?= $lang['tProduct_code'] ?></label>
            <input type="text" name="oetProductCode" id="oetProductCode" class="form-control" required />
            <br />
            <label for="name"><?= $lang['tProduct_name'] ?></label>
            <input type="text" name="oetProductName" id="oetProductName" class="form-control" required />
            <br />
            <label for="price"><?= $lang['tProduct_price'] ?></label>
            <input type="number" step="0.01" name="oetProductPrice" id="oetProductPrice" class="form-control" required />
            <br />
            <label for="description"><?= $lang['tProduct_description'] ?></label>
            <textarea name="otaProductDesc" id="otaProductDesc" class="form-control"></textarea>
            <br />
            <label for="category"><?= $lang['tProduct_category'] ?></label>
            <select name="ocmProductCategory" id="ocmProductCategory" class="form-control" required>
                <?php
                foreach ($categories as $category) {
                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                }
                ?>
            </select>
            <br />
            <input type="file" name="oflProductImage" size="20" />
            <br /><br />
            <input type="submit" value="<?= $lang['tSave'] ?>" />
        </form>
    </div>
</div>