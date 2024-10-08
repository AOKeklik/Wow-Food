<?php require_once ("../partials/header.php")?>

<!-- main content -->
<div class="bg-gray-100 my-3">
    <div class="wrapper">
        <h3 class="mb-3">ADMIN MANAGEMENT</h3>
    </div>
    <?php if($set->isAdmin()):?>
        <div class="wrapper">
            <div class="text-right">
                <div class="btn btn-sky modal-btn" data-modal-id="9">
                    <span>Add Admin</span>
                    <i class="bi bi-person-add text-2"></i>
                </div>
            </div>
        </div>
    <?php endif?>
    <?php if (isset($_SESSION["message"]["update"])):?>
        <div class="wrapper">
            <?php echo $_SESSION["message"]["update"]?>
        </div>
    <?php unset($_SESSION["message"]["update"]); endif?>
    <p class="message message-info"></p>
    <div class="wrapper">
        <div class="table-full">
            <table>
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($set->getAll ("tbl_admin") as $item):?>
                        <tr>
                            <td><?php echo $item->id()?></td>
                            <td><?php echo $item->name()?></td>
                            <td><?php echo $item->username()?></td>
                            <td>
                                <div class="nav-update">
                                    <i class="nav-update-toggle bi bi-three-dots pointer text-3"></i>
                                    <div class="nav-update-items">
                                        <?php if ($item->username() === $_SESSION["userLoggedIn"] || $set->isAdmin()):?>
                                            <?php if ($item->role() !== "admin"):?>   
                                                <div onclick="deleteUser (event, <?php echo $item->id()?>)" class="nav-update-item">
                                                    <i class="bi bi-trash text-2 text-red-400"></i>
                                                    <span>Delete</span>
                                                </div>                           
                                            <?php endif?>                 
                                            <a class="nav-update-item" href="<?php echo Constants::$ROOT_URL?>admin/userupdate/<?php echo $item->id()?>">
                                                <i class="bi bi-pencil-square text-2 text-red-400"></i>
                                                <span>Update</span>
                                            </a>
                                        <?php endif?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal" data-modal-id="9">
    <div class="modal-overlay">
        <div class="wrapper">
            <div class="modal-content">
                <h2 class="mb-2">Add User</h2>
                <p class="message"></p>
                <form action="" method="post" class="form">
                    <label for="name">
                        <span>Name</span>
                        <input type="text" name="name" id="name">
                    </label>
                    <label for="username">
                        <span>Username</span>
                        <input type="text" name="username" id="username">
                    </label>
                    <label for="password">
                        <span>Password</span>
                        <input type="text" name="password" id="password">
                    </label>
                    <label for="confirmpassword">
                        <span>Confirm Password</span>
                        <input type="text" name="confirmpassword" id="confirmpassword">
                    </label>
                    <p onclick="addUser(event)" class="btn btn-sky self-center">
                        <i class="bi bi-plus"></i>
                        <span>Add</span>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ("../partials/footer.php")?>

