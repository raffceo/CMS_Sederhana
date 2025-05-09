<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Posts</span>
                        <span class="info-box-number"><?= $totalPosts ?? 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-folder"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Categories</span>
                        <span class="info-box-number"><?= $totalCategories ?? 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Users</span>
                        <span class="info-box-number"><?= $totalUsers ?? 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-images"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Media Files</span>
                        <span class="info-box-number"><?= $totalMedia ?? 0 ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Posts</h3>
                        <div class="card-tools">
                            <a href="/admin/posts/create" class="btn btn-tool">
                                <i class="fas fa-plus"></i> New Post
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($recentPosts) && !empty($recentPosts)): ?>
                                        <?php foreach ($recentPosts as $post): ?>
                                            <tr>
                                                <td>
                                                    <a href="/admin/posts/edit/<?= $post['id'] ?>">
                                                        <?= htmlspecialchars($post['title']) ?>
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($post['author_name']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($post['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No posts found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="/admin/posts/create" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus mr-2"></i> New Post
                            </a>
                            <a href="/admin/categories/create" class="list-group-item list-group-item-action">
                                <i class="fas fa-folder-plus mr-2"></i> New Category
                            </a>
                            <a href="/admin/media/upload" class="list-group-item list-group-item-action">
                                <i class="fas fa-upload mr-2"></i> Upload Media
                            </a>
                            <a href="/admin/users/create" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-plus mr-2"></i> New User
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">System Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>PHP <?= PHP_VERSION ?></h3>
                                <p>Server Software: <?= $_SERVER['SERVER_SOFTWARE'] ?></p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-server"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 