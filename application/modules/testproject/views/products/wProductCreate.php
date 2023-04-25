<div class="card mt-5">
    <div class="card-header">
        <div class="d-flex align-items-center pt-2 pb-2">
            <div class="d-flex align-items-center">
                <?= $title ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('products/create'); ?>" method="post" enctype="multipart/form-data">
            <label for="name">Code</label>
            <input type="text" name="oetProductCode" id="oetProductCode" class="form-control" />
            <br />
            <label for="name">Name</label>
            <input type="text" name="oetProductName" id="oetProductName" class="form-control" />
            <br />
            <label for="price">Price</label>
            <input type="number" name="oetProductPrice" id="oetProductPrice" class="form-control" />
            <br />
            <label for="description">Description</label>
            <textarea name="otaProductDesc" id="otaProductDesc" class="form-control"></textarea>
            <br />
            <label for="category">Category</label>
            <select name="ocmProductCategory" id="ocmProductCategory" class="form-control">
                <?php
                foreach ($categories as $category) {
                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                }
                ?>
            </select>
            <br />
            <input type="file" name="oflProductImage" size="20" />
            <br /><br />
            <input type="submit" value="Save" />
        </form>
    </div>
</div>