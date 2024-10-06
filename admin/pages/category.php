<?php require_once ("../partials/header.php")?>

<!-- main content -->
<div class="bg-gray-100 my-3">
    <div class="wrapper">
        <h3 class="mb-3">CATEGORY MANAGEMENT</h3>
    </div>
    <?php if($set->isAdmin()):?>
        <div class="wrapper">
            <div class="text-right">
                <div class="btn btn-sky modal-btn" data-modal-id="9">
                    <span>Add Category</span>
                    <i class="bi bi-plus text-2"></i>
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
                        <th>Title</th>
                        <th>Img</th>
                        <th>Featured</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $cats=$set->getAll ("tbl_category"); if(count ($cats) > 0): foreach ($cats as $item):?>
                        <tr>
                            <td><?php echo $item->id()?></td>
                            <td><?php echo $item->title()?></td>
                            <td>
                                <?php if ($item->img() != ""):?>
                                    <img src="<?php echo Constants::$ROOT_URL.$item->img()?>" alt="" class="h-7 w-100% obj-cover" />
                                <?php else:?>
                                    <p class="text-red-400">No selected img!</p>
                                <?php endif?>
                            </td>
                            <td><?php echo $item->featured()?></td>
                            <td><?php echo $item->active()?></td>
                            <td>
                                <form class="nav-update" action="<?php echo Constants::$ROOT_URL?>admin/categoryupdate" method="post">
                                    <i class="nav-update-toggle bi bi-three-dots pointer text-3"></i>
                                    <div class="nav-update-items">
                                        <?php if ($set->isAdmin()):?>
                                            <div onclick="deleteCategory (event, <?php echo $item->id()?>)" class="nav-update-item">
                                                <i class="bi bi-trash text-2 text-red-400"></i>
                                                <span>Delete</span>
                                            </div>                    
                                            <button type="submit" class="nav-update-item">
                                                <i class="bi bi-pencil-square text-2 text-red-400"></i>
                                                <span>Update</span>
                                            </button>
                                            <input type="hidden" name="categoryId" value="<?php echo $item->id()?>">
                                        <?php endif?>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal" data-modal-id="9">
    <div class="modal-overlay">
        <div class="wrapper">
            <div class="modal-content">
                <h2 class="mb-2">Add Category</h2>
                <p class="message"></p>
                <form action="" method="post" class="form">
                    <label for="title">
                        <span>Title</span>
                        <input type="text" name="title" id="title">
                    </label>
                    <label for="image_name" role="file">
                        <span>Image</span>
                        <input type="file" name="image_name" id="image_name">
                    </label>
                    <label for="featured" role="checkbox">
                        <span>Featured</span>
                        <input type="checkbox" name="featured" id="featured" checked>
                        <i></i>
                    </label>
                    <label for="active" role="checkbox">
                        <span>Active</span>
                        <input type="checkbox" name="active" id="active" checked>
                        <i></i>
                    </label>
                    <p onclick="addCategory(event)" class="btn btn-sky self-center">
                        <i class="bi bi-plus"></i>
                        <span>Add</span>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ("../partials/footer.php")?>