<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= isset($post->id) ? 'Edit Post' : 'New Post' ?></h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="/admin/posts" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <form action="<?= isset($post->id) ? "/admin/posts/edit/{$post->id}" : "/admin/posts/create" ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control <?= $post->hasError('title') ? 'is-invalid' : '' ?>" 
                                       id="title" name="title" value="<?= htmlspecialchars($post->title) ?>" required>
                                <div class="invalid-feedback">
                                    <?= $post->getFirstError('title') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control <?= $post->hasError('content') ? 'is-invalid' : '' ?>" 
                                          id="content" name="content" rows="10" required><?= htmlspecialchars($post->content) ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $post->getFirstError('content') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="excerpt">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($post->excerpt) ?></textarea>
                                <small class="form-text text-muted">A short summary of the post.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Publish</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control <?= $post->hasError('status') ? 'is-invalid' : '' ?>" 
                                        id="status" name="status" required>
                                    <option value="draft" <?= $post->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= $post->status === 'published' ? 'selected' : '' ?>>Published</option>
                                    <option value="private" <?= $post->status === 'private' ? 'selected' : '' ?>>Private</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= $post->getFirstError('status') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Uncategorized</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category->id ?>" <?= $post->category_id == $category->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="featured_image">Featured Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="featured_image" name="featured_image">
                                        <label class="custom-file-label" for="featured_image">Choose file</label>
                                    </div>
                                </div>
                                <?php if ($post->featured_image): ?>
                                    <div class="mt-2">
                                        <img src="<?= htmlspecialchars($post->featured_image) ?>" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <?= isset($post->id) ? 'Update Post' : 'Publish Post' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 500,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help',
        images_upload_url: '/admin/posts/upload-image',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/admin/posts/upload-image');
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.url != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.url);
            };
            formData = new FormData();
            formData.append('image', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });
</script> 