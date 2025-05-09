<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= isset($category->id) ? 'Edit Category' : 'New Category' ?></h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="/admin/categories" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= isset($category->id) ? "/admin/categories/edit/{$category->id}" : "/admin/categories/create" ?>" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control <?= $category->hasError('name') ? 'is-invalid' : '' ?>" 
                                       id="name" name="name" value="<?= htmlspecialchars($category->name) ?>" required>
                                <div class="invalid-feedback">
                                    <?= $category->getFirstError('name') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($category->description) ?></textarea>
                                <small class="form-text text-muted">A brief description of the category.</small>
                            </div>

                            <div class="form-group">
                                <label for="parent_id">Parent Category</label>
                                <select class="form-control" id="parent_id" name="parent_id">
                                    <option value="">None</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <?php if ($cat->id !== $category->id): ?>
                                            <option value="<?= $cat->id ?>" <?= $category->parent_id == $cat->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat->name) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Select a parent category if this is a subcategory.</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <?= isset($category->id) ? 'Update Category' : 'Create Category' ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Category Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-folder"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Categories</span>
                                <span class="info-box-number"><?= count($categories) ?></span>
                            </div>
                        </div>

                        <?php if (isset($category->id)): ?>
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-file-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Posts in Category</span>
                                    <span class="info-box-number"><?= $category->getPostCount() ?></span>
                                </div>
                            </div>

                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-sitemap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Subcategories</span>
                                    <span class="info-box-number"><?= count($category->getChildren()) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 