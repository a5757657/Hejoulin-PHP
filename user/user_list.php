<?php
require __DIR__ . '\..\parts\__connect_db.php';

$title = '使用者列表';
$pageName = 'userList';

$perPage = 6;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `user`";

//總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);//幾頁

$sql = sprintf("SELECT * FROM `user` LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

$rows = $pdo->query($sql)->fetchAll();
?>
<?php include __DIR__ . '\..\parts\__head.php' ?>
<?php include __DIR__ . '\..\parts\__navbar.html' ?>
<?php include __DIR__ . '\..\parts\__sidebar.html' ?>
<?php include __DIR__ . '\..\parts\__main_start.html' ?>

    <div class="d-flex justify-content-between mt-5">
        <div class="btnbar">
            <button type="button" class="btn btn-secondary btn-sm" id="delAll">刪除選擇項目</button>
            <button type="button" class="btn btn-secondary btn-sm"><a href="user_insert.php"
                                                                      style="text-decoration: none; color: white;">新增</a>
            </button>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="?page=1">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
                <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
                <?php for ($i = $page - 2; $i <= $page + 2; $i++)
                    if ($i >= 1 && $i <= $totalPages): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <li class="page-item<?= $totalPages == $page ? '' : 'disabled' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">
                        <i class="fas fa-angle-right" style="color: gray"></i>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $totalPages ?>">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>
                    <input class="form-check-input" type="checkbox" value="" id="allCk" onclick="cAll()"/>
                </th>

                <th scope="col">刪除</th>
                <th>使用者ID</th>
                <th>使用者帳號</th>
                <th>使用者密碼</th>
                <th>建立時間</th>
                <th>
                    <a href="#"><i class="fas fa-pen"></i></a>
                </th>
            </tr>
            </thead>
            <?php foreach ($rows

            as $r): ?>
            <tbody>
            <td>
                <input class="del" type="checkbox" name="check">
            </td>
            <td>
                <a href="javascript: delete_it(<?= $r['user_id'] ?>)">
                    <i class="fas fa-trash-alt text-center"></i>
                </a>
            </td>
            <td><?= $r['user_id'] ?></td>
            <td><?= $r['user_account'] ?></td>
            <td><?= $r['user_pass'] ?></td>
            <td><?= $r['user_time'] ?></td>
            <td>
                <a href="user_edit.php?user_id=<?= $r['user_id'] ?>">
                    <i class="fas fa-edit"></i>
                </a>
            </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php include __DIR__ . '\..\parts\__main_end.html' ?>
<?php include __DIR__ . '\..\parts\__modal.html' ?>
<?php include __DIR__ . '\..\parts\__script.html' ?>
    <!-- 如果要 modal 的話留下面的 script -->
    <script>
        function delete_it(user_id) {
            if (confirm(`確定要刪除＃${user_id}的資料嗎`)) {
                location.href = `user-delete.php?user_id=${user_id}`;
            }
        }

        function cAll() {
            const checkAll = document.getElementById("allCk");
            const cks = document.getElementsByName("check");

            if (checkAll.checked == true) {
                for (let i = 0; i < cks.length; i++) {
                    cks[i].checked = true;
                }
            } else {
                for (let i = 0; i < cks.length; i++) {
                    cks[i].checked = false;
                }
            }
        }

        let delAll = document.querySelector('#delAll');
        delAll.addEventListener('click', () => {
            let del = document.querySelectorAll('.del');
            let arr = [];
            let str;

            del.forEach((el) => {
                if (el.checked) {
                    str = el.parentElement.nextElementSibling;
                    str = str.nextElementSibling.innerHTML;
                    arr.push(str);
                }
            })
            arr = arr.join(',');
            console.log(arr);
            if (arr) {
                delete_it(arr);
            }
        })

    </script>
<?php include __DIR__ . '\..\parts\__foot.html' ?>