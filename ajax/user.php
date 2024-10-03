<?php
require_once ("../admin/functions.php");

try {
    
    if (isset($_POST["add-user"])) {
        $name = $set->formSanitizer($_POST["name"]);
        $userName = $set->formSanitizer($_POST["username"]);
        $password = $set->formSanitizer($_POST["password"]);

        echo <<<HTML
            <tr>
                <td>1</td>
                <td>Ahmet Tarhan</td>
                <td>ahmet</td>
                <td>
                    <div class="nav-update">
                        <i class="nav-update-toggle bi bi-three-dots pointer text-3"></i>
                        <div class="nav-update-items">
                            <div class="nav-update-item">
                                <i class="bi bi-trash text-2 text-red-400"></i>
                                <span>Delete</span>
                            </div>
                            <div class="nav-update-item">
                                <i class="bi bi-pencil-square text-2 text-red-400"></i>
                                <span>Update</span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        HTML;
    }
    
    
} catch (ErrorException $err) {
    echo "user-add: ".$err->getMessage();
}

?>