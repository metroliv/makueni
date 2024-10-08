<form action="update_user.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <div class="mb-3">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="password">New Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>

    <div class="mb-3">
        <label for="phone">Phone Number:</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
    </div>

    <button type="submit" name="update" class="btn btn-primary">Update</button>
</form>
