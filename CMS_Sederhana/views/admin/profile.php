<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Flash Messages -->
        <?php if (Application::$app->session->getFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= Application::$app->session->getFlash('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (Application::$app->session->getFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= Application::$app->session->getFlash('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <!-- Profile Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profile Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="/admin/profile" method="post">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" class="form-control <?= $user->hasError('firstname') ? 'is-invalid' : '' ?>" 
                                       id="firstname" name="firstname" value="<?= htmlspecialchars($user->firstname) ?>" required>
                                <div class="invalid-feedback">
                                    <?= $user->getFirstError('firstname') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" class="form-control <?= $user->hasError('lastname') ? 'is-invalid' : '' ?>" 
                                       id="lastname" name="lastname" value="<?= htmlspecialchars($user->lastname) ?>" required>
                                <div class="invalid-feedback">
                                    <?= $user->getFirstError('lastname') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control <?= $user->hasError('email') ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                                <div class="invalid-feedback">
                                    <?= $user->getFirstError('email') ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <span class="btn-text">Update Profile</span>
                                <span class="loading d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Updating...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Change Password -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <div class="card-body">
                        <form action="/admin/profile/password" method="post">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" class="form-control <?= $user->hasError('currentPassword') ? 'is-invalid' : '' ?>" 
                                       id="currentPassword" name="currentPassword" required>
                                <div class="invalid-feedback">
                                    <?= $user->getFirstError('currentPassword') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control <?= $user->hasError('password') ? 'is-invalid' : '' ?>" 
                                       id="password" name="password" required>
                                <div class="invalid-feedback">
                                    <?= $user->getFirstError('password') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" class="form-control <?= $user->hasError('confirmPassword') ? 'is-invalid' : '' ?>" 
                                       id="confirmPassword" name="confirmPassword" required>
                                <div class="invalid-feedback">
                                    <?= $user->getFirstError('confirmPassword') ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <span class="btn-text">Change Password</span>
                                <span class="loading d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Updating...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card mt-3 profile-info">
                    <div class="card-header">
                        <h3 class="card-title">Account Information</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong> 
                            <span class="badge badge-<?= $user->status === 1 ? 'success' : 'warning' ?>">
                                <?= $user->status === 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                        <p><strong>Member Since:</strong> <?= date('M d, Y', strtotime($user->created_at)) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add JavaScript for form submission animation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const btnText = button.querySelector('.btn-text');
            const loading = button.querySelector('.loading');
            
            btnText.classList.add('d-none');
            loading.classList.remove('d-none');
        });
    });
});
</script> 